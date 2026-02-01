<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

class Block
{
    protected static $blocks = [];

    public static function initialize()
    {
        // Get all directories within the blocks folder
        $directories = glob(PATH_TEMPLATES . 'blocks/*', GLOB_ONLYDIR);

        if ($directories === false)
            return;

        foreach ($directories as $dir)
        {
            // Use directory name as the block name
            $block = basename($dir);
            
            // The file we're looking for is index.php inside the directory
            $file = $dir . '/index.php';
            
            // Check if the index.php file exists before registering it
            if (is_file($file))
            {
                self::$blocks[$block] = $file;
            }
        }
    }

    public static function render(string $block, array $atts = [])
    {
        if (!array_key_exists($block, self::$blocks))
            return "";

        ob_start();

        // Extract variables
        foreach ($atts as $key => $value)
        {
            if (is_string($key))
            {
                $$key = $value;
            }
        }

        include self::$blocks[$block];

        echo ob_get_clean();
    }
}
