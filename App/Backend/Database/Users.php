<?php declare(strict_types=1);
namespace Backend\Database;
defined("ACCESS") or exit("Access Denied");

use Backend\Core\Database;
use Backend\Core\Entity;

class Users extends Database
{
    private static $instance = null;
    protected $database;
    protected $table;
    protected $primary_key;
    protected $columns;

    public function __construct()
    {
        $this->database = "primary";
        $this->table = "users";
        $this->primary_key = "id";

        $this->columns = array(
            // User credentials
            "id" => "int(11) unsigned AUTO_INCREMENT",
            "username" => "varchar(" . USERNAME_MAX_LENGTH . ")",
            "email" => "varchar(" . EMAIL_MAX_LENGTH . ") NOT NULL UNIQUE",
            "password_hash" => "varchar(255)",

            // Security
            "account_status" => "varchar(20) DEFAULT 'active'",
            "email_verified" => "tinyint(1) DEFAULT 0",
            "verification_token" => "varchar(255)",
            "two_factor_enabled" => "tinyint(1) DEFAULT 0",
            "tfa_code" => "varchar(255)",
            "tfa_expires" => "datetime",
            "password_reset_token" => "varchar(255)",
            "password_reset_expires" => "datetime",
            "session_version" => "int(11) DEFAULT 1",

            // Metadata
            "avatar" => "varchar(100)",
            "theme" => "varchar(50)",
            "created_date" => "datetime",
            "last_login" => "datetime",

            // Encryption
            "encryption_salt" => "varchar(255)",
            "encryption_key_version" => "int(11) DEFAULT 1",
        );

        parent::__construct(["database" => $this->database]);
        $this->confirmTable();
    }

    // Singleton Pattern
    public static function getInstance()
    {
        if (self::$instance === null)
        {
            self::$instance = new Users();
        }
        return self::$instance;
    }

    /* ====================================================================== */
    /*                           DATABASE API CALLS                           */
    /* ====================================================================== */

    public function createUser($username, $email, $password_hash, $salt): void
    {
        $now = date("Y-m-d H:i:s");
        $token = bin2hex(random_bytes(32));

        $this->table()->insert([
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash,
            'account_status' => 'active',
            'email_verified' => 0,
            'verification_token' => $token,
            'two_factor_enabled' => 0,
            'tfa_code' => null,
            'tfa_expires' => null,
            'password_reset_token' => null,
            'password_reset_expires' => null,
            'session_version' => 1,
            'avatar' => '',
            'theme' => DEFAULT_THEME,
            'created_date' => $now,
            'last_login' => null,
            'encryption_salt' => $salt,
            'encryption_key_version' => 1
        ]);
    }

    public function doesEmailExist($email): bool
    {
        $userRecord = $this->table()
            ->where("email", $email)
            ->select(["id"])
            ->getFirst();

        return !empty(get_object_vars($userRecord));
    }

    public function getPublicData($email): object
    {
        return $this->table()
            ->where("email", $email)
            ->select(["id", "email", "created_date", "username", "email", "avatar", "email_verified", "two_factor_enabled", "account_status", "last_login", "theme", "session_version"])
            ->getFirst();
    }

    public function getPublicDataById($id): object
    {
        return $this->table()
            ->where("id", $id)
            ->select(["id", "email", "created_date", "username", "email", "avatar", "email_verified", "two_factor_enabled", "account_status", "last_login", "theme", "session_version"])
            ->getFirst();
    }

    public function getVerificationData($email): object
    {
        return $this->table()
            ->where("email", $email)
            ->select(["id", "username", "email_verified", "verification_token"])
            ->getFirst();
    }

    public function updateVerificationToken($email, $newToken): void
    {
        $this->table()
            ->where("email", $email)
            ->update([
                "verification_token" => $newToken,
            ]);
    }

    public function activateUser($email): bool
    {
        return $this->table()
            ->where("email", $email)
            ->update([
                "email_verified" => 1,
                "verification_token" => null,
            ]);
    }

    public function getResetPasswordData($email): object
    {
        return $this->table()
            ->where("email", $email)
            ->select(["id", "password_reset_token", "password_reset_expires"])
            ->getFirst();
    }

    public function updatePasswordResetToken($email, $token, $expires): void
    {
        $this->table()
            ->where("email", $email)
            ->update([
                "password_reset_token" => $token,
                "password_reset_expires" => $expires,
            ]);
    }

    public function getSessionVersion(int $userId): int
    {
        $data = $this->table()
            ->where("id", $userId)
            ->select(["session_version"])
            ->getFirst();

        if (isset($data->session_version))
        {
            return (int)$data->session_version;
        }

        return 0;
    }

    public function incrementSessionVersion(int $userId): void
    {
        $current = $this->getSessionVersion($userId);
        $next = $current + 1;

        $this->table()
            ->where("id", $userId)
            ->update(["session_version" => $next]);
    }

    public function setTFACode($email, $code, $expires): void
    {
        $this->table()
            ->where("email", $email)
            ->update([
                "tfa_code" => $code,
                "tfa_expires" => $expires,
            ]);
    }

    public function clearTFACode($email): void
    {
        $this->table()
            ->where("email", $email)
            ->update([
                "tfa_code" => null,
                "tfa_expires" => null,
            ]);
    }

    public function getTFAData($email): object
    {
        return $this->table()
            ->where("email", $email)
            ->select(["id", "tfa_code", "tfa_expires"])
            ->getFirst();
    }

    public function updateLastLogin($userId): void
    {
        $this->table()
            ->where("id", $userId)
            ->update([
                "last_login" => date("Y-m-d H:i:s")
            ]);
    }

    public function updatePasswordHash($userId, $passwordHash): void
    {
        $this->table()
            ->where("id", $userId)
            ->update([
                "password_hash" => $passwordHash
            ]);
    }

    public function getPasswordHash($userId): ?string
    {
        $password_hash = $this->table()
            ->where("id", $userId)
            ->select(["password_hash"])
            ->getFirst();

        if (isset($password_hash->password_hash))
        {
            return $password_hash->password_hash;
        }

        return null;
    }

    public function enableTwoFactor($userId): void
    {
        $this->table()
            ->where("id", $userId)
            ->update([
                "two_factor_enabled" => 1,
            ]);
    }

    public function disableTwoFactor($userId): void
    {
        $this->table()
            ->where("id", $userId)
            ->update([
                "two_factor_enabled" => 0,
            ]);
    }

    public function updateProfile($userId, $username = null, $avatarFileName = null): void
    {
        $update = [];

        if ($username !== null) {
            $update["username"] = $username;
        }

        if ($avatarFileName !== null) {
            $update["avatar"] = $avatarFileName;
        }

        if (!empty($update)) {
            $this->table()->where("id", $userId)->update($update);
        }
    }

    public function updateTheme($userId, $newTheme): void
    {
        $this->table()
            ->where("id", $userId)
            ->update([
                "theme" => $newTheme
            ]);
    }

    public function getTheme($userId): object
    {
        $themeData = $this->table()
            ->where("id", $userId)
            ->select(["theme"])
            ->getFirst();

        if (isset($themeData->theme)) {
            return $themeData->theme;
        }

        return DEFAULT_THEME;
    }

    public function getUserEncryption($userId): object
    {
        return $this->table()
            ->where("id", $userId)
            ->select(["id", "encryption_salt", "encryption_key_version"])
            ->getFirst();
    }

    public function deleteUser($userId, $userEmail): bool
    {
        return $this->table()
            ->where("id", $userId)->where("email", $userEmail)
            ->delete();
    }
}

/* ===================================================================== */
/*                            DATABASE Entity                            */
/* ===================================================================== */
class User extends Entity
{
    protected string $database = 'primary';
    protected string $table = 'users';
    protected string $primary_key = 'id';

    protected array $columns = [
        'id' => 'int(11) unsigned AUTO_INCREMENT',
        'username' => 'varchar(' . USERNAME_MAX_LENGTH . ')',
        'email' => 'varchar(' . EMAIL_MAX_LENGTH . ')',
        'password_hash' => 'varchar(255)',
        'account_status' => "varchar(20) DEFAULT 'active'",
        'email_verified' => 'tinyint(1)',
        'verification_token' => 'varchar(255)',
        'two_factor_enabled' => 'tinyint(1)',
        'tfa_code' => 'varchar(255)',
        'tfa_expires' => 'datetime',
        'password_reset_token' => 'varchar(255)',
        'password_reset_expires' => 'datetime',
        "session_version" => "int(11) DEFAULT 1",
        'avatar' => 'varchar(100)',
        'theme' => 'varchar(50)',
        'created_date' => 'datetime',
        'last_login' => 'datetime',
        'encryption_salt' => 'varchar(255)',
        'encryption_key_version' => 'int(11)'
    ];

    public ?int $id = null;
    public ?string $username = null;
    public ?string $email = null;
    public ?string $password_hash = null;
    public ?string $account_status = null;
    public ?int $email_verified = null;
    public ?string $verification_token = null;
    public ?int $two_factor_enabled = null;
    public ?string $tfa_code = null;
    public ?string $tfa_expires = null;
    public ?string $password_reset_token = null;
    public ?string $password_reset_expires = null;
    public ?int $session_version = null;
    public ?string $avatar = null;
    public ?string $theme = null;
    public ?string $created_date = null;
    public ?string $last_login = null;
    public ?string $encryption_salt = null;
    public ?int $encryption_key_version = null;

    public function __construct(?int $id = null)
    {
        parent::__construct($id, $this->database);
    }
}
