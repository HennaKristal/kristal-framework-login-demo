<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

use Backend\Core\Database;

abstract class Entity extends Database
{
    protected string $table;
    protected string $primary_key = 'id';
    protected array $columns = [];
    protected mixed $identifier_value = null;
    protected bool $persisted = false;

    public function __construct(mixed $identifier = null, string $database = 'primary')
    {
        parent::__construct(['database' => $database]);
        $this->confirmTable();

        if ($identifier !== null)
        {
            $this->load($identifier);
        }
    }

    /* ===================================================== */
    /*                       Loading                         */
    /* ===================================================== */
    protected function load(mixed $identifier): void
    {
        $this->assertSafeIdentifier($this->primary_key);

        $row = $this->table()->where($this->primary_key, $identifier)->getFirst();

        if (empty((array) $row))
        {
            return;
        }

        foreach ($this->columns as $column => $_)
        {
            $this->{$column} = $row->{$column} ?? null;
        }

        $this->identifier_value = $identifier;
        $this->persisted = true;
    }

    /* ===================================================== */
    /*                       Saving                          */
    /* ===================================================== */
    public function save(): bool
    {
        $data = [];

        foreach ($this->columns as $column => $_)
        {
            if ($column === $this->primary_key)
                continue;

            $data[$column] = $this->{$column} ?? null;
        }

        if ($this->persisted)
        {
            return $this->table()->where($this->primary_key, $this->identifier_value)->update($data);
        }

        $result = $this->table()->insert($data);

        if ($result)
        {
            $this->identifier_value = $this->connection->lastInsertId();
            $this->persisted = true;
        }

        return $result;
    }

    /* ===================================================== */
    /*                       Delete                          */
    /* ===================================================== */
    public function delete(): bool
    {
        if (!$this->persisted)
            return false;

        $result = $this->table()->where($this->primary_key, $this->identifier_value)->delete();

        if ($result)
        {
            $this->persisted = false;
            $this->identifier_value = null;
        }

        return $result;
    }
}
