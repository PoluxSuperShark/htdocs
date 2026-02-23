<?php
session_start();
require '../../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php"); // redirige vers tableau de bord
        exit;
    } else {
        $message = 'Email ou mot de passe incorrect.';
    }
}

header("Location: index.php?message=" . urlencode($message));
exit;