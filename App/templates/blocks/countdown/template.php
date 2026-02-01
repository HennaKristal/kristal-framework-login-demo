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
            data-format="<?php echo $atts['format']; ?>" 
            data-days="<?php echo $atts['days']; ?>" 
            data-hours="<?php echo $atts['hours']; ?>" 
            data-minutes="<?php echo $atts['minutes']; ?>" 
            data-seconds="<?php echo $atts['seconds']; ?>" 
            data-expired="<?php echo $atts['expired']; ?>">
        </span> 
    
    <?php else: ?>
        
        <span class="countdown-timer" ><?php echo $atts['expired']; ?></span>

    <?php endif; ?>

</div>
