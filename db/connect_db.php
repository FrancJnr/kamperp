<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
include  "../../config_db.php";

class Database{
  
    // specify your own database credentials
    private $host = "localhost";
    public $db_name;
    public $username;
    public $password;
    public $conn;
    public $validToken = false;
  
    public function init(){
        include  "../../config_db.php";
        $this->conn = null;
        // $this->db_name= $db_connections[0]['dbname'];
        $this->username= $db_connections[0]['dbuser'];
        $this->password= $db_connections[0]['dbpassword'];
        $this->host= $db_connections[0]['host'];

    
    }
    public function getConnection(){
         $this->init();
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
    public function authenticate($username, $password){
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $authenticatedUser = -1;
        $encrypt = md5($password);
        try {
            $sql = "SELECT * FROM `0_users` WHERE user_id = ? AND password = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $encrypt);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row); 
                    $authenticatedUser= $row['id'];
                }
            return $authenticatedUser;
        } catch (Exception $th) {
            echo $th;
        }
    }
    public function invoiceShare($username, $password){
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $authenticatedUser = -1;
        $encrypt = md5($password);
        try {
            $sql = "SELECT * FROM `0_users` WHERE user_id = ? AND password = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $encrypt);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row); 
                    $authenticatedUser= $row['id'];
                }
            return $authenticatedUser;
        } catch (Exception $th) {
            echo $th;
        }
    }
}

?>






