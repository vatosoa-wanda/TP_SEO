<!DOCTYPE html>
<html>
<head>
  <!-- Chemin local vers tinymce.min.js dans votre dossier includes/tinymce -->
  <script src="../tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({
      selector: '#contenu',
      height: 400,
      license_key: 'gpl',
      plugins: 'lists link image table code',
      toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
      // language: 'fr_FR' // optionnel si vous avez le fichier de langue français
      images_upload_url: 'upload.php',
      automatic_uploads: true,
      images_reuse_filename: true,
    });
  </script>
</head>
<body>
<h2>Ajouter un article</h2>
<form method="POST" action="save.php">
  <label>Titre</label><br>
  <input type="text" name="titre" placeholder="Titre" required><br><br>
  
  <label>Slug</label><br>
  <input type="text" name="slug" placeholder="ex: guerre-iran-2024"><br><br>
  
  <label>Meta description (160 car. max)</label><br>
  <textarea name="meta_description" maxlength="160" rows="3" placeholder="Courte description pour les moteurs de recherche..."></textarea><br><br>

  <label>Contenu</label><br>
  <textarea id="contenu" name="contenu"></textarea><br>
  <button type="submit">Enregistrer</button>
</form>
</body>
</html>