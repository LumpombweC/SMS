<?php

class Database
{
    private $connection;

    public function __construct()
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'student_management';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';

        try {
            $this->connection = new PDO(
                "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $exception) {
            $this->connection = null;
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getRecentStudents($limit = 3)
    {
        if ($this->connection === null) {
            return [];
        }

        $statement = $this->connection->prepare(
            'SELECT studentnumber AS studentNo, fullname AS name, programme AS course, year AS status FROM students ORDER BY studentnumber DESC LIMIT :limit'
        );
        $statement->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
