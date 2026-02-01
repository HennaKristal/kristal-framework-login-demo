<?php defined("ACCESS") or exit("Access Denied");

function sanitize_file(string $value): string
{
    $value = basename($value);
    $value = trim($value);
    $value = preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $value);
    return substr($value, 0, 255);
}
