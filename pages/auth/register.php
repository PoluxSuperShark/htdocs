<?php
session_start();
require '../../config/database.php';

$message = '';

// Get and put datas in database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Infos
    $username   = $_POST['username'] ?? '';     // Username
    $email      = $_POST['email'] ?? '';        // Email
    $password   = $_POST['password'] ?? '';     // Password
    $first_name = $_POST['first_name'] ?? '';   // First name
    $last_name  = $_POST['last_name'] ?? '';    // Last name
    $address    = $_POST['address'] ?? null;    // Adress
    $phone      = $_POST['phone'] ?? null;      // Phone
    $age        = $_POST['age'] ?? null;        // Age
    $gender     = $_POST['gender'] ?? 'other';  // Gender

    // Verify if email or username is already used
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR username = :username");
    $stmt->execute([':email' => $email, ':username' => $username]);

    if ($stmt->fetchColumn() > 0) {
        
        $message = 'Email ou nom d\'utilisateur déjà utilisé.'; // Error
    
    } else {
        
        // Password verify
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users 
            (username, email, password, first_name, last_name, address, phone, age, gender) 
            VALUES 
            (:username, :email, :password, :first_name, :last_name, :address, :phone, :age, :gender)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':username' => $username,           // Username
            ':email' => $email,                 // Email
            ':password' => $hashedPassword,     // Password
            ':first_name' => $first_name,       // First name
            ':last_name' => $last_name,         // Last name
            ':address' => $address,             // Adress
            ':phone' => $phone,                 // Phone
            ':age' => $age,                     // Age
            ':gender' => $gender                // Gender
        ]);

        $message = 'Inscription réussie ! Vous pouvez maintenant vous connecter.'; // Success

    }
}

header("Location: index.php?message=" . urlencode($message));
exit;