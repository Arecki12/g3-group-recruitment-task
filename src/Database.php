<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;

    private ?PDO $pdo = null;

    private function __construct()
    {
        try {
            $dsn = 'mysql:host=mysql;dbname=test;charset=utf8';
            $username = 'root';
            $password = 'secret';

            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Error during connection: ' . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
