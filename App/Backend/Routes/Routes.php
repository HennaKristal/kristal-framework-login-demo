<?php declare(strict_types=1); 
namespace Backend\Routes;
defined("ACCESS") or exit("Access Denied");

use Backend\Core\Router;
use Backend\Core\Session;
use Backend\Controllers\RegistrationController;
use Backend\Controllers\LoginController;
use Backend\Controllers\AccountController;

class Routes extends Router
{
    public function __construct()
    {
        parent::setHomepageHandler("homepageHandler");
        parent::setDefaultHandler("homepageHandler");

        // Set routes
        if (Session::get("logged_in"))
        {
            parent::addRoute("account", "accountHandler");
            parent::addRoute("logout", "logoutHandler");
        }
        else
        {
            parent::addRoute("create-account", "createAccountHandler");
            parent::addRoute("activate-account", "accountActivationHandler");
            parent::addRoute("login", "loginHandler");
            parent::addRoute("two-factor-authentication", "tfaHandler");
            parent::addRoute("forgot-password", "forgotPasswordHandler");
            parent::addRoute("reset-password", "passwordResetHandler");
        }
        
        // Let router handle the routes
        parent::handleRoutes();
        
        Session::remove("feedback_status");
        Session::remove("feedback_message");
    }


    // ============ Content pages ============
    public function homepageHandler(): void
    {
        $this->render("frontpage");
    }

    public function accountHandler(): void
    {
        $accountController = new AccountController();
        $userData = $accountController->getCurrentUser();
        
        $this->render("account", [
            "user" => $userData,
            "feedback_status" => Session::get("feedback_status"),
            "feedback_message" => Session::get("feedback_message"),
        ]);
    }


    // ============ Login ============
    public function loginHandler(): void
    {
        $this->render("login", [
            "feedback_status" => Session::get("feedback_status"),
            "feedback_message" => Session::get("feedback_message"),
        ]);
    }

    public function tfaHandler(string $tfa_code = ""): void
    {
        $this->render("two-factor-authentication", [
            "tfa_code" => $tfa_code,
            "feedback_status" => Session::get("feedback_status"),
            "feedback_message" => Session::get("feedback_message"),
        ]);
    }

    public function forgotPasswordHandler(): void
    {
        $this->render("forgot-password", [
            "feedback_status" => Session::get("feedback_status"),
            "feedback_message" => Session::get("feedback_message"),
        ]);
    }

    public function passwordResetHandler(string $email = "", string $token = ""): void
    {
        $loginController = new LoginController();
        $loginController->checkPasswordResetValues($email, $token);
    
        $this->render("reset-password", [
            "email" => $email,
            "token" => $token,
            "feedback_status" => Session::get("feedback_status"),
            "feedback_message" => Session::get("feedback_message"),
        ]);
    }


    // ============ Create Account ============
    public function createAccountHandler(): void
    {
        $this->render("create-account", [
            "feedback_status" => Session::get("feedback_status"),
            "feedback_message" => Session::get("feedback_message"),
        ]);
    }

    public function accountActivationHandler(string $email = "", string $token = ""): void
    {
        if (!empty($email) && !empty($token)) {
            $registrationController = new RegistrationController();
            $registrationController->activateAccount($email, $token);
        }
        else if (!empty($email)) {
            $registrationController = new RegistrationController();
            $registrationController->sendActivationEmail($email);
        }

        $this->render("activate-account", [
            "email" => $email,
            "feedback_status" => Session::get("feedback_status"),
            "feedback_message" => Session::get("feedback_message"),
        ]);
    }


    // ============ Logout ============
    public function logoutHandler(): void
    {
        Session::end();
        redirect(route("login"));
    }
}
