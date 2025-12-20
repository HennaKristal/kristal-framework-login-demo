<nav class="navbar navbar-expand-lg border-bottom <?php echo $page === 'frontpage.php' ? 'transparent-at-top' : '' ?>" data-page="<?php echo $page ?>">
    
    <div class="container">

        <!-- Navbar Title (text) -->
        <!-- <a class="navbar-brand" href="<?php echo route(""); ?>">Kristal Framework Login Demo</a> -->

        <!-- Navbar Title (image) -->
        <a class="navbar-brand" href="<?php echo route(""); ?>"><img class="navbar-logo colorized" src="<?php echo webp("kristal_framework_alt_icon.png"); ?>" alt="Kristal login demo logo"/></a> 

        <!-- Navbar mobile toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation" data-bs-theme="dark">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar-menu">

            <!-- Navbar links -->
            <ul class="navbar-nav me-auto">
                <a class="navbar-brand" href="<?php echo route(""); ?>">Kristal Framework Login Demo</a>
            </ul>

            <ul class="account-menu navbar-nav justify-content-end">
                <li class="nav-item">
                    <?php if (Session::get("logged_in")): ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle account-dropdown-toggle d-flex align-items-center gap-2"  href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <!-- Username -->
                                <span class="navbar-username"><?php echo esc_html($user->username ?? "Account"); ?></span>
                                <!-- Profile picture -->
                                <?php if (Session::get("account_avatar") !== ""): ?>
                                    <img src="<?php echo image("avatars/" . Session::get("account_avatar")); ?>" alt="Avatar" class="navbar-avatar">
                                <?php endif; ?>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end account-dropdown shadow" aria-labelledby="accountDropdown">
                                <li><a class="dropdown-item" href="<?php echo route("account"); ?>">Account Settings</a></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo route("logout"); ?>">Sign Out</a></li>
                            </ul>
                        </li>
                        
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" page="login.php" href="<?php echo route("login"); ?>">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" page="signup.php" href="<?php echo route("create-account"); ?>">Create Account</a>
                        </li>
                    <?php endif; ?>
                </li>
            </ul>

        </div>
    </div>
</nav>

<!-- Activate correct navigation link -->
<!-- (add page attribute to your navigation links with page file name as it's value, for example page="home.php") -->
<script>$("[page='<?php echo $page ?>']").addClass("active");</script>
