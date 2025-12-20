<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->isHTML(true);
        $this->mailer->SMTPAuth = true;
        $this->mailer->CharSet = "UTF-8";
        $this->mailer->SMTPSecure = MAILER_PROTOCOL;
        $this->mailer->Port = MAILER_PORT;
        $this->mailer->Host = MAILER_HOST;
        $this->mailer->Username = MAILER_EMAIL;
        $this->mailer->Password = MAILER_PASSWORD;
    }

    private function setFromEmail(): void
    {
        $this->mailer->setFrom(MAILER_EMAIL, MAILER_NAME);
    }

    public function setReplyToEmail(string $email, string $name = ""): void
    {
        if ($name === "")
        {
            $name = $email;
        }

        $this->mailer->addReplyTo($email, $name);
    }

    public function send(string|array $receivers, string $title, string $content, ?array $variables = null): bool
    {
        $this->mailer->clearAllRecipients();

        try
        {
            $this->setFromEmail();

            // Set email receivers
            if (is_array($receivers))
            {
                foreach ($receivers as $receiver)
                {
                    $this->mailer->addAddress($receiver);
                }
            }
            else
            {
                $this->mailer->addAddress($receivers);
            }

            // Get email temnplate
            $templateFile = PATH_TEMPLATES . "emails/" . ensurePHPExtension($content);

            if (!is_file($templateFile))
            {
                debuglog("Email template not found: {$templateFile}", "warning");
                return false;
            }

            $body = file_get_contents($templateFile);

            if ($body === false)
                return false;

            // Include variables passed to email
            if (!empty($variables))
            {
                foreach ($variables as $key => $value)
                {
                    $body = str_replace("{{ " . $key . " }}", esc_html((string)$value), $body);
                }
            }

            // Send mail
            $this->mailer->Subject = $title;
            $this->mailer->Body = $body;
            $this->mailer->send();
            return true;
        }
        catch (Exception $e)
        {
            debuglog("Mailer error: {$exception->getMessage()}", "warning");
            return false;
        }
        finally
        {
            $this->mailer->clearAllRecipients();
            $this->mailer->clearReplyTos();
            $this->mailer->clearAddresses();
        }
    }
}
