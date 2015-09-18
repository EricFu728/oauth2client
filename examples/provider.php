<?php

require __DIR__ . '/../vendor/autoload.php';
use League\OAuth2\Client\Provider\Google;

// Replace these with your token settings
// Create a project at https://console.developers.google.com/
$clientId     = '141278208077-5psqrk0iloi5bvidcgl472o6i84n2mee.apps.googleusercontent.com';
$clientSecret = 'p2lgAKQOZXGHVGswv3T5HOR3';

// Change this if you are not using the built-in PHP server
$redirectUri  = 'http://localhost/';

// Start the session
session_start();

// Initialize the provider
$provider = new Google(compact('clientId', 'clientSecret', 'redirectUri'));

// No HTML for demo, prevents any attempt at XSS
header('Content-Type', 'text/plain');

return $provider;
