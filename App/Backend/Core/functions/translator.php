<?php defined("ACCESS") or exit("Access Denied");

// Set language for translator
function setAppLocale(string $language): void
{
    if (in_array($language, AVAILABLE_LANGUAGES, true) && $language != getAppLocale())
    {
        Session::add("language", $language);
    }
}

// Get translator's language
function getAppLocale(): string
{
    return Session::has("language") ? Session::get("language") : DEFAULT_LANGUAGE;
}

// Translate
function translate(string $key, array $variables = []): string
{
    // Make sure $variables is an array
    if (!is_array($variables))
    {
        $variables = [$variables];
    }

    // Get translations
    static $translations = null;

    if ($translations === null)
    {
        $path = PATH_TRANSLATIONS . 'translations.php';
    
        if (!file_exists($path))
        {
            if (PRODUCTION_MODE)
            {
                debuglog("Translation for text '$key' failed because text was not found in App/media/translations/translations.php file");
                return vsprintf($key, $variables);
            }
            else
            {
                kristal_fatalExit("Translation for text '$key' failed because text was not found in App/media/translations/translations.php file");
            }
        }

        $translations = include $path;

        // Make sure translations are in an array
        if (!is_array($translations))
        {
            $translations = [];
        }
    }

    // Get translation language
    $language = getAppLocale();
    
    // Return original string if no translation was found
    if (!array_key_exists($key, $translations))
    {
        debuglog("Translation for text '$key' failed because text was not found in App/media/translations/translations.php file");
        return vsprintf($key, $variables);
    }

    // return translated string if key and language were found
    if (isset($translations[$key][$language]))
    {
        return vsprintf($translations[$key][$language], $variables);
    }

    // Return original string if no translation was found
    debuglog("Translation for text '$key' failed because it did not have translation for language '$language'");
    return vsprintf($key, $variables);
}
