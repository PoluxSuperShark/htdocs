<?php
session_start();

// DB
require __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Non autorisé");
}

/* ======================================
   API - SEND MESSAGE
====================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {

    $message = trim($_POST['message']);

    if ($message !== '') {

        // Récupère infos utilisateur
        $stmtUser = $pdo->prepare("SELECT username, role FROM users WHERE id = :id");
        $stmtUser->execute([':id' => $_SESSION['user_id']]);
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if ($userData) {

            // Insert message en base
            $stmtInsert = $pdo->prepare("
                INSERT INTO messages (user_id, message)
                VALUES (:user_id, :message)
            ");

            $stmtInsert->execute([
                ':user_id' => $_SESSION['user_id'],
                ':message' => $message
            ]);

            /* ======================================
               DISCORD WEBHOOK
            ====================================== */

            // ⚠️ Mets ton webhook dans un fichier config idéalement
            $webhookUrl = "";

            $color = ($userData['role'] === 'admin') ? 16711680 : 3447003;

            $payload = [
                "embeds" => [[
                    "title" => "Message envoyé depuis le chat",
                    "color" => $color,
                    "fields" => [
                        [
                            "name" => "Utilisateur",
                            "value" => $userData['username'],
                            "inline" => true
                        ],
                        [
                            "name" => "Rôle",
                            "value" => $userData['role'],
                            "inline" => true
                        ],
                        [
                            "name" => "Message",
                            "value" => $message
                        ]
                    ],
                    "timestamp" => date("c")
                ]]
            ];

            $ch = curl_init($webhookUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_exec($ch);
            curl_close($ch);
        }
    }

    exit;
}

/* ======================================
   API - FETCH MESSAGES
====================================== */
if (isset($_GET['fetch'])) {

    header('Content-Type: application/json');

    $stmt = $pdo->prepare("
        SELECT m.message, m.created_at, u.username, u.role
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
<title>Chat entre les membres connectés | PoluxSuperShark</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/settings.css">
<link rel="stylesheet" href="../css/pages/chat.html.css">

</head>
<body class="bg-light">

    <div class="container mt-5">

        <br>

        <h1 class="mb-3">Chat entre les membres connectés</h1>
        <hr>

        <div id="chatBox" class="mb-3"></div>

        <div class="input-group">
            <input type="text" id="messageInput" class="form-control" placeholder="Écrivez votre message...">
            <button class="btn btn-primary" onclick="sendMessage()">Envoyer le message</button>
        </div>

    </div>

    

</div>

<script src="../js/chat.js"></script>

</body>
</html>