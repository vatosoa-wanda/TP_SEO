<?php
include('../includes/config.php');
requireLogin();

$id_article = $_POST['id_article'] ?? null;
$errors = [];

// Vérifier que l'article existe
if (!$id_article || !is_numeric($id_article)) {
    $errors[] = "Article invalide";
} else {
    $stmt = $pdo->prepare("SELECT id FROM article WHERE id = :id");
    $stmt->execute([':id' => $id_article]);
    if (!$stmt->fetch()) {
        $errors[] = "Article non trouvé";
    }
}

// Vérifier les fichiers uploadés
if (empty($_FILES['photos']['name'][0])) {
    $errors[] = "Aucun fichier sélectionné";
}

if (!empty($errors)) {
    echo "Erreurs:<br>";
    foreach ($errors as $error) {
        echo "- " . htmlspecialchars($error) . "<br>";
    }
    echo "<br><a href='photos_manage.php?id=" . $id_article . "'>← Retour</a>";
    exit;
}

// Créer le dossier uploads s'il n'existe pas
$uploads_dir = '../uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
}

// Traiter chaque fichier
$uploaded_count = 0;
foreach ($_FILES['photos']['name'] as $key => $filename) {
    $file_tmp = $_FILES['photos']['tmp_name'][$key];
    $file_size = $_FILES['photos']['size'][$key];
    $file_error = $_FILES['photos']['error'][$key];

    // Vérifier les erreurs d'upload
    if ($file_error !== UPLOAD_ERR_OK) {
        $errors[] = "Erreur lors de l'upload de $filename";
        continue;
    }

    // Vérifier la taille (max 5MB)
    if ($file_size > 5 * 1024 * 1024) {
        $errors[] = "$filename dépasse 5MB";
        continue;
    }

    // Vérifier le type MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file_tmp);
    finfo_close($finfo);
    
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime_type, $allowed_mimes)) {
        $errors[] = "$filename n'est pas une image valide";
        continue;
    }

    // Générer un nom de fichier sécurisé
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
    $new_filename = 'article_' . $id_article . '_' . time() . '_' . rand(1000, 9999) . '.' . strtolower($file_ext);
    $file_path = $uploads_dir . '/' . $new_filename;

    // Déplacer le fichier
    if (move_uploaded_file($file_tmp, $file_path)) {
        // Insérer en base de données
        try {
            $stmt = $pdo->prepare("INSERT INTO photos (id_article, photos) VALUES (:id_article, :photos)");
            $stmt->execute([
                ':id_article' => $id_article,
                ':photos' => $new_filename
            ]);
            $uploaded_count++;
        } catch (Exception $e) {
            $errors[] = "Erreur base de données pour $filename";
            unlink($file_path); // Supprimer le fichier en cas d'erreur
        }
    } else {
        $errors[] = "Impossible de déplacer le fichier $filename";
    }
}

// Redirection avec message
if ($uploaded_count > 0) {
    header("Location: photos_manage.php?id=$id_article&success=" . urlencode("$uploaded_count photo(s) uploadée(s) avec succès"));
} else {
    header("Location: photos_manage.php?id=$id_article&error=Aucune photo n'a pu être uploadée");
}
exit;
?>
