<?php
session_start();

// ⚡ Chemin fiable vers database.php
require_once __DIR__ . '/../../config/database.php';

// Vérifie si l’utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

// Récupération des infos utilisateur
$stmt = $pdo->prepare("
    SELECT username, last_name, first_name, email, address, phone, age, gender, role, created_at, last_login
    FROM users
    WHERE id = :id
");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: ../../index.php");
    exit;
}

// Vérification admin pour navbar
$isAdmin = strtolower(trim($user['role'])) === 'admin';
$username = $user['username'];

// Base URL dynamique
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
             || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $host;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mon compte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.sidebar { position: sticky; top: 20px; }
.nav-pills .nav-link { text-align: left; font-weight: 500; margin-bottom: 8px; }
.nav-pills .nav-link.active { background-color: #0d6efd; color: white; }
</style>
</head>
<body class="bg-light">

<div class="container mt-5">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= $base_url ?>/index.php">PoluxSuperShark</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarComplexe" aria-controls="navbarComplexe" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarComplexe">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="<?= $base_url ?>/index.php">Accueil</a></li>

        <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/pages/downloads.php">Rejoindre</a></li>

        <?php if($isAdmin): ?>
        <li class="nav-item"><a class="nav-link text-warning" href="<?= $base_url ?>/admin.php">Admin Panel</a></li>
        <?php endif; ?>
      </ul>

      <div class="d-flex">
        <span class="navbar-text text-light me-2">Salut, <?= htmlspecialchars($username) ?></span>
        <a class="btn btn-outline-danger btn-sm" href="<?= $base_url ?>/pages/auth/logout.php">Déconnexion</a>
      </div>
    </div>
  </div>
</nav>

<!-- CONTENU -->
<h2 class="mt-4">Bienvenue, <?= htmlspecialchars($username) ?> 👋</h2>

<div class="row mt-4">
    <aside class="col-md-3">
        <div class="sidebar bg-white p-3 rounded-3 shadow-sm">
            <div class="nav flex-column nav-pills" role="tablist">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#infos" type="button">👤 Informations</button>
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#securite" type="button">🔒 Sécurité</button>
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#activite" type="button">📜 Activité</button>
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#parametres" type="button">⚙️ Paramètres</button>
            </div>
        </div>
    </aside>

    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="infos">
                <div class="card shadow-sm rounded-3 p-3">
                    <h5>Informations</h5>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($user['last_name']) ?></p>
                    <p><strong>Prénom :</strong> <?= htmlspecialchars($user['first_name']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>

            <div class="tab-pane fade" id="securite">
                <div class="card shadow-sm rounded-3 p-3">
                    <h5>Sécurité</h5>
                    <p><strong>Création :</strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                    <p><strong>Dernière connexion :</strong> <?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : '-' ?></p>
                    <a href="edit_profile.php" class="btn btn-danger btn-sm">Modifier mes informations</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>