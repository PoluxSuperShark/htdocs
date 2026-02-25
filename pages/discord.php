<?php
session_start();

require '../config/database.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rejoignez le serveur Discord officiel !</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/settings.css">
    <link rel="stylesheet" href="../css/pages/discord.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    
    <div class="container mt-5">
    
        <?php include "../components/navbar.php"; ?>

        <br>

        <?php include "../src/discord.php"; ?>

        <br>
        
        <?php include "../components/footer.php"; ?>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>