<?php defined("ACCESS") or exit("Access Denied");
/**
 * This content is rendered by language_menu block
 * Block::render("language_menu", ["request" => "", "languages" => ""]); 
 * 
 * Available variables:
 *  - $atts['request']
 *  - $atts['languages']
 */

// Give default values to attributes
$atts = array_merge(array(
    'request' => '',
    'languages' => AVAILABLE_LANGUAGES,
), $atts);

// Validation
if (!is_array($atts['languages']))
{
    echo "Languages must be given as an array";
    return;
}

ob_start();
include( __DIR__ . '/template.php' );
$output = ob_get_clean();
echo $output;
