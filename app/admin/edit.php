<?php
include('../includes/config.php');
requireLogin();

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: list.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM article WHERE id = :id");
$stmt->execute([':id' => $id]);
$article = $stmt->fetch();

if (!$article) { header('Location: list.php'); exit; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier l'article</title>
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
  <style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
    input[type="text"], textarea { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
    button { padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; margin-top: 15px; }
    a { color: #333; }
  </style>
</head>
<body>

<h2>Modifier l'article</h2>
<a href="list.php">← Retour à la liste</a><br><br>

<form method="POST" action="update.php">
  <input type="hidden" name="id" value="<?= $article['id'] ?>">

  <label>Titre</label>
  <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required><br><br>

  <label>Slug</label>
  <input type="text" name="slug" value="<?= htmlspecialchars($article['slug']) ?>"><br><br>

  <label>Meta description (160 car. max)</label>
  <textarea name="meta_description" maxlength="160" rows="3"><?= htmlspecialchars($article['meta_description'] ?? '') ?></textarea><br><br>

  <label>Contenu</label>
  <textarea id="contenu" name="contenu"><?= $article['contenu'] ?></textarea><br>

  <button type="submit">Enregistrer les modifications</button>
</form>

</body>
</html>