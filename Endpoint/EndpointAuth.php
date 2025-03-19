<?php
include '../connexionBDAuth.php';
include '../Functions/FunctionsAuth.php';
// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD']; 

switch ($http_method){ 
    case "GET" : 
        if (is_jwt_valid()

        deliver_response(201,"La c'est GET",null);
    break; 
    case "POST" : 
        if (isValidUser($data['login'],$data['password'])){
            $login = $data['login'];
            
            $headers = array('alg'=>'HS256','typ'=>'JWT');
            if ($login == "bob") {
                $payload = array('login'=>$login, 'exp'=>(1739438125) );
            } else {
                $payload = array('login'=>$login, 'exp'=>(1739438263));
            }

            $jwt = generate_jwt($headers, $payload, 'secret');

            deliver_response(221, "POST Fonctionne", $jwt);
        } else {
            deliver_response(404, "Not Found", null);
        }
    break;  
    
    case "PATCH" :
        deliver_response(203,"La c'est PATCH",null);
    break;

    case "PUT" :
        deliver_response(204,"La c'est PUT",null);
    break;

    case "DEL" :
        deliver_response(205,"La c'est DELETE",null);
    break;

    case "OPTIONS" :
        deliver_response(206,"La c'est OPTIONS",null);
    break;
}

?>