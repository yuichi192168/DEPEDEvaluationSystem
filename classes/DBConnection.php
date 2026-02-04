<?php
if (!defined('DB_SERVER')) {
    require_once(__DIR__ . "/../initialize.php");
}

if (!class_exists('DBConnection', false)) {

class DBConnection {

    private static $instance = null;
    private static $conn = null;

    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    private $port = DB_PORT;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getConnection() {
        self::getInstance();
        return self::$conn;
    }

    private function __construct() {

        if (self::$conn instanceof mysqli) {
            return;
        }

        self::$conn = @new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            intval($this->port)
        );

        if (self::$conn->connect_errno && $this->port == 3307) {
            self::$conn = @new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database,
                3307
            );
        }

        if (self::$conn->connect_errno) {
            die(
                "Database Connection Failed\n\n" .
                "Server: {$this->host}:{$this->port}\n" .
                "Database: {$this->database}\n" .
                "Error: " . self::$conn->connect_error
            );
        }
    }

    private function __clone() {}
    public function __wakeup() {}
}

}
?>
