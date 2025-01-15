<?php
class Dbh
{
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "crud_switch";
    private $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

}