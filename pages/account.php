<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/components/navbar.css">
<title>Mon compte</title>

<style>
.container {
    margin-top: 50px;
    display: flex;
    font-family: Arial, sans-serif;
    width: 15rem;
    justify-content: center;
}

.tabs {
    display: flex;
    margin-bottom: 20px;
}

.tabs button {
    flex: 1;
    padding: 10px;
    cursor: pointer;
}

main {
    position: relative;
    height: 350px;
}

form {
    position: absolute;
    width: 100%;
    opacity: 0;
    transform: translateX(30px);
    transition: 0.4s ease;
    pointer-events: none;
}

form.active {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
}

input, select {
    width: 100%;
    padding: 8px;
    margin: 6px 0;
}

.remember_me {
    display: flex;
    align-items: center;
    gap: 8px;
}
</style>
</head>

<body>

    <?php include '../components/navbar.php'; ?>

    <br><br><br>

<div class="container">

    <main>

        <div class="tabs">
            <button id="showLogin">Se connecter</button>
            <button id="showRegister">S'inscrire</button>
        </div>

        <form method="post" id="connect" class="active">
            <h2>Se connecter</h2>

            <input type="email" name="login-email" placeholder="Votre e-mail" required>
            <input type="password" name="login-pass" placeholder="Mot de passe" required>

            <div class="remember_me">
                <input type="checkbox" id="rememberMe">
                <label for="rememberMe">Se rappeler de moi</label>
            </div>

            <input type="submit" value="Se connecter">
            <a href="#">Mot de passe oublié ?</a>
        </form>

        <form method="post" id="register">
            <h2>S'inscrire</h2>

            <input type="text" name="name" placeholder="Pseudo ou nom" required>
            <input type="tel" name="tel" placeholder="Numéro de téléphone">
            <input type="text" name="address" placeholder="Adresse">
            <input type="number" name="age" placeholder="Âge">

            <select name="sex">
                <option select disabled>Votre sexe</option>
                <option>Homme</option>
                <option>Femme</option>
            </select>

            <input type="email" name="register-email" placeholder="Votre e-mail" required>
            <input type="password" name="register-pass" placeholder="Mot de passe" required>

            <input type="submit" value="Créer un compte">
        </form>

    </main>

</div>


<script>
    const login     = document.getElementById("connect");
    const register  = document.getElementById("register");

    document.getElementById("showLogin").onclick = () => {
        login.classList.add("active");
        register.classList.remove("active");
    };

    document.getElementById("showRegister").onclick = () => {
        register.classList.add("active");
        login.classList.remove("active");
    };
</script>

<br><hr>

<?php include '../components/footer.php'; ?>

</body>
</html>
