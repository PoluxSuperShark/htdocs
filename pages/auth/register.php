<?php
session_start();
require '../../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération des données
    $username   = $_POST['username'] ?? '';
    $email      = $_POST['email'] ?? '';
    $password   = $_POST['password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name  = $_POST['last_name'] ?? '';
    $address    = $_POST['address'] ?? null;
    $phone      = $_POST['phone'] ?? null;
    $age        = $_POST['age'] ?? null;
    $gender     = $_POST['gender'] ?? 'other';

    // Vérifie si l'email ou le username existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR username = :username");
    $stmt->execute([':email' => $email, ':username' => $username]);

    if ($stmt->fetchColumn() > 0) {
        $message = 'Email ou nom d\'utilisateur déjà utilisé.';
    } else {

        // Hash du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertion en base
        $sql = "INSERT INTO users 
            (username, email, password, first_name, last_name, address, phone, age, gender) 
            VALUES 
            (:username, :email, :password, :first_name, :last_name, :address, :phone, :age, :gender)";

        $stmtInsert = $pdo->prepare($sql);
        $stmtInsert->execute([
            ':username'   => $username,
            ':email'      => $email,
            ':password'   => $hashedPassword,
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':address'    => $address,
            ':phone'      => $phone,
            ':age'        => $age,
            ':gender'     => $gender
        ]);

        $message = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';

        /* ==========================
           WEBHOOK DISCORD
        ========================== */

        $webhookUrl = ""; // ⚠️ Remplace-le par ton webhook sécurisé

        $payload = [
            "embeds" => [
                [
                    "title" => "Nouvelle inscription",
                    "color" => 5763719, // vert
                    "fields" => [
                        [
                            "name" => "Nom d'utilisateur",
                            "value" => $username,
                            "inline" => true
                        ],
                        [
                            "name" => "Email",
                            "value" => $email,
                            "inline" => true
                        ],
                        [
                            "name" => "Nom complet",
                            "value" => $first_name . " " . $last_name
                        ],
                        [
                            "name" => "Âge",
                            "value" => $age ?: "Non renseigné",
                            "inline" => true
                        ],
                        [
                            "name" => "Genre",
                            "value" => $gender,
                            "inline" => true
                        ],
                        [
                            "name" => "IP",
                            "value" => $_SERVER['REMOTE_ADDR'] ?? 'Inconnue'
                        ]
                    ],
                    "timestamp" => date("c")
                ]
            ]
        ];

        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2); // Timeout court pour ne pas bloquer
        curl_exec($ch);
        curl_close($ch);
    }
}

// Redirection vers index avec message
header("Location: index.php");
exit;