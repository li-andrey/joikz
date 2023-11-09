<?
include_once($_SERVER['DOCUMENT_ROOT']."/modules/Module.php");

class Shop extends Module{
	public $table = 'i_shop';
	public $tableE = 'i_shop_elements';
	public $link = 'shop';


	private static $_instance;

	public static function getInstance()
	{
		if (!self::$_instance) {
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

	public function breadcrumb($title)
	{
	    $breadcrumb = new breadcrumb();
	    return $breadcrumb->init($this->table, $this->tableE, $this->link, $title);
	}

	public function init()
	{
	    if (@A::$url->url[1] != '') {
	        $method = A::$url->url[1];

	        if (method_exists($this, $method.'Action')) {
	            if ($this->before() !== false) {
	                call_user_func_array([$this, $method.'Action'], []);
	                $this->after();
	            }
	        } else {
	            $this->page404(array());
	            exit;
	        }
	    }
	}

	public function script()
	{
		$lang = $this->lang;
		include_once($this->path.'/assets/js/shop.php');
	}
	public function css()
	{
		$lang = $this->lang;
		include_once($this->path.'/assets/css/style.php');
	}

	public function loginForm()
	{
		$data = array();
		return $this->viewJSON('login_form', $data);
	}

	public function registerForm()
	{
		$data = array();
		return $this->viewJSON('register_form', $data);
	}

	public function forgotForm()
	{
		$data = array();
		return $this->viewJSON('forgot_form', $data);
	}

	public function addForm()
	{
		$data = array();
		return $this->viewJSON('add_form', $data);
	}

	public function cartAction()
	{
	    $data = [];
	    $path = '';
	    $view = 'cart';

	    $this->data["meta"] = [
	        'title' => 'Корзина',
	        'h1' => 'Корзина',
	        'description' => '',
	        'keywords' => '',
	    ];

	    $this->view($view, $this->data, $path);
	}

	public function orderAction()
	{
	    $data = [];
	    $path = '';
	    $view = 'order';

	    $this->data["meta"] = [
	        'title' => 'Оформление заказа',
	        'h1' => 'Оформление заказа',
	        'description' => '',
	        'keywords' => '',
	    ];

	    $this->view($view, $this->data, $path);
	}

}