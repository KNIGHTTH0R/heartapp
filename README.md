# heartapp: A simplest Facebook canvas app

## Installation
This repo does not include the Facebook PHP SDK needed to run this app. The SDK can be installed using composer. To install composer, more details can be found [here](https://getcomposer.org/doc/00-intro.md).

After composer is installed and this git repo is checked out, Facebook PHP SDK can be installed using this simple command:
```
composer install
```

Two script needs some _extra attention_:
* **index.php**: This script imitates the app's initial home page from where user begin the process of authorizing the app.
* **app.php**: This script will build the app's landing page, displayed via Facebook canvas.

The Facebook canvas app initially accessed by a user via the app home page hosted by Facebook. For example, the page for the game [Desert Dash](https://www.facebook.com/games/desert-dash/?fbs=1303). When the user clicks on "Play Now" button, the app receives the user details with access token which can be saved for later use. For simplicity, I am going to put the Facebook login button on a standalone webpage, which can initiate the login process and redirect to our Facebook app upon success. 
