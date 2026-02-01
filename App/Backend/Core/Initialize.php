<?php

// Grant access to php files
define("ACCESS", "Granted");

// Load configurations
require_once PATH_CONFIG . "config.php";
require_once PATH_CORE . "functions/config.php";

// Load composer autoload.php
if (file_exists(PATH_ROOT . "vendor/autoload.php"))
{
    require_once PATH_ROOT . "vendor/autoload.php";
}
else
{
    if (PRODUCTION_MODE)
    {
        exit("A critical error has occurred. Please contact the site administrator.");
    }
    else
    {
        exit("Composer autoload file is missing. Run 'composer install --prefer-dist --optimize-autoloader'. If this does not fix the issue, run 'composer dump-autoload --optimize' to regenerate the autoload.php file.");
    }
}

// Load core files
require_once PATH_CORE . "functions/sanitize.php";
require_once PATH_CORE . "functions/escape.php";
require_once PATH_CORE . "functions/errors.php";
require_once PATH_CORE . "functions/debug.php";
require_once PATH_CORE . "functions/cookies.php";

// Initialize session
class_alias("Backend\Core\Session", "Session");
Session::initialize();

// Include cross-site request forgery protection
class_alias("Backend\Core\CSRF", "CSRF");

// Initialize Blocks
class_alias("Backend\Core\Block", "Block");
Block::initialize();

// Load utility files
require_once PATH_CORE . "functions/utility-helpers.php";
require_once PATH_CORE . "functions/media-helpers.php";
require_once PATH_CORE . "functions/webp.php";
require_once PATH_CORE . "functions/translator.php";

// Compile SCSS and JavaScript
if (!PRODUCTION_MODE)
{
    // Compile SCSS
    if (AUTO_COMPILE_SCSS)
    {
        Backend\Core\SCSS_Compiler::initialize();
    }

    // Compile JavaScript
    if (AUTO_COMPILE_JS)
    {
        Backend\Core\JS_Compiler::initialize();
    }
}

// Load form requests and routes
require_once PATH_BACKEND . "Routes/FormRequests.php";
require_once PATH_BACKEND . "Routes/Routes.php";

// Initialize form requests
new Backend\Routes\FormRequests();

// Initialize routes
new Backend\Routes\Routes();
