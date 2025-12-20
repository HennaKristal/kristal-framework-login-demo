<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

class CSRF
{
    // Resets all CSRF data from session
    public static function reset(): void
    {
        foreach (Session::getAll() as $key => $value)
        {
            if (str_starts_with($key, "csrf_"))
            {
                Session::remove($key);
            }
        }
    }

    // Add CSRF:create("identifier", "formRequest") inside a form template to create CSRF protected form requests
    public static function create(string $identifier, string $formRequest): void
    {
        $sessionKey = "csrf_" . $identifier;

        if (!Session::has($sessionKey))
        {
            Session::add($sessionKey, [
                "token" => bin2hex(random_bytes(32)),
                "formRequest" => $formRequest,
            ]);
        }

        $token = Session::get($sessionKey)["token"];
        
        echo "<input type='hidden' name='csrf_identifier' value='" . esc_html($identifier) . "'>";
        echo "<input type='hidden' name='csrf_token' value='" . esc_html($token) . "'>";
    }
}
