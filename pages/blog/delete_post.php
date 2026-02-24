<?php
session_start();
require '../../config/database.php';

if(!isset($_SESSION['blog_admin_id'])) {
    exit("Accès refusé. Vous devez être admin pour supprimer un article.");
}

if(!isset($_GET['id'])) {
    exit("Article introuvable");
}

$id = (int)$_GET['id'];

// Supprimer les commentaires liés
$pdo->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$id]);

// Supprimer les likes liés (si tu as une table likes)
$pdo->prepare("DELETE FROM likes WHERE post_id = ?")->execute([$id]);

// Supprimer l'article
$pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$id]);

header("Location: admin.php");
exit;