<?php
/**
 * SCRIPT DE DIAGNOSTIC
 * Vérifie l'état de la base de données et crée tout ce qui manque
 */

$host     = 'localhost';
$user     = 'root';
$password = 'root';
$dbname   = 'guerre_iran';

echo "<html><head><meta charset='UTF-8'><style>";
echo "body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }";
echo ".success { background: #e8f5e9; padding: 15px; border-left: 4px solid green; margin: 10px 0; }";
echo ".error { background: #fdecea; padding: 15px; border-left: 4px solid red; margin: 10px 0; }";
echo ".info { background: #e3f2fd; padding: 15px; border-left: 4px solid blue; margin: 10px 0; }";
echo "code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; }";
echo "h2 { color: #333; border-bottom: 2px solid #ddd; padding-bottom: 10px; }";
echo "</style></head><body>";
echo "<h1>🔍 Diagnostic - Authentification</h1>";

// ÉTAPE 1 : Vérifier connexion à MySQL
echo "<h2>ÉTAPE 1 : Connexion à MySQL</h2>";
try {
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "<div class='success'>✅ Connexion MySQL réussie</div>";
} catch (PDOException $e) {
    echo "<div class='error'>❌ Impossible de se connecter à MySQL :<br>" . htmlspecialchars($e->getMessage()) . "</div>";
    die();
}

// ÉTAPE 2 : Vérifier si la base de données existe
echo "<h2>ÉTAPE 2 : Vérifier la base de données <code>$dbname</code></h2>";
try {
    $result = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    if ($result->fetch()) {
        echo "<div class='success'>✅ Base de données <code>$dbname</code> existe</div>";
    } else {
        echo "<div class='error'>❌ Base de données <code>$dbname</code> N'EXISTE PAS</div>";
        echo "<div class='info'>⚠️ Création automatique...</div>";
        try {
            $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
            echo "<div class='success'>✅ Base de données créée</div>";
        } catch (Exception $e) {
            echo "<div class='error'>❌ Erreur lors de la création : " . htmlspecialchars($e->getMessage()) . "</div>";
            die();
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
    die();
}

// Se connecter à la bonne base de données
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    echo "<div class='error'>❌ Impossible de se connecter à la base : " . htmlspecialchars($e->getMessage()) . "</div>";
    die();
}

// ÉTAPE 3 : Vérifier la table admin
echo "<h2>ÉTAPE 3 : Vérifier la table <code>admin</code></h2>";
try {
    $result = $pdo->query("SHOW TABLES LIKE 'admin'");
    if ($result->fetch()) {
        echo "<div class='success'>✅ Table <code>admin</code> existe</div>";
    } else {
        echo "<div class='error'>❌ Table <code>admin</code> N'EXISTE PAS - Création</div>";
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS admin (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            echo "<div class='success'>✅ Table admin créée</div>";
        } catch (Exception $e) {
            echo "<div class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

// ÉTAPE 4 : Vérifier l'utilisateur admin
echo "<h2>ÉTAPE 4 : Vérifier l'utilisateur <code>admin</code></h2>";
try {
    $stmt = $pdo->prepare("SELECT id, username, password FROM admin WHERE username = 'admin'");
    $stmt->execute();
    $user_admin = $stmt->fetch();
    
    if ($user_admin) {
        echo "<div class='success'>✅ Utilisateur <code>admin</code> existe en base</div>";
        echo "<div class='info'>ID: " . htmlspecialchars($user_admin['id']) . "<br>";
        echo "Password hash: " . htmlspecialchars(substr($user_admin['password'], 0, 40)) . "...</div>";
        
        // Tester le mot de passe
        if (password_verify('admin123', $user_admin['password'])) {
            echo "<div class='success'>✅ Le mot de passe 'admin123' est CORRECT</div>";
        } else {
            echo "<div class='error'>❌ Le mot de passe 'admin123' ne correspond PAS au hash</div>";
            echo "<div class='info'>Réinitialisation du mot de passe...</div>";
            $new_hash = password_hash('admin123', PASSWORD_BCRYPT);
            $pdo->prepare("UPDATE admin SET password = ? WHERE username = 'admin'")->execute([$new_hash]);
            echo "<div class='success'>✅ Mot de passe réinitialisé à 'admin123'</div>";
        }
    } else {
        echo "<div class='error'>❌ Utilisateur <code>admin</code> N'EXISTE PAS - Création</div>";
        try {
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)")
                ->execute(['admin', $hash]);
            echo "<div class='success'>✅ Utilisateur admin créé avec mot de passe: admin123</div>";
        } catch (Exception $e) {
            echo "<div class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

// ÉTAPE 5 : Vérifier la table article
echo "<h2>ÉTAPE 5 : Vérifier la table <code>article</code></h2>";
try {
    $result = $pdo->query("SHOW TABLES LIKE 'article'");
    if ($result->fetch()) {
        echo "<div class='success'>✅ Table <code>article</code> existe</div>";
    } else {
        echo "<div class='error'>❌ Table <code>article</code> N'EXISTE PAS - Création</div>";
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS article (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titre VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                contenu LONGTEXT NOT NULL,
                meta_description VARCHAR(160) DEFAULT NULL,
                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
                date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_slug (slug),
                INDEX idx_date (date_creation)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            echo "<div class='success'>✅ Table article créée</div>";
        } catch (Exception $e) {
            echo "<div class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

// RÉSUMÉ FINAL
echo "<h2 style='color: green;'>✅ DIAGNOSTIC TERMINÉ</h2>";
echo "<div class='success'>";
echo "<strong>La base de données est maintenant prête !</strong><br><br>";
echo "Identifiants de connexion :<br>";
echo "<code>Utilisateur : admin</code><br>";
echo "<code>Mot de passe : admin123</code><br><br>";
echo "<a href='login.php' style='display: inline-block; background: green; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>→ Aller au login</a>";
echo "</div>";

?>
</body></html>
