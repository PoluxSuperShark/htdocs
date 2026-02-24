<?php
require __DIR__ . '../../../../config/database.php';

/* ============================
   RECHERCHE
============================ */
$searchResults = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {

    $search = "%" . $_GET['search'] . "%";

    $stmt = $pdo->prepare("
        SELECT * FROM transport_lines
        WHERE name LIKE :search
        ORDER BY name ASC
    ");
    $stmt->execute([':search' => $search]);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ============================
   AFFICHER LIGNE PAR ID
============================ */
$line = null;
$stations = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $stmt = $pdo->prepare("
        SELECT * FROM transport_lines
        WHERE id = :id
    ");
    $stmt->execute([':id' => (int)$_GET['id']]);
    $line = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($line) {
        $stmtStations = $pdo->prepare("
            SELECT * FROM transport_stations
            WHERE line_id = :id
            ORDER BY position ASC
        ");
        $stmtStations->execute([':id' => $line['id']]);
        $stations = $stmtStations->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Transports</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

<h2>Recherche de ligne</h2>
<hr>

<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Nom de la ligne...">
        <button class="btn btn-primary">Rechercher</button>
    </div>
</form>

<?php if (!empty($searchResults)): ?>
    <h5>Résultats :</h5>
    <ul class="list-group mb-4">
        <?php foreach ($searchResults as $result): ?>
            <li class="list-group-item d-flex justify-content-between">
                <span>
                    <?= htmlspecialchars($result['name']) ?> 
                    (<?= htmlspecialchars($result['type']) ?>)
                </span>
                <a href="?id=<?= $result['id'] ?>" class="btn btn-sm btn-outline-primary">
                    Voir
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($line): ?>

<div class="card">
    <div class="card-body">

        <h3 style="color: <?= htmlspecialchars($line['color']) ?>">
            <?= htmlspecialchars($line['name']) ?>
            (<?= htmlspecialchars($line['type']) ?>)
        </h3>

        <p>
            Service : 
            <?= $line['service_start'] ?> - <?= $line['service_end'] ?>
        </p>

        <?php if ($line['map_image']): ?>
            <img src="<?= $line['map_image'] ?>" class="img-fluid mb-3">
        <?php endif; ?>

        <h5>Stations :</h5>
        <ul class="list-group">
            <?php foreach ($stations as $station): ?>
                <li class="list-group-item">
                    <?= htmlspecialchars($station['name']) ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</div>

<?php elseif (isset($_GET['id'])): ?>
    <div class="alert alert-danger">
        Ligne introuvable.
    </div>
<?php endif; ?>

<a href="https://trains-map.poluxsupershark.net/" target="_blank">Consulter la carte interactive</a>

</div>
</body>
</html>