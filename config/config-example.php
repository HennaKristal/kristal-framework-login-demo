<?php defined("ACCESS") or exit("Access Denied");


# --------------------------------------------------------------------------
# Framework configurations
# --------------------------------------------------------------------------

// Enable this in production to prevent development features from running
define("PRODUCTION_MODE", false);





# --------------------------------------------------------------------------
# Maintenance configurations
# --------------------------------------------------------------------------

// When enabled, visitors see the maintenance page instead of the site
define("MAINTENANCE_MODE", false);

// Displays a login form on the maintenance page if enabled
define("ENABLE_MAINTENANCE_LOGIN", false);

// Password that grants access during maintenance
define("MAINTENANCE_PASSWORD", "________");

// Number of incorrect maintenance logins allowed before lockout
define("MAINTENANCE_LOCKOUT_LIMIT", 5);

// Duration of the lockout period in seconds after too many failed attempts
define("MAINTENANCE_LOCKOUT_CLEAR_TIME", 900);





# --------------------------------------------------------------------------
# Error reporting configurations
# --------------------------------------------------------------------------

// Determines the visibility of debug and error messages in frontend
define("ENABLE_DEBUG_DISPLAY", true);

// Determines whether debug and error messages are recorded in a log file
define("ENABLE_DEBUG_LOG", true);

// Defines the file path for logging debug and error messages (please keep this outside of html root)
define("DEBUG_LOG_PATH", "./storage/logs/debug.log");

// When enabled, PHP notices will not be displayed or logged
define("DEBUG_IGNORE_WARNINGS", false);

// When enabled, PHP deprecated notices will not be displayed or logged
define("DEBUG_IGNORE_NOTICES", false);

// When enabled, PHP warnings will not be displayed or logged
define("DEBUG_IGNORE_DEPRECATED", false);

// When enabled, PHP strict standards notices will not be displayed or logged
define("DEBUG_IGNORE_STRICT", false);





# --------------------------------------------------------------------------
# Database configurations
# --------------------------------------------------------------------------

// Specify your database connections
define("DATABASES", [
    "primary" => [
        "host" => "________",
        "database_name" => "________",
        "username" => "________",
        "password" => "________"
    ],
    "secondary" => [
        "host" => "________",
        "database_name" => "________",
        "username" => "________",
        "password" => "________"
    ],
    "additional" => [
        "host" => "________",
        "database_name" => "________",
        "username" => "________",
        "password" => "________"
    ],
]);





# --------------------------------------------------------------------------
# SMTP Mail configurations
# --------------------------------------------------------------------------

// Host of the email service provider
define("MAILER_HOST", "________");

// Email address for the account
define("MAILER_EMAIL", "________");

// Password for the email account
define("MAILER_PASSWORD", "________");

// Display name for outgoing emails
define("MAILER_NAME", "________");

// Common protocols include ssl. Leave blank if your email service lacks encryption
define("MAILER_PROTOCOL", "ssl");

// Common email service ports include 25, 465, 587, and 2525
define("MAILER_PORT", 25);





# --------------------------------------------------------------------------
# reCAPTCHA
# --------------------------------------------------------------------------

// reCAPTCHA v2 API keys
define("RECAPTCHA_V2_SITE_KEY", "xxxxxxxxxxxxxxxxxxxxxxxxxxxx");
define("RECAPTCHA_V2_SITE_SECRET", "xxxxxxxxxxxxxxxxxxxxxxxxxxxx");

// reCAPTCHA v3 API keys
define("RECAPTCHA_V3_SITE_KEY", "xxxxxxxxxxxxxxxxxxxxxxxxxxxx");
define("RECAPTCHA_V3_SITE_SECRET", "xxxxxxxxxxxxxxxxxxxxxxxxxxxx");





# --------------------------------------------------------------------------
# Session configurations
# --------------------------------------------------------------------------

// Replace with a securely generated string (30-50 characters recommended)
define("SESSION_NAME", "________");

// Session expires after x seconds (0 = when browser closed)
define("SESSION_LIFETIME", 0);

// Session ID is regenerated every x seconds (0 to always keep same ID)
define("SESSION_REGENERATE_ID_TIME", 180);

// Session expires after x seconds if user doesn't perform any actions
define("SESSION_AFK_TIMEOUT", 1800);

// Control cross-site session behavior. Recommended values: "Strict" or "Lax"
define("SESSION_SAMESITE", "Lax");

// Regenerate CSRF tokens on each page request for heightened security
define("REGENERATE_CSRF_ON_PAGE_REFRESH", false);





# --------------------------------------------------------------------------
# Cookie configurations
# --------------------------------------------------------------------------

// Replace with a securely generated string (30-50 characters recommended)
define("COOKIE_NAME", "________");

// Cookies are set to expire after a specified number of seconds
define("COOKIE_LIFETIME", 86400);

// Control cross-site cookie behavior. Recommended values: "Strict" or "Lax"
define("COOKIE_SAMESITE", "Lax");





# --------------------------------------------------------------------------
# Time configurations
# --------------------------------------------------------------------------

