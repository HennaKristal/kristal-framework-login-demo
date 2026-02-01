/** Available variables:
 * - $currentDate
 * - $targetDate
 * - $dateDifference (in seconds)
 * - $uniqueId
 * - $atts['date']
 * - $atts['format']
 * - $atts['days']
 * - $atts['hours']
 * - $atts['minutes']
 * - $atts['seconds']
 * - $atts['expired']
 */

jQuery(document).ready(function()
{
    const countdownContainer = jQuery("#<?php echo $uniqueId; ?>");
    const countdownTimer = jQuery("#<?php echo $uniqueId; ?> .countdown-timer");
    
    let date_difference = countdownTimer.data('date-difference');
    let format = countdownTimer.data('format');
    let daysText = countdownTimer.data('days').split('|');
    let hoursText = countdownTimer.data('hours').split('|');
    let minutesText = countdownTimer.data('minutes').split('|');
    let secondsText = countdownTimer.data('seconds').split('|');
    let expiredText = countdownTimer.data('expired');
    let intervalId;

    updateCountdown();
    countdownContainer.show();

    function padNumber(number) 
    {
        return (number < 10 ? '0' : '') + number;
    }

    function updateCountdown()
    {
        if (date_difference <= 0)
        {
            countdownTimer.html(expiredText);
            clearInterval(intervalId);
            return;
        }

        const days = Math.floor(date_difference / (60 * 60 * 24));
        const hours = Math.floor((date_difference % (60 * 60 * 24)) / (60 * 60));
        const minutes = Math.floor((date_difference % (60 * 60)) / 60);
        const seconds = Math.floor(date_difference % 60);

        let dayLabel = days > 1 ? daysText[0] : daysText[1] || daysText[0];
        let hourLabel = hours > 1 ? hoursText[0] : hoursText[1] || hoursText[0];
        let minuteLabel = minutes > 1 ? minutesText[0] : minutesText[1] || minutesText[0];
        let secondLabel = seconds > 1 ? secondsText[0] : secondsText[1] || secondsText[0];

        let displayString = format;

        if (days > 0)
        {
            displayString = displayString
                .replace('{d}', days)
                .replace('{D}', dayLabel);
        }
        else
        {
            displayString = displayString
                .replace('{d}', '')
                .replace('{D}', '');
        }

        displayString = displayString
            .replace('{h}', padNumber(hours))
            .replace('{m}', padNumber(minutes))
            .replace('{s}', padNumber(seconds))
            .replace('{H}', hourLabel)
            .replace('{M}', minuteLabel)
            .replace('{S}', secondLabel);

        countdownTimer.html(displayString);

        date_difference = date_difference - 1;
    }

    intervalId = setInterval(updateCountdown, 1000);
});
