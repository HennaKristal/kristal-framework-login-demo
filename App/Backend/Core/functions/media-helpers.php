<?php defined("ACCESS") or exit("Access Denied");

function image(string $file) { return kristal_getAssetURL("images", $file); }
function css(string $file) { return kristal_getAssetURL("css", $file); }
function js(string $file) { return kristal_getAssetURL("javascript", $file); }
function download(string $file) { return kristal_getAssetURL("downloads", $file); }
function audio(string $file) { return kristal_getAssetURL("audio", $file); }

function kristal_getAssetURL(string $folder, string $file): string
{
    // Remove leading slash if present
    if (strpos($file, "/") === 0)
    {
        $file = substr($file, 1);
    }

    $searchFolder = "App/media/" . $folder . "/";
    $filePath = PATH_ROOT . $searchFolder . $file;

    // If file doesn't exist try to find same file with any extension
    if (!file_exists($filePath))
    {
        $matches = glob($filePath . ".*");

        if (empty($matches))
            return "";

        $filePath = $matches[0];
    }

    // Extract filename from file path
    $position = strpos($filePath, $searchFolder);
    if ($position === false)
        return "";

    // Extract filename from file path
    $fileName = substr($filePath, $position);

    // URL version for css and javascript
    if ($folder === "CSS" || $folder === "Javascript")
    {
        $fileName .= "?ver=" . filemtime($filePath);
    }

    return rtrim(URL_BASE, "/") . "/" . $fileName;
}


function imagePath(string $file) { return kristal_getAssetPath("images", $file); }
function cssPath(string $file) { return kristal_getAssetPath("css", $file); }
function jsPath(string $file) { return kristal_getAssetPath("javascript", $file); }
function downloadPath(string $file) { return kristal_getAssetPath("downloads", $file); }
function audioPath(string $file) { return kristal_getAssetPath("audio", $file); }

function kristal_getAssetPath(string $folder, string $file): string
{
    $filePath = PATH_ROOT . "/App/media/" . $folder . "/" . $file;

    // Return path as is if file exists
    if (file_exists($filePath))
    {
        return $filePath;
    }

    // Get all files that match the file name
    $matches = glob($filePath . ".*");

    // Return null if no file was found
    if (empty($matches))
    {
        return null;
    }

    // Return 1st file that matches to mimic getAssetURL logic
    return $matches[0];
}
