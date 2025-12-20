<script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_html(RECAPTCHA_V3_SITE_KEY); ?>"></script>

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('<?php echo esc_html(RECAPTCHA_V3_SITE_KEY); ?>', {action: 'contact_form'}).then(function(token) {
            document.getElementById('g-recaptcha-response').value = token;
        });
    });
</script>

<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
