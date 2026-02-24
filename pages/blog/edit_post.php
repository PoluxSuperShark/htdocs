<?php
require 'config.php';
if(!isset($_SESSION['admin'])) exit("Accès refusé");
if(!isset($_GET['id'])) exit("Article introuvable");

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$post) exit("Article introuvable");

if($_SERVER['REQUEST_METHOD']==='POST'){
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $stmt = $pdo->prepare("UPDATE posts SET titre=?, contenu=? WHERE id=?");
    $stmt->execute([$titre, $contenu, $id]);
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Modifier l'article</h1>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($post['titre']) ?>" required>
        </div>
        <div class="mb-3">
            <textarea name="contenu" class="form-control" rows="5" required><?= htmlspecialchars($post['contenu']) ?></textarea>
        </div>
        <button class="btn btn-warning">Modifier</button>
        <a href="admin.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html><?php
require 'config.php';
if(!isset($_SESSION['admin'])) exit("Accès refusé");
if(!isset($_GET['id'])) exit("Article introuvable");

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$post) exit("Article introuvable");

if($_SERVER['REQUEST_METHOD']==='POST'){
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $stmt = $pdo->prepare("UPDATE posts SET titre=?, contenu=? WHERE id=?");
    $stmt->execute([$titre, $contenu, $id]);
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Modifier l'article</h1>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($post['titre']) ?>" required>
        </div>
        <div class="mb-3">
            <textarea name="contenu" class="form-control" rows="5" required><?= htmlspecialchars($post['contenu']) ?></textarea>
        </div>
        <button class="btn btn-warning">Modifier</button>
        <a href="admin.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>