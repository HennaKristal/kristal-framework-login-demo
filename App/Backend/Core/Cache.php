<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

class Cache
{
    public static function add(string $name, $value, string $duration = "never")
    {
        // Create the cache folder if it is missing
        if (!file_exists(PATH_CACHE))
        {
            mkdir(PATH_CACHE, 0755, true);
        }

        $fileName = sanitize_file($name) . ".json";
        $filePath = PATH_CACHE . $fileName;
        $expires = self::parseExpiry($duration);
        $content = [
            'expires' => $expires,
            'data' => serialize($value)
        ];

        $json = json_encode($content, JSON_PRETTY_PRINT);
        if ($json === false)
        {
            debuglog("Could not encode cache data for {$name}: " . json_last_error_msg(), "warning");
            return false;
        }

        return file_put_contents($filePath, $json, LOCK_EX) !== false;
    }

    public static function get(string $name)
    {
        $fileName = sanitize_file($name) . ".json";
        $filePath = PATH_CACHE . $fileName;

        if (!file_exists($filePath))
            return null;

        $json = file_get_contents($filePath);
        if ($json === false)
            return null;

        $content = json_decode($json, true);

        // Remove cache if corrupted or invalid
        if (!is_array($content) || !isset($content['expires'], $content['data'])
            || !is_int($content['expires']) || !is_string($content['data']))
        {
            debuglog("Cache file is invalid or corrupted for {$name}. Removing file.", "warning");
            self::remove($name);
            return null;
        }

        // Remove cache if expired
        if (self::isExpired($content['expires']))
        {
            self::remove($name);
            return null;
        }

        return unserialize($content['data']);
    }

    public static function remove(string $name): bool
    {
        $fileName = sanitize_file($name) . ".json";
        $filePath = PATH_CACHE . $fileName;

        if (file_exists($filePath))
        {
            return unlink($filePath);
        }

        return false;
    }

    public static function clear(): void
    {
        foreach (glob(PATH_CACHE . "*.json") as $file)
        {
            unlink($file);
        }
    }

    private static function parseExpiry($duration): int
    {
        if ($duration === "never")
            return PHP_INT_MAX;

        $expires = strtotime("now + " . $duration);

        if ($expires === false)
        {
            debuglog("Invalid cache duration. Duration given: {$duration}", "warning");
            return time();
        }

        return $expires;
    }

    private static function isExpired(int $expires): bool
    {
        return $expires !== PHP_INT_MAX && time() >= $expires;
    }
}
