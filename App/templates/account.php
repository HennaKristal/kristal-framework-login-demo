<?php include "partials/navigation.php"; ?>

<div class="account-page container">

    <!-- Feedback -->
    <?php if (!empty($feedback_message)): ?>
        <div class="feedback <?php echo $feedback_status; ?>">
            <?php echo $feedback_message; ?>
        </div>
    <?php endif; ?>


    <!-- Header -->
    <section class="settings-section">
        <h1 class="section-title">Account Settings</h1>
        <p class="section-subtitle">Manage your profile, security, and personalization options.</p>
    </section>


    <!-- Profile Section -->
    <section class="settings-section">

        <h2 class="settings-heading">Profile</h2>
        <p class="settings-description">Update your username and avatar.</p>

        <div class="card form-card settings-card">
            <div class="card-body">
                <form method="post" autocomplete="off" spellcheck="false" enctype="multipart/form-data">>

                    <?php CSRF::create("update_profile", "update_profile"); ?>

                    <div class="form-group mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" value="<?php echo $user->username; ?>" required minlength="3">
                    </div>

                    <div class="feedback" id="username-feedback"></div>

                    <div class="form-group mb-3 avatar-row">
                        <label class="form-label">Avatar</label>

                        <div class="d-flex align-items-center">
                            <input type="file" class="form-control" name="avatar" id="avatar" accept="image/*">

                            <?php if (!empty(Session::get("account_avatar"))): ?>
                                <button type="button" class="btn btn-danger" id="clear-avatar-button">Clear Avatar</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" id="update-profile-button" class="btn btn-primary">Save Changes</button>
                </form>

                <form id="clear-avatar-form" method="post" class="ms-2" autocomplete="off">
                    <?php CSRF::create("clear_avatar", "clear_avatar"); ?>
                </form>
            </div>
        </div>
    </section>


    <!-- Security Section -->
    <section class="settings-section">

        <h2 class="settings-heading">Security</h2>
        <p class="settings-description">Strengthen your account protection.</p>

        <!-- Two-Factor Authentication -->
        <div class="card form-card settings-card mb-4">
            <div class="card-body">

                <h3 class="settings-subheading">Two-Factor Authentication</h3>

                <?php if ($user->two_factor_enabled): ?>

                    <p>Two-factor authentication is currently enabled.</p>

                    <form method="post" autocomplete="off" spellcheck="false">
                        <?php CSRF::create("disable_two_factor", "disable_two_factor"); ?>
                        <button type="submit" class="btn btn-danger">Disable 2FA</button>
                    </form>

                <?php else: ?>

                    <p>Add extra protection to your account with two-factor authentication.</p>

                    <form method="post" autocomplete="off" spellcheck="false">
                         <?php CSRF::create("enable_two_factor", "enable_two_factor"); ?>
                        <button type="submit" class="btn btn-primary">Enable 2FA</button>
                    </form>

                <?php endif; ?>

            </div>
        </div>


        <!-- Password Change -->
        <div class="card form-card settings-card">
            <div class="card-body">

                <h3 class="settings-subheading">Change Password</h3>

                <form method="post" autocomplete="off" spellcheck="false">
                    <?php CSRF::create("change_password", "change_password"); ?>

                    <div class="form-group mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current-password" id="current-password" required minlength="8">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new-password" id="new-password" required minlength="8">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm-password" id="confirm-password" required minlength="8">
                    </div>

                    <div class="feedback" id="password-feedback"></div>

                    <button id="change-password-button" type="submit" class="btn btn-primary">Update Password</button>
                </form>

            </div>
        </div>

    </section>


    <!-- Theme Section -->
    <section class="settings-section">

        <h2 class="settings-heading">Theme</h2>
        <p class="settings-description">Choose the appearance that suits you best.</p>

        <div class="row">

            <div class="col-md-3">
                <form method="post" autocomplete="off" spellcheck="false">

                    <?php CSRF::create("change_theme_dark", "change_theme"); ?>

                    <input type="hidden" name="theme-name" id="theme-dark" value="dark">

                    <div class="card form-card settings-card theme-card">
                        <div class="card-body text-center">
                            <p class="theme-name">Dark</p>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-3">
                <form method="post" autocomplete="off" spellcheck="false">

                    <?php CSRF::create("change_theme_light", "change_theme"); ?>

                    <input type="hidden" name="theme-name" id="theme-light" value="light">

                    <div class="card form-card settings-card theme-card">
                        <div class="card-body text-center">
                            <p class="theme-name">Light</p>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>


    <!-- Danger Zone -->
    <section class="settings-section danger-zone">

        <div class="card form-card settings-card danger-card">
            <div class="card-body">

                <h2 class="settings-subheading text-danger">Delete Account</h2>
                <p class="text-danger">Deleting your account will permanently remove all data associated with it. This action cannot be undone.</p>

                <form id="delete-account-form" method="post" autocomplete="off" spellcheck="false">
                    <?php CSRF::create("delete_account", "delete_account"); ?>

                    <div class="form-group mb-3">
                        <label class="form-label text-danger" for="delete-account-password">Confirm with password</label>
                        <input type="password" class="form-control" name="delete-account-password" id="delete-account-password" required minlength="8">
                    </div>

                    <br>

                    <button type="submit" class="btn btn-danger" id="delete-account-button" disabled>Delete My Account</button>
                </form>

            </div>
        </div>

    </section>



</div>

<script src="<?php echo js("account.js"); ?>"></script>
