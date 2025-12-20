<?php include "partials/navigation.php"; ?>

<div class="forgot-password-page container">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm form-card">
                <div class="card-body">

                    <h1 class="forgot-title">Reset Your Password</h1>
                    <p class="forgot-description">Enter your email address to receive a password reset link.</p>

                    <form method="post" autocomplete="off" spellcheck="false">
                        <?php CSRF::create("forgot_password", "forgot_password"); ?>

                        <div class="form-group mb-3">
                            <label class="form-label" for="forgot-email">Email</label>
                            <input type="email" class="form-control" name="forgot-password-email" id="forgot-password-email" required minlength="4">
                        </div>

                        <div class="feedback <?php echo $feedback_status; ?>" id="forgot-password-feedback">
                            <?php if (!empty($feedback_message)): ?>
                                <?php echo $feedback_message; ?>
                            <?php endif; ?>
                        </div>
             
                        <button type="submit" class="btn btn-success" id="send-forgot-password-email-button">Send Reset Link</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo js("forgot-password.js"); ?>"></script>
