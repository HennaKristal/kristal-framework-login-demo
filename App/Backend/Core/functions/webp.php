<?php defined("ACCESS") or exit("Access Denied");

function webp(string $file, int $compression = -1)
{
    // Normalize input before passing to mode handlers
    if (strpos($file, "/") === 0)
    {
        $file = substr($file, 1);
    }

    if (!is_numeric($compression) || $compression < 0)
    {
        $compression = WEBP_DEFAULT_QUALITY;
    }
    else
    {
        $compression = intval($compression);

        if ($compression < 0)
        {
            $compression = 0;
        }

        if ($compression > 100)
        {
            $compression = 100;
        }
    }

    if (PRODUCTION_MODE === true)
    {
        return webpProduction($file, $compression);
    }

    return webpDevelopment($file, $compression);
}


function webpProduction(string $file, int $compression)
{
    // Build the expected filename directly
    $cleanName = preg_replace("/[^a-zA-Z0-9\/\.\-_]/", "", $file);
    $cleanName = str_replace("/", "-", $cleanName);
    $cleanName = preg_replace("/\.[a-zA-Z0-9]+$/", "", $cleanName);
    $cleanName = $cleanName . "-" . $compression . ".webp";

    return URL_WEBP . $cleanName;
}


function webpDevelopment(string $file, int $compression)
{
    // Ensure output folder exists
    if (!is_dir(PATH_WEBP))
    {
        mkdir(PATH_WEBP, 0755, true);
    }

    // Resolve full path
    $filePath = PATH_IMAGES . $file;

    // Attempt alternative extensions
    if (!file_exists($filePath))
    {
        $matches = glob($filePath . ".*");

        if (empty($matches))
        {
            return "";
        }

        $filePath = $matches[0];
    }

    // Detect type
    $imageType = exif_imagetype($filePath);

    // Resolve relative name
    $searchFolder = "/App/media/images/";
    $position = strpos($filePath, $searchFolder);

    if ($position === false)
    {
        return "";
    }

    $relativeName = substr($filePath, $position + strlen($searchFolder));
    $cleanName = str_replace("/", "-", $relativeName);

    // Native webp
    if ($imageType === IMAGETYPE_WEBP)
    {
        // Remove extension from cleanName
        $cleanName = preg_replace("/\.[a-zA-Z0-9]+$/", "", $cleanName);

        // Output should match naming rules: name-quality.webp
        $outputPath = PATH_WEBP . $cleanName . "-" . $compression . ".webp";

        // Use cached version if available
        if (!file_exists($outputPath))
        {
            copy($filePath, $outputPath);
        }

        $fileName = basename($outputPath);
        return URL_WEBP . $fileName;
    }

    // Build output
    $cleanName = preg_replace("/\.[a-zA-Z0-9]+$/", "", $cleanName);
    $outputPath = PATH_WEBP . $cleanName . "-" . $compression . ".webp";

    // Cached version
    if (file_exists($outputPath))
    {
        $fileName = basename($outputPath);
        return URL_WEBP . $fileName;
    }

    // Load source
    if ($imageType === IMAGETYPE_PNG)
    {
        $image = imagecreatefrompng($filePath);
    }
    else if ($imageType === IMAGETYPE_JPEG)
    {
        $image = imagecreatefromjpeg($filePath);
    }
    else
    {
        return "";
    }

    // Generate
    imagewebp($image, $outputPath, $compression);
    imagedestroy($image);

    $fileName = basename($outputPath);
    return URL_WEBP . $fileName;
}
