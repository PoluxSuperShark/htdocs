<nav class="navbar">
  <div class="logo">PoluxSuperShark</div>

  <div class="burger" onclick="toggleMenu()">
    ☰
  </div>

  <ul class="nav-links" id="navLinks">
    <li><a href="#">Accueil</a></li>
    <li><a href="#">A propos</a></li>
    <li><a href="#">Transports</a></li>
    <li><a href="#">Gouvernement</a></li>
    <li><a href="#">Boutique</a></li>
    <li><a href="#">Rejoindre</a></li>
    <li><a href="#">Mon compte</a></li>
  </ul>
</nav>

<script>
    // Navbar
    function toggleMenu() {
        document.getElementById("navLinks").classList.toggle("active");
    }
</script>