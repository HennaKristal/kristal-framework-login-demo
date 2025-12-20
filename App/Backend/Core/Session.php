<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

class Session
{
    public static function initialize(): void
    {
        self::start();
    }

    public static function start(): void
    {
        // Start session if it is not yet active
        if (!self::isActive())
        {
            self::startSession(self::getUniqueIdentity());
        }
    }

    public static function getClientIPAddress(): string
    {
        $remoteAddress = $_SERVER["REMOTE_ADDR"] ?? "";

        if (filter_var($remoteAddress, FILTER_VALIDATE_IP))
        {
            return $remoteAddress;
        }
    
        return "unknown";
    }

    public static function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    }

    public static function getUniqueIdentity(): string
    {
        return hash('sha256', self::getUserAgent());
    }

    private static function startSession(string $visitorIdentity): void
    {
        if (SESSION_NAME == "________")
        {
            if (PRODUCTION_MODE)
            {
                debuglog("Could not start session for security reasons because the session name was default value", "warning");
                return;
            }
            else
            {
                kristal_fatalExit("Please update your session key from the default value to a unique value in config.php");
            }
        }
        
        session_name(SESSION_NAME);

        if (DOMAIN !== "")
        {
            ini_set('session.cookie_domain', DOMAIN);
        }

        ini_set('session.cookie_lifetime', SESSION_LIFETIME);
        ini_set('session.cookie_path', '/');
        ini_set('session.cookie_secure', '1');
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_samesite', COOKIE_SAMESITE);
        ini_set('session.use_strict_mode', '1');

        session_start();

        self::regenerateSessionIdPeriodically();
        
        // Check session duration
        self::afkTimeout(SESSION_AFK_TIMEOUT);

        if (empty(self::get("visitor_identity")))
        {
            // Set user identity if not yet set
            self::add("visitor_identity", $visitorIdentity);
            session_regenerate_id(true);
        }
        else if (self::get("visitor_identity") !== $visitorIdentity)
        {
            // Restart if user's IP address doesn't match the original one
            self::restart();
        }
    }

    private static function regenerateSessionIdPeriodically(): void
    {
        // If regeneration is disabled, skip
        if (SESSION_REGENERATE_ID_TIME === 0)
            return;

        $lastRegeneration = self::get("last_id_regeneration_time");

        // If no regeneration has been tracked yet, set it now
        if ($lastRegeneration === null)
        {
            self::add("last_id_regeneration_time", time());
            return;
        }

        // If enough time has passed, regenerate
        if (time() - (int)$lastRegeneration >= SESSION_REGENERATE_ID_TIME)
        {
            session_regenerate_id(true);
            self::add("last_id_regeneration_time", time());
        }
    }

    public static function end(): void
    {
        // Remove all session variables
        self::removeAll();
    
        // Destroy session data on server
        if (session_status() === PHP_SESSION_ACTIVE)
            session_destroy();
    
        // Destroy session cookie in browser
        if (ini_get("session.use_cookies"))
        {
            $parameters = session_get_cookie_params();
    
            setcookie(
                session_name(),
                "",
                time() - 3600,
                $parameters["path"],
                $parameters["domain"],
                $parameters["secure"],
                $parameters["httponly"]
            );
        }
    }

    public static function restart(): void
    {
        self::end();
        self::start();
    }

    // Add variables to session
    public static function add(string $identifier, $value): void
    {
        $_SESSION[$identifier] = $value;
        return;
    }

    // Check does session has variable
    public static function has(string $key): bool
    {
        return (isset($_SESSION[$key])) ? true : false;
    }

    // Remove variables from session
    public static function remove(string $key): void
    {
        // Remove single variable
        if (!is_array($key))
        {
            unset($_SESSION[$key]);
            return;
        }

        // Remove multiple variables
        foreach ($key as $variable)
        {
            unset($_SESSION[$variable]);
        }
    }

    // Remove every variable from session
    public static function removeAll(): void
    {
        session_unset();
    }

    // Get variables from session
    public static function get(string $key, $default = null): mixed
    {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : $default;
    }

    public static function getAll(): array
    {
        return $_SESSION;
    }

    // Check if session is already active
    private static function isActive(): bool
    {
        $serverInterface = php_sapi_name();

        if ($serverInterface === "cli") {
            return false;
        }

        return session_status() === PHP_SESSION_ACTIVE;
    }

    // End Session if user isn't active for x seconds (specified in the config.php)
    private static function afkTimeout(int $duration): void
    {
        // Get previous time afk_timeout was set
        $timeout = self::get("afk_timeout");

        // Update the new afk_timeout
        self::add("afk_timeout", time());

        // Nothing to do if there was not check for previous afk_timeout
        if (!isset($timeout))
        {
            return;
        }

        // Check has the user been afk longer than the allowed duration
        if (time() - (int)$timeout > $duration)
        {
            self::restart();
        }
    }
}
