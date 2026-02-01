<?php defined("ACCESS") or exit("Access Denied");

setcookie(COOKIE_NAME, bin2hex(random_bytes(16)), [
    'expires' => time() + COOKIE_LIFETIME,
    'path' => "/",
    'domain' => DOMAIN,
    'secure' => true,                   // Ensures the cookie is only sent over HTTPS
    'httponly' => true,                 // Ensures the cookie is not accessible by client-side scripts
    'samesite' => COOKIE_SAMESITE,      // Control cross-site cookie behavior
]);
