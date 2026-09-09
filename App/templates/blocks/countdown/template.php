<?php
/** Available variables:
 *  - $currentDate
 *  - $targetDate
 *  - $dateDifference (in seconds)
 *  - $uniqueId
 *  - $atts['date']
 *  - $atts['format']
 *  - $atts['days']
 *  - $atts['hours']
 *  - $atts['minutes']
 *  - $atts['seconds']
 *  - $atts['expired']
 */
?>

<div class="countdown-timer-container" id="<?php echo $uniqueId; ?>">

    <?php if ($dateDifference > 0): ?>

        <span class="countdown-timer"
            data-date-difference="<?php echo $dateDifference; ?>"
            data-format="<?php echo esc_html($atts['format']); ?>"
            data-days="<?php echo esc_html($atts['days']); ?>"
            data-hours="<?php echo esc_html($atts['hours']); ?>"
            data-minutes="<?php echo esc_html($atts['minutes']); ?>"
            data-seconds="<?php echo esc_html($atts['seconds']); ?>"
            data-expired="<?php echo esc_html($atts['expired']); ?>">
        </span>

    <?php else: ?>

        <span class="countdown-timer" ><?php echo $atts['expired']; ?></span>

    <?php endif; ?>

</div>
