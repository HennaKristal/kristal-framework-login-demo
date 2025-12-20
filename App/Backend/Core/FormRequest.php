<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

use \ReflectionMethod;

class FormRequest
{
    public function __construct(array $parameters = array("allow_protected_calls" => false))
    {
        $csrfIdentifier = $_POST["csrf_identifier"] ?? "";
        $csrfToken = $_POST["csrf_token"] ?? "";
        $csrfData = Session::get("csrf_" . $csrfIdentifier);

        if (REGENERATE_CSRF_ON_PAGE_REFRESH)
            CSRF::reset();

        if (!$csrfData)
            return;

        $requestedMethod = $csrfData["formRequest"];
        $expectedToken = $csrfData["token"];

        // Only handle real form submissions
        if ($_SERVER["REQUEST_METHOD"] !== "POST")
            return;

        // Verify required CSRF fields
        if (empty($requestedMethod) || empty($expectedToken) || empty($csrfToken) || empty($csrfIdentifier))
            return;

        // Make sure CSRF token matches
        if ($csrfToken !== $expectedToken)
            return;

        // Make sure requested method exists
        if (!method_exists($this, $requestedMethod))
            return;

        $allowProtectedCalls = $parameters["allow_protected_calls"] === true;
        $method = new ReflectionMethod($this, $requestedMethod);
        $isPublic = $method->isPublic();
        $isProtectedAndAllowed = $method->isProtected() && $allowProtectedCalls;

        if ($isPublic || $isProtectedAndAllowed)
        {
            $requestData = $_POST + $_FILES;
            $this->{$requestedMethod}($requestData);
        }
    }
}
