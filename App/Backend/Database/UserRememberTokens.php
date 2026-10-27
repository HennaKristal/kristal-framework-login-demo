<?php declare(strict_types=1);

namespace Backend\Database;
defined("ACCESS") or exit("Access Denied");

use Backend\Core\Database;
use Backend\Core\Entity;

class UserRememberTokens extends Database
{
    private static $instance = null;
    protected $database;
    protected $table;
    protected $primary_key;
    protected $columns;

    public function __construct()
    {
        $this->database = "primary";
        $this->table = "user_remember_tokens";
        $this->primary_key = "id";

        $this->columns = array(
            "id" => "int(11) unsigned AUTO_INCREMENT",
            "user_id" => "int(11) unsigned NOT NULL",
            "token_hash" => "char(64) NOT NULL",
            "expires_at" => "datetime NOT NULL",
            "created_date" => "datetime NOT NULL",
            "last_used_date" => "datetime",
            "device_label" => "varchar(100)",
            "ip_address" => "varchar(45)"
        );

        parent::__construct(["database" => $this->database]);
        $this->confirmTable();
    }

    // Singleton Pattern
    public static function getInstance()
    {
        if (self::$instance === null)
        {
            self::$instance = new UserRememberTokens();
        }
        return self::$instance;
    }

    /* ====================================================================== */
    /*                           DATABASE API CALLS                           */
    /* ====================================================================== */

    public function createToken(
        int $userId,
        string $tokenHash,
        string $expiresAt,
        ?string $deviceLabel = null,
        ?string $ipAddress = null
    ): void
    {
        $this->table()->insert([
            "user_id" => $userId,
            "token_hash" => $tokenHash,
            "expires_at" => $expiresAt,
            "created_date" => date("Y-m-d H:i:s"),
            "last_used_date" => null,
            "device_label" => $deviceLabel,
            "ip_address" => $ipAddress
        ]);
    }

    public function getValidToken(string $tokenHash): object
    {
        return $this->table()
            ->where("token_hash", $tokenHash)
            ->where("expires_at", date("Y-m-d H:i:s"), ">")
            ->getFirst();
    }

    public function updateTokenUsage(int $id, string $newTokenHash, string $newExpiresAt): void
    {
        $this->table()
            ->where("id", $id)
            ->update([
                "token_hash" => $newTokenHash,
                "expires_at" => $newExpiresAt,
                "last_used_date" => date("Y-m-d H:i:s")
            ]);
    }

    public function deleteTokenById(int $id): void
    {
        $this->table()
            ->where("id", $id)
            ->delete();
    }

    public function deleteTokensForUser(int $userId): void
    {
        $this->table()
            ->where("user_id", $userId)
            ->delete();
    }

    public function cleanupExpiredTokens(): void
    {
        $this->table()
            ->where("expires_at", date("Y-m-d H:i:s"), "<")
            ->delete();
    }
}

/* ===================================================================== */
/*                            DATABASE Entity                            */
/* ===================================================================== */

class UserRememberToken extends Entity
{
    protected string $database = "primary";
    protected string $table = "user_remember_tokens";
    protected string $primary_key = "id";

    protected array $columns = [
        "id" => "int(11) unsigned AUTO_INCREMENT",
        "user_id" => "int(11) unsigned",
        "token_hash" => "char(64)",
        "expires_at" => "datetime",
        "created_date" => "datetime",
        "last_used_date" => "datetime",
        "device_label" => "varchar(100)",
        "ip_address" => "varchar(45)"
    ];

    public ?int $id = null;
    public ?int $user_id = null;
    public ?string $token_hash = null;
    public ?string $expires_at = null;
    public ?string $created_date = null;
    public ?string $last_used_date = null;
    public ?string $device_label = null;
    public ?string $ip_address = null;

    public function __construct(?int $id = null)
    {
        parent::__construct($id, $this->database);
    }
}
