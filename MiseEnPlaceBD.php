<?php
require('confAuth.php'); // Contient les informations de connexion au serveur
require("connexionBDAuth.php"); // Fichier supplémentaire avec des variables nécessaires

// Exécution des commandes SQL
try {
    // Lire le contenu du fichier SQL
    $sql = file_get_contents('./CreationBD.sql');

    // Exécuter les commandes
    $linkpdo->exec($sql);

    echo '<div class="success">L\'insertion des données dans la BD a bien été effectuée.</div>';
} catch (Exception $e2) {
    die('<div class="error">Erreur lors de l\'exécution du script SQL : ' . $e2->getMessage() . '</div>');
}

?>