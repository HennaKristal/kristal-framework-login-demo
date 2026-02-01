<?php include "partials/navigation.php"; ?>

<div class="activate-account-page container">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm form-card">
                <div class="card-body">

                    <h1>Account Activation</h1>

                    <?php if (!empty($feedback_message)): ?>
                        <div class="feedback <?php echo $feedback_status; ?>" id="activate-accound-feedback">
                            <?php echo $feedback_message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off" spellcheck="false">

                        <h2>Resend activation email</h2>

                        <div class="form-group mb-3">
                            <label class="form-label" for="reset-email">Email</label>
                            <input type="email" class="form-control" name="reset-email" id="reset-email" required minlength="4" value="<?php echo $email; ?>">
                        </div>
                        
                        <a id="activation-link" class="link-btn" href="/activate-account/<?php echo $email; ?>">Resend Activation Link</a>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo js("activate-account.js"); ?>"></script>