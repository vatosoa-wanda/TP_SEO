<?php
/**
 * Script d'initialisation de la base de données
 * À exécuter UNE FOIS pour créer les tables et l'utilisateur admin
 */

$host     = 'localhost';
$user     = 'root';
$password = 'root';

try {
    // Connexion sans spécifier la base de données
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );

    // Lire le fichier SQL
    $sqlFile = '../bdd/init.sql';
    if (!file_exists($sqlFile)) {
        die("Erreur : Fichier init.sql non trouvé à l'emplacement : $sqlFile");
    }

    $sql = file_get_contents($sqlFile);

    // Exécuter chaque instruction SQL
    $statements = explode(';', $sql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }

    echo "<div style='background: #e8f5e9; padding: 20px; border-radius: 5px; border-left: 4px solid green;'>";
    echo "<h2 style='color: green; margin-top: 0;'>✅ Succès!</h2>";
    echo "<p><strong>La base de données a été initialisée avec succès.</strong></p>";
    echo "<p>Les tables et données par défaut ont été créées.</p>";
    echo "<hr>";
    echo "<p><strong>Identifiants pour se connecter :</strong></p>";
    echo "<ul>";
    echo "<li><strong>Utilisateur :</strong> admin</li>";
    echo "<li><strong>Mot de passe :</strong> admin123</li>";
    echo "</ul>";
    echo "<p><a href='login.php' style='background: green; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>Accéder à la page de connexion →</a></p>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<div style='background: #fdecea; padding: 20px; border-radius: 5px; border-left: 4px solid red;'>";
    echo "<h2 style='color: red; margin-top: 0;'>❌ Erreur!</h2>";
    echo "<p><strong>Erreur lors de l'initialisation :</strong></p>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 3px; overflow: auto;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initialisation de la base de données</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 30px;">
        <h1>Initialisation Base de Données</h1>
        <p style="color: #666;">Création des tables et données par défaut</p>
    </div>
</body>
</html>
