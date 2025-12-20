<?php include "partials/navigation.php"; ?>

<div class="login-page container">

    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8">
            <div class="card shadow-sm form-card">
                <div class="card-body">

                    <h1 class="login-title">Sign In</h1>
                    <p class="login-subtitle">And access your account.</p>

                    <form method="post" autocomplete="off" spellcheck="false">
                        <?php CSRF::create("login", "login"); ?>

                        <div class="form-group mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="login-email" name="login-email" value="<?php echo isset($_POST["login-email"]) ? $_POST["login-email"] : ""; ?>" required maxlength="<?php echo EMAIL_MAX_LENGTH; ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="login-password" name="login-password" required>
                            <div class="text-end mt-1">
                                <a href="<?php echo route("forgot-password"); ?>">Forgot password?</a>
                            </div>
                        </div>

                        <div class="form-check mb-3 login-remember-email-container">
                            <input type="checkbox" class="form-check-input" id="login-remember-email" name="login-remember-email">
                            <label class="form-check-label" for="login-remember-email">Remember email</label>
                        </div>

                        <?php if (!empty($feedback_message)) : ?>
                            <div class="feedback <?php echo $feedback_status; ?>">
                                <?php echo $feedback_message; ?>
                            </div>
                        <?php endif;?>
                 
                        <button type="submit" class="btn btn-primary w-100" id="login-button">Login</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo js("login.js"); ?>"></script>
