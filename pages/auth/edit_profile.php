<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require '../../config/database.php';

$message = '';

// Get current infos
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// If not user
if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $age = $_POST['age'] ?? null;
    $gender = $_POST['gender'] ?? 'other';

    // Verify if email is not used by another one
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :id");
    $stmt->execute([':email' => $email, ':id' => $_SESSION['user_id']]);
    if ($stmt->fetchColumn() > 0) {
        $message = "Cet email est déjà utilisé par un autre utilisateur.";
    } else {
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, address = :address, phone = :phone, age = :age, gender = :gender, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':address' => $address,
            ':phone' => $phone,
            ':age' => $age,
            ':gender' => $gender,
            ':id' => $_SESSION['user_id']
        ]);
        $message = "Profil mis à jour avec succès !";
        // Update if username changes
        $_SESSION['username'] = $user['username'];
        // Reload datas
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Modifier mon profil</h2>

    <?php if($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="text" name="first_name" class="form-control mb-2" placeholder="Prénom" value="<?= htmlspecialchars($user['first_name']) ?>" required>
        <input type="text" name="last_name" class="form-control mb-2" placeholder="Nom" value="<?= htmlspecialchars($user['last_name']) ?>" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="text" name="address" class="form-control mb-2" placeholder="Adresse" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
        <input type="text" name="phone" class="form-control mb-2" placeholder="Téléphone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        <input type="number" name="age" class="form-control mb-2" placeholder="Âge" value="<?= htmlspecialchars($user['age'] ?? '') ?>">
        <select name="gender" class="form-select mb-2">
            <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Homme</option>
            <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Femme</option>
            <option value="other" <?= $user['gender'] === 'other' ? 'selected' : '' ?>>Autre</option>
        </select>
        <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Retour au tableau de bord</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>