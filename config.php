<?php

/**
 * The configuration script, contains all configuration values,
 * needed to run the app successfully. This script should be 
 * included in all the scripts.
 */

// Define the environment, should be retrievable from server's 
// env variables. For simplicity, lets do it here.
define('PROD', false);
define('DEV', true);

if (PROD) {
    // If PROD, we will use the original live app
    define('APP_ID', '{app-id}');
    define('APP_SECRET', 'app-secret');
    define('OAUTH_REDIRECT_URL', 'https://apps.facebook.com/'.APP_ID.'/app.php');
} else {
    // otherwise, we will use a test version of the app, mainly for debugging
    define('APP_ID', 'test-app-id');
    define('APP_SECRET', 'test-app-secret');
    define('OAUTH_REDIRECT_URL', 'https://apps.facebook.com/'.APP_ID.'/app.php');
}

// Autoload the Facebook PHP-SDK
require_once('vendor/autoload.php');

// Load the database script
require_once('db.php');

// Error message only for logging purpose, might contain technical error details. 
$errorMsg = '';

// Initialize the Facebook class with our app details
$fb = new \Facebook\Facebook([
    'app_id' => APP_ID,
    'app_secret' => APP_SECRET,
    'default_graph_version' => 'v2.9',
]);

// Get a db instance object
try {
    $db = DbFactory::Instance();
} catch (Exception $e) {
    $errorMsg = 'DB initialization failed: '.$e->getMessage();
}
