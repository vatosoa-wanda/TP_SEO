<?php
// Démarrer les sessions
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Détecter l'environnement (Docker ou local)
$host     = getenv('DB_HOST') ?: (file_exists('/.dockerenv') ? 'db' : 'localhost');
$dbname   = 'guerre_iran';
$user     = 'root';
$password = 'root';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Fonction pour rediriger vers login si pas connecté
function requireLogin() {
    if (!isLoggedIn()) {
        // Si on est dans /app/admin/, rediriger vers index.php (login)
        // Si on est dans /app/, rediriger vers admin/index.php
        $current_dir = dirname($_SERVER['PHP_SELF']);
        if (strpos($current_dir, '/admin') !== false) {
            header('Location: index.php');
        } else {
            header('Location: admin/index.php');
        }
        exit;
    }
}
?>