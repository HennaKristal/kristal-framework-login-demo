<?php

define("PATH_ROOT", __DIR__ . "/");

$framework_https = !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off";
$framework_host = $_SERVER["HTTP_HOST"] ?? "";
$framework_host = strtolower($framework_host);
if (!preg_match('/^[a-z0-9.-]+(?::[0-9]+)?$/', $framework_host)) {
    http_response_code(400);
    exit("Invalid request host.");
}

define("DOMAIN", explode(":", $framework_host)[0]);
define("URL_BASE", ($framework_https ? "https" : "http") . "://" . $framework_host . "/");
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
define("PATH_LOGS", PATH_ROOT . "storage/logs/");
define("PATH_TEMPLATES", PATH_ROOT . "App/templates/");
define("PATH_CONFIG", PATH_ROOT . "config/");
define("PATH_CORE", PATH_ROOT . "App/Backend/Core/");
define("PATH_BACKEND", PATH_ROOT . "App/Backend/");

if (!is_readable(PATH_CORE . "Initialize.php"))
{
    http_response_code(500);
    exit("Could not load framework core, please check index.php file.");
}

require_once PATH_CORE . "Initialize.php";
