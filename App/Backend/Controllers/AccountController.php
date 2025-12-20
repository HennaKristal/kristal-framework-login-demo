<?php declare(strict_types=1); 
namespace Backend\Controllers;
defined("ACCESS") or exit("Access Denied");

use Backend\Core\Session;
use Backend\Database\Users;
use Backend\Controllers\PasswordHelper;

class AccountController
{
    public function getCurrentUser(): object
    {
        $usersDatabase = Users::getInstance();
        return $usersDatabase->getPublicData(Session::get("account_email"));
    }

    public function changeTheme(string $themeName = ""): void
    {
        $themeName = sanitize_file($themeName);

        // Make sure theme name is not empty
        if ($themeName === "") {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Aborted action to change theme, because no name was specified.");
            return;
        }

        // Get Theme file name
        $fileName = pathinfo($themeName, PATHINFO_FILENAME) . ".css";

        // Check does the theme exist
        if (!file_exists("App/media/css/" . $fileName)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Tried to activate theme called " . $themeName . ", but theme did not exist.");
            return;
        }

        // Save new theme to session
        Session::add("theme", $themeName);
        Session::add("feedback_status", "success");
        Session::add("feedback_message", "Successfully changed theme to " . $themeName);

        $usersDatabase = Users::getInstance();
        $usersDatabase->updateTheme(Session::get("account_id"), $themeName);
    }


    public function updateProfile(string $newUsername, ?string $newAvatarName = null, ?string $newAvatarTempName = null, ?int $newAvatarStatus = null): void
    {
        $newUsername = sanitize_file($newUsername);

        // Username min length
        if (strlen($newUsername) < USERNAME_MIN_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Username must be at least three characters long.");
            return;
        }

        // Username max length
        if (strlen($newUsername) > USERNAME_MAX_LENGTH) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Username has to be shorter than 50 characters.");
            return;
        }

        // Username format
        if (!preg_match('/^[A-Za-z0-9_-]+$/', $newUsername)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Username may only contain letters, numbers, underscores, and hyphens.");
            return;
        }

        // Save avatar
        $avatarFileName = null;
        if ($newAvatarStatus === UPLOAD_ERR_OK && !empty($newAvatarName) && !empty($newAvatarTempName)) {

            // Check is extension allowed
            $extension = strtolower(pathinfo($newAvatarName, PATHINFO_EXTENSION));
            if (!in_array($extension, AVATAR_ALLOWED_EXTENSIONS)) {
                Session::add("feedback_status", "failed");
                Session::add("feedback_message", "Image extension not supported, please try another image.");
                return;
            }
        
            // Generate a safe random name for privacy
            $avatarFileName = bin2hex(random_bytes(16)) . Session::get("account_id") . "." . $extension;

            // Move file
            if (!move_uploaded_file($newAvatarTempName, "App/media/images/avatars/" . $avatarFileName)) {
                Session::add("feedback_status", "failed");
                Session::add("feedback_message", "Could not save avatar image.");
                return;
            }

            // Delete previous avatar
            $previousAvatar = Session::get("account_avatar");
            if (!empty($previousAvatar)) {
                $oldAvatarPath = imagePath("avatars/" . $previousAvatar);
            
                if (is_file($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }
            
            Session::add("account_avatar", $avatarFileName);
        }

        $usersDatabase = Users::getInstance();
        $usersDatabase->updateProfile(Session::get("account_id"), $newUsername, $avatarFileName);

        Session::add("account_username", $newUsername);
        Session::add("feedback_status", "success");
        Session::add("feedback_message", "Your profile has been updated.");
    }


    public function clearAvatar(): void
    {
        $usersDatabase = Users::getInstance();
        $currentAvatar = Session::get("account_avatar");
        
        if (empty($currentAvatar)) {

            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "No avatar to remove.");
            return;
        }

        // Delete avatar file from disk
        $oldPath = imagePath("avatars/" . $currentAvatar);
        if (is_file($oldPath)) {
            unlink($oldPath);
        }

        // Remove avatar from database
        $usersDatabase->updateProfile(Session::get("account_id"), null, "");

        Session::add("account_avatar", "");
        Session::add("feedback_status", "success");
        Session::add("feedback_message", "Avatar removed successfully.");
    }


    public function changePassword(string $oldPassword, string $newPassword, string $confirmPassword): void
    {
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

        $usersDatabase = Users::getInstance();
        $oldPasswordHash = $usersDatabase->getPasswordHash(Session::get("account_id"));

        // Make sure old password was corrrect
        if ($oldPasswordHash === null) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Could not verify your account.");
            return;
        }
        else if (!password_verify($oldPassword, $oldPasswordHash)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Current password incorrect.");
            return;
        }

        // Save new password
        $newpasswordHash = password_hash($newPassword, PASSWORD_ARGON2ID);
        $usersDatabase->updatePasswordHash(Session::get("account_id"), $newpasswordHash);

        Session::add("feedback_status", "success");
        Session::add("feedback_message", "Your password has been successfully changed.");
    }


    public function enableTwoFactor(): void
    {
        $usersDatabase = Users::getInstance();
        $usersDatabase->enableTwoFactor(Session::get("account_id"));

        Session::add("feedback_status", "success");
        Session::add("feedback_message", "Two factor authentication has been turned on.");
    }


    public function disableTwoFactor(): void
    {
        $usersDatabase = Users::getInstance();
        $usersDatabase->disableTwoFactor(Session::get("account_id"));

        Session::add("feedback_status", "success");
        Session::add("feedback_message", "Two factor authentication has been turned off.");
    }


    public function deleteAccount(string $password): void
    {
        // make sure password was given
        if (empty($password)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "You must enter your password to continue.");
            return;
        }
    
        // Get stored password hash
        $usersDatabase = Users::getInstance();
        $passwordHash = $usersDatabase->getPasswordHash(Session::get("account_id"));
    
        if ($passwordHash === null) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Unable to verify your account credentials.");
            return;
        }
    
        // Verify password
        if (!password_verify($password, $passwordHash)) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Couldn't delete the account because given password was not correct.");
            return;
        }

        // Try to delete the user
        if (!$usersDatabase->deleteUser(Session::get("account_id"), Session::get("account_email"))) {
            Session::add("feedback_status", "failed");
            Session::add("feedback_message", "Something went wrong, couldn't delete the account, please try again later or contact support.");
            return;
        }

        Session::end();
        redirect(route(""));
    }
}
