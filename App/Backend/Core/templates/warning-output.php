<div class="kristal-warning-block">
    <p class="kristal-warning-title"><strong><?php echo esc_html($label); ?></strong>: <?php echo esc_html($message); ?></p>
    <span class="kristal-warning-message">Occurred on line <?php echo esc_html($line); ?> in file <?php echo esc_html($file); ?></span>
</div>

<style>
    .kristal-warning-block {
        background-color: #fff3cd !important;
        margin: 12px !important;
        padding: 20px !important;
        border: 2px solid orange !important;
    }
    
    .kristal-warning-title {
        font-family: Helvetica, Arial, sans-serif !important;
        color: black !important;
        margin-bottom: 0px;
    }

    .kristal-warning-message {
        font-family: Helvetica, Arial, sans-serif !important;
        color: black !important;
    }
</style>
