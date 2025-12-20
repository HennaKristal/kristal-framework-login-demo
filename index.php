<?php

define("PATH_ROOT", __DIR__ . "/");

define(
    "DOMAIN",
    explode(":", $_SERVER["HTTP_HOST"])[0]
);

define(
    "URL_BASE",
    (
        (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off")
            ? "https"
            : "http"
    ) . "://" . DOMAIN . "/"
);

define("URL_ROOT", __DIR__ . "/");
define("URL_AUDIO", URL_BASE . "App/media/audio/");
define("URL_IMAGES", URL_BASE . "App/media/images/");
define("URL_DOWNLOADS", URL_BASE . "App/media/downloads/");
define("URL_CSS", URL_BASE . "App/media/css/");
define("URL_JS", URL_BASE . "App/media/javascript/");
define("URL_TRANSLATIONS", URL_BASE . "App/media/translations/");
define("URL_CACHE", URL_BASE . "storage/cache/");
define("URL_WEBP", URL_BASE . "storage/webp/");

define("PATH_AUDIO", PATH_ROOT . "App/media/audio/");
define("PATH_IMAGES", PATH_ROOT . "App/media/images/");
define("PATH_DOWNLOADS", PATH_ROOT . "App/media/downloads/");
define("PATH_CSS", PATH_ROOT . "App/media/css/");
define("PATH_JS", PATH_ROOT . "App/media/javascript/");
define("PATH_TRANSLATIONS", PATH_ROOT . "App/media/translations/");
define("PATH_CACHE", PATH_ROOT . "storage/cache/");
define("PATH_WEBP", PATH_ROOT . "storage/webp/");
define("PATH_WEBP", PATH_ROOT . "storage/logs/");
define("PATH_TEMPLATES", PATH_ROOT . "App/templates/");
define("PATH_CONFIG", PATH_ROOT . "config/");
define("PATH_CORE", PATH_ROOT . "App/Backend/Core/");
define("PATH_BACKEND", PATH_ROOT . "App/Backend/");

if (!file_exists(PATH_ROOT . "App/Backend/Core/Initialize.php"))
{
    exit("Could not load framework core, please check index.php file.");
}

require_once PATH_ROOT . "App/Backend/Core/Initialize.php";
