<?php

/**
 * This script will build the app's landing page, displayed via Facebook canvas.
 */

require_once('config.php');

$helper = $fb->getCanvasHelper();

try {
    $accessToken = $helper->getAccessToken();
    $helper->getSignedRequest();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    $errorMsg = 'Graph returned an error: ' . $e->getMessage();
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    $errorMsg = 'Facebook SDK returned an error: ' . $e->getMessage();
}

if (!isset($accessToken)) {
    $errorMsg = 'No OAuth data could be obtained from the signed request. User has not authorized your app yet.';   
}

if (empty($errorMsg) && !empty($accessToken)) {
    try {
        // Get the \Facebook\GraphNodes\GraphUser object for the current user.
        // If you provided a 'default_access_token', the '{access-token}' is optional.
        $response = $fb->get('/me?fields=id,name,picture', $accessToken);
        $me = $response->getGraphUser();
        if (empty($me)) {
            throw new Exceptions('Unexpectly empty user returned');
        }

        // Save the user to database
        $user = new StdClass;
        $user->fbuser_id = $me->getId();
        $user->access_token = $accessToken->getValue();
        $user->is_active = true;
        $db->saveUser($user);
    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        $errorMsg = 'Graph returned an error: '.$e->getMessage();
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        $errorMsg = 'Facebook SDK returned an error: '.$e->getMessage();
    } catch (Exception $e) {
        $errorMsg = 'Something went wrong: '. $e->getMessage();
    }
}

?>
<!doctype html>
<html>
    <head>
        <title>Heartapp</title>
    </head>
    <body>
        <?php if (!empty($errorMsg)) { ?>
        <div>
            <?php 
                if (PROD) {
                    // We are on production, show generic error message to user
                    echo 'We are having some technical problem right now. Please try again later.';
                } else {
                    // We are on dev, show the real errorMsg for debugging
                    echo $errorMsg;
                } 
            ?>
        </div>
        <?php } else { ?>
        <div>
            <img width="auto" height="auto" src="<?php echo $me->getPicture()->getUrl(); ?>" />
            <?php echo $me->getName(); ?>
        </div>
        <?php } // end of else ?>
    </body>
</html>

