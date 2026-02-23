<?php

// DB infos
$host = 'localhost';                // ton serveur MySQL
$db   = 'poluxsupershark';          // nom de ta base
$user = 'root';                     // utilisateur MySQL
$pass = '';                         // mot de passe
$charset = 'utf8mb4';

// Connection to DB
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Gestion des erreurs
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Résultat en tableau associatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Pour sécurité SQL
];

// Err Managment
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>