<?php
include('../includes/config.php');

$titre   = $_POST['titre'];
$contenu = $_POST['contenu'];
$slug    = $_POST['slug'];

$sql  = "INSERT INTO article (titre, slug, contenu) VALUES (:titre, :slug, :contenu)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([
    ':titre'   => $titre,
    ':slug'    => $slug,
    ':contenu' => $contenu
])) {
    echo "Article enregistré avec succès !";
} else {
    echo "Erreur lors de l'enregistrement.";
}
?>