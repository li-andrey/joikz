<?php
class Vote extends Module{
	public $table = 'i_vote';
	public $tableE = 'i_vote_elements';
	
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
	
	function bxslider($id)
	{
		$banners = $this->elements($id);
		$data = array(
			'banners' => $banners
		);
		return $this->viewJSON('bxslider', $data);
	}
	
	function slider($id)
	{
		$cat = $this->category($id);

		if (@$cat["active"]==1){
			$banners = $this->elements($id);
			$data = array(
				'banners' => $banners
			);
			return $this->viewJSON('slider', $data);
		}
	}
	
	
	function carousel($id)
	{
		$banners = $this->elements( $id);
		$data = array(
			'banners' => $banners
		);
		return $this->viewJSON('carousel', $data);
	}
	
}
?>