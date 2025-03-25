<?php
include '../connexionBDAuth.php';

// Récupération des données dans le corps 
$postedData = file_get_contents('php://input'); 
$data = json_decode($postedData,true); //Reçoit du json et renvoi une adaptation exploitable en php. Le paramètre true impose un tableau en retour et non un objet.

$secret = "Bob";

function isValidUser($login, $password) {

    return ($login === "bob" && $password === 'BobBatman74!') || ($login === "prof" && $password === 'Karaba123');
}

/// Envoi de la réponse au Client 
function deliver_response($status_code, $status_message, $data=null){ 
    /// Paramétrage de l'entête HTTP 
    http_response_code($status_code); 
    //Utilise un message standardisé en fonction du code HTTP 
    //header("HTTP/1.1 $status_code $status_message"); 
    //Permet de personnaliser le message associé au code HTTP 
    header("Content-Type:application/json; charset=utf-8");
    //Indique au client le format de la réponse 
    $response['status_code'] = $status_code; 
    $response['status_message'] = $status_message; 
    $response['data'] = $data; 
    /// Mapping de la réponse au format JSON 
    $json_response = json_encode($response);
    if($json_response===false) die('json encode ERROR : '.json_last_error_msg()); 
    /// Affichage de la réponse (Retourné au client) 
    echo $json_response; 
}

function generate_jwt($headers, $payload, $secret) {
	$headers_encoded = base64url_encode(json_encode($headers));

	$payload_encoded = base64url_encode(json_encode($payload));

	$signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
	$signature_encoded = base64url_encode($signature);

	$jwt = "$headers_encoded.$payload_encoded.$signature_encoded";

	return $jwt;
}

function is_jwt_valid($jwt, $secret) {
    // Vérifier si le JWT est bien formé
    $tokenParts = explode('.', $jwt);
    if (count($tokenParts) !== 3) {
        deliver_response(400, "JWT mal formé", null);
        return false;
    }

    // Décoder les parties
    $header = base64_decode($tokenParts[0]);
    $payload = base64_decode($tokenParts[1]);
    $signature_provided = $tokenParts[2];

    // Vérifier si le payload est valide JSON
    $payload_decoded = json_decode($payload);
    if (!$payload_decoded) {
        deliver_response(400, "Erreur de décodage du payload", null);
        return false;
    }

    // Vérifier l'expiration du token
    if (!isset($payload_decoded->exp)) {
        deliver_response(400, "Le champ 'exp' est manquant dans le JWT", null);
        return false;
    }

    $expiration = $payload_decoded->exp;
    $is_token_expired = ($expiration - time()) < 0;

    if ($is_token_expired) {
        deliver_response(401, "JWT expiré", null);
        return false;
    }

    // Vérifier la signature
    $base64_url_header = base64url_encode(json_encode(json_decode($header, true))); // Reencoder proprement
    $base64_url_payload = base64url_encode($payload);
    $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
    $base64_url_signature = base64url_encode($signature);

    if ($base64_url_signature !== $signature_provided) {
        deliver_response(401, "Signature JWT invalide", null);
        return false;
    }

    // Si tout est bon
    return true;
}


function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function get_authorization_header(){
	$headers = null;

	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	} else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	} else if (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		//print_r($requestHeaders);
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		}
	}

	return $headers;
}

function get_bearer_token() {
    $headers = get_authorization_header();
    
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            if($matches[1]=='null') //$matches[1] est de type string et peut contenir 'null'
                return null;
            else
                return $matches[1];
        }
    }
    return null;
}


?>
