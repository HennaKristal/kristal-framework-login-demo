<?php declare(strict_types=1); 
namespace Backend\Routes;
defined("ACCESS") or exit("Access Denied");

use Backend\Core\FormRequest;
use Backend\Core\Session;
use Backend\Controllers\ThemeController;
use Backend\Controllers\LanguageController;
use Backend\Controllers\AccountController;
use Backend\Controllers\LoginController;
use Backend\Controllers\RegistrationController;

class FormRequests extends FormRequest
{
    public function __construct()
    {
        $allowProtected = Session::get("logged_in") === true;

        parent::__construct([
            "allow_protected_calls" => $allowProtected
        ]);
    }

    // Form Request for changing language
    // public function change_language(array $request): void
    // {
    //     $language_controller = new LanguageController();
    //     $language_controller->changeLanguage($request["language"]);
    // }

    // ================ Account ================
    protected function change_theme(array $request): void
    {
        $accountController = new AccountController();
        $accountController->changeTheme(
            $request["theme-name"]
        );
    }

    protected function update_profile(array $request): void
    {
        $avatar = isset($request["avatar"]) ? $request["avatar"] : null;
    
        $newAvatarName = $avatar["name"] ?? null;
        $newAvatarTempName = $avatar["tmp_name"] ?? null;
        $newAvatarStatus = $avatar["error"] ?? null;
    
        $accountController = new AccountController();
        $accountController->updateProfile(
            $request["username"],
            $newAvatarName,
            $newAvatarTempName,
            $newAvatarStatus
        );
    }

    protected function clear_avatar(array $request): void
    {
        $accountController = new AccountController();
        $accountController->clearAvatar();
    }

    protected function change_password(array $request): void
    {
        $accountController = new AccountController();
        $accountController->changePassword(
            $request["current-password"],
            $request["new-password"],
            $request["confirm-password"]);
    }

    protected function enable_two_factor(array $request): void
    {
        $accountController = new AccountController();
        $accountController->enableTwoFactor();
    }

    protected function disable_two_factor(array $request): void
    {
        $accountController = new AccountController();
        $accountController->disableTwoFactor();
    }

    protected function delete_account(array $request): void
    {
        $accountController = new AccountController();
        $accountController->deleteAccount(
            $request["delete-account-password"]
        );
    }
    

    // ================ Registration ================
    public function registration(array $request): void
    {
        $registration = new RegistrationController();
        $registration->createUser(
            $request["registration-username"],
            $request["registration-email"],
            $request["registration-password"],
            $request["registration-confirmation-password"]
        );
    }


    // ================ Login ================
    public function login(array $request): void
    {
        $loginController = new LoginController();
        $loginController->login(
            $request["login-email"],
            $request["login-password"]
        );
    }

    public function forgot_password(array $request): void
    {
        $loginController = new LoginController();
        $loginController->forgotPassword(
            $request["forgot-password-email"]
        );
    }

    public function password_reset(array $request): void
    {
        $loginController = new LoginController();
        $loginController->resetPassword(
            $request["email"],
            $request["token"],
            $request["new-password"],
            $request["confirm-password"],
        );
    }

    public function two_factor_authentication(array $request): void
    {
        $loginController = new LoginController();
        $loginController->loginTFA(
            $request["tfa-code"]
        );
    }

    public function resend_two_factor_authentication(array $request): void
    {
        $loginController = new LoginController();
        $loginController->resendTFA();
    }
}
