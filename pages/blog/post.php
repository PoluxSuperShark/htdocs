<?php
require 'config.php';

if(!isset($_GET['id'])) exit("Article introuvable");
$post_id = (int)$_GET['id'];

// Récupérer article
$stmt = $pdo->prepare("
    SELECT p.*, u.username,
           (SELECT COUNT(*) FROM likes WHERE post_id=p.id) AS likes
    FROM posts p 
    JOIN users u ON u.id = p.auteur_id
    WHERE p.id=?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$post) exit("Article introuvable");

// Ajouter commentaire
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['comment']) && isset($_SESSION['user_id'])){
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, contenu) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $_SESSION['user_id'], $_POST['comment']]);
    header("Location: post.php?id=$post_id");
    exit;
}

// Liker
if(isset($_GET['like']) && isset($_SESSION['user_id'])){
    $stmt = $pdo->prepare("INSERT IGNORE INTO likes (post_id, user_id) VALUES (?, ?)");
    $stmt->execute([$post_id, $_SESSION['user_id']]);
    header("Location: post.php?id=$post_id");
    exit;
}

// Récupérer commentaires
$stmt = $pdo->prepare("
    SELECT c.*, u.username
    FROM comments c
    JOIN users u ON u.id = c.user_id
    WHERE post_id=?
    ORDER BY date_commentaire ASC
");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php include('../../components/navbar.php'); ?><br>
    <a href="index.php" class="btn btn-secondary mb-3">Retour</a>
    <div class="card mb-3">
        <div class="card-body">
            <h3 class="card-title"><?= htmlspecialchars($post['titre']) ?></h3>
            <p class="card-text"><?= nl2br(htmlspecialchars($post['contenu'])) ?></p>
            <small class="text-muted">Publié par <?= htmlspecialchars($post['username']) ?> le <?= $post['date_publication'] ?></small>
            <div class="mt-2">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="?id=<?= $post_id ?>&like=1" class="btn btn-sm btn-primary">👍 Liker (<?= $post['likes'] ?>)</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <h5>Commentaires</h5>
    <?php if(isset($_SESSION['user_id'])): ?>
        <form method="POST" class="mb-3">
            <textarea name="comment" class="form-control mb-2" rows="3" placeholder="Écrire un commentaire..." required></textarea>
            <button class="btn btn-success" type="submit">Commenter</button>
        </form>
    <?php else: ?>
        <p>Connecte-toi pour commenter.</p>
    <?php endif; ?>

    <?php foreach($comments as $c): ?>
        <div class="card mb-2">
            <div class="card-body">
                <strong><?= htmlspecialchars($c['username']) ?></strong> <small class="text-muted"><?= $c['date_commentaire'] ?></small>
                <p><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>