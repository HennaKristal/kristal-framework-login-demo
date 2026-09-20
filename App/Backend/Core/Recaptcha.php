<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

class Recaptcha
{
    public static function validateV2(): bool
    {
        $response = self::verify(RECAPTCHA_V2_SITE_SECRET);

        if (!$response || empty($response['success'])) {
            return false;
        }

        return ($response['hostname'] ?? '') === $_SERVER['HTTP_HOST'];
    }

    public static function validateV3(string $action, float $minimumScore = 0.5): bool
    {
        $response = self::verify(RECAPTCHA_V3_SITE_SECRET);

        if (!$response || empty($response['success'])) {
            return false;
        }

        if (($response['hostname'] ?? '') !== $_SERVER['HTTP_HOST']) {
            return false;
        }

        if (($response['action'] ?? '') !== $action) {
            return false;
        }

        return (float) ($response['score'] ?? 0) >= $minimumScore;
    }

    private static function verify(string $secretKey): array|false
    {
        if (empty($_POST['g-recaptcha-response'])) {
            return false;
        }

        $postData = [
            'secret' => $secretKey,
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
        ]);

        $result = curl_exec($curl);

        curl_close($curl);

        if ($result === false) {
            return false;
        }

        $response = json_decode($result, true);

        return is_array($response) ? $response : false;
    }
}
