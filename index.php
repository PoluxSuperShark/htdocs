<?php
session_start();

require './config/database.php';

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Accueil | PoluxSuperShark, une ville virtuelle, une communauté réelle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/settings.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SEO -->
    <meta name="description" content="Accueil du site de PoluxSuperShark">
    <meta name="keywords" content="PoluxSuperShark, PoluxSuperShark.net">

</head>

<body class="bg-light">

    
    <div class="container mt-5">
    
        <?php include "./components/navbar.php"; ?>
        <br>
        <?php include "./src/index.php"; ?>
        <br>
        <?php include "./components/footer.php"; ?>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>