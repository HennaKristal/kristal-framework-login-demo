<?php defined("ACCESS") or exit("Access Denied");

// ------------------------------------------------------------------------------------------------
// Page Helpers
// ------------------------------------------------------------------------------------------------
function page(string $file): string
{
    $file = ensurePHPExtension($file);
    $realPath = realpath(PATH_TEMPLATES . $file);

    if ($realPath === false)
        return false;

    if (strpos($realPath, PATH_TEMPLATES) !== 0)
        return false;

    return $realPath;
}

function pageExists(string $file): bool
{
    return page($file) !== false;
}


// ------------------------------------------------------------------------------------------------
// Routing and Redirect Helpers
// ------------------------------------------------------------------------------------------------
function route(string $page = ""): string
{
    if (ENABLE_LANGUAGES)
    {
        return URL_BASE . getAppLocale() . "/" . $page;
    }

    return URL_BASE . $page;
}

function redirect(string $target = null): void
{
    // Redirect to given page
    if (!empty($target))
    {
        header("Location: " . $target);
        exit;
    }

    // Redirect back to previous page
    if (isset($_SERVER["HTTP_REFERER"]))
    {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    }

    refreshPage();
}

function redirectBack(?string $fallback = null): void
{
    // Redirect back to previous page
    if (isset($_SERVER["HTTP_REFERER"]))
    {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit;
    }

    // Redirect to fallback page
    if (!empty($fallback))
    {
        header("Location: " . $fallback);
        exit;
    }

    header("Location: /");
    exit;
}

function refreshPage(): void
{
    header("Refresh:0");
    exit;
}


// ------------------------------------------------------------------------------------------------
// File Extension Helpers
// ------------------------------------------------------------------------------------------------
function ensurePHPExtension(string $file): string
{
    return substr($file, -4) === ".php" ? $file : $file . ".php";
}

function ensureJSExtension(string $file): string
{
    return substr($file, -3) === ".js" ? $file : $file . ".js";
}

function ensureCSSExtension(string $file): string
{
    return substr($file, -4) === ".css" ? $file : $file . ".css";
}

function ensureSCSSExtension(string $file): string
{
    return substr($file, -5) === ".scss" ? $file : $file . ".scss";
}
