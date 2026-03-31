<?php
require_once '../includes/config.php';

// Vérifier que l'utilisateur est connecté
requireLogin();

// Détruire la session
$_SESSION = [];
session_destroy();

// Rediriger vers la page de login
header('Location: index.php');
exit;
?>