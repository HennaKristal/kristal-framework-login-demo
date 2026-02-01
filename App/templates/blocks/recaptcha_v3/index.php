<?php defined("ACCESS") or exit("Access Denied");
/**
 * This content is rendered by recaptcha_v3 block
 * Block::render("recaptcha_v3");
 */

ob_start();
include( __DIR__ . '/template.php' );
$output = ob_get_clean();
echo $output;
