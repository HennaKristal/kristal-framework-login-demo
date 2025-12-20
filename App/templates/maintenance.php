<!doctype html>
<html lang="<?php echo Session::has('language') ? Session::get('language') : DEFAULT_LANGUAGE; ?>">
    <head>

        <!-- Metadata -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="generator" content="Kristal Framework" />
        <meta name="robots" content="noindex, nofollow">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="<?php echo css("maintenance.css"); ?>">
        <style>body { background-image: url("<?php echo webp('backgrounds/maintenance.jpg'); ?>"); }</style>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="<?php echo js("core.js"); ?>"></script>
        <script src="<?php echo js("maintenance.js"); ?>"></script>

        <!-- Page title -->
        <title><?php echo translate("Maintenance"); ?></title>

        <!-- Website icon -->
        <link rel="icon" type="image/gif" href="<?php echo webp("kristal_framework_alt_icon.png"); ?>" />
    </head>

    <body>
        
        <!-- language settings -->
        <div id="language-selection">
            <?php Block::render("language_menu", ["request" => "change_language"]);  ?>
        </div>

        <!-- Title -->
        <div class="container maintenance-title">
            <h1 class="maintenance-title"><?php echo translate("Maintenance"); ?></h1>
        </div>

        <!-- Description -->
        <div class="container maintenance-text">
            <p><?php echo translate("We are currently maintaining our website, we will be back shortly. Thanks for your patience."); ?></p>
        </div>

        <!-- Authentication -->
        <?php if (ENABLE_MAINTENANCE_LOGIN): ?>
            <div class="container authentication-container">
                <h2><?php echo translate("Authentication"); ?></h2>
                <form method="post">
                    
                    <div class="mb-3">
                        <input type="password" class="form-control" name="maintenance-password" id="maintenance-password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn btn-primary"><?php echo translate("Sign In"); ?></button>

                    <?php if ($authenticationAttemptLimitReached) : ?>
                        <p id="feedback" style="color: red;">
                            <span><?php echo translate("Too many login attempts, please wait %s before you are allowed to try again.", $authenticationLockoutLabel); ?></span>
                        </p>
                    <?php elseif ($authenticationFailed) : ?>
                        <p id="feedback" style="color: red;"><?php echo translate("Failed to authenticate."); ?></p>
                    <?php else: ?>
                        <p id="feedback" style="padding: 12px;"></p>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>
        
        <!-- Social links -->
        <div class="social-icons">
            <!-- Email -->
            <a class="social-menu-link" href="mailto:example@example.com" target="_blank" data-bs-toggle="tooltip" data-bs-title="<?php echo translate("Email"); ?>">
                <img class="social-menu-image" src="<?php echo webp("icons/email.png") ?>">
            </a>

            <!-- Twitter -->
            <a class="social-menu-link" href="https://twitter.com" target="_blank" data-bs-toggle="tooltip" data-bs-title="Twitter">
                <img class="social-menu-image" src="<?php echo webp("icons/twitter.png") ?>">
            </a>

            <!-- Facebook -->
            <a class="social-menu-link" href="https://facebook.com" target="_blank" data-bs-toggle="tooltip" data-bs-title="Facebook">
                <img class="social-menu-image" src="<?php echo webp("icons/facebook.png") ?>">
            </a>

            <!-- Youtube -->
            <a class="social-menu-link" href="https://youtube.com" target="_blank" data-bs-toggle="tooltip" data-bs-title="Youtube">
                <img class="social-menu-image" src="<?php echo webp("icons/youtube.png") ?>">
            </a>
        </div>

    </body>
</html>
