<?
define("DBHOST", 'localhost');
define("DBUSER", 'p-333274_alpv');
define("DBPASS", 'P65qgbp65qgb@');
define("DBNAME", 'p-333274_bw');
define("TEMPLATE", $_SERVER["DOCUMENT_ROOT"].'/admin/template/main');

define(
    'ROOT',
    str_replace(
        'admin/modules/general', 
        '',  
        str_replace(
            '\\',
            '/',
            dirname(__FILE__)
        )
    )
);
define(
    'ADMIN', 
    '/admin/modules'
);



@session_start();
@header("Content-Type: text/html; charset=utf-8");

error_reporting(E_ALL);
ini_set("display_errors", "1");

error_reporting(0);
ini_set("display_errors", "0");

include_once ROOT . "/modules/Query.php";
include_once ROOT . "/modules/Request.php";
$mysql = new Query();
$mysql = $mysql->sql;



include_once dirName(__FILE__)."/function.php";
include_once dirName(__FILE__)."/resize-class.php";


function getFormData($method) {
    $data = [];
    if ($method === 'GET') return $_GET;

    if ($method === 'POST') {
        $data = $_POST;
        $data["q"] = $_GET["q"];
        $data["json"] = json_decode(file_get_contents("php://input"), 1);
    }
    return $data;
}

$method = $_SERVER['REQUEST_METHOD'];
$formData = getFormData($method);

//$log = date('Y-m-d H:i:s') . ' '.$method.' '.json_encode($formData);
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs.txt', $log . PHP_EOL, FILE_APPEND);


if (isset($_POST["data"]) && isset($_POST["sign"])){
    echo 'OK';
    exit;
}



class CleanGetPost extends Query
{

    public $fields = array(
        'version',
    );

    public function __construct()
    {
        parent::__construct();
        $this->clean_get();
        $this->clean_post();
    }
    private function clean($input)
    {
        $text     = @preg_replace('%&\s*\{[^}]*(\}\s*;?|$)%', '', $input);
        $text     = @strip_tags($text);
        $text     = @htmlspecialchars($text, ENT_QUOTES);
        $text     = @preg_replace('/[<>]/', '', $text);
        $text     = @$this->sql->real_escape_string($text);
        if ($input!='select-region'){
            $badwords = array('input', 'union', 'script', 'select', 'update', 'script');
        }else{
            $badwords = array('input', 'union', 'script',  'update', 'script');
        }
        $text     = @str_replace($badwords, '', $text);
        return $text;
    }

    private function isi_clean($input)
    {
        $text = @$this->sql->real_escape_string(@$input);
        return $text;
    }

    private function clean_get()
    {
        if (isset($_GET)) {
            foreach ($_GET as $name => $value) {
                if (is_array($value)) {
                    foreach ($value as $name1 => $value1) {
                        if (in_array($name1, $this->fields)) {
                            $_GET[$name][$name1] = $this->isi_clean(@$value1);
                        } else {
                            $_GET[$name][$name1] = $this->clean(@$value1);
                        }
                    }
                } else {
                    if (in_array($name, $this->fields)) {
                        $_GET[$name] = $this->isi_clean(@$value);
                    } else {
                        $_GET[$name] = $this->clean(@$value);
                    }
                }
            }
        }
    }
    private function clean_post()
    {
        if (isset($_POST)) {
            foreach ($_POST as $name => $value) {
                if (is_array($value)) {
                    foreach ($value as $name1 => $value1) {
                        $_POST[$name][$name1] = $this->clean(@$value1);
                    }
                } else {
                    $_POST[$name] = $this->clean(@$value);
                }
            }
        }
    }
}

$ob = new application();
if (!$ob->check_admin()) {
    if (
        !strstr($_SERVER["REQUEST_URI"], 'handle-payment') && 
        !strstr($_SERVER["REQUEST_URI"], 'payment-failure') && 
        !strstr($_SERVER["REQUEST_URI"], 'payment-success')
    ){
        $clean = new CleanGetPost;    
    }
}






if (isset($_COOKIE["auto_login"]) && !isset($_SESSION["userid"])){
    $array = explode('|',$_COOKIE["auto_login"]);
    if (@$array[0]!='' && @$array[1]!=''){
        if ($api->Users->check_auth()==false){

            $sql = "select id from i_shop_users where (sha1(mail)='".$array[0]."' or sha1(phone)='".$array[0]."') ";
            $sql.= "and (password)='".$array[1]."' and active=1 limit 1";

            $select = $mysql->query($sql);
            if ($select && $select->num_rows==1){
                $result = $select->fetch_array();
                $_SESSION['userid'] = $result["id"];
            }
        }
    }
}


include_once ROOT . "/modules/Api.php";
include_once ROOT . "/modules/Helper.php";
include_once ROOT . "/modules/Module.php";
include_once ROOT . "/modules/Admin.php";
