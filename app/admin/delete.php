<?php
include('../includes/config.php');

$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM article WHERE id = :id");

if ($stmt->execute([':id' => $id])) {
    header('Location: list.php?success=supprime');
    exit;
} else {
    echo "Erreur lors de la suppression.";
}
?>