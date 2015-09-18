<?php

require __DIR__ . '/vendor/autoload.php';
use League\OAuth2\Client\Provider\Test;

// Replace these with your token settings
// Create a project at https://console.developers.google.com/
$clientId     = 'testclient';
$clientSecret = 'testpass';

// Change this if you are not using the built-in PHP server
$redirectUri  = 'http://localhost/oauth2client/index.php';

// Start the session
session_start();

// Initialize the provider
$provider = new Test(compact('clientId', 'clientSecret', 'redirectUri'));

// No HTML for demo, prevents any attempt at XSS
header('Content-Type', 'text/plain');

return $provider;
