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
  <input type="text" name="titre" placeholder="Titre" required><br><br>
  <input type="text" name="slug" placeholder="Slug"><br><br>
  <textarea id="contenu" name="contenu"></textarea><br>
  <button type="submit">Enregistrer</button>
</form>
</body>
</html>