<?php

require __DIR__ . '/vendor/autoload.php';
use League\OAuth2\Client\Provider\Test;

$clientId     = 'testclient';
$clientSecret = 'testpass';

$redirectUri  = 'http://localhost/oauth2client/index.php';

// Start the session
session_start();

// Initialize the provider
$provider = new Test(compact('clientId', 'clientSecret', 'redirectUri'));

header('Content-Type', 'text/plain');

return $provider;
