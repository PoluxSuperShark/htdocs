<?php
// Get the current protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
             || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// Get hostname
$host = $_SERVER['HTTP_HOST'];
// e.g: https://poluxsupershark.net/
$base_url = $protocol . $host;

// echo $base_url;

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

        <!-- Menu avec sous-sous-menu (mega menu simple) -->
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
      </ul>

      <!-- Formulaire ou bouton à droite -->
      <form class="d-flex" role="search">
        <input class="form-control me-1" type="search" placeholder="Taper ici pour rechercher">
        <button class="btn btn-outline-success" type="submit">Rechercher</button>
      </form>
    </div>
  </div>
</nav>