<?php
session_start();

require '../config/database.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>A propos de nous</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/settings.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        nav {
            width: 200%;
        }
    </style>
    <style>
        .sidebar {
            position: sticky;
            top: 20px;
        }

        .nav-pills .nav-link {
            text-align: left;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .nav-pills .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>

<body class="bg-light">

    
    <div class="container mt-5">
    
        <?php include "../components/navbar.php"; ?>

        <?php include "../src/about.php"; ?>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>