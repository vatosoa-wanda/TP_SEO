<?php include('../includes/config.php'); requireLogin(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des articles</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; padding: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.85rem; }
    .btn-edit   { background: #f0ad4e; color: white; }
    .btn-delete { background: #d9534f; color: white; border: none; cursor: pointer; }
    .btn-add    { background: #5cb85c; color: white; display: inline-block; margin-bottom: 20px; }
  </style>
</head>
<body>

<h2>Gestion des articles</h2>
<a class="btn btn-add" href="add.php">+ Nouvel article</a>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Titre</th>
      <th>Slug</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $stmt    = $pdo->query("SELECT id, titre, slug, date_creation FROM article ORDER BY date_creation DESC");
  $articles = $stmt->fetchAll();
  foreach ($articles as $a): ?>
    <tr>
      <td><?= $a['id'] ?></td>
      <td><?= htmlspecialchars($a['titre']) ?></td>
      <td><?= htmlspecialchars($a['slug']) ?></td>
      <td><?= date('d/m/Y', strtotime($a['date_creation'])) ?></td>
      <td>
        <a class="btn btn-edit" href="edit.php?id=<?= $a['id'] ?>">Modifier</a>

        <form method="POST" action="delete.php" style="display:inline"
              onsubmit="return confirm('Supprimer cet article ?')">
          <input type="hidden" name="id" value="<?= $a['id'] ?>">
          <button class="btn btn-delete" type="submit">Supprimer</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>