<?php include "partials/navigation.php"; ?>

<div class="container reset-password-page">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm form-card">
                <div class="card-body">

                    <?php if ($feedback_status === "blocked"): ?>

                        <h1 class="reset-title">Password Reset Blocked</h1>
                        <p class="reset-description">For your security, this password reset attempt cannot continue. Please contact support or try again later.</p>

                        <?php if (!empty($feedback_message)): ?>
                            <div class="feedback failed">
                                <?php echo $feedback_message; ?>
                            </div>
                        <?php endif; ?>

                        <a href="/forgot-password">Try again</a>

                    <?php else: ?>

                        <h1 class="reset-title">Create a New Password</h1>
                        <p class="reset-description">Set a secure new password for your account.</p>

                        <form method="post" autocomplete="off" spellcheck="false">
                            <?php CSRF::create("password_reset"); ?>
                            <?php CSRF::request("password_reset"); ?>

                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                            <input type="hidden" name="token" value="<?php echo $token; ?>">

                            <div class="form-group mb-3">
                                <label class="form-label" for="reset-email">New Password</label>
                                <input type="password" class="form-control" id="new-password" name="new-password" required minlength="8">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label" for="reset-email">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password" name="confirm-password" required minlength="8">
                            </div>

                            <div class="feedback <?php echo $feedback_status; ?>" id="reset-feedback">
                                <?php if (!empty($feedback_message)): ?>
                                    <?php echo $feedback_message; ?>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-success" id="reset-button">Reset Password</button>
                        </form>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo js("reset-password.js"); ?>"></script>
