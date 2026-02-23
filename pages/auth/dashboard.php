<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require '../../config/database.php';

// Récupérer toutes les infos de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    // Si l'utilisateur n'existe plus
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Bienvenue <?= htmlspecialchars($user['username']) ?> !</h2>
    
    <div class="card mt-4">
        <div class="card-header">
            <strong>Informations du compte</strong>
        </div>
        <div class="card-body">
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['last_name']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['first_name']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($user['address'] ?? '-') ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['phone'] ?? '-') ?></p>
            <p><strong>Âge :</strong> <?= htmlspecialchars($user['age'] ?? '-') ?></p>
            <p><strong>Sexe :</strong> <?= htmlspecialchars($user['gender']) ?></p>
            <p><strong>Rôle :</strong> <?= htmlspecialchars($user['role']) ?></p>
            <p><strong>Date de création :</strong> <?= htmlspecialchars($user['created_at']) ?></p>
            <p><strong>Dernière connexion :</strong> <?= htmlspecialchars($user['last_login'] ?? '-') ?></p>
        </div>
    </div>

    <a href="logout.php" class="btn btn-danger mt-3">Se déconnecter</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>