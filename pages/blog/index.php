<?php
session_start();

// ======================================
// CONFIGURATION ERREURS & LOG
// ======================================
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log'); // Crée un dossier logs si nécessaire

// ======================================
// INCLUSION BASE DE DONNEES
// ======================================
require_once(__DIR__ . '/../../config/database.php'); // Ajuste le chemin si besoin

// ======================================
// RÉCUPÉRATION DES ARTICLES
// ======================================
$articles = [];
try {
    $stmt = $pdo->query("
        SELECT p.*, u.username,
               (SELECT COUNT(*) FROM likes WHERE post_id=p.id) AS likes
        FROM posts p 
        JOIN users u ON u.id = p.auteur_id
        ORDER BY created_at DESC
    ");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur SQL: " . $e->getMessage());
}

// ======================================
// LOGIQUE ADMIN POUR LIEN DANS NAVBAR
// ======================================
$isAdmin = false;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['role'] === 'admin') {
            $isAdmin = true;
        }
    } catch (PDOException $e) {
        error_log("Erreur SQL role: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mon Blog</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

<!-- NAVBAR INTÉGRÉE -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="../../index.php">PoluxSuperShark</a>
    <div class="d-flex">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="navbar-text text-light me-3">
                Salut, <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
            </span>
            <?php if($isAdmin): ?>
                <a href="admin.php" class="btn btn-outline-warning btn-sm me-2">Admin</a>
            <?php endif; ?>
            <a href="../auth/logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
        <?php else: ?>
            <a href="login_blog.php" class="btn btn-outline-primary btn-sm me-2">Connexion</a>
            <a href="register.php" class="btn btn-outline-success btn-sm">Inscription</a>
        <?php endif; ?>
    </div>
  </div>
</nav>

<h1>Articles</h1>

<?php if(empty($articles)): ?>
    <div class="alert alert-info">Aucun article disponible pour le moment.</div>
<?php endif; ?>

<?php foreach($articles as $post): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($post['titre']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($post['contenu'])) ?></p>
            <small class="text-muted">
                Publié par <?= htmlspecialchars($post['username']) ?> 
                le <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
            </small>
        </div>
    </div>
<?php endforeach; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>