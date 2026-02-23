<?php
session_start();                // Start a session
session_unset();                // Make unset the session
session_destroy();              // Destroy the session
header("Location: index.php");  // Go back in login form
exit;                           // Stop