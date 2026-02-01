<?php declare(strict_types=1);
namespace Backend\Core;
defined("ACCESS") or exit("Access Denied");

use \PDO;
use \PDOException;

class Database
{
    protected PDO $connection;
    protected array $arguments = [];
    protected array $securedInputs = [];

    public function __construct(array $params = array("database" => "primary"))
    {
        $databases = DATABASES;

        // Make sure database information is set
        if (empty($databases[$params["database"]]))
        {
            kristal_fatalExit("Database configuration error. Database (" . $params["database"] . ") was empty, please double check your config file.");
        }

        try
        {
            $config = $databases[$params["database"]];

            // Create connection
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['database_name']};charset=utf8mb4",
                $config['username'],
                $config['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]
            );
        }
        catch (PDOException $e)
        {
            kristal_fatalExit($e->getMessage());
        }

        $this->resetArguments();
    }


    /* ===================================================================== */
    /*                              Schema                                   */
    /* ===================================================================== */
    protected function confirmTable(string $engine = "InnoDB", string $charset = "utf8mb4"): void
    {
        $this->assertSafeIdentifier($this->table);

        if (!$this->doesTableExist($this->table))
        {
            $this->createTable(
                $this->table,
                $this->primary_key,
                $this->columns,
                $engine,
                $charset
            );
        }
    }

    public function doesTableExist(?string $table = null): bool
    {
        $table ??= $this->table;

        if ($table === null) {
            return false;
        }

        // Iterate through the tables and check if the specified table exists
        foreach ($this->getTables() as $row)
        {
            if (in_array($table, $row, true))
            {
                return true;
            }
        }

        return false;
    }

    protected function createTable(string $table, string $primaryKey, array $columns, string $engine, string $charset): void
    {
        $this->assertSafeIdentifier($table);
        $this->assertSafeIdentifier($primaryKey);

        $sql = "CREATE TABLE IF NOT EXISTS {$table} (";

        foreach ($columns as $name => $definition)
        {
            $this->assertSafeIdentifier($name);
            $sql .= "{$name} {$definition},";
        }

        $sql .= "PRIMARY KEY ({$primaryKey})) ENGINE={$engine} AUTO_INCREMENT=1 DEFAULT CHARSET={$charset};";
        $this->connection->exec($sql);
    }

    public function dropTable(?string $table = null): void
    {
        $table ??= $this->table;

        if ($table === null)
        {
            kristal_fatalExit("Fatal Error: you are trying to delete a table using dropTable() without specifying a table.");
        }

        $this->assertSafeIdentifier($table);
        $this->connection->exec("DROP TABLE {$table}");
    }

    public function dropTableCascade(?string $table = null): void
    {
        $table ??= $this->table;

        if ($table === null)
        {
            kristal_fatalExit("Fatal Error: you are trying to delete a table using dropTable() without specifying a table.");
        }

        $this->assertSafeIdentifier($table);
        $this->connection->exec("DROP TABLE {$table} CASCADE");
    }

    public function getTables(): object
    {
        return $this->fetchAll('SHOW TABLES');
    }


    /* ===================================================================== */
    /*                               Internals                               */
    /* ===================================================================== */
   protected function resetArguments(): void
    {
        $this->securedInputs = [];
        $this->arguments = [
            'where'   => '',
            'select'  => '',
            'insert'  => '',
            'update'  => '',
            'orderBy' => '',
            'limit'   => '',
            'offset'  => '',
        ];
    }

    protected function assertSafeIdentifier(string $value): void
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $value))
        {
            kristal_fatalExit("Invalid SQL identifier: {$value}");
        }
    }
    
    protected function assertSafeQualifiedIdentifier(string $value): void
    {
        if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $value))
        {
            kristal_fatalExit("Invalid SQL identifier: {$value}");
        }
    }

    protected function normalizeOperator(string $operator): string
    {
        $operator = strtoupper($operator);
        $allowed = ['=', '!=', '<', '>', '<=', '>=', 'LIKE'];

        if (!in_array($operator, $allowed, true))
        {
            kristal_fatalExit("Invalid SQL operator: {$operator}");
        }

        return $operator;
    }

    public function close(): void
    {
        unset($this->connection);
    }


    /* ==================================================================== */
    /*                     Query builder initialization                     */
    /* ==================================================================== */
    public function table()
    {
        return $this;
    }


    /* ==================================================================== */
    /*                                Select                                */
    /* ==================================================================== */
    public function select(array|string $columns): static
    {
        $this->arguments['select'] = '';

        if (is_string($columns))
        {
            $this->arguments['select'] = $columns;
            return $this;
        }

        $safe = [];

        foreach ($columns as $column)
        {
            if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $column))
                continue;

            $safe[] = $column;
        }

        $this->arguments['select'] = $safe ? implode(', ', $safe) : '*';
        return $this;
    }


    /* =================================================================== */
    /*                                Where                                */
    /* =================================================================== */
    private function baseWhere(string $connector, string $sql, array $bindings = []): void
    {
        if (!empty($this->arguments['where'])) {
            $this->arguments['where'] .= " $connector ";
        }
    
        $this->arguments['where'] .= $sql;
    
        foreach ($bindings as $key => $value) {
            $this->securedInputs[$key] = $value;
        }
    }
    
    public function where(string $field, mixed $value, string $operator = "="): static
    {
        $this->assertSafeQualifiedIdentifier($field);
        $operator = $this->normalizeOperator($operator);

        $param = 'param_' . uniqid();
        $this->baseWhere('and', "$field $operator :$param", [$param => $value]);
        return $this;
    }
    
    public function orWhere(string $field, mixed $value, string $operator = "="): static
    {
        $this->assertSafeQualifiedIdentifier($field);
        $operator = $this->normalizeOperator($operator);
 
        $param = 'param_' . uniqid();
        $this->baseWhere('or', "$field $operator :$param", [$param => $value]);
        return $this;
    }
    
    public function whereLike(string $field, string $value): static
    {
        return $this->where($field, "%$value%", 'LIKE');
    }
    
    public function whereStartsWith(string $field, string $value): static
    {
        return $this->where($field, "$value%", 'LIKE');
    }
    
    public function whereEndsWith(string $field, string $value): static
    {
        return $this->where($field, "%$value", 'LIKE');
    }
    
    public function whereIn(string $field, array $values): static
    {
        if (empty($values))
            return $this;
    
        $this->assertSafeQualifiedIdentifier($field);

        $placeholders = [];
        $bindings = [];
    
        foreach ($values as $value) {
            $key = 'param_' . uniqid();
            $placeholders[] = ":$key";
            $bindings[$key] = $value;
        }
    
        $sql = "$field IN (" . implode(',', $placeholders) . ")";
    
        $this->baseWhere('and', $sql, $bindings);
        return $this;
    }
    
    public function whereBetween(string $field, mixed $min, mixed $max, bool $not = false): static
    {
        $this->assertSafeQualifiedIdentifier($field);

        $p1 = 'param_' . uniqid();
        $p2 = 'param_' . uniqid();
        $sql = $not ? "$field NOT BETWEEN :$p1 AND :$p2" : "$field BETWEEN :$p1 AND :$p2";
        $this->baseWhere('and', $sql, [$p1 => $min, $p2 => $max]);
        return $this;
    }
    
    public function whereNull(string $field): static
    {
        $this->assertSafeQualifiedIdentifier($field);

        $this->baseWhere('and', "$field IS NULL");
        return $this;
    }
    
    public function whereNotNull(string $field): static
    {
        $this->assertSafeQualifiedIdentifier($field);

        $this->baseWhere('and', "$field IS NOT NULL");
        return $this;
    }


    /* ==================================================================== */
    /*                              Additionals                             */
    /* ==================================================================== */
    public function limit(int $value): static
    {
        if ($value > 0)
        {
            $this->arguments["limit"] = "limit $value";
        }

        return $this;
    }

    public function offset(int $value): static
    {
        if ($value >= 0)
        {
            $this->arguments["offset"] = "offset $value";
        }

        return $this;
    }

    public function orderBy(string $column, string $direction = "asc"): static
    {
        $direction = strToLower($direction);

        if ($direction !== 'asc' && $direction !== 'desc')
        {
            $direction = 'asc';
        }

        // Whitelist protection
        $this->assertSafeQualifiedIdentifier($column);

        $this->arguments['orderBy'] = "order by $column $direction";
        return $this;
    }


    /* ==================================================================== */
    /*                                Actions                               */
    /* ==================================================================== */
    public function execute(string $query, array $securedInputs = []): void
    {
        $this->connection->prepare($query)->execute($securedInputs);
    }

    public function fetch(string $query, array $securedInputs = []): object
    {
        $statement = $this->connection->prepare($query);
        $statement->execute($securedInputs);
    
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row === false ? new \stdClass() : (object) $row;
    }

    public function fetchAll(string $query, array $securedInputs = []): object
    {
        $statement = $this->connection->prepare($query);
        $statement->execute($securedInputs);
    
        return (object) $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function baseGet(): \PDOStatement
    {
        $query = "select * from {$this->table}";

        if ($this->arguments["select"])
        {
            $query = "select {$this->arguments['select']} from {$this->table}";
        }
        if ($this->arguments["where"])
        {
            $query .= " where {$this->arguments['where']}";
        }
        if ($this->arguments["orderBy"])
        {
            $query .= " {$this->arguments['orderBy']}";
        }
        if ($this->arguments["limit"])
        {
            $query .= " {$this->arguments['limit']}";
        }
        if ($this->arguments["offset"])
        {
            $query .= " {$this->arguments['offset']}";
        }

        $query .= ";";

        $statement = $this->connection->prepare($query);
        $statement->execute($this->securedInputs);
        $this->resetArguments();
        return $statement;
    }

    public function get(): object
    {
        $statement = $this->baseGet();
        return (object) $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getFirst(): object
    {
        $this->limit(1);
        $statement = $this->baseGet();
        $row = $statement->fetch(\PDO::FETCH_OBJ);
        return $row === false ? (object)[] : $row;
    }

    public function getValue(string $value): mixed
    {
        $this->assertSafeIdentifier($value);
        $this->limit(1);
        $statement = $this->baseGet();
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row[$value] ?? null;
    }

    public function insert(array $data): bool
    {
        if (empty($data))
            return false;

        $columns = [];
        $placeholders = [];
        $bindings = [];
    
        foreach ($data as $column => $value) 
        {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $column))
                continue;

            $param = 'param_' . uniqid();
            $columns[] = $column;
            $placeholders[] = ":$param";
            $bindings[$param] = $value;
        }

        if (empty($columns))
        {
            kristal_fatalExit("No valid columns provided for insert.");
        }
    
        $cols = implode(', ', $columns);
        $vals = implode(', ', $placeholders);

        $sql = "INSERT INTO {$this->table} ($cols) VALUES ($vals)";
    
        $this->connection->prepare($sql)->execute($bindings);
        $this->resetArguments();
    
        return true;
    }

    public function update(array $data): bool
    {
        if (empty($data))
            return false;
    
        $sets = [];
        $bindings = [];
    
        foreach ($data as $column => $value)
        {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $column))
                continue;

            $param = 'param_' . uniqid();
            $sets[] = "$column = :$param";
            $bindings[$param] = $value;
        }

        if (empty($sets))
        {
            kristal_fatalExit("No valid columns provided for update.");
        }
    
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->arguments['where']}";
    
        $this->connection->prepare($sql)->execute(array_merge($bindings, $this->securedInputs));
        $this->resetArguments();
        return true;
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->arguments['where']))
        {
            $sql .= " WHERE {$this->arguments['where']}";
        }

        $this->connection->prepare($sql)->execute($this->securedInputs);
        $this->resetArguments();
        return true;
    }
}
