<?php
session_start();
require '../../config/database.php';

// Vérification admin blog avec la nouvelle session
if (!isset($_SESSION['blog_admin_id'])) {
    exit("Accès refusé. Vous devez être admin pour voir cette page.");
}

// Récupération des articles
$articles = $pdo->query("
    SELECT p.*, u.username,
           (SELECT COUNT(*) FROM likes WHERE post_id=p.id) AS likes
    FROM posts p
    JOIN users u ON u.id = p.auteur_id
    ORDER BY date_publication DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Gestion des articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Administration du Blog</h1>
    <div class="mb-3">
        <a href="add_post.php" class="btn btn-success">Ajouter un article</a>
        <a href="logout.php" class="btn btn-secondary">Déconnexion</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Likes</th>
                <th>Date de publication</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($articles as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['titre']) ?></td>
                <td><?= htmlspecialchars($a['username']) ?></td>
                <td><?= $a['likes'] ?></td>
                <td><?= $a['date_publication'] ?></td>
                <td>
                    <a href="edit_post.php?id=<?= $a['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="delete_post.php?id=<?= $a['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>