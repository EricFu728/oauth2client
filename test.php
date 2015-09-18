<?php
$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/oauth2server/token.php");
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    $output = curl_exec($ch);
    curl_close($ch);
    
    print_r($output);