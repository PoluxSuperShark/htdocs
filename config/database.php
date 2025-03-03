<?php
$host = "localhost";
$dbname = "website";
$username = "root";  // Remplace par ton utilisateur MySQL
$password = "";      // Mets ton mot de passe s'il y en a un

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie !";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
