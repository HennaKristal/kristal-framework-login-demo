<?php defined("ACCESS") or exit("Access Denied");
/**
 * This content is rendered by countdown block
 * Block::render("countdown", ["date" => "", "format" => "", "days" => "", "hours" => "", "minutes" => "", "seconds" => "", "expired" => ""]); 
 * 
 * Available variables:
 *  - $atts['date']
 *  - $atts['format']
 *  - $atts['days']
 *  - $atts['hours']
 *  - $atts['minutes']
 *  - $atts['seconds']
 *  - $atts['expired']
 */

// Give default values to attributes
$atts = array_merge(array(
    'date' => '01.01.2000 00:00:00',
    'format' => '⏰ {d} {D} {h}{H}:{m}{M}:{s}{S} ⏰',
    'days' => 'days|day',
    'hours' => 'h',
    'minutes' => 'm',
    'seconds' => 's',
    'expired' => '',
), $atts);

$currentDate = new DateTime('now', new DateTimeZone(TIMEZONE));
$targetDate = DateTime::createFromFormat('d.m.Y H:i:s', $date, new DateTimeZone(TIMEZONE));

if ($targetDate === false)
{
    return;
}

$dateDifference = $targetDate->getTimestamp() - $currentDate->getTimestamp();
$uniqueId = uniqid('countdown_');

ob_start();
include( __DIR__ . '/template.php' );

if ($dateDifference > 0)
{
    ?><script><?php include( __DIR__ . '/javascript.js' ); ?></script><?php
}

$output = ob_get_clean();
echo $output;
