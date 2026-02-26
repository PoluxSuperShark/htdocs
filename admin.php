<?php
session_start();

/* =================================================
   🔹 CONFIGURATION PDO
================================================= */
$host = 'localhost';
$db   = 'poluxsupershark';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur PDO : " . $e->getMessage());
}

/* =================================================
   🔹 VERIFICATION ADMIN
================================================= */
if (empty($_SESSION['user_id'])) {
    die("Accès refusé : tu dois te connecter.");
}

$user_id = (int)$_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || strtolower(trim($user['role'])) !== 'admin') {
    die("Accès refusé : tu n'es pas admin.");
}

$username = $user['username'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Panel Admin Racine</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Reset du body */
body { 
    background:#f4f6f9; 
    margin:0; 
    padding:0; 
}

/* Cards */
.card { 
    border:none; 
    border-radius:15px; 
    transition: transform .2s; 
}
.card:hover { 
    transform: translateY(-5px); 
}

/* Contenu principal décalé sous la navbar */
.main-content {
    padding-top: 80px; /* hauteur navbar fixe */
}
</style>
</head>
<body>

<!-- Navbar fixe avec z-index -->
<nav class="navbar navbar-dark bg-dark position-fixed top-0 w-100 navbar-expand-lg" style="z-index: 1050;">
  <div class="container-fluid">
    <span class="navbar-brand">Administration</span>
    <span class="text-white ms-3">Bonjour, <?= htmlspecialchars($username) ?></span>
    <a href="./pages/auth/logout.php" class="btn btn-danger btn-sm">Déconnexion</a>
  </div>
</nav>

<!-- Contenu principal -->
<div class="container main-content">
    <h2 class="mb-4">Bienvenue sur le panel admin, <?= htmlspecialchars($username) ?></h2>
    <p>Choisis la section à gérer :</p>

    <a href="/index.php" class="btn btn-link mb-3">Retour à l'accueil</a>
    <hr>

    <div class="row g-4">

        <!-- Section Blog -->
        <h3>Blog</h3>
        <div class="col-md-4">
            <div class="card shadow p-4 text-center">
                <h5>Blog</h5>
                <p>Ajouter un post</p>
                <a href="pages/blog/add_post.php" class="btn btn-primary btn-sm">Ajouter</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow p-4 text-center">
                <h5>Blog</h5>
                <p>Supprimer un post</p>
                <a href="pages/blog/delete_post.php" class="btn btn-primary btn-sm">Supprimer</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow p-4 text-center">
                <h5>Blog</h5>
                <p>Login</p>
                <a href="pages/blog/login_blog.php" class="btn btn-primary btn-sm">Login</a>
            </div>
        </div>

        <hr>

        <!-- Section Shop -->
        <h3>Shop</h3>
        <div class="col-md-4">
            <div class="card shadow p-4 text-center">
                <h5>Shop</h5>
                <p>Ajouter un produit</p>
                <a href="pages/shop/admin_shop.php" class="btn btn-primary btn-sm">Ajouter</a>
            </div>
        </div>

        <hr>

        <!-- Section Transports -->
        <h3>Transports</h3>
        <div class="col-md-4">
            <div class="card shadow p-4 text-center">
                <h5>Transports</h5>
                <p>Ajouter une ligne</p>
                <a href="pages/about/transports/admin_transports.php" class="btn btn-primary btn-sm">Ajouter</a>
            </div>
        </div>

    </div>
</div>

</body>
</html>