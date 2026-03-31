<?php include('../includes/config.php'); requireLogin(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des articles</title>
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
  <div class="page-title">Gestion des articles</div>
  <p class="page-subtitle">Créez, modifiez ou supprimez vos articles</p>

  <div class="btn-group">
    <a href="add.php" class="btn btn-success">+ Nouvel article</a>
  </div>

  <?php
  $stmt = $pdo->query("SELECT id, titre, slug, date_creation FROM article ORDER BY date_creation DESC");
  $articles = $stmt->fetchAll();
  ?>

  <?php if (empty($articles)): ?>
    <div class="empty-state">
      <p>Aucun article publié pour le moment</p>
      <a href="add.php" class="btn btn-primary">Créer le premier article</a>
    </div>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th style="width: 50px">#</th>
          <th>Titre</th>
          <th style="width: 180px">Slug</th>
          <th style="width: 120px">Date</th>
          <th style="width: 250px">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($articles as $a): ?>
          <tr>
            <td><?= $a['id'] ?></td>
            <td><strong><?= htmlspecialchars($a['titre']) ?></strong></td>
            <td><code><?= htmlspecialchars($a['slug']) ?></code></td>
            <td><?= date('d/m/Y', strtotime($a['date_creation'])) ?></td>
            <td class="actions">
              <a href="edit.php?id=<?= $a['id'] ?>" class="btn btn-warning">✏️ Modifier</a>
              <a href="upload_form.php" class="btn btn-primary">🖼️ Images</a>
              <form method="POST" action="delete.php" style="display:inline" onsubmit="return confirm('Supprimer définitivement cet article ?')">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<div class="admin-footer">
  <p>&copy; 2026 Conflit en Iran - Back-office d'administration</p>
</div>

</body>
</html>