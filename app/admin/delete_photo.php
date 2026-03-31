<?php
include('../includes/config.php');
requireLogin();

$photo_id = $_GET['id'] ?? null;
$article_id = $_GET['article_id'] ?? null;

if (!$photo_id || !is_numeric($photo_id) || !$article_id || !is_numeric($article_id)) {
    header('Location: list.php');
    exit;
}

// Récupérer la photo
$stmt = $pdo->prepare("SELECT photos FROM photos WHERE id = :id AND id_article = :id_article LIMIT 1");
$stmt->execute([':id' => $photo_id, ':id_article' => $article_id]);
$photo = $stmt->fetch();

if ($photo) {
    // Supprimer le fichier
    $file_path = '../uploads/' . $photo['photos'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    // Supprimer de la base
    $stmt = $pdo->prepare("DELETE FROM photos WHERE id = :id");
    $stmt->execute([':id' => $photo_id]);
}

header("Location: photos_manage.php?id=$article_id");
exit;
?>
