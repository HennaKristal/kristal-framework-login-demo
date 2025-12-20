<?php defined("ACCESS") or exit("Access Denied");

$kristalMandatoryConstants = [
    "PRODUCTION_MODE",
    "MAINTENANCE_MODE",
    "MAINTENANCE_PASSWORD",
    "MAINTENANCE_LOCKOUT_LIMIT",
    "MAINTENANCE_LOCKOUT_CLEAR_TIME",
    "ENABLE_DEBUG_DISPLAY",
    "ENABLE_DEBUG_LOG",
    "DEBUG_LOG_PATH",
    "DEBUG_IGNORE_WARNINGS",
    "DEBUG_IGNORE_NOTICES",
    "DEBUG_IGNORE_DEPRECATED",
    "DEBUG_IGNORE_STRICT",
    "DATABASES",
    "MAILER_HOST",
    "MAILER_EMAIL",
    "MAILER_PASSWORD",
    "MAILER_NAME",
    "MAILER_PROTOCOL",
    "MAILER_PORT",
    // "RECAPTCHA_V2_SITE_KEY",
    // "RECAPTCHA_V2_SITE_SECRET",
    // "RECAPTCHA_V3_SITE_KEY",
    // "RECAPTCHA_V3_SITE_SECRET",
    "SESSION_NAME",
    "SESSION_LIFETIME",
    "SESSION_REGENERATE_ID_TIME",
    "SESSION_AFK_TIMEOUT",
    "SESSION_SAMESITE",
    "REGENERATE_CSRF_ON_PAGE_REFRESH",
    "COOKIE_NAME",
    "COOKIE_LIFETIME",
    "COOKIE_SAMESITE",
    "TIMEZONE",
    "DATE_FORMAT",
    "TIME_FORMAT",
    "ENABLE_LANGUAGES",
    "DEFAULT_LANGUAGE",
    "AVAILABLE_LANGUAGES",
    "AUTO_COMPILE_SITEMAP",
    "DISCOURAGE_SEARCH_ENGINES",
    "WEBP_DEFAULT_QUALITY",
    "AUTO_COMPILE_SCSS",
    "COMPILED_CSS_TYPE",
    "DEFAULT_THEME",
    "PRINT_COMPILE_DATE_CSS",
    "AUTO_COMPILE_JS",
    "PRINT_COMPILE_DATE_JS",
    "JS_BUNDLES",
    "METADATA",
];


// Make sure constants exist
foreach ($kristalMandatoryConstants as $constant)
{
    if (!defined($constant))
    {
        $message = PRODUCTION_MODE ? "A critical error has occurred. Please contact the site administrator." : "Mandatory configuration variable $constant is not set, please create this constant to the project's config.php file.";
        debuglog($message);
        exit($message);
    }
}

// Set default timezone
date_default_timezone_set(TIMEZONE);
