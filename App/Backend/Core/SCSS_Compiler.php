<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class SCSS_Compiler
{
    private static string $compiledCssFolderPath= PATH_CSS;
    private static string $globThemesFolder = PATH_CSS . "themes/*.scss";
    private static string $scssFolderPath = PATH_CSS . "scss/";

    public static function initialize(): void
    {
        $compiler = new Compiler();

        // Select formatter
        $compiler->setOutputStyle( strtolower( COMPILED_CSS_TYPE ) === 'expanded' ? OutputStyle::EXPANDED : OutputStyle::COMPRESSED );

        // Check should we compile
        if (self::shouldCompile())
        {
            self::compile($compiler);

        }
    }

    private static function shouldCompile(): bool
    {
        $themes = glob(self::$globThemesFolder);
        $outputs = $themes === [] ? [self::$compiledCssFolderPath . ensureCSSExtension(DEFAULT_THEME)] : array_map(static fn(string $theme): string => self::$compiledCssFolderPath . ensureCSSExtension(strtolower(pathinfo($theme, PATHINFO_FILENAME))), $themes);

        foreach ($outputs as $output) { if (!is_file($output)) { return true; } }
        $oldestCompiledTime = min(array_map('filemtime', $outputs));

        // Check if any theme file was modified after CSS compilation time
        foreach ($themes as $theme)
        {
            if (filemtime($theme) > $oldestCompiledTime)
            {
                return true;
            }
        }

        // Check recursively if any SCSS file is newer than the oldest theme output.
        $directory = new RecursiveDirectoryIterator(self::$scssFolderPath);
        $iterator = new RecursiveIteratorIterator($directory);
        foreach ($iterator as $file)
        {
            if ($file->isFile() && strtolower($file->getExtension()) === 'scss')
            {
                if ($file->getMTime() > $oldestCompiledTime)
                {
                    return true;
                }
            }
        }

        return false;
    }

    private static function compile($compiler): void
    {
        // Check if there are any themes
        $themes = glob(self::$globThemesFolder);

        // Compile without theme
        if (empty($themes))
        {
            self::compileOutput($compiler, DEFAULT_THEME);
            return;
        }

        // Compile each theme
        foreach ($themes as $theme)
        {
            $themeName = strtolower(pathinfo($theme, PATHINFO_FILENAME));
            self::compileOutput($compiler, $themeName, $theme);
        }
    }

    private static function compileOutput(Compiler $compiler, string $outputName, ?string $themeFile = null): void
    {
        $scss = "";
        $files = [];

        // Add theme variables if a theme file is provided
        if ($themeFile !== null && is_file($themeFile))
        {
            $scss .= file_get_contents($themeFile);
        }

        // Iterate through each file in the directory and its subdirectories
        $directory = new RecursiveDirectoryIterator(self::$scssFolderPath);
        $iterator = new RecursiveIteratorIterator($directory);
        foreach ($iterator as $file)
        {
            if ($file->isFile() && $file->getExtension() === "scss")
            {
                $scss .= file_get_contents($file->getRealPath());
            }
        }

        // Compile sass files
        $compiledCss = $compiler->compileString($scss)->getCss();

        // Add comment when file was last modified
        if (PRINT_COMPILE_DATE_CSS)
        {
            $compiledCss .= "\n/* Generated at: " . date(DATE_FORMAT . " " . TIME_FORMAT) . " */";
        }

        $compiledCss .= "\n";

        // Create css files from compiled sass
        file_put_contents(self::$compiledCssFolderPath. ensureCSSExtension($outputName), $compiledCss);
    }
}
