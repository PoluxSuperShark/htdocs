<?php
session_start();
require __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Non autorisé");
}

// Récupère rôle et statut banné
$stmtUser = $pdo->prepare("SELECT role, banned FROM users WHERE id = :id");
$stmtUser->execute([':id' => $_SESSION['user_id']]);
$currentUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
$currentUserRole = $currentUser['role'] ?? 'user';
$isBanned = $currentUser['banned'] ?? 0;

/* ======================================
   API - SEND MESSAGE
====================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ENVOI MESSAGE
    if (isset($_POST['message'])) {
        $message = trim($_POST['message']);
        if ($message !== '' && !$isBanned) {
            $stmtInsert = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (:user_id, :message)");
            $stmtInsert->execute([
                ':user_id' => $_SESSION['user_id'],
                ':message' => $message
            ]);
        }
        exit;
    }

    // SUPPRIMER MESSAGE
    if (isset($_POST['delete_id'])) {
        $msgId = (int)$_POST['delete_id'];

        if ($currentUserRole === 'admin') {
            // Admin supprime n'importe quel message
            $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id");
            $stmt->execute([':id' => $msgId]);
        } else {
            // Utilisateur supprime seulement ses propres messages
            $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id AND user_id = :uid");
            $stmt->execute([':id' => $msgId, ':uid' => $_SESSION['user_id']]);
        }
        exit;
    }

    // BANNIR UTILISATEUR
    if (isset($_POST['ban_user_id']) && $currentUserRole === 'admin') {
        $banUserId = (int)$_POST['ban_user_id'];

        // Bannir et supprimer ses messages
        $stmt = $pdo->prepare("UPDATE users SET banned = 1 WHERE id = :id");
        $stmt->execute([':id' => $banUserId]);

        $stmt = $pdo->prepare("DELETE FROM messages WHERE user_id = :uid");
        $stmt->execute([':uid' => $banUserId]);

        exit;
    }
}

/* ======================================
   API - FETCH MESSAGES
====================================== */
if (isset($_GET['fetch'])) {
    header('Content-Type: application/json');

    $stmt = $pdo->prepare("
        SELECT m.id, m.message, m.created_at, u.username, u.role, m.user_id
        FROM messages m
        JOIN users u ON m.user_id = u.id
        ORDER BY m.created_at ASC
        LIMIT 100
    ");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Chat | PoluxSuperShark</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
#chatBox { height:400px; overflow-y:auto; background:#f8f9fa; padding:15px; border-radius:10px; border:1px solid #ddd; }
.message { margin-bottom:8px; position:relative; }
.username-admin { color:red; font-weight:bold; }
.username-user { color:black; }
.delete-btn, .ban-btn { position:absolute; right:0; top:0; cursor:pointer; font-size:0.8rem; color:#888; margin-left:5px; }
.delete-btn:hover { color:red; }
.ban-btn:hover { color:orange; }
</style>
</head>
<body class="bg-light">
<div class="container mt-5">

    <?php include "../components/navbar.php"; ?>

    <h1 class="mb-3">Chat entre les membres</h1>

    <p>En cas de non-respect des règles, vous pouvez être banni du chat. Veuillez ne pas faire n'importe quoi comme :</p>
    <ul>
        <li>Doxxing, harcèlement, menaces</li>
        <li>Spam, pubs et auto-promotions</li>
        <li>Discuter de politique, religion ou moeurs sensibles</li>
        <li>Dire des gros mots, insultes, ou langage innaproprié</li>
        <li>Discuter sur le sexe en particulier le langage cru également</li>
    </ul>

    <hr>

    <?php if($isBanned): ?>
    <p class="text-danger">Vous êtes banni et ne pouvez plus poster de messages.</p>
    <?php endif; ?>

    <div id="chatBox" class="mb-3"></div>
    <div class="input-group">
        <input type="text" id="messageInput" class="form-control" placeholder="Écrivez votre message..." <?= $isBanned ? 'disabled' : '' ?>>
        <button class="btn btn-primary" onclick="sendMessage()" <?= $isBanned ? 'disabled' : '' ?>>Envoyer le message</button>
    </div>

    <br>

    <p>Réfléchissez à deux fois avant d'envoyer ce message</p>

    <?php include "../components/footer.php"; ?>
    

</div>

<br>


<script>
    // TODO : Don't care about errs in the two vars
let currentUserId = <?= json_encode($_SESSION['user_id']) ?>;
let currentUserRole = <?= json_encode($currentUserRole) ?>;

function fetchMessages() {
    fetch("chat.php?fetch=1")
    .then(res => res.json())
    .then(data => {
        let chatBox = document.getElementById("chatBox");
        chatBox.innerHTML = "";

        data.forEach(msg => {
            let div = document.createElement("div");
            div.classList.add("message");
            let roleClass = (msg.role === "admin") ? "username-admin" : "username-user";
            div.innerHTML = `<span class="${roleClass}">${msg.username}</span>: ${msg.message}`;

            // Supprimer message
            if (msg.user_id === currentUserId || currentUserRole === "admin") {
                let btn = document.createElement("span");
                btn.className = "delete-btn";
                btn.textContent = "✖";
                btn.onclick = () => deleteMessage(msg.id);
                div.appendChild(btn);
            }

            // Bannir utilisateur (admins seulement)
            if (currentUserRole === "admin" && msg.user_id !== currentUserId) {
                let btnBan = document.createElement("span");
                btnBan.className = "ban-btn";
                btnBan.textContent = "🚫";
                btnBan.onclick = () => banUser(msg.user_id);
                div.appendChild(btnBan);
            }

            chatBox.appendChild(div);
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

function sendMessage() {
    let input = document.getElementById("messageInput");
    let message = input.value.trim();
    if (!message) return;

    fetch("chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "message=" + encodeURIComponent(message)
    }).then(()=> { input.value=""; fetchMessages(); });
}

function deleteMessage(id) {
    fetch("chat.php", {
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body:"delete_id=" + encodeURIComponent(id)
    }).then(()=>fetchMessages());
}

function banUser(userId) {
    if (!confirm("Voulez-vous vraiment bannir cet utilisateur ?")) return;
    fetch("chat.php", {
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body:"ban_user_id=" + encodeURIComponent(userId)
    }).then(()=>fetchMessages());
}

setInterval(fetchMessages,2000);
fetchMessages();

document.getElementById("messageInput").addEventListener("keypress", e=>{
    if(e.key==="Enter") sendMessage();
});
</script>

</body>
</html>