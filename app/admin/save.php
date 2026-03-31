<?php
include('../includes/config.php');
requireLogin();

$titre            = $_POST['titre'];
$contenu          = $_POST['contenu'];
$meta_description = $_POST['meta_description'] ?? null;

// Générer automatiquement le slug à partir du titre
$slug = slugify($titre);

// Insert en base
$sql  = "INSERT INTO article 
            (titre, slug, contenu, meta_description) 
         VALUES 
            (:titre, :slug, :contenu, :meta_description)";

$stmt = $pdo->prepare($sql);

if ($stmt->execute([
    ':titre'            => $titre,
    ':slug'             => $slug,
    ':contenu'          => $contenu,
    ':meta_description' => $meta_description,
])) {
    echo "Article enregistre avec succes !";
    exit;
} else {
    echo "Erreur lors de l'enregistrement.";
}
?>