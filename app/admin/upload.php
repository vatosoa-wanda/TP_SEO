<?php
$types_autorises = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$max_taille      = 5 * 1024 * 1024; // 5 MB
$dossier         = '../uploads/';
$succes          = [];
$erreurs         = [];

// -----------------------------------------------
// Fonction commune : valider et déplacer un fichier
// -----------------------------------------------
function traiter_fichier($tmp, $nom, $taille, $erreur, $dossier, $types_autorises, $max_taille) {
    if ($erreur !== UPLOAD_ERR_OK) {
        return ['erreur' => "$nom : erreur lors de l'upload."];
    }
    if ($taille > $max_taille) {
        return ['erreur' => "$nom : fichier trop volumineux (max 5MB)."];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $tmp);
    finfo_close($finfo);

    if (!in_array($mime, $types_autorises)) {
        return ['erreur' => "$nom : type non autorisé ($mime)."];
    }

    $extension   = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
    $nom_fichier = uniqid('img_', true) . '.' . $extension;
    $destination = $dossier . $nom_fichier;

    if (move_uploaded_file($tmp, $destination)) {
        return ['succes' => $nom_fichier];
    }

    return ['erreur' => "$nom : échec de la sauvegarde."];
}

// -----------------------------------------------
// CAS 1 : TinyMCE — envoie $_FILES['file']
// Retourne JSON
// -----------------------------------------------
if (!empty($_FILES['file'])) {
    $f = $_FILES['file'];
    $resultat = traiter_fichier(
        $f['tmp_name'], $f['name'], $f['size'], $f['error'],
        $dossier, $types_autorises, $max_taille
    );

    if (isset($resultat['succes'])) {
        echo json_encode(['location' => '/uploads/' . $resultat['succes']]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => $resultat['erreur']]);
    }
    exit;
}

// -----------------------------------------------
// CAS 2 : Formulaire multi-upload — envoie $_FILES['fichiers']
// Retourne HTML
// -----------------------------------------------
if (!empty($_FILES['fichiers'])) {
    $fichiers = $_FILES['fichiers'];
    $total    = count($fichiers['name']);

    for ($i = 0; $i < $total; $i++) {
        $resultat = traiter_fichier(
            $fichiers['tmp_name'][$i],
            $fichiers['name'][$i],
            $fichiers['size'][$i],
            $fichiers['error'][$i],
            $dossier, $types_autorises, $max_taille
        );

        if (isset($resultat['succes'])) {
            $succes[] = ['original' => $fichiers['name'][$i], 'sauvegarde' => $resultat['succes']];
        } else {
            $erreurs[] = $resultat['erreur'];
        }
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
    .erreur  { padding: 10px; background: #fdecea; border-left: 4px solid red; margin-bottom: 10px; }
    img { width: 120px; height: 120px; object-fit: cover; margin: 5px; border: 1px solid #ddd; }
    a { display: inline-block; margin-top: 20px; color: #333; }
  </style>
</head>
<body>

  <?php if (empty($succes) && empty($erreurs)): ?>
    <p>Aucun fichier envoyé. <a href="upload_form.php">← Retour</a></p>

  <?php else: ?>
    <?php foreach ($succes as $f): ?>
      <div class="message">
        ✅ <?= htmlspecialchars($f['original']) ?> → <strong><?= $f['sauvegarde'] ?></strong><br>
        <img src="/uploads/<?= $f['sauvegarde'] ?>" alt="<?= htmlspecialchars($f['original']) ?>">
      </div>
    <?php endforeach; ?>

    <?php foreach ($erreurs as $e): ?>
      <div class="erreur">❌ <?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <a href="upload_form.php">← Uploader d'autres images</a>
  <?php endif; ?>

</body>
</html>
