<?php
require_once 'includes/config.php';

$error = '';

// Si l'utilisateur est déjà connecté, rediriger vers l'accueil
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        // Chercher l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && $password === $admin['password']) {
            // Connexion réussie - créer la session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            // Rediriger vers la page d'accueil
            header('Location: index.php');
            exit;
        } else {
            $error = 'Identifiants incorrects';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Conflit en Iran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Georgia, 'Times New Roman', serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .login-container h1 {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #1a1a1a;
            font-weight: normal;
        }

        .login-container p {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: Arial, sans-serif;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #c00;
            box-shadow: 0 0 5px rgba(204, 0, 0, 0.2);
        }

        .error {
            background: #fee;
            color: #c00;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #c00;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #c00;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background: #a00;
        }

        .info {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #666;
            border-left: 4px solid #c00;
        }

        .info strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion</h1>
        <p>Accédez à l'espace d'administration</p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>

        <div class="info">
            <strong>Identifiants de démonstration :</strong><br>
            Utilisateur : admin<br>
            Mot de passe : admin123
        </div>
    </div>
</body>
</html>
