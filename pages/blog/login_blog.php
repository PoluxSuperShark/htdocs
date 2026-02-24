<?php
session_start();
require '../../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérifie uniquement les comptes admin
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND role='admin'");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Stocke les infos essentielles pour le blog
        $_SESSION['blog_admin_id'] = $admin['id'];
        $_SESSION['blog_admin_username'] = $admin['username'];

        header("Location: admin.php"); // Redirection vers l'admin blog
        exit;
    } else {
        $message = 'Email ou mot de passe incorrect pour l’admin.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion Admin Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Connexion Admin Blog</h1>
    <?php if($message) echo "<div class='alert alert-danger'>$message</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email admin" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>
</body>
</html>