<?php
class Redirect extends Module{
    public $table = 'i_redirect';
    public $tableE = 'i_redirect';

    private static $_instance;

    public static function getInstance() 
    {
        if(!self::$_instance) { 
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
        $this->path =  realpath(dirname(__FILE__));
        $this->configLoad();
    }

    public function init(){
        $url = $this->sql->real_escape_string($_SERVER["REQUEST_URI"]);
        $sql = "select * from ".$this->table." where page='$url' and active=1 and version='".$this->lang."' limit 1";
        $res = $this->sql->get($sql, 1);
        if (@$res["pageto"]!=''){
            header("HTTP/1.1 301 Moved Permanently");
            header('Location: '.$res["pageto"]);
            exit();
        }
    }
}
