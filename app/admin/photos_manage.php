<?php
include('../includes/config.php');
requireLogin();

$id_article = $_GET['id'] ?? null;

if (!$id_article || !is_numeric($id_article)) {
    header('Location: list.php');
    exit;
}

// Récupérer l'article
$stmt = $pdo->prepare("SELECT * FROM article WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id_article]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: list.php');
    exit;
}

// Récupérer les photos existantes
$stmt_photos = $pdo->prepare("SELECT * FROM photos WHERE id_article = :id_article ORDER BY date_ajout ASC");
$stmt_photos->execute([':id_article' => $id_article]);
$photos = $stmt_photos->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gérer les photos de l'article</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .photos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 15px;
      margin: 20px 0;
    }
    .photo-item {
      position: relative;
      border: 1px solid #ddd;
      border-radius: 4px;
      overflow: hidden;
      background: #f5f5f5;
    }
    .photo-item img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      display: block;
    }
    .photo-delete {
      position: absolute;
      top: 5px;
      right: 5px;
      background: #c00;
      color: white;
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      cursor: pointer;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .photo-delete:hover {
      background: #a00;
    }
    .photo-info {
      font-size: 0.8rem;
      color: #666;
      padding: 8px;
      text-align: center;
    }
    .upload-area {
      border: 2px dashed #c00;
      border-radius: 4px;
      padding: 30px;
      text-align: center;
      background: #fafafa;
      margin: 20px 0;
    }
    .upload-area input[type="file"] {
      display: none;
    }
    .upload-area label {
      cursor: pointer;
      color: #c00;
      font-weight: bold;
    }
  </style>
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

  <div class="page-title">Gérer les photos</div>
  <p class="page-subtitle"><?= htmlspecialchars($article['titre']) ?></p>

  <div class="card">
    <div class="card-body">
      
      <?php 
      $success = $_GET['success'] ?? null;
      $error = $_GET['error'] ?? null;
      
      if ($success): ?>
        <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; padding: 12px; margin-bottom: 20px; color: #155724;">
          ✅ <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>
      
      <?php if ($error): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; padding: 12px; margin-bottom: 20px; color: #721c24;">
          ❌ <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      
      <h3>Ajouter des photos</h3>
      <form method="POST" action="upload_photos.php" enctype="multipart/form-data">
        <input type="hidden" name="id_article" value="<?= $id_article ?>">
        
        <div class="upload-area">
          <label for="photos">
            📸 Cliquez pour sélectionner des photos<br>
            <small>(JPG, PNG, GIF - Max 5MB par photo)</small>
          </label>
          <input type="file" id="photos" name="photos[]" multiple accept="image/*" required>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-success">⬆️ Uploader les photos</button>
        </div>
      </form>

      <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">

      <h3>Photos actuelles (<?= count($photos) ?>)</h3>
      
      <?php if (count($photos) > 0): ?>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">
          La <strong>première photo</strong> est la photo principale affichée à l'accueil.
        </p>
        
        <div class="photos-grid">
          <?php foreach ($photos as $index => $photo): ?>
            <div class="photo-item">
              <img src="/uploads/<?= htmlspecialchars($photo['photos']) ?>" alt="Photo">
              <button type="button" class="photo-delete" onclick="deletePhoto(<?= $photo['id'] ?>)">✕</button>
              <div class="photo-info">
                <?php if ($index === 0): ?>
                  <strong style="color: #c00;">PRINCIPALE</strong>
                <?php else: ?>
                  Photo <?= $index + 1 ?>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p style="color: #999; font-style: italic;">Aucune photo pour cet article. Ajoutez-en une ci-dessus.</p>
      <?php endif; ?>

    </div>
  </div>
</div>

<div class="admin-footer">
  <p>&copy; 2026 Conflit en Iran - Back-office d'administration</p>
</div>

<script>
function deletePhoto(photoId) {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')) {
    window.location.href = 'delete_photo.php?id=' + photoId + '&article_id=<?= $id_article ?>';
  }
}

// Permet de sélectionner les fichiers en cliquant sur la zone
document.querySelector('.upload-area').addEventListener('click', function() {
  document.getElementById('photos').click();
});

// Affiche les noms des fichiers sélectionnés
document.getElementById('photos').addEventListener('change', function(e) {
  let names = Array.from(e.target.files).map(f => f.name).join(', ');
  document.querySelector('.upload-area label').textContent = names || '📸 Cliquez pour sélectionner des photos';
});
</script>

</body>
</html>
