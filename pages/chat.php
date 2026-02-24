<?php

session_start();

// Gets the DB
require __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Non autorisé");
}

// API - Send a msg
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {

    $message = trim($_POST['message']);

    if ($message !== '') {

        // Verifies if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);

        // Set the msg into DB
        if ($stmt->fetch()) {

            $stmt = $pdo->prepare("
                INSERT INTO messages (user_id, message)
                VALUES (:user_id, :message)
            ");

            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':message' => $message
            ]);
        }
    }

    exit;
}

// API - Fetch msg
if (isset($_GET['fetch'])) {

    header('Content-Type: application/json');

    // Get msg from DB
    $stmt = $pdo->prepare("
        SELECT m.message, m.created_at, u.username, u.role
        FROM messages m
        JOIN users u ON m.user_id = u.id
        ORDER BY m.created_at ASC
        LIMIT 100
    ");

    // JSON data
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
<style>
#chatBox {
    height: 400px;
    overflow-y: auto;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #ddd;
}

.message {
    margin-bottom: 8px;
}

.username-admin {
    color: red;
    font-weight: bold;
}

.username-user {
    color: black;
}
</style>
</head>
<body class="bg-light">
    
<div class="container mt-5">
    
    
    <h1 class="mb-3">Chat entre les membres connectés</h1>

    <hr>

    <!-- Chatbox with the button and txt send -->
    <div id="chatBox" class="mb-3"></div>
        
    <div class="input-group">
        <input type="text" id="messageInput" class="form-control" placeholder="Écrivez votre message...">
        <button class="btn btn-primary" onclick="sendMessage()">Envoyer le message</button>
    </div>
    
</div>

<script src="../js/chat.js"></script>

<?php
// Webhook
$webhookUrl = "";

// Récupère infos utilisateur
$stmtUser = $pdo->prepare("SELECT username, role FROM users WHERE id = :id");
$stmtUser->execute([':id' => $_SESSION['user_id']]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

$color = ($userData['role'] === 'admin') ? 16711680 : 3447003; 
// Rouge si admin, bleu sinon

$data = [
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
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 2); // No block

curl_exec($ch);
curl_close($ch);
?>

</body>
</html>