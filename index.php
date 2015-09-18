<?php
$provider = require __DIR__ . '/provider.php';

if (!empty($_GET['error'])) {
    exit('Got error: ' . $_GET['error']);

} elseif (empty($_GET['code'])) {
    $authUrl = $provider->getAuthorizationUrl();
    
    header('Location: ' . $authUrl);
    exit;

} elseif (empty($_GET['state']) || (urldecode($_GET['state']) !== $_SESSION['state'])) {

    unset($_SESSION['state']);
    exit('Invalid state');

} else {
    $code = $_GET['code'];
    $grant_type = 'authorization_code';
    $username = $_GET['username'];
    
    $post_data = array ("grant_type" => "authorization_code","code" => $code,
        "client_id"=>$clientId,"client_secret"=>$clientSecret,"redirect_uri"=>$redirectUri);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/oauth2server/token.php");
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    $token = curl_exec($ch);
    $token = (array)(json_decode($token));
    curl_close($ch);
    
//    
    $data = array("access_token"=>$token['access_token'],"username"=>$username);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/oauth2server/resource.php");
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $resource = curl_exec($ch);
    $resource = (json_decode($resource));
    curl_close($ch);
    print_r($resource->info);exit;
    
    
    $_SESSION['token'] = serialize($token);

    // Optional: Now you have a token you can look up a users profile data
    //header('Location: /oauth2server/resource.php');
}