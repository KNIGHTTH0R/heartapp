<?php

/**
 * A little db helper class to perform database functionalities.
 */

final class DbFactory {
    private $server = '{servername}';
    private $user = '{username}';
    private $password = '{password}';
    private $db = 'heart';

    private $conn = null;
    
    public static function Instance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new DbFactory();
        }

        return $instance;
    }

    /**
     * We will be using Singleton to make sure only one $db instance exists system wise
     */
    private function __construct() {
        $this->conn = new mysqli($this->server, $this->user, $this->password, $this->db);
        if ($this->conn->connect_error) {
            throw new Exception('Connection failed: '.$this->conn->connect_error);
        }
    }

    /**
     * This method creates a user data row in the fbapp table if not exists. 
     * If a user specified by $fbuser_id already exists, it will update the 
     * $access_token and the $is_active status. 
     * 
     * This method expects a standard $user object with the following member
     * variables: $fbuser_id (string), $access_token (string) and $is_active
     * (boolean).
     */
    public function saveUser($user) {
        $sql = "INSERT INTO 
                    `fbapp` (fbuser_id, access_token, is_active) 
                VALUES 
                    ('{$user->fbuser_id}', '{$user->access_token}', $user->is_active) 
                ON DUPLICATE KEY UPDATE 
                    access_token = '{$user->access_token}', is_active = {$user->is_active}";

        if (!$this->conn->query($sql)) {
            throw new Exception('Query failed: ('.$this->conn->errno.') '.$this->conn->error);
        }
    }

    /**
     * This method returns a standar $user object with the following fields:
     * $user_id (int, primary ke), $fbuser_id (string), $access_token (string) and $is_active (boolean)
     * by provided Facebook user id. If not found, a standard object with given $fbuser_id is returned.
     */
    public function getUser($fbuser_id) {
        $user = new stdClass;
        $user->fbuser_id = $fbuser_id;
        
        $sql = "SELECT * FROM `fbapp` WHERE fbuser_id = '{$fbuser_id}'";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception('Query failed: ('.$this->conn->errno.') '.$this->conn->error);
        }

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user->user_id = $row['user_id'];
            $user->access_token = $row['access_token'];
            $user->is_active = $row['is_active'];
        }

        return $user;
    }
}