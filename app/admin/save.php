<?php
include('../includes/config.php');

$titre = $_POST['titre'];
$contenu = $_POST['contenu'];
$slug = $_POST['slug'];

$sql = "INSERT INTO article (titre, slug, contenu) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $titre, $slug, $contenu);

if ($stmt->execute()) {
    echo "Article enregistré";
} else {
    echo "Erreur";
}
?>