<?php include "partials/navigation.php"; ?>

<div class="two-factor-authentication-page container">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm form-card">
                <div class="card-body">

                    <h1 class="tfa-title">Verify Your Login</h1>
                    <p class="tfa-description">We've sent a 6-digit verification code to your email.</p>

                    <form method="post" autocomplete="off" spellcheck="false">
                        <?php CSRF::create("two_factor_authentication", "two_factor_authentication"); ?>

                        <div class="form-group mb-3">
                            <label class="form-label" for="tfa-code">Enter Verification Code</label>
                            <input type="number" class="form-control" name="tfa-code" id="tfa-code" required minlength="6" maxlength="6" value="<?php echo esc_html($tfa_code) ?>">
                        </div>

                        <div class="feedback <?php echo $feedback_status; ?>" id="tfa-feedback">
                            <?php if (!empty($feedback_message)): ?>
                                <?php echo $feedback_message; ?>
                            <?php endif; ?>
                        </div>
             
                        <button type="submit" class="btn btn-success" id="tfa-button" disabled>Verify</button>

                    </form>

                    <form method="post" autocomplete="off" spellcheck="false">
                        <?php CSRF::create("resend_two_factor_authentication", "resend_two_factor_authentication"); ?>
                        <button type="submit" class="btn btn-outline-light" id="tfa-resend-button">Resend Code</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Delete if URL code is removed
document.addEventListener("DOMContentLoaded", function () {
    const code = "<?php echo $tfa_code ?>";
    if (code.length === 6) {
        $("#tfa-code").val(code);
        $("#tfa-button").prop("disabled", false);
    }
});
</script>

<script src="<?php echo js("tfa.js"); ?>"></script>
