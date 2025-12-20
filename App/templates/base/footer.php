        </main>

        <footer class="site-footer">
            <div class="container footer-container">

                <div class="footer-brand">
                    <h3 class="footer-title">Kristal Framework Login Demo</h3>
                    <p class="footer-tagline">Fast and secure way to authenticate your users.</p>
                </div>

                <div class="footer-links">
            
                    <div class="footer-column">
                        <h4 class="footer-column-title">Get Started</h4>
                        <a href="<?php echo route('create-account'); ?>">Create Account</a>
                        <a href="<?php echo route('login'); ?>">Sign In</a>
                    </div>

                    <div class="footer-column">
                        <h4 class="footer-column-title">Troubleshoot</h4>
                        <a href="<?php echo route('forgot-password'); ?>">Reset Password</a>
                    </div>

                </div>

            </div>

            <div class="footer-bottom container text-left">
                <p>© <?php echo date("Y"); ?> Kristal Framework. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>