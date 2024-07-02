<?php

namespace core;

use PDO;
use PDOException;

class DatabaseHandler
{
    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $pass = DB_PASS;
    private string $dbname = DB_NAME;

    private PDO $handler;
    private string $error;
    private false|\PDOStatement $statement;

    public function __construct()
    {
        $connection = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->handler = new PDO($connection, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query(string $query): void
    {
        $this->statement = $this->handler->prepare($query);
    }

    public function bind(string $param, string $value, int $type = PDO::PARAM_STR): void
    {
        $this->statement->bindValue($param, $value, $type);
    }

    public function execute(): bool
    {
        return $this->statement->execute();
    }

    public function resultSet(): bool|array
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
