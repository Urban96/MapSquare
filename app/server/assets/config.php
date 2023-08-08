<?php

define('DEBUG', false); // Show erros/warnings
error_reporting(E_ALL);

define('DB_HOST', 'localhost'); //Database server
define('DB_NAME', 'mapsquare'); //Database name
define('DB_USER', 'root'); //Database username
define('DB_PASSWORD', ''); //Database password

define('URL_ADDRESS', 'http://localhost/'); //URL adress (https://mapsquare.io/, http://localhost/)

define('EMAIL_VERIFICATION', false); //Email verification in registration process, true if sending an email available
define('PASSWORD_RECOVER', false); //Password recover, true if sending an email available
define('CONTACT_EMAIL', false); //Contact via email, true if sending an email available

?>
