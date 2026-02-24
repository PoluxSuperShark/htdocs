<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    exit("Non autorisé");
}

/* ==========================
   ENVOI MESSAGE
========================== */
if (isset($_POST['message'])) {

    $message = trim($_POST['message']);

    if ($message !== "") {

        // Récupérer infos utilisateur depuis table users
        $stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stmt = $pdo->prepare("
                INSERT INTO messages (user_id, message, created_at)
                VALUES (:uid, :message, NOW())
            ");

            $stmt->execute([
                ':uid' => $_SESSION['user_id'],
                ':message' => htmlspecialchars($message)
            ]);
        }
    }

    exit;
}

/* ==========================
   FETCH MESSAGES
========================== */
if (isset($_GET['fetch'])) {

    $stmt = $pdo->prepare("
        SELECT m.message, m.created_at, u.username, u.role
        FROM messages m
        JOIN users u ON m.user_id = u.id
        ORDER BY m.created_at DESC
        LIMIT 50
    ");
    $stmt->execute();

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(array_reverse($messages));
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Chat en direct</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
#chatBox {
    height: 400px;
    overflow-y: auto;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
}

.message {
    margin-bottom: 8px;
}

.admin {
    color: red;
    font-weight: bold;
}

.user {
    color: black;
}
</style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Chat en direct</h3>

    <div id="chatBox" class="mb-3"></div>

    <div class="input-group">
        <input type="text" id="messageInput" class="form-control" placeholder="Écris ton message...">
        <button class="btn btn-primary" onclick="sendMessage()">Envoyer</button>
    </div>
</div>

<script>
function fetchMessages() {
    fetch("chat.php?fetch=1")
        .then(res => res.json())
        .then(data => {

            let chatBox = document.getElementById("chatBox");
            chatBox.innerHTML = "";

            data.forEach(msg => {

                let div = document.createElement("div");
                div.classList.add("message");

                let roleClass = (msg.role === "admin") ? "admin" : "user";

                div.innerHTML = `
                    <span class="${roleClass}">${msg.username}</span> :
                    ${msg.message}
                `;

                chatBox.appendChild(div);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        });
}

function sendMessage() {
    let input = document.getElementById("messageInput");
    let message = input.value;

    if (message.trim() === "") return;

    fetch("chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "message=" + encodeURIComponent(message)
    }).then(() => {
        input.value = "";
        fetchMessages();
    });
}

// Actualisation toutes les 2 secondes
setInterval(fetchMessages, 2000);
fetchMessages();

document.getElementById("messageInput")
    .addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            sendMessage();
        }
    });
</script>

</body>
</html>