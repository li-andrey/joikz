<?php
class Settings extends Module{
    public $table = 'i_settings';
    public $tableE = 'i_settings_elements';

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

    public function v($id){
        $sql = "select value from ".$this->tableE." where id=$id and active=1 limit 1";
        $res = A::$db->get($sql);
        if (sizeof($res)==1){
            return $res[0]["value"];
        }else{
            return false;
        }
    }
}