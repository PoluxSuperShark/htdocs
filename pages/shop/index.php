<?php
require './config.php'; // ajuste le chemin selon ton arborescence

// Récupération des produits
$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>La Boutique | PoluxSuperShark</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php include "../../components/navbar.php"; ?>
    <br>
    <h1 class="mb-4">La Boutique</h1>
    <div class="row">
        <?php foreach($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="../../images/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="card-text fw-bold"><?= number_format($product['price'], 2) ?> €</p>
                        <a href="checkout.php?id=<?= $product['id'] ?>" class="btn btn-primary mt-auto">Acheter</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php include "../../components/footer.php"; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>