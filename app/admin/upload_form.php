<?php
include('../includes/config.php');
requireLogin();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionnaire d'images</title>
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-header">
  <h1>📰 Conflit en Iran - Administration</h1>
  <div>
    <span class="user-info">Connecté : <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
    <a href="logout.php">Déconnexion</a>
  </div>
</div>

<div class="admin-container">
  <div class="breadcrumb">
    <a href="list.php">← Retour à la liste</a>
  </div>

  <div class="page-title">Upload d'images</div>
  <p class="page-subtitle">Téléchargez des images pour vos articles</p>

  <div class="card">
    <form method="POST" action="upload.php" enctype="multipart/form-data" class="card-body">
      <div class="form-group">
        <label>Choisir une ou plusieurs images</label>
        <input type="file" name="fichiers[]" multiple accept="image/*" required>
        <small>Formats supportés : JPG, PNG, GIF, WebP (Max 5 MB par image)</small>
      </div>

      <div class="card-footer">
        <a href="list.php" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-success">⬆️ Envoyer les images</button>
      </div>
    </form>
  </div>
</div>

<div class="admin-footer">
  <p>&copy; 2026 Conflit en Iran - Back-office d'administration</p>
</div>

</body>
</html>