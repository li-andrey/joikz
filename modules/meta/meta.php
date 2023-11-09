<?php
class Meta extends Module{
	public $table = 'i_meta';
	public $tableE = 'i_meta';
	
	private static $_instance;

    public static function getInstance($request) 
    {
        if(!self::$_instance) { 
            self::$_instance = new self($request);
        }
        return self::$_instance;
    }
	
	public function __construct($request)
	{
		parent::__construct($request);
		$this->path =  realpath(dirname(__FILE__));
		$this->configLoad();
	}

    public function seo(){
        $url = A::$app->path();
        $sql = "select * from ".$this->table." where page='$url' and active=1 and version='".$this->lang."' limit 1";
        $res = $this->sql->get($sql, 1);

        return $res;
    }
}
?>