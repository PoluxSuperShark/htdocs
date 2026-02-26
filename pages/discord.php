<?php
session_start();

require '../config/database.php'; // Décommente si tu as la config DB
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

        <?php 
        // Navbar : vérifie si le fichier existe avant de l'inclure
        $navbarPath = __DIR__ . '/../components/navbar.php';
        if (file_exists($navbarPath)) {
            require_once $navbarPath;
        }
        ?>

        <br>

        <?php 
        // Discord content : vérifie si le fichier existe avant de l'inclure
        $discordSrcPath = __DIR__ . '/../src/discord.php';
        if (file_exists($discordSrcPath)) {
            include $discordSrcPath;
        }
        ?>

        <br>
        
        <?php 
        // Footer : vérifie si le fichier existe avant de l'inclure
        $footerPath = __DIR__ . '/../components/footer.php';
        if (file_exists($footerPath)) {
            include $footerPath;
        }
        ?>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>