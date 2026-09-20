<?php defined("ACCESS") or exit("Access Denied");
/**
 * This content is rendered by recaptcha_v3 block
 * Block::render("recaptcha_v3", ["action" => ""]);
 *
 * Available variables:
 *  - $atts['action']
 */

// Give default values to attributes
$atts = array_merge(array(
    'action' => 'default_form_submission',
), $atts);

ob_start();
include( __DIR__ . '/template.php' );
$output = ob_get_clean();
echo $output;
