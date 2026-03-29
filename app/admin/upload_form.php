<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Upload Images</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; }
    h2 { margin-bottom: 20px; }
    input[type="file"] { display: block; margin-bottom: 15px; }
    button { padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; }
    button:hover { background: #555; }
    .message { margin-top: 20px; padding: 10px; background: #e8f5e9; border-left: 4px solid green; }
    .erreur  { margin-top: 20px; padding: 10px; background: #fdecea; border-left: 4px solid red; }
  </style>
</head>
<body>

<h2>Upload d'images</h2>

<form method="POST" action="upload.php" enctype="multipart/form-data">
  <label>Choisir une ou plusieurs images :</label><br><br>
  <input type="file" name="fichiers[]" multiple accept="image/*">
  <br>
  <button type="submit">Envoyer</button>
</form>

</body>
</html>