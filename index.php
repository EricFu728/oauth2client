<?php

$provider = require __DIR__ . '/provider.php';

// token session is existed.
// if token is existed and expired, using refresh token, otherwise using existed access_token

if ($_SESSION['token']) {
    $username = $_SESSION['username'];
    if ($_SESSION['expiredTime'] < time()) {
        echo 'Using Refresh Token' . '<br />';
        $grant_type = 'refresh_token';

        $post_data = array("grant_type" => $grant_type, "refresh_token" => $_SESSION['token']['refresh_token'],
            "client_id" => $clientId, "client_secret" => $clientSecret, "redirect_uri" => $redirectUri);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost/oauth2server/token.php");
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        $token = curl_exec($ch);
        $token = (array) (json_decode($token));
        curl_close($ch);
        $_SESSION['token'] = ($token);
    }
    $data = array("access_token" => $_SESSION['token']['access_token'], "username" => $username);
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
    print_r($resource->info);
    exit;
}
if (!empty($_GET['error'])) {
    exit('Got error: ' . $_GET['error']);
} elseif (empty($_GET['code'])) {
    $authUrl = $provider->getAuthorizationUrl();
    header('Location: ' . $authUrl);
    exit;
} elseif (empty($_GET['state']) || (urldecode($_GET['state']) !== $_SESSION['state'])) {
    echo ($_GET['state']) . "<br />";
    echo $_SESSION['state'];
    unset($_SESSION['state']);
    exit('Invalid state');
} else {
    echo 'Using Authorization Code' . '<br />';
    $code = $_GET['code'];
    $grant_type = 'authorization_code';
    $username = $_GET['username'];

    $post_data = array("grant_type" => $grant_type, "code" => $code,
        "client_id" => $clientId, "client_secret" => $clientSecret, "redirect_uri" => $redirectUri);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/oauth2server/token.php");
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    $token = curl_exec($ch);
    $token = (array) (json_decode($token));
    curl_close($ch);
    $_SESSION['token'] = $token;
    $_SESSION['expiredTime'] = time() + $token['expires_in'];
    $_SESSION['username'] = $username;



    $data = array("access_token" => $token['access_token'], "username" => $username);
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
    print_r($resource->info);
    exit;
}