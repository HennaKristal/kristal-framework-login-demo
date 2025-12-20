<?php declare(strict_types=1); 
namespace Backend\Controllers;
defined("ACCESS") or exit("Access Denied");

class PasswordHelper
{
    public static function validate(string $password): bool
    {
        $errorMessage = "";

        // Length check
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errorMessage = "Password must be at least " . PASSWORD_MIN_LENGTH . " characters long.";
            return $errorMessage;
        }

        // Vulnerable password check
        $lowerPassword = strtolower($password);
        $exposedList = self::getVulnerablePasswords();

        if (in_array($lowerPassword, $exposedList, true)) {
            $errorMessage = "This password is publicly vulnerable and cannot be used.";
            return $errorMessage;
        }

        return true;
    }

    private static function getVulnerablePasswords(): array
    {
        return array("123456789", "qwertyuiop", "1234567890", "987654321", "q1w2e3r4t5", "123123123", "qazwsxedc", "password1", "qwerty123", "asdfghjkl", "liverpool", "1q2w3e4r5t", "4815162342", "789456123", "minecraft", "147258369", "metallica", "0987654321", "qweasdzxc", "12345qwert", "123456789a", "1234554321", "Usuckballz1", "alexander", "123654789", "741852963", "1111111111", "fktrcfylh", "147852369", "spiderman", "123456789q", "123456789", "password1", "liverpool", "metallica", "1234567890", "fuck_inside", "987654321", "microsoft", "deepthroat", "qwertyuiop", "asdfghjkl", "spiderman", "basketball", "webmaster", "chocolate", "alexander", "beautiful", "swordfish", "0.0.0.000", "elizabeth", "masterbate", "penetration", "christine", "wolverine", "masterbating", "unbelievable", "intercourse", "squerting", "insertion", "temptress", "celebrity", "interacial", "streaming", "pertinant", "fantasies", "ejaculation", "businessbabe", "experience", "contortionist", "cheerleaers", "christian", "housewifes", "seductive", "gangbanged", "experienced", "passwords", "transexual", "gallaries", "lockerroom", "absolutely", "homepage-", "masterbaiting", "housewife", "masturbation", "pornographic", "thumbnils", "knickerless", "underwear", "enterprise", "scandinavian", "techniques", "manchester", "penetrating", "butterfly", "earthlink", "films+pic+galeries", "girfriend", "uncencored", "gymnastic", "hollywood", "insertions", "wonderboy", "skywalker", "fuckinside", "ursitesux", "stonecold", "christina", "stephanie", "password2", "quant4307s", "iloveyou1", "sebastian", "jamesbond", "iloveyou2", "amsterdam", "catherine", "football1", "charlotte", "christopher", "september", "123123123", "qwerty123", "southpark", "california", "washington", "lightning", "postov1000", "chevrolet", "snowboard", "birthday1", "australia", "charlie123", "outoutout", "superstar", "pinkfloyd", "hurricane", "idontknow", "qazwsxedc", "baseball1", "favorite2", "alexandra", "primetime21", "blackjack", "cleveland", "sexsexsex", "letmein22", "fatluvr69", "dragonball", "iloveyou!", "slimed123", "scoobydoo", "highlander", "playstation", "gnasher23", "porn4life", "excalibur", "wednesday", "sweetness", "undertaker", "university", "moonlight", "president", "newcastle", "1q2w3e4r5t", "pimpdaddy", "panasonic", "motherfucker", "peternorth", "cardinals", "fortune12");
    }
}
