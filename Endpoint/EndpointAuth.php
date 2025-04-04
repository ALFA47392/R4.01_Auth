<?php

// Inclusion des fichiers nécessaires à la connexion à la base de données et aux fonctions d'authentification
include '../connexionBDAuth.php';  // Connexion à la base de données pour l'authentification
include '../Functions/FunctionsAuth.php';  // Fonctions liées à l'authentification

// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];

switch ($http_method) { 
    case "GET":
        // Récupération du token JWT envoyé dans l'en-tête de la requête
        $token = get_bearer_token();
        
        // Vérification si le token est valide
        if ($token && is_jwt_valid($token, $secret)) {
            // Si le token est valide, envoyer une réponse indiquant que la méthode GET fonctionne
            deliver_response(201, "GET Fonctionne avec un JWT valide", null);
        } else {
            // Si le token est absent ou invalide, envoyer une réponse d'erreur
            deliver_response(401, "JWT invalide ou absent", null);
        }
    break; 

    case "POST":
        // Récupération des données envoyées dans la requête
        $postedData = file_get_contents('php://input');
        $data = json_decode(trim($postedData), true);
    
        // Vérification de la validité des informations de l'utilisateur
        if (isValidUser($data['login'], $data['password'])) {
            $login = $data['login'];

            // Définition des en-têtes pour la création du JWT
            $headers = array('alg' => 'HS256', 'typ' => 'JWT');
            $expiration = time() + 1200; // Expiration dans 20 minutes
    
            // Construction du payload du JWT
            $payload = array('login' => $login, 'exp' => $expiration);
    
            // Génération du JWT
            $jwt = generate_jwt($headers, $payload, $secret);
    
            // Envoi de la réponse avec le JWT généré
            deliver_response(221, "POST Fonctionne", $jwt);
        } else {
            // Si les informations de l'utilisateur sont invalides, envoyer une réponse d'erreur
            deliver_response(404, "Not Found", null);
        }
    break;
     
    case "PATCH":
        // Réponse indiquant que la méthode PATCH a été reçue
        deliver_response(203, "La c'est PATCH", null);
    break;

    case "PUT":
        // Réponse indiquant que la méthode PUT a été reçue
        deliver_response(204, "La c'est PUT", null);
    break;

    case "DEL":
        // Réponse indiquant que la méthode DELETE a été reçue
        deliver_response(205, "La c'est DELETE", null);
    break;

    case "OPTIONS":
        // Réponse indiquant que la méthode OPTIONS a été reçue
        deliver_response(206, "La c'est OPTIONS", null);
    break;

    default:
        // Si la méthode HTTP n'est pas autorisée, envoyer une réponse d'erreur
        deliver_response(405, "Méthode non autorisée", null);
    break;
}
?>
