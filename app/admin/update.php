<?php
include('../includes/config.php');
requireLogin();

$id               = $_POST['id'];
$titre            = $_POST['titre'];
$contenu          = $_POST['contenu'];
$meta_description = $_POST['meta_description'] ?? null;

// Générer automatiquement le slug à partir du titre
$slug = slugify($titre);

$sql  = "UPDATE article SET 
            titre            = :titre,
            slug             = :slug,
            contenu          = :contenu,
            meta_description = :meta_description
         WHERE id = :id";

$stmt = $pdo->prepare($sql);

if ($stmt->execute([
    ':titre'            => $titre,
    ':slug'             => $slug,
    ':contenu'          => $contenu,
    ':meta_description' => $meta_description,
    ':id'               => $id,
])) {
    header('Location: list.php?success=modifie');
    exit;
} else {
    echo "Erreur lors de la modification.";
}
?>