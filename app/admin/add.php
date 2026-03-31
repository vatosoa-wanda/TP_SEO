<?php
include('../includes/config.php');
requireLogin();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un article</title>
  <link rel="stylesheet" href="css/admin.css">
  <script src="../tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({
      selector: '#contenu',
      height: 400,
      license_key: 'gpl',
      plugins: 'lists link image table code',
      toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
      images_upload_url: 'upload.php',
      automatic_uploads: true,
      images_reuse_filename: true,
      convert_urls: false,
    });
  </script>
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

  <div class="page-title">Nouvel article</div>
  <p class="page-subtitle">Créez un nouvel article pour le journal</p>

  <div class="card">
    <form method="POST" action="save.php" class="card-body">
      <div class="form-group">
        <label>Titre de l'article</label>
        <input type="text" name="titre" placeholder="Entrez le titre principal" required>
        <small>Ce titre s'affichera en gros dans les listes</small>
      </div>
      
      <div class="form-group">
        <label>URL générée automatiquement</label>
        <input type="text" id="slug-preview" name="slug-preview" disabled="disabled" placeholder="L'URL sera générée à partir du titre..." value="">
        <small>L'URL s'auto-génère à partir du titre (espaces → tirets)</small>
      </div>
      
      <div class="form-group">
        <label>Meta description</label>
        <textarea name="meta_description" maxlength="160" placeholder="Courte description pour les moteurs de recherche..." rows="2"></textarea>
        <small>Maximum 160 caractères</small>
      </div>

      <div class="form-group">
        <label>Contenu de l'article</label>
        <textarea id="contenu" name="contenu" placeholder="Écrivez votre article ici..."></textarea>
        <small>Vous pouvez mettre en forme le texte, ajouter des liens et des images</small>
      </div>

      <div class="card-footer">
        <a href="list.php" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-success">💾 Enregistrer l'article</button>
      </div>
    </form>
  </div>
</div>

<div class="admin-footer">
  <p>&copy; 2026 Conflit en Iran - Back-office d'administration</p>
</div>

</body>
</html>