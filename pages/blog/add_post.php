<?php
session_start();
require '../../config/database.php';

// 🔹 Vérification connexion + rôle admin
if (!isset($_SESSION['user_id'])) {
    die("Accès refusé : connecte-toi.");
}

$stmt = $pdo->prepare("SELECT role, username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || strtolower(trim($user['role'])) !== 'admin') {
    die("Accès refusé. Vous devez être admin pour voir cette page.");
}

$username = $user['username'];

// 🔹 Traitement du formulaire
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');

    if ($titre && $contenu) {
        // Vérifie si la colonne created_at existe
        $columns = $pdo->query("SHOW COLUMNS FROM posts LIKE 'created_at'")->rowCount();
        if ($columns) {
            $stmt = $pdo->prepare("INSERT INTO posts (titre, contenu, auteur_id, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$titre, $contenu, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO posts (titre, contenu, auteur_id) VALUES (?, ?, ?)");
            $stmt->execute([$titre, $contenu, $_SESSION['user_id']]);
        }

        $message = "Article ajouté avec succès !";
    } else {
        $message = "Merci de remplir tous les champs.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter un article</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

<nav class="navbar navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <span class="navbar-brand">Admin Blog - Ajouter un article</span>
    <span class="text-light me-3">Salut, <?= htmlspecialchars($username) ?></span>
    <a href="../../pages/auth/logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
  </div>
</nav>

<?php if($message): ?>
<div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card shadow-sm p-4">
    <form method="POST">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contenu" class="form-label">Contenu</label>
            <textarea name="contenu" id="contenu" rows="8" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter l'article</button>
        <a href="admin.php" class="btn btn-secondary">Retour au panel</a>
    </form>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>