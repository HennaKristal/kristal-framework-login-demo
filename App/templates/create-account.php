<?php include "partials/navigation.php"; ?>

<div class="create-account-page container">

    <div class="row justify-content-center align-items-center">

        <!-- Left Column -->
        <div class="col-xl-6 col-lg-12 mb-5 signup-marketing-container">

            <h1 class="signup-title">Create Your Free Account</h1>
            <p class="signup-subtitle">Sign in, authenticate, and manage your session securely using a modern login framework example.</p>

            <div class="signup-features">
                <div class="signup-feature">
                    <img src="<?php echo webp("pictures/icon-encryption.webp"); ?>" class="signup-icon" alt="">
                    <span class="signup-feature-title">Highest quality security practices</span>
                </div>

                <div class="signup-feature">
                    <img src="<?php echo webp("pictures/icon-passwords.webp"); ?>" class="signup-icon" alt="">
                    <span class="signup-feature-title">Secure credential handling using modern authentication patterns.</span>
                </div>

                <div class="signup-feature">
                    <img src="<?php echo webp("pictures/icon-protected.webp"); ?>" class="signup-icon" alt="">
                    <span class="signup-feature-title">Your data is processed securely and never exposed unnecessarily.</span>
                </div>

                <div class="signup-feature">
                    <img src="<?php echo webp("pictures/icon-authentication.webp"); ?>" class="signup-icon" alt="">
                    <span class="signup-feature-title">Optional two-factor authentication flow for enhanced protection.</span>
                </div>

                <div class="signup-feature">
                    <img src="<?php echo webp("pictures/icon-cloud.webp"); ?>" class="signup-icon" alt="">
                    <span class="signup-feature-title">Automatic session handling and account lockout examples included.</span>
                </div>
                
                <div class="signup-feature">
                    <img src="<?php echo webp("pictures/icon-unlimited.webp"); ?>" class="signup-icon" alt="">
                    <span class="signup-feature-title">Secure server communication with encrypted data in transit.</span>
                </div>

                <div class="signup-feature">
                    <img src="<?php echo webp("pictures/icon-free.webp"); ?>" class="signup-icon" alt="">
                    <span class="signup-feature-title">Clean, extensible structure for adding users and roles.</span>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-xl-6 col-lg-12 d-flex signup-form-container justify-content-center">

            <div class="card shadow-sm form-card signup-form-card w-100">
                <div class="card-body">

                    <h2 class="section-title mb-4">Create an Account</h2>

                    <form method="post" autocomplete="off" spellcheck="false">
                        <?php CSRF::create("registration", "registration"); ?>

                        <div class="form-group mb-3">
                            <label class="form-label" for="registration-username">Username</label>
                            <input type="text" class="form-control" id="registration-username" name="registration-username" value="<?php echo isset($_POST["registration-username"]) ? $_POST["registration-username"] : ""; ?>" required minlength="<?php echo USERNAME_MIN_LENGTH; ?>" maxlength="<?php echo USERNAME_MAX_LENGTH; ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="registration-email">Email</label>
                            <input type="email" class="form-control" id="registration-email" name="registration-email" value="<?php echo isset($_POST["registration-email"]) ? $_POST["registration-email"] : ""; ?>" required maxlength="<?php echo EMAIL_MAX_LENGTH; ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="registration-password">Password</label>
                            <input type="password" class="form-control" id="registration-password" name="registration-password" required minlength="8">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="registration-confirmation-password">Confirm Password</label>
                            <input type="password" class="form-control" id="registration-confirmation-password" name="registration-confirmation-password" required minlength="8">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms-of-service-checkbox" name="terms-of-service-checkbox" required>
                            <label class="form-check-label" for="terms-of-service-checkbox">I accept the</label>
                            <a class="popup-link" id="terms-of-service-link" popupID="terms-of-service-popup">Terms of Service</a>
                        </div>
                        
                        <div id="registration-feedback" class="feedback <?php echo $feedback_status; ?>">
                            <?php if(!empty($feedback_message)): ?>
                                    <?php echo $feedback_message; ?>
                            <?php endif; ?>
                        </div>
 
                        <button type="submit" class="btn btn-success w-100 mb-3" id="registration-button" disabled>Create Account</button>

                        <p class="already-have-account-label text-center mb-0">Already have an account? <a href="<?php echo route("login"); ?>">Sign in</a></p>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <!-- TERMS OF SERVICE POPUP -->
    <div class="tos-popup" id="terms-of-service-popup" style="display: none;">
        <div class="tos-popup-content card shadow-sm form-card">
            <h2 class="section-title">Terms of Service</h2>

            <p class="tos-popup-text">Place your Terms of Service content here. Long text, policies, conditions, anything you want.</p>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-success" id="tos-agree-button">Agree</button>
                <button class="btn btn-danger" id="tos-disagree-button">Disagree</button>
            </div>
        </div>
    </div>

</div>


<script src="<?php echo js("create-account.js"); ?>"></script>
