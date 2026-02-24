<?php
require 'config.php';

$stmt = $pdo->query("
    SELECT p.*, u.username,
           (SELECT COUNT(*) FROM likes WHERE post_id=p.id) AS likes
    FROM posts p 
    JOIN users u ON u.id = p.auteur_id
    ORDER BY date_publication DESC
");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mon Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Articles</h1>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="btn btn-secondary mb-3">Déconnexion</a>
    <?php else: ?>
        <a href="login.php" class="btn btn-primary mb-3">Connexion</a>
        <a href="register.php" class="btn btn-success mb-3">Inscription</a>
    <?php endif; ?>

    <?php foreach($articles as $post): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($post['titre']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($post['contenu'])) ?></p>
                <small class="text-muted">Publié par <?= htmlspecialchars($post['username']) ?> le <?= $post['date_publication'] ?></small>
                <div class="mt-2">
                    <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary">
                        👍 <?= $post['likes'] ?> Likes
                    </a>
                    <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-secondary">Commentaires</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>