<?php
session_start();
require '../../config/database.php';

// 🔹 Vérification connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/auth/login.php");
    exit;
}

// 🔹 Vérification rôle admin
$stmt = $pdo->prepare("SELECT role, username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || strtolower(trim($user['role'])) !== 'admin') {
    die("Accès refusé : tu dois être admin pour voir cette page.");
}

$username = $user['username'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

<nav class="navbar navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <span class="navbar-brand">Admin Panel</span>
    <span class="text-light me-3">Salut, <?= htmlspecialchars($username) ?></span>
    <a href="../../pages/auth/logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
  </div>
</nav>

<h1>Bienvenue dans le panneau Admin</h1>
<p>Seuls les administrateurs peuvent voir cette page.</p>

<!-- Ici tu pourras ajouter des liens vers tes fichiers CRUD blog -->
<ul>
    <li><a href="add_post.php">Ajouter un article</a></li>
    <li><a href="manage_posts.php">Gérer les articles</a></li>
    <li><a href="manage_comments.php">Gérer les commentaires</a></li>
</ul>

<a href="../../index.php" class="btn btn-secondary mt-3">Retour à l'accueil</a>

</div>
</body>
</html>