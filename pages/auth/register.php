<?php
session_start();
require '../../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $address = $_POST['address'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $age = $_POST['age'] ?? null;
    $gender = $_POST['gender'] ?? 'other';

    // Vérifier que l'email ou username n'existe pas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR username = :username");
    $stmt->execute([':email' => $email, ':username' => $username]);
    if ($stmt->fetchColumn() > 0) {
        $message = 'Email ou nom d\'utilisateur déjà utilisé.';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users 
            (username, email, password, first_name, last_name, address, phone, age, gender) 
            VALUES 
            (:username, :email, :password, :first_name, :last_name, :address, :phone, :age, :gender)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':address' => $address,
            ':phone' => $phone,
            ':age' => $age,
            ':gender' => $gender
        ]);
        $message = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
    }
}

header("Location: index.php?message=" . urlencode($message));
exit;