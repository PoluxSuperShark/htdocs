<?php
session_start();
require '../../config/database.php';

// Vérification admin blog
if (!isset($_SESSION['blog_admin_id'])) {
    exit("Accès refusé. Vous devez être admin pour voir cette page.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $contenu = $_POST['contenu'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO posts (titre, contenu, auteur_id) VALUES (?, ?, ?)");
    $stmt->execute([$titre, $contenu, $_SESSION['blog_admin_id']]);

    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Ajouter un article</h1>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="titre" class="form-control" placeholder="Titre" required>
        </div>
        <div class="mb-3">
            <textarea name="contenu" class="form-control" rows="5" placeholder="Contenu" required></textarea>
        </div>
        <button class="btn btn-success">Publier</button>
        <a href="admin.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>