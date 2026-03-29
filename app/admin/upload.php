<?php
$types_autorises = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$max_taille      = 5 * 1024 * 1024; // 5 MB
$dossier         = '../uploads/';

$succes  = [];
$erreurs = [];

// Réorganiser $_FILES pour itérer facilement
$fichiers = $_FILES['fichiers'];
$total    = count($fichiers['name']);

for ($i = 0; $i < $total; $i++) {

    $nom      = $fichiers['name'][$i];
    $tmp      = $fichiers['tmp_name'][$i];
    $taille   = $fichiers['size'][$i];
    $erreur   = $fichiers['error'][$i];

    // Vérifier erreur d'upload
    if ($erreur !== UPLOAD_ERR_OK) {
        $erreurs[] = "$nom : erreur lors de l'upload.";
        continue;
    }

    // Vérifier la taille
    if ($taille > $max_taille) {
        $erreurs[] = "$nom : fichier trop volumineux (max 5MB).";
        continue;
    }

    // Vérifier le type MIME réel
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $tmp);
    finfo_close($finfo);

    if (!in_array($mime, $types_autorises)) {
        $erreurs[] = "$nom : type non autorisé ($mime).";
        continue;
    }

    // Générer un nom unique
    $extension   = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
    $nom_fichier = uniqid('img_', true) . '.' . $extension;
    $destination = $dossier . $nom_fichier;

    // Déplacer
    if (move_uploaded_file($tmp, $destination)) {
        $succes[] = ['original' => $nom, 'sauvegarde' => $nom_fichier];
    } else {
        $erreurs[] = "$nom : échec de la sauvegarde.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Résultat Upload</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; }
    .message { padding: 10px; background: #e8f5e9; border-left: 4px solid green; margin-bottom: 10px; }
    .erreur  { padding: 10px; background: #fdecea; border-left: 4px solid red;   margin-bottom: 10px; }
    img { width: 120px; height: 120px; object-fit: cover; margin: 5px; border: 1px solid #ddd; }
    a { display: inline-block; margin-top: 20px; color: #333; }
  </style>
</head>
<body>

  <?php foreach ($succes as $f): ?>
    <div class="message">
      ✅ <?= htmlspecialchars($f['original']) ?> → sauvegardé sous <strong><?= $f['sauvegarde'] ?></strong><br>
      <img src="../uploads/<?= $f['sauvegarde'] ?>" alt="<?= htmlspecialchars($f['original']) ?>">
    </div>
  <?php endforeach; ?>

  <?php foreach ($erreurs as $e): ?>
    <div class="erreur">❌ <?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>

  <a href="upload_form.php">← Uploader d'autres images</a>

</body>
</html>
