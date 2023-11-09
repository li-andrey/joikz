<?php
class Mail extends Module{
	public $table = 'i_mail';
	public $tableE = 'i_mail_settings';
	
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