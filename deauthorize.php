<?php

/**
 * This script will detect when users uninstall the app via Facebook. 
 * Facebook performs a callback to the given deauthorize url with a 
 * POST request containing $signed_request value. The $signed_request 
 * value can be decoded to retrieve the user id and access_token. 
 *
 * Deauthorize callback url can be set on app's dashboard.
 */  

require_once('config.php');

/**
 * Helper function to parse the $signed_request, used with little 
 * modification as got from Facebook doc. 
 */
function parse_signed_request($signed_request) {
    list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

    // decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);

    // confirm the signature
    $expected_sig = hash_hmac('sha256', $payload, APP_SECRET, $raw = true);
    if ($sig !== $expected_sig) {
        // This log should go in server access log, for simplicity lets do the 
        // logging here on local file.
        file_put_contents('heartapp.log', 'Bad Signed JSON signature!', FILE_APPEND);
        return null;
    }

    return $data;
}

/**
 * A little helper function to decode base64 encoded string, particularly 
 * helpful for decoding Facebook's $signed_request.
 */
function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}

// Perform the deauthorization.
if (!empty($_POST['signed_request'])) {
    $data = parse_signed_request($_POST['signed_request']);
    if (!empty($data['user_id']) && !empty($data['oauth_token'])) {
        $user = new StdClass;
        $user->fbuser_id = $data['user_id'];
        $user->access_token = $data['outh_token'];
        $user->is_active = false;
        $db->saveUser($user);
        
        // Again, for simplicity, logging in local file
        file_put_contents('heartapp.log', 'User deauthorized app, fbusre_id: '.$user->fbuser_id, FILE_APPEND);
    }
}
