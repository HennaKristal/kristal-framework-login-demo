<?php declare(strict_types=1); 
namespace Backend\Controllers;
defined("ACCESS") or exit("Access Denied");

class LanguageController
{
    public function changeLanguage(string $language): void
    {
        if (in_array($language, AVAILABLE_LANGUAGES) && $language != getAppLocale())
        {
            setAppLocale($language);
            redirect(route(""));
        }

        refreshPage();
    }
}
