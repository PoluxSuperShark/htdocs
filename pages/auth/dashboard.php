<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require '../../config/database.php';

$stmt = $pdo->prepare("
    SELECT username, last_name, first_name, email, address, phone, age, gender, role, created_at, last_login
    FROM users
    WHERE id = :id
");

$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>
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
    
    <?php include "../../components/navbar.php"; ?>

    <br>

    <h2 class="mb-4">
        Bienvenue <?= htmlspecialchars($user['username']) ?> 👋 !
    </h2>

    <div class="row">

        <!-- FIXED SIDEBAR -->
        <aside class="col-md-3">
            <div class="sidebar bg-white p-3 rounded-3 shadow-sm">
                <div class="nav flex-column nav-pills" role="tablist">

                    <button class="nav-link active"
                            data-bs-toggle="pill"
                            data-bs-target="#infos"
                            type="button">
                        👤 Informations
                    </button>

                    <button class="nav-link"
                            data-bs-toggle="pill"
                            data-bs-target="#securite"
                            type="button">
                        🔒 Sécurité
                    </button>

                    <button class="nav-link"
                            data-bs-toggle="pill"
                            data-bs-target="#activite"
                            type="button">
                        📜 Activité
                    </button>

                    <button class="nav-link"
                            data-bs-toggle="pill"
                            data-bs-target="#parametres"
                            type="button">
                        ⚙️ Paramètres
                    </button>

                    <button class="nav-link"
                            data-bs-toggle="pill"
                            data-bs-target="#notifications"
                            type="button">
                        🔔 Notifications
                    </button>

                    <button class="nav-link"
                            data-bs-toggle="pill"
                            data-bs-target="#confidentialite"
                            type="button">
                        🛡 Confidentialité
                    </button>

                </div>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="col-md-9">
            <div class="tab-content">

                <div class="tab-pane fade show active" id="infos">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <h5>Informations</h5>
                            <hr>
                            <p><strong>Nom :</strong> <?= htmlspecialchars($user['last_name']) ?></p>
                            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['first_name']) ?></p>
                            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="securite">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <h5>Sécurité</h5>
                            <hr>
                            <p><strong>Création :</strong>
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </p>
                            <p><strong>Dernière connexion :</strong>
                                <?= $user['last_login']
                                    ? date('d/m/Y H:i', strtotime($user['last_login']))
                                    : '-' ?>
                            </p>
                            <a href="edit_profile.php" class="btn btn-danger">Modifier mes informations</a>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="activite">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <h5>Activité</h5>
                            <hr>
                            <p>Historique des actions ici.</p>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="parametres">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <h5>Paramètres</h5>
                            <hr>
                            <p>Préférences du compte.</p>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="notifications">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <h5>Notifications</h5>
                            <hr>
                            <p>Gestion des emails et alertes.</p>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="confidentialite">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <h5>Confidentialité</h5>
                            <hr>
                            <p>Options de confidentialité.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="mt-4">
        <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
        <a href="../../index.php" class="btn btn-secondary">Accueil</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>