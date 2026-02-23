<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <!-- Inscription -->
        <div class="col-md-6">
            <h3>S'inscrire</h3>
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label>Nom d'utilisateur</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Prénom</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Nom</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Adresse</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Téléphone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Âge</label>
                    <input type="number" name="age" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Sexe</label>
                    <select name="gender" class="form-select">
                        <option value="male">Homme</option>
                        <option value="female">Femme</option>
                        <option value="other" selected>Autre</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
        </div>

        <!-- Connexion -->
        <div class="col-md-6">
            <h3>Se connecter</h3>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Se connecter</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>