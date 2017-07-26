<?php

/**
 * This script imitates the Facebook app's initial home page from where
 * user begin the process of authorizing the app. For simplicity, we 
 * can host this page on any web server and begin the authorization 
 * process. On successful authorization, it will redirect to our 
 * app's landing page inside Facebook canvas.
 */

require_once('config.php');

$helper = $fb->getRedirectLoginHelper();

// Add here if any optional permissions are required,
// currently we don't need any.
$permissions = [];
$loginUrl = $helper->getLoginUrl(OAUTH_REDIRECT_URL, $permissions);

?>
<!doctype html>
<html>
    <head>
        <title>Heartapp</title>
    </head>
    <body>
        <a href="<?php echo htmlspecialchars($loginUrl); ?>">Log in with Facebook!</a>
    </body>
</html>

