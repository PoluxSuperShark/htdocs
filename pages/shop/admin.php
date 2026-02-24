<?php
require '../../config/database.php'; // Ajuste le chemin selon ton projet

// Gestion des actions
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $image]);
    } elseif ($action === 'update' && $id) {
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $image, $id]);
    }
    header("Location: admin.php");
    exit;
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit;
}

// Récupération des produits
$products = $pdo->query("SELECT * FROM products")->fetchAll();
$editProduct = ($action === 'edit' && $id) ? $pdo->query("SELECT * FROM products WHERE id=$id")->fetch() : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion des produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4 text-center">Admin - Gestion des produits</h1>

    <!-- Formulaire création / édition -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= $editProduct ? 'Modifier un produit' : 'Ajouter un produit' ?></h5>
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nom" required value="<?= $editProduct['name'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required value="<?= $editProduct['description'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Prix" required value="<?= $editProduct['price'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="image" class="form-control" placeholder="Image" required value="<?= $editProduct['image'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success"><?= $editProduct ? 'Mettre à jour' : 'Ajouter' ?></button>
                    <?php if($editProduct): ?>
                        <a href="admin.php" class="btn btn-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des produits -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Nom</th><th>Description</th><th>Prix</th><th>Image</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $prod): ?>
                <tr>
                    <td><?= $prod['id'] ?></td>
                    <td><?= htmlspecialchars($prod['name']) ?></td>
                    <td><?= htmlspecialchars($prod['description']) ?></td>
                    <td><?= number_format($prod['price'], 2) ?> €</td>
                    <td><?= htmlspecialchars($prod['image']) ?></td>
                    <td>
                        <a href="admin.php?action=edit&id=<?= $prod['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="admin.php?action=delete&id=<?= $prod['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>