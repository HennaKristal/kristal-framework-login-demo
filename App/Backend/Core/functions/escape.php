<?php defined("ACCESS") or exit("Access Denied");

function esc_html(string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function esc_url(string $value): string
{
    $value = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $value);
    
    if (preg_match('/^\s*(javascript|vbscript):/i', $value))
    {
        return '#';
    }

    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function esc_js(string $value): string
{
    return json_encode($value, JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function esc_css(string $value): string
{
    return preg_replace('/[^a-zA-Z0-9\-\_\#\(\)\.\s]/', '', (string) $value);
}
