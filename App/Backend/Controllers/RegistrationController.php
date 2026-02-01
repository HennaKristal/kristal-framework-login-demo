<?php declare(strict_types=1); 
namespace Backend\Controllers;
defined("ACCESS") or exit("Access Denied");

use Backend\Database\Users;
use Backend\Core\Session;
use Backend\Core\Mailer;

class RegistrationController
{
    public function createUser(string $username, string $email, string $password, string $confirmPassword): void
    {
        $username = sanitize_file($username);
        $email = strtolower(trim($email));

        // Username min length
        if (strlen($username) < USERNAME_MIN_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Username must be at least three characters long.");
            return;
        }

        // Username max length
        if (strlen($username) > USERNAME_MAX_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Username has to be shorter than 50 characters.");
            return;
        }

        // Username format
        if (!preg_match('/^[A-Za-z0-9_-]+$/', $username)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Username may only contain letters, numbers, underscores, and hyphens.");
            return;
        }

        // Email length
        if (strlen($email) > EMAIL_MAX_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Email address is too long.");
            return;
        }

        // Email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Please enter a valid email address.");
            return;
        }

        // Password and confirm password matching
        if ($password !== $confirmPassword) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Password did not match with confirmation password.");
            return;
        }

        // Password validation
        $passwordValidation = PasswordHelper::validate($password);
        if ($passwordValidation !== true) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", $passwordValidation);
            return;
        }

        $usersDatabase = Users::getInstance();

        // Does user exist
        if ($usersDatabase->doesEmailExist($email)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "This email is already registered. Please sign in instead.");
            return;
        }

        // Create user
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
        $encryptionSalt = bin2hex(random_bytes(32));
        $newUser = $usersDatabase->createUser($username, $email, $passwordHash, $encryptionSalt);

        if (!$newUser) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Unable to create account. Please try again.");
            return;
        }

        redirect(route("activate-account/$email"));
    }


    /* --------------------------- Account activation --------------------------- */
    public function activateAccount(string $email, string $token): void
    {
        $email = strtolower(trim($email));

        // Check valid email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > EMAIL_MAX_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Invalid email address.");
            return;
        }

        $usersDatabase = Users::getInstance();
        $verificationData = $usersDatabase->getVerificationData($email);
    
        // No user found with email
        if (empty(get_object_vars($verificationData))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Invalid activation token.");
            return;
        }
    
        // User already activated
        if ($verificationData->email_verified == 1) {
            Session::add("feedback_status", "success");
            Session::add("feedback_message", "Your account is already activated.");
            return;
        }

        // Tokens do not match
        if (!hash_equals($verificationData->verification_token, hash("sha256", $token))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Invalid activation token.");
            return;
        }
    
        // Try activate user's account
        if (!$usersDatabase->activateUser($email)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Could not activate account. Please try again later.");
            return;
        }
    
        Session::add("feedback_status", "success");
        Session::add("feedback_message", "Your account has been successfully activated! You may now sign in.");
    }


    public function sendActivationEmail(string $receiverEmail): void
    {
        $receiverEmail = strtolower(trim($receiverEmail));

        // Check valid email
        if (!filter_var($receiverEmail, FILTER_VALIDATE_EMAIL) || strlen($receiverEmail) > EMAIL_MAX_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Please enter a valid email.");
            return;
        }

        $usersDatabase = Users::getInstance();
        $verificationData = $usersDatabase->getVerificationData($receiverEmail);

        // Check does user exist
        if (empty(get_object_vars($verificationData))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "This email is not registered.");
            return;
        }

        // Check do they already have an active account
        if ($verificationData->email_verified == 1) {
            Session::add("feedback_status", "success");
            Session::add("feedback_message", "Your account is already activated.");
            return;
        }

        // Limit how often emails can be sent
        $cooldown = ActionRateLimiter::GetActionCooldown();
        if ($cooldown > 0) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Please wait {$cooldown} seconds before trying again.");
            return;
        }
        
        // Generate a new verification token
        $tokenPlain = bin2hex(random_bytes(32));
        $tokenHash = hash("sha256", $tokenPlain);
        $usersDatabase->updateVerificationToken($receiverEmail, $tokenHash);

        // Send email
        $mailer = new Mailer();
        $mailer->send(
            $receiverEmail,
            "Activate Your Account",
            "activate-account.php",
            [
                "url" => URL_BASE,
                "username" => $verificationData->username,
                "email" => $receiverEmail,
                "token" => $tokenPlain
            ]
        );

        Session::add("feedback_status", "info");
        Session::add("feedback_message", "A new activation link has been sent to your email.");
    }
}
