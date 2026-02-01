<?php declare(strict_types=1); 
namespace Backend\Controllers;
defined("ACCESS") or exit("Access Denied");

use Backend\Core\Session;

class ActionRateLimiter
{
    public static function GetActionCooldown(): int
    {
        $now = time();

        $last_time = Session::get("last_action_timestamp", true, 0);
        $attempts = Session::get("action_attempts", true, 0);
        $force_until = Session::get("action_force_until", true, 0);

        // Forced cooldown check
        if ($force_until > $now) {
            $remaining = $force_until - $now;
            return $remaining;
        }

        // Normal cooldown check
        if ($last_time > 0) {
            $difference = $now - $last_time;

            if ($difference < ACTION_COOLDOWN) {
                $remaining = ACTION_COOLDOWN - $difference;

                // Add an attempt inside the cooldown window
                $attempts++;
                Session::add("action_attempts", $attempts);

                // Too many attempts inside cooldown window
                if ($attempts > ACTION_MAX_ATTEMPTS) {
                    $force = $now + ACTION_LOCKED_OUT_COOLDOWN;
                    Session::add("action_force_until", $force);
                    return ACTION_LOCKED_OUT_COOLDOWN;
                }

                return $remaining;
            }
        }

        // Reset attempts if outside cooldown window
        Session::add("action_attempts", 0);

        // Update last action time
        Session::add("last_action_timestamp", $now);

        return 0;
    }
}
