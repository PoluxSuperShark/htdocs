<?php
// Get the current protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
             || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// Get hostname
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $host;

// Vérification admin
$isAdmin = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user && strtolower(trim($user['role'])) === 'admin') {
        $isAdmin = true;
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $base_url . "/index.php"; ?>">PoluxSuperShark</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarComplexe" aria-controls="navbarComplexe" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarComplexe">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="<?php echo $base_url . "/index.php"; ?>">Accueil</a></li>

        <!-- Menu déroulant classique -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="menu1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            A propos
          </a>
          <ul class="dropdown-menu" aria-labelledby="menu1">
            <li><a class="dropdown-item" href="#">Gouvernement</a></li>
            <li><a class="dropdown-item" href="#">Transports</a></li>
            <li><a class="dropdown-item" href="#">A propos</a></li>
          </ul>
        </li>

        <!-- Menu avec sous-sous-menu -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="megaMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Boutique
          </a>
          <ul class="dropdown-menu p-3" aria-labelledby="megaMenu" style="width: 500px;">
            <div class="row">
              <div class="col-md-6">
                <h6>Cosmétique </h6>
                <li><a class="dropdown-item" href="#">Pets</a></li>
                <li><a class="dropdown-item" href="#">Cosmétiques</a></li>
                <li><a class="dropdown-item" href="#">Particules</a></li>
              </div>
              <div class="col-md-6">
                <h6>Autres</h6>
                <li><a class="dropdown-item" href="#">Grades</a></li>
                <li><a class="dropdown-item" href="#"></a></li>
              </div>
            </div>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link" href="../pages/downloads.php">Rejoindre</a></li>

        <!-- 🔹 Lien admin uniquement si l'utilisateur est admin -->
        <?php if ($isAdmin): ?>
        <li class="nav-item"><a class="nav-link text-danger" href="<?php echo $base_url . "/admin"; ?>">Admin</a></li>
        <?php endif; ?>

      </ul>

      <form class="d-flex position-relative" role="search" onsubmit="return false;">
        <input 
            class="form-control me-1" 
            type="search" 
            id="searchInput"
            placeholder="Taper ici pour rechercher"
            autocomplete="off"
        >
        <button class="btn btn-outline-success" type="submit">
            Rechercher
        </button>

        <div id="searchResults"
            class="list-group position-absolute w-100 mt-5"
            style="z-index:999;">
        </div>
      </form>
    </div>
  </div>
</nav>

<script>
const searchData = [
    { title: "Accueil", url: "/index.php" },
    { title: "Gouvernement", url: "/gouvernement.php" },
    { title: "Transports", url: "/transports.php" },
    { title: "Boutique - Pets", url: "/pets.php" },
    { title: "Boutique - Cosmétiques", url: "/cosmetiques.php" },
    { title: "Grades", url: "/grades.php" },
    { title: "Téléchargements", url: "/pages/downloads.php" }
];

const input = document.getElementById("searchInput");
const resultsBox = document.getElementById("searchResults");

input.addEventListener("keyup", function () {
    const query = this.value.toLowerCase();
    resultsBox.innerHTML = "";

    if (query.length < 2) return;

    const filtered = searchData.filter(item =>
        item.title.toLowerCase().includes(query)
    );

    filtered.slice(0, 5).forEach(item => {
        const link = document.createElement("a");
        link.href = item.url;
        link.className = "list-group-item list-group-item-action";
        link.textContent = item.title;
        resultsBox.appendChild(link);
    });
});

// Fermer si on clique ailleurs
document.addEventListener("click", function(e) {
    if (!input.contains(e.target)) {
        resultsBox.innerHTML = "";
    }
});
</script>