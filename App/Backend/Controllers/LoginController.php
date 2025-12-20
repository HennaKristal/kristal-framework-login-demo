<?php declare(strict_types=1); 
namespace Backend\Controllers;
defined("ACCESS") or exit("Access Denied");

use Backend\Database\Users;
use Backend\Core\Session;
use Backend\Core\Mailer;

class LoginController
{
    public function login(string $email, string $password): void
    {
        $email = strtolower(trim($email));

        // Check valid email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > EMAIL_MAX_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Invalid email address.");
            return;
        }

        $usersDatabase = Users::getInstance();
        $userData = $usersDatabase->getPublicData($email);

        // Check does account exist
        if (empty(get_object_vars($userData))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Incorrect email or password.");
            return;
        }

        // Check password
        $passwordHash = $usersDatabase->getPasswordHash($userData->id);
        if (!password_verify($password, $passwordHash)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Incorrect email or password.");
            return;
        }

        // Check account status
        if ($userData->account_status !== "active") {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "This account is not available, please try later.");
            return;
        }
        
        // Check email verification
        if ($userData->email_verified == 0) {
            redirect("/activate-account/$email");
            return;
        }

        // Handle two factor authentication initialization
        if ($userData->two_factor_enabled) {
            Session::add("tfa_user_email", $email);
            Session::add("tfa_required", true);

            $this->sendTFAEmail($email);

            redirect(route("two-factor-authentication"));
            return;
        }

        // Success
        $this->finishLogin($email);
    }


    public function loginTFA(string $requested_tfa_code): void
    {
        $requested_tfa_code = trim($requested_tfa_code);
        $requested_tfa_email = Session::get("tfa_user_email");

        if (empty($requested_tfa_code) || empty($requested_tfa_email)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Invalid verification attempt.");
            return;
        }

        // Limit the rate user can attempt two factor authentication
        $cooldown = ActionRateLimiter::GetActionCooldown();
        if ($cooldown > 0) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Please wait {$cooldown} seconds before trying again.");
            return;
        }
    
        $usersDatabase = Users::getInstance();
        $tfa_data = $usersDatabase->getTFAData($requested_tfa_email);

        // Check is the TFA values in database
        if (empty(get_object_vars($tfa_data))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "No active verification code found.");
            return;
        }
        else if (empty($tfa_data->tfa_code) || empty($tfa_data->tfa_expires)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "No active verification code found.");
            return;
        }
    
        // Check has TFA code expired
        if (time() > strtotime($tfa_data->tfa_expires)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Your verification code has expired.");
            return;
        }

        // Check does requested TFA code match the one saved to database
        if (!hash_equals($tfa_data->tfa_code, hash("sha256", $requested_tfa_code))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Incorrect verification code.");
            return;
        }
    
        // Success
        $usersDatabase->clearTFACode($requested_tfa_email);
        Session::remove("tfa_user_email");
        Session::remove("tfa_required");
        $this->finishLogin($requested_tfa_email);
    }


    private function finishLogin(string $email): void
    {
        $usersDatabase = Users::getInstance();
        $userData = $usersDatabase->getPublicData($email);
        $usersDatabase->updateLastLogin($userData->id);

        Session::restart();
   
        // Create session
        Session::add("logged_in", true);
        Session::add("account_id", $userData->id);
        Session::add("account_email", $userData->email);
        Session::add("account_username", $userData->username);
        Session::add("account_avatar", $userData->avatar);
        Session::add("theme", $userData->theme);

        redirect(route("account"));
    }


    public function resendTFA(): void
    {
        // Check do we have active TFA in session
        if (!Session::has("tfa_user_email")) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "No active TFA session.");
            redirect(route("login"));
            return;
        }

        // Limit the rate user can request email for TFA token
        $cooldown = ActionRateLimiter::GetActionCooldown();
        if ($cooldown > 0) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Please wait {$cooldown} seconds before trying again.");
            return;
        }

        $this->sendTFAEmail(Session::get("tfa_user_email"));

        Session::add("feedback_status", "success");
        Session::add("feedback_message", "A new verification code has been sent.");
    }


    private function sendTFAEmail(string $email): void
    {
        $codePlain = random_int(100000, 999999);
        $codeHash = hash('sha256', (string) $codePlain);
        $expires = date("Y-m-d H:i:s", time() + TFA_EXPIRATION_TIME);
        $usersDatabase = Users::getInstance();
        $usersDatabase->setTFACode($email, $codeHash, $expires);

        $mailer = new Mailer();
        $mailer->send(
            $email,
            "Your Two-Factor Authentication Code",
            "tfa-email.php",
            [
                "url" => URL_BASE,
                "tfa_token" => $codePlain
            ]
        );
    }


    /* --------------------------- Password Reset --------------------------- */
    public function forgotPassword(string $email): void
    {
        // Limit the rate user can request password reset
        $cooldown = ActionRateLimiter::GetActionCooldown();
        if ($cooldown > 0) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Please wait {$cooldown} seconds before trying again.");
            return;
        }

        $email = strtolower(trim($email));

        // Always respond with generic message to avoid leaking which emails exist
        Session::add("feedback_status", "info");
        Session::add("feedback_message", "If this email exists, a password reset link has been sent.");

        // Make sure email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > EMAIL_MAX_LENGTH) {
            return;
        }

        $usersDatabase = Users::getInstance();
        $verificationData = $usersDatabase->getVerificationData($email);

        // Make sure account exists
        if (empty(get_object_vars($verificationData))) {
            return;
        }

        // Make sure account is activated
        if ($verificationData->email_verified == 0) {
            return;
        }

        // Generate tokens
        $tokenPlain = bin2hex(random_bytes(32));
        $tokenHash = hash("sha256", $tokenPlain);
        $expires = date("Y-m-d H:i:s", time() + 3600);
        $usersDatabase->updatePasswordResetToken($email, $tokenHash, $expires);

        // Send email
        $mailer = new Mailer();
        $mailer->send(
            $email,
            "Password reset request",
            "reset-password.php",
            [
                "url" => URL_BASE,
                "email" => $email,
                "token" => $tokenPlain
            ]
        );
    }


    public function checkPasswordResetValues(string $email, string $token): void
    {
        $email = strtolower(trim($email));

        // Make sure email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > EMAIL_MAX_LENGTH) {
            Session::add("feedback_status", "blocked");
            Session::add("feedback_message", "Invalid password reset link.");
            return;
        }

        $usersDatabase = Users::getInstance();
        $resetData = $usersDatabase->getResetPasswordData($email);

        // Make sure account exists
        if (empty(get_object_vars($resetData))) {
            Session::add("feedback_status", "blocked");
            Session::add("feedback_message", "Invalid password reset link.");
            return;
        }

        // Make sure token matches the one stored to database
        if (!hash_equals($resetData->password_reset_token, hash("sha256", $token))) {
            Session::add("feedback_status", "blocked");
            Session::add("feedback_message", "Invalid password reset link.");
            return;
        }

        // Make sure the token has not expired
        if (strtotime($resetData->password_reset_expires) < time()) {
            Session::add("feedback_status", "blocked");
            Session::add("feedback_message", "This password reset link has expired.");
            return;
        }
    }


    public function resetPassword(string $email, string $token, string $newPassword, string $confirmPassword): void
    {
        $email = strtolower(trim($email));
        $token = trim($token);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > EMAIL_MAX_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Invalid reset attempt.");
            return;
        }

        $usersDatabase = Users::getInstance();
        $resetData = $usersDatabase->getResetPasswordData($email);

        // Make sure data exists
        if (empty(get_object_vars($resetData))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Invalid reset attempt.");
            return;
        }

        // Make sure token matches the one stored to database
        if (!hash_equals($resetData->password_reset_token, hash("sha256", $token))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Invalid reset token.");
            return;
        }

        // Make sure token is not expired
        if (strtotime($resetData->password_reset_expires) < time()) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Reset token has expired.");
            return;
        }

        // Password and confirm password matching
        if ($newPassword !== $confirmPassword) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Password did not match with confirmation password.");
            return;
        }

        // Password validation
        $passwordValidation = PasswordHelper::validate($newPassword);
        if ($passwordValidation !== true) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", $passwordValidation);
            return;
        }

        // Update hash and clear token
        $newHash = password_hash($newPassword, PASSWORD_ARGON2ID);
        $usersDatabase->updatePasswordResetToken($email, null, null);
        $usersDatabase->updatePasswordHash($resetData->id, $newHash);

        Session::add("feedback_status", "success");
        Session::add("feedback_message", "Your password has been reset. You may now log in.");

        redirect(route("login"));
    }
}
