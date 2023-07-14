<?php

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/src/Google/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('970790083133-mim42r6m9iuf7pkfs8uq8buci99gui3p.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('k_vyI7dHvtAVbVnmHVBvCfMu');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri("http://" . $_SERVER["HTTP_HOST"] . "/runpharmomics.php?fromapp2=true");

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page
session_start();
