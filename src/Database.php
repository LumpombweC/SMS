<?php

class Database
{
    private static $instance = null;
    private $connection;
<?php

class Database
{
    private static $instance = null;
    private $connection;

  
    private function __construct()
    {
        // Load configuration using environment variables for high security
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'students_records';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';

   
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

        // Secure, high-performance PDO configuration settings
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
            PDO::ATTR_PERSISTENT         => true,                   
            PDO::ATTR_EMULATE_PREPARES   => false,                  
        ];

        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
    
            error_log("Database Connection Failed Securely: " . $e->getMessage());
            die("Database Connection Failure. Please audit internal server error logs.");
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

 
    public function getRecentStudents($limit = 5)
    {
        $stmt = $this->connection->prepare("SELECT id, student_number, first_name, last_name, programme, year_of_study FROM students ORDER BY id DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    private function __clone() {}

   
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a standalone database singleton.");
    }
}

    private $host = "localhost";
    private $dbname = "students_records";
    private $username = "root";
    private $password = "";

    private function __construct()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
