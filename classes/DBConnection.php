<?php
/**
 * DBConnection
 *
 * Uses constants from initialize.php:
 * DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT
 */

// Load constants if not already defined
if (!defined('DB_SERVER')) {
    require_once(__DIR__ . "/../initialize.php");
}

class DBConnection {
    private $host = DB_SERVER;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    private $port = DB_PORT;

    public $conn;

    public function __construct() {
        if (!isset($this->conn)) {
            // Try configured port first (commonly 3306 on hosting, 3307 on some XAMPP setups)
            $this->conn = @new mysqli($this->host, $this->username, $this->password, $this->database, intval($this->port));

            // If failed, try 3307 (some XAMPP setups use 3307)
            if ($this->conn->connect_errno) {
                $this->port = 3307;
                $this->conn = @new mysqli($this->host, $this->username, $this->password, $this->database, intval($this->port));
            }

            if ($this->conn->connect_errno) {
                die("Cannot connect to database server: " . $this->conn->connect_error);
            }
        }
    }

    public function __destruct() {
        if ($this->conn instanceof mysqli) {
            if (@$this->conn->ping()) {
                $this->conn->close();
            }
        }
    }
}
?>



