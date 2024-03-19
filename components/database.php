<?php 

$host = 'localhost'; // Replace with hosted server
$username = 'root';
$password = '1234';
$database = 'arundel';
// Create a connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}


class DatabaseConnection{

    private PDO $pdo;
    private string $dbpath = "..\database.db";

    public function __construct()
    {
        try {
            $this->pdo = new PDO("sqlite:$this->dbpath");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle database connection errors
            // You can log or display an error message here
            die("Database connection failed: " . $e->getMessage());
        }
    }


    /**
     * Executes an SQL query and binds parameters to prevent SQL injection.
     *
     * @param string $sql SQL string to complete. Do not bind parameters!
     * @param array $params Array of parameters to bind in prepare step. 
     */
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
    public function executeQuery(string $sql, array $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query execution errors
            die("Query execution failed: " . $e->getMessage());
        }
    }
    public function prepareQuery(string $sql, array $params = [])
    {
        try {
            return $this->pdo->prepare($sql);
        } catch (PDOException $e) {
            // Handle query preparation errors
            die("Query preparation failed: " . $e->getMessage());
        }
    }
}
?>