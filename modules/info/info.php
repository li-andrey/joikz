<?php
class Info extends Module{
	public $table = 'i_block';
	public $tableE = 'i_block_elements';
	
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
}
?>