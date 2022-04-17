<?php
class Database
{
    private $server;
    private $user;
    private $password;
    private $db;

    public function connect()
    {
        $this->server = "localhost";
        $this->user = "root";
        $this->password = "";
        $this->db = "e-money-kelompok-12";

        try {
            $conn = new mysqli($this->server, $this->user, $this->password, $this->db);
            return $conn;
        } catch (Exception $error) {
            echo "Connection Error:" . $error->getMessage();
            exit;
        }
    }
}
?>