<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

class PHPJS
{
    private static array $jsVariables = [];
    private static array $jsScripts = [];

    public static function addJSVariable(string|array $variable, mixed $value = ""): void
    {
        if (is_array($variable))
        {
            foreach ($variable as $key => $val)
            {
                self::$jsVariables[$key] = $val;
            }

            return;
        }

        self::$jsVariables[$variable] = $value;
    }

    public static function addScript(string $script): void
    {
        self::$jsScripts[] = $script;
    }

    public static function release(): void
    {
        self::$jsVariables["baseURL"] = URL_BASE;
        self::$jsVariables["production_mode"] = PRODUCTION_MODE;
        self::$jsVariables["language"] = getAppLocale();

        $json = json_encode(self::$jsVariables, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false)
            return;

        echo "<script>";
        echo "window.AppConfig = {$json};";
        echo "window.getVariable = function(key) { return window.AppConfig[key]; };";

        foreach (self::$jsScripts as $script)
        {
            echo $script;
        }

        echo "</script>";
    }
}