// List of all available timezones: https://www.php.net/manual/en/timezones.php
define("TIMEZONE", "UTC");

// Specify the format for displaying dates (for example: "j.n.Y" for 31.1.2020, "m/d/Y" for 01/31/2020, "F j, Y" for January 31, 2020)
define("DATE_FORMAT", "j.n.Y");

// Define the format for displaying times (for example: "H:i:s" for 18:50:04, "g:i a" for 06:50 pm, "g:i:s a" for 06:50:04 pm)
define("TIME_FORMAT", "H:i:s");





# --------------------------------------------------------------------------
# Language configurations
# --------------------------------------------------------------------------

// Enable native support for multilingual URLs, appending the language to the URL (for example: 'example.com/en')
define("ENABLE_LANGUAGES", true);

// Set the default language for your site (this will also be the main language of your site)
define("DEFAULT_LANGUAGE", "en");

// List of all available languages
define("AVAILABLE_LANGUAGES", [
    "en",
    "fi",
    "sv",
]);





# --------------------------------------------------------------------------
# SEO configurations
# --------------------------------------------------------------------------

// Automatically generate sitemaps from your routes (excluding private and protected functions)
define("AUTO_COMPILE_SITEMAP", false);

// When true, adds noindex and nofollow meta tags to discourage search engines
define("DISCOURAGE_SEARCH_ENGINES", false);





# --------------------------------------------------------------------------
# Image configurations
# --------------------------------------------------------------------------

// Default compression when calling webp("imageName")
define("WEBP_DEFAULT_QUALITY", 70);





# --------------------------------------------------------------------------
# SCSS configurations (does not work when in production mode)
# --------------------------------------------------------------------------

// Automatically recompile SCSS files upon modifications and page reload
define("AUTO_COMPILE_SCSS", true);

// Define the SCSS compilation mode, choose between 'expanded' for readability and 'compressed' for optimization
define("COMPILED_CSS_TYPE", "compressed");

// Set a default theme if Session::get('theme') is undefined. If you don't use any themes then assign a default name for the compilation target of your SCSS files
define("DEFAULT_THEME", "dark");

// Include a comment indicating the last compilation time of the CSS file
define("PRINT_COMPILE_DATE_CSS", true);





# --------------------------------------------------------------------------
# JavaScript configurations (does not work when in production mode)
# --------------------------------------------------------------------------

// Automatically recompile JavaScript files upon modifications and page reload
define("AUTO_COMPILE_JS", true);

// Include a comment indicating the last compilation time of the JavaScript file
define("PRINT_COMPILE_DATE_JS", true);

// Tell the framework how do you want your js files to be bundled and minified
define("JS_BUNDLES", [
    "core.js" => [
        "core/form.js",
        "core/tooltips.js",
        "core/translator.js",
        "core/animator.js",
    ],
    "maintenance.js" => [
        "scripts/maintenance.js",
    ],
    "main.js" => [
        "scripts/main.js",
    ],
]);





# --------------------------------------------------------------------------
# Page metadata configurations
# --------------------------------------------------------------------------
// This tell the header file how to set the page's metadata
define("METADATA", [

    // Metadata for home page
    "home" => [
        "title" => "________",
        "description" => "________",
        "author" => "________",
        "keywords" => "________",
        "twitter:card" => "summary_large_image",
        "twitter:title" => "________",
        "twitter:description" => "________",
        "twitter:image" => URL_IMAGES . "kristal_framework_logo.png",
        "og:type" => "website",
        "og:title" => "________",
        "og:description" => "________",
        "og:url" => URL_BASE,
        "og:site_name" => "________",
        "og:image" => URL_IMAGES . "kristal_framework_logo.png",
    ],

    // Metadata from '/demo' page
    "demo" => [
        "title" => "________",
        "description" => "________",
        "author" => "________",
        "keywords" => "________",
        "twitter:card" => "summary_large_image",
        "twitter:title" => "________",
        "twitter:description" => "________",
        "twitter:image" => URL_IMAGES . "kristal_framework_logo.png",
        "og:type" => "website",
        "og:title" => "________",
        "og:description" => "________",
        "og:url" => URL_BASE,
        "og:site_name" => "________",
        "og:image" => URL_IMAGES . "kristal_framework_logo.png",
    ],

    // Metadata for pages without predefined specifications
    "*" => [
        "title" => "________",
        "description" => "________",
        "author" => "________",
        "keywords" => "________",
        "twitter:card" => "summary_large_image",
        "twitter:title" => "________",
        "twitter:description" => "________",
        "twitter:image" => URL_IMAGES . "kristal_framework_logo.png",
        "og:type" => "website",
        "og:title" => "________",
        "og:description" => "________",
        "og:url" => URL_BASE,
        "og:site_name" => "________",
        "og:image" => URL_IMAGES . "kristal_framework_logo.png",
    ],

]);





# --------------------------------------------------------------------------
# Custom configurations
# --------------------------------------------------------------------------

// You can also define custom constants for use throughout the application
define("CUSTOM_NAME", "CUSTOM_VALUE");
