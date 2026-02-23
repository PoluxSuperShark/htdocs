<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=website;charset=utf8', 'root', '');

// Admin simple
$admin_user = 'admin';
$admin_pass = 'fveARV42&!';

// Déconnexion si ?login=false
if (isset($_GET['login']) && $_GET['login'] === 'false') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Connexion admin
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}

// Formulaire ajout train
$logged_in = $_SESSION['admin'] ?? false;
if ($logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['line'])) {
    $stmt = $pdo->prepare('
        INSERT INTO trains (line, direction, frequency_minutes, start_time, end_time, platform) 
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
        $_POST['line'],
        $_POST['direction'],
        $_POST['frequency_minutes'],
        $_POST['start_time'],
        $_POST['end_time'],
        $_POST['platform'],
    ]);

    $message = 'Ligne ajoutée !';
}

// ==================== DONNÉES ====================
$trains = $pdo->query('SELECT * FROM trains ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
$showLogin = isset($_GET['login']) && $_GET['login'] === 'true';

// Calcul des 5 prochains départs
$now = time();
$upcomingTrains = [];
foreach ($trains as $t) {
    $start_ts = strtotime($t['start_time']);
    $end_ts = strtotime($t['end_time']);
    $next = $start_ts;

    while (count($upcomingTrains) < 5 && $next <= $end_ts) {
        if ($next >= $now) {
            $departure = ($next - $now <= 60) ? "À l'approche" : date('H:i', $next);
            $upcomingTrains[] = [
                'id' => $t['id'],
                'line' => $t['line'],
                'direction' => $t['direction'],
                'departure' => $departure,
                'platform' => $t['platform'],
            ];
        }
        $next += max(1, $t['frequency_minutes']) * 60;
        if ($t['frequency_minutes'] == 0) {
            break;
        }
    }
    if (count($upcomingTrains) >= 5) {
        break;
    }
}

// Compléter jusqu'à 5 lignes
while (count($upcomingTrains) < 5) {
    $upcomingTrains[] = ['id' => '-', 'line' => '-', 'direction' => '-', 'departure' => '-', 'platform' => '-'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Horaire du prochain train</title>
<style>
body { font-family: 'Roboto', sans-serif; background: #f0f4f8; padding: 20px; margin:0; }
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.line-name { font-size: 32px; font-weight: bold; }
.direction { font-size: 20px; margin-top:5px; }
.current-time { font-size: 24px; font-weight: bold; color: #1a3e6d; text-align:right; }
h2 { color: #1a3e6d; margin-bottom:10px; }
table { width: 100%; border-collapse: collapse; font-size: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top:20px;}
th, td { padding: 12px 8px; text-align: center; vertical-align: middle; }
thead { background-color: #1a3e6d; color: #fff; }
tbody tr:nth-child(even) { background-color: #e8f0fb; }
tbody tr:hover { background-color: #ffde9e; }
form { margin-bottom: 20px; }
input, select, button { padding: 6px; margin: 4px; }
button { background: #1a3e6d; color: #fff; border: none; cursor: pointer; }
button:hover { background: #16345b; }
</style>
</head>
<body>

<div class="header">
    <div>
        <div class="line-name"><?php echo htmlspecialchars($trains[0]['line'] ?? ''); ?></div>
        <div class="direction"><?php echo htmlspecialchars($trains[0]['direction'] ?? ''); ?></div>
    </div>
    <div class="current-time" id="current-time"></div>
</div>

<?php if ($showLogin && !$logged_in) { ?>
<h2>Connexion Admin</h2>
<?php if (!empty($error)) {
    echo "<p style='color:red;'>$error</p>";
} ?>
<form method="POST">
Nom d'utilisateur: <input type="text" name="username" required><br>
Mot de passe: <input type="password" name="password" required><br>
<button type="submit">Se connecter</button>
</form>

<?php } elseif ($logged_in) { ?>
<h2>Ajouter une ligne</h2>
<a href="?login=false">Déconnexion</a>
<?php if (!empty($message)) {
    echo "<p style='color:green;'>$message</p>";
} ?>
<form method="POST">
Ligne: <input type="text" name="line" required>
Direction: <input type="text" name="direction" required>
Voie: <input type="text" name="platform" required>
Fréquence (min): <input type="number" name="frequency_minutes" value="0" required>
Début: <input type="time" name="start_time" required>
Fin: <input type="time" name="end_time" required>
<button type="submit">Ajouter</button>
</form>
<?php } ?>

<h2>Horaires des 5 prochains trains</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Ligne</th>
<th>Direction</th>
<th>Prochain départ</th>
<th>Voie</th>
</tr>
</thead>
<tbody>
<?php foreach ($upcomingTrains as $t) { ?>
<tr>
<td><?php echo $t['id']; ?></td>
<td><?php echo htmlspecialchars($t['line']); ?></td>
<td><?php echo htmlspecialchars($t['direction']); ?></td>
<td><?php echo $t['departure']; ?></td>
<td><?php echo htmlspecialchars($t['platform']); ?></td>
</tr>
<?php } ?>
</tbody>
</table>

<script>
function updateTime() {
    const now = new Date();
    const h = now.getHours().toString().padStart(2,'0');
    const m = now.getMinutes().toString().padStart(2,'0');
    const s = now.getSeconds().toString().padStart(2,'0');
    document.getElementById('current-time').textContent = `${h}:${m}:${s}`;
}
setInterval(updateTime, 1000);
updateTime();
</script>

</body>
</html>