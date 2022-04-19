<?php
class Database
{
    private $server;
    private $user;
    private $password;
    private $db;

    public function connect()
    {
        $this->server = "remotemysql.com";
        $this->user = "5fiCsWmD49";
        $this->password = "1cpMlSuNOB";
        $this->db = "5fiCsWmD49";

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