<?php
class Database {

    private $_connection;
    private static $_instance;
    private $_host = DBHOST;
    private $_username = DBUSER;
    private $_password = DBPASS;
    private $_database = DBNAME;

    public static function getInstance() 
    {
        if(!self::$_instance) { 
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() 
    {
        $this->_connection = new MyMysqli(
            $this->_host, 
            $this->_username, 
            $this->_password, 
            $this->_database
        );

        if(mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysqlli_connect_error(), E_USER_ERROR);
        }
    }

    private function __clone() 
    { 

    }

    public function getConnection() 
    {
        return $this->_connection;
    }

}

class MyMysqli extends mysqli
{
    public function __construct($host, $user, $password, $db) 
    {
        parent::__construct($host, $user, $password, $db);
    }

    public function get($sql, $limit=0)
    {
        $array = array();
        $query = $this->query($sql);
        if ($limit==0){
            if ($query && $query->num_rows>0){
                while($result = $query->fetch_assoc()){
                    $array[] = $result;
                }
            }
        }else{
            if ($query && $query->num_rows==1){
                $result = $query->fetch_assoc();
                $array = $result;
            }   
        }
        return $array;
    }
}
class Query
{
    public $sql;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->sql = $db->getConnection();
        $this->sql->set_charset("utf8");
        $this->sql->query("use " . DBNAME);
        $this->sql->query("set SQL_MODE='' ");

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/admin/modules/general/mysql.php") ||
            (filesize($_SERVER['DOCUMENT_ROOT'] . "/admin/modules/general/mysql.php") < 10)) {
            header("Location: /install/index.php");
            exit();
        }
    }

    public function split_sql($sql)
    {
        $sql = trim($sql);
        $sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);
        $buffer = array();
        $ret = array();
        $in_string = false;
        for($i=0; $i<strlen($sql)-1; $i++){
            if($sql[$i] == ";" && !$in_string){
                $ret[] = substr($sql, 0, $i);
                $sql = substr($sql, $i + 1);
                $i = 0;
            }
            if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\"){
                $in_string = false;
            }
            elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")){
                $in_string = $sql[$i];
            }
            if(isset($buffer[1])){
                $buffer[0] = $buffer[1];
            }
            $buffer[1] = $sql[$i];
        }
        if(!empty($sql)){
            $ret[] = $sql;
        }
        return($ret);
    }

    public function pr($i)
    {
        $i = @strip_tags($i);
        $i = @htmlspecialchars($i, ENT_QUOTES);
        $i = @$this->sql->real_escape_string($i);
        return trim($i);
    }

    public function __descruct()
    {
        $this->sql->close();
    }
}