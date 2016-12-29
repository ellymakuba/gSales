<?php
class DB_Connect {
    private $conn;
    // Connecting to database
    public function connect() {
        //require_once 'config/config.php';
        // Connecting to mysql database
		try{
        $this->conn = new PDO("mysql:host=localhost;dbname=pos", 'root', '');
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        return $this->conn;
		}
		catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}

?>
