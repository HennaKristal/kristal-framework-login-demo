<?php defined("ACCESS") or exit("Access Denied");

// ------------------------------------------------------------------------------------------------
// PHP error reporting configuration
// ------------------------------------------------------------------------------------------------
kristal_configureErrorReporting();

function kristal_configureErrorReporting(): void
{
    ini_set("display_startup_errors", ENABLE_DEBUG_DISPLAY ? "1" : "0");
    ini_set("display_errors", ENABLE_DEBUG_DISPLAY ? "1" : "0");
    ini_set("log_errors", ENABLE_DEBUG_LOG ? "1" : "0");
    ini_set("error_log", DEBUG_LOG_PATH);

    if (!ENABLE_DEBUG_DISPLAY && !ENABLE_DEBUG_LOG)
    {
        error_reporting(0);
        return;
    }

    $level = E_ALL;

    if (DEBUG_IGNORE_WARNINGS)
        $level &= ~(E_WARNING | E_USER_WARNING | E_CORE_WARNING | E_COMPILE_WARNING);

    if (DEBUG_IGNORE_NOTICES)
        $level &= ~(E_NOTICE | E_USER_NOTICE);

    if (DEBUG_IGNORE_DEPRECATED)
        $level &= ~(E_DEPRECATED | E_USER_DEPRECATED);

    if (DEBUG_IGNORE_STRICT)
        $level &= ~(E_STRICT);

    error_reporting($level);

    if (ENABLE_DEBUG_DISPLAY)
    {
        set_error_handler("kristal_errorHandler");
        register_shutdown_function("kristal_shutDownHandler");
    }
}


// ------------------------------------------------------------------------------------------------
// Error handler
// ------------------------------------------------------------------------------------------------
function kristal_errorHandler(string|int $type, string $message, string $file, int $line): void
{
    $label = "Error";

    if ($type === E_WARNING || $type === E_USER_WARNING || $type === E_CORE_WARNING || $type === E_COMPILE_WARNING)
    {
        if (DEBUG_IGNORE_WARNINGS) return;
        $label = "Warning";
    }
    elseif ($type === E_NOTICE || $type === E_USER_NOTICE)
    {
        if (DEBUG_IGNORE_NOTICES) return;
        $label = "Notice";
    }
    elseif ($type === E_DEPRECATED || $type === E_USER_DEPRECATED)
    {
        if (DEBUG_IGNORE_DEPRECATED) return;
        $label = "Deprecated";
    }
    elseif ($type === E_STRICT)
    {
        if (DEBUG_IGNORE_STRICT) return;
        $label = "Strict";
    }

    kristal_errorOutput($label, $message, $file, $line);
}


// ------------------------------------------------------------------------------------------------
// Debug output for warnings and non-fatal errors
// ------------------------------------------------------------------------------------------------
function kristal_errorOutput(string $label, string $message, string $file, int $line): void
{
    debuglog($message . " in " . $file . " on line " . $line, $label);

    if (!PRODUCTION_MODE)
    {
        require PATH_CORE . "templates/warning-output.php";
    }
}


// ------------------------------------------------------------------------------------------------
// Fatal error handler
// ------------------------------------------------------------------------------------------------
function kristal_shutDownHandler(): void
{
    // Don't output anything if there was no error
    $error = error_get_last();
    if (!$error)
        return;

    // Don't output anything if the error was not fatal
    $errorType = $error["type"];
    $isFatal = $errorType === E_ERROR || $errorType === E_PARSE || $errorType === E_CORE_ERROR || $errorType === E_COMPILE_ERROR || $errorType === E_RECOVERABLE_ERROR || $errorType === E_USER_ERROR;
    if (!$isFatal)
        return;

    // Clear output buffer safely
    if (ob_get_level() > 0)
        ob_end_clean();

    // Display custom error message for fatal errors
    if (PRODUCTION_MODE)
        kristal_productionFatalErrorOutput();
    else
        kristal_fatalErrorOutput($error["message"], $error["file"], $error["line"]);
}


function kristal_productionFatalErrorOutput(): void
{
    require PATH_CORE . "templates/production-fatal-error-output.php";
}


function kristal_fatalErrorOutput(string $message, string $file, int $line): void
{
    $lines = @file($file);
    
    if ($lines) 
    {
        $start = max(0, $line - 11);
        $end = min(count($lines), $line + 10);
    }

    require PATH_CORE . "templates/fatal-error-output.php";
}


function kristal_fatalExit(string $message, string $productionMessage = "A critical error has occurred. Please contact the site administrator."): void
{
    debuglog($message);

    if (PRODUCTION_MODE)
    {
        $message = $productionMessage;
    }

    require PATH_CORE . "templates/fatal-exit-output.php";
    exit();
}
