<?php
session_start();
require __DIR__ . '../../../../config/database.php';

/* ============================
   SÉCURITÉ ADMIN
============================ */
if (!isset($_SESSION['user_id'])) {
    die("Non autorisé");
}

$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    die("Accès réservé aux administrateurs");
}

/* ============================
   CREATE LIGNE
============================ */
if (isset($_POST['create_line'])) {

    $type  = htmlspecialchars($_POST['type']);
    $name  = htmlspecialchars($_POST['name']);
    $color = htmlspecialchars($_POST['color']);
    $start = $_POST['service_start'];
    $end   = $_POST['service_end'];

    $imagePath = null;

    if (!empty($_FILES['map_image']['name'])) {

        $ext = strtolower(pathinfo($_FILES['map_image']['name'], PATHINFO_EXTENSION));

        if ($ext === 'png') {

            $newName = uniqid() . '.png';
            $uploadDir = '../uploads/maps/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $imagePath = $uploadDir . $newName;
            move_uploaded_file($_FILES['map_image']['tmp_name'], $imagePath);
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO transport_lines
        (type, name, color, service_start, service_end, map_image)
        VALUES (:type, :name, :color, :start, :end, :map)
    ");

    $stmt->execute([
        ':type'  => $type,
        ':name'  => $name,
        ':color' => $color,
        ':start' => $start,
        ':end'   => $end,
        ':map'   => $imagePath
    ]);
}

/* ============================
   DELETE LIGNE
============================ */
if (isset($_POST['delete_line'])) {

    $id = (int)$_POST['line_id'];

    $stmt = $pdo->prepare("DELETE FROM transport_lines WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

/* ============================
   AJOUT STATION
============================ */
if (isset($_POST['add_station'])) {

    $lineId = (int)$_POST['line_id'];
    $stationName = htmlspecialchars($_POST['station_name']);

    $stmt = $pdo->prepare("SELECT MAX(position) FROM transport_stations WHERE line_id = :id");
    $stmt->execute([':id' => $lineId]);
    $position = $stmt->fetchColumn();
    $position = $position ? $position + 1 : 1;

    $stmt = $pdo->prepare("
        INSERT INTO transport_stations (line_id, name, position)
        VALUES (:line_id, :name, :position)
    ");

    $stmt->execute([
        ':line_id' => $lineId,
        ':name' => $stationName,
        ':position' => $position
    ]);
}

/* ============================
   DELETE STATION
============================ */
if (isset($_POST['delete_station'])) {

    $stationId = (int)$_POST['station_id'];

    $stmt = $pdo->prepare("DELETE FROM transport_stations WHERE id = :id");
    $stmt->execute([':id' => $stationId]);
}

/* ============================
   RÉCUPÉRATION DONNÉES
============================ */
$stmt = $pdo->query("SELECT * FROM transport_lines ORDER BY created_at DESC");
$lines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Transports</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

<h2>Gestion des lignes</h2>
<hr>

<form method="POST" enctype="multipart/form-data" class="mb-5">

    <div class="row">
        <div class="col-md-2">
            <select name="type" class="form-control" required>
                <option value="Bus">Bus</option>
                <option value="Métro">Métro</option>
                <option value="Tramway">Tramway</option>
                <option value="Train">Train</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="text" name="name" class="form-control" placeholder="Nom" required>
        </div>

        <div class="col-md-2">
            <input type="color" name="color" class="form-control form-control-color" required>
        </div>

        <div class="col-md-2">
            <input type="time" name="service_start" class="form-control" required>
        </div>

        <div class="col-md-2">
            <input type="time" name="service_end" class="form-control" required>
        </div>

        <div class="col-md-2">
            <input type="file" name="map_image" accept="image/png" class="form-control">
        </div>
    </div>

    <button type="submit" name="create_line" class="btn btn-success mt-3">
        Créer la ligne
    </button>
</form>

<?php foreach ($lines as $line): ?>

<div class="card mb-4">
    <div class="card-body">

        <h5 style="color: <?= htmlspecialchars($line['color']) ?>">
            <?= htmlspecialchars($line['name']) ?> (<?= htmlspecialchars($line['type']) ?>)
        </h5>

        <p>
            Service : <?= $line['service_start'] ?> - <?= $line['service_end'] ?>
        </p>

        <?php if ($line['map_image']): ?>
            <img src="<?= $line['map_image'] ?>" width="300" class="mb-3">
        <?php endif; ?>

        <!-- Ajouter station -->
        <form method="POST" class="d-flex mb-2">
            <input type="hidden" name="line_id" value="<?= $line['id'] ?>">
            <input type="text" name="station_name" class="form-control me-2" placeholder="Nouvelle station" required>
            <button type="submit" name="add_station" class="btn btn-primary btn-sm">
                Ajouter
            </button>
        </form>

        <!-- Liste stations -->
        <?php
        $stmtStations = $pdo->prepare("
            SELECT * FROM transport_stations
            WHERE line_id = :id
            ORDER BY position ASC
        ");
        $stmtStations->execute([':id' => $line['id']]);
        $stations = $stmtStations->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <ul class="list-group mb-3">
            <?php foreach ($stations as $station): ?>
                <li class="list-group-item d-flex justify-content-between">
                    <?= htmlspecialchars($station['name']) ?>
                    <form method="POST">
                        <input type="hidden" name="station_id" value="<?= $station['id'] ?>">
                        <button type="submit" name="delete_station" class="btn btn-danger btn-sm">
                            Supprimer
                        </button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Supprimer ligne -->
        <form method="POST">
            <input type="hidden" name="line_id" value="<?= $line['id'] ?>">
            <button type="submit" name="delete_line" class="btn btn-danger">
                Supprimer la ligne
            </button>
        </form>

    </div>
</div>

<?php endforeach; ?>

</div>
</body>
</html>