<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

use JShrink\Minifier;
use Exception;

final class JS_Compiler
{
    public static function initialize(): void
    {
        foreach (JS_BUNDLES as $bundleName => $files)
        {
            self::processBundle($bundleName, $files);
        }
    }

    private static function processBundle(string $bundleName, array $files): void
    {
        $bundlePath = PATH_JS . ensureJSExtension($bundleName);
        $sourceMapPath = $bundlePath . '.map';
        
        $lastCompileTime = file_exists($bundlePath) ? filemtime($bundlePath) : 0;
        $shouldCompile = false;
        $resolvedFiles = [];

        // Check has there been newer changes since last compilation
        foreach ($files as $file)
        {
            $filePath = PATH_JS . ensureJSExtension($file);

            if (!is_file($filePath))
            {
                kristal_fatalExit("JS_Compiler Error: Failed to load JS file '{$filePath}'.");
            }

            $resolvedFiles[] = $filePath;

            if (filemtime($filePath) > $lastCompileTime)
            {
                $shouldCompile = true;
            }
        }

        // Return if nothing to compile
        if (!$shouldCompile || empty($resolvedFiles))
        {
            return;
        }

        // Build Bundle
        self::buildBundle($resolvedFiles, $bundlePath, $sourceMapPath);
    }

    private static function buildBundle(array $files, string $bundlePath, string $bundleSourceMap): void
    {
        $bundleContent = "";
        $mapData = [
            "version" => 3,
            "file" => basename($bundlePath),
            "sources" => [],
            "names" => [],
            "mappings" => ""
        ];

        foreach ($files as $filePath)
        {
            $content = file_get_contents($filePath);

            if ($content === false)
                continue;

            // Normalize path for sourcemap
            $mapData["sources"][] = str_replace(PATH_JS, "", $filePath);

            try
            {
                $minified = Minifier::minify($content, ['flaggedComments' => false]);
            }
            catch (Exception $e)
            {
                debuglog("Javascript minification error: {$e->getMessage()}", "warning");
                $minified = $content;
            }

            $bundleContent .= $minified . "\n";
        }

        // Add Timestamp
        if (PRINT_COMPILE_DATE_JS)
        {
            $bundleContent .= "/* Generated: " . date(DATE_FORMAT . " " . TIME_FORMAT) . " */\n";
        }

        // Write Source Map Link
        $bundleContent .= "//# sourceMappingURL=" . basename($bundleSourceMap) . "\n";

        // Atomic Write
        file_put_contents($bundlePath, $bundleContent);
        file_put_contents($bundleSourceMap, json_encode($mapData, JSON_UNESCAPED_SLASHES));
    }
}
