<?php
session_start();

// Supprime uniquement les variables de session du blog/admin
unset($_SESSION['blog_admin_id']);
unset($_SESSION['blog_admin_username']);

// Optionnel : tu peux aussi faire session_destroy() si c'est uniquement pour le blog
// session_destroy();

// Redirection vers la page de login du blog
header("Location: login_blog.php");
exit;