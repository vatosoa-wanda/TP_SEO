<!DOCTYPE html>
<html>
<head>
  <script src="../tinymce/tinymce.min.js"></script>
  <!-- <script>
    tinymce.init({
      selector: '#contenu',
      height: 400
    });
  </script> -->
</head>
<body>

<h2>Ajouter un article</h2>

<form method="POST" action="save.php">
  <input type="text" name="titre" placeholder="Titre" required><br><br>

  <textarea id="contenu" name="contenu"></textarea><br>
  <input type="text" name="slug" placeholder="Slug"><br><br>

  <button type="submit">Enregistrer</button>
</form>

</body>
</html>