<?php defined("ACCESS") or exit("Access Denied");

// ------------------------------------------------------------------------------------------------
// Debug output for variables
// ------------------------------------------------------------------------------------------------
function debug(mixed $value, string $name = null): void
{
    if (ENABLE_DEBUG_DISPLAY && !PRODUCTION_MODE)
    {
        require PATH_CORE . "templates/debug-output.php";
    }
}


// ------------------------------------------------------------------------------------------------
// Logging
// ------------------------------------------------------------------------------------------------
function debuglog(mixed $message, string $severity = "Debug"): void
{
    if (!ENABLE_DEBUG_LOG)
        return;

    $time = date("Y-m-d H:i:s e");

    if (is_array($message))
    {
        $message = print_r($message, true);
    }

    $cleanMessage = "[" . $time . "] " . $severity . ": " . $message . "\n";

    error_log($cleanMessage, 3, DEBUG_LOG_PATH);
}
