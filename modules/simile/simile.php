<?php
class Simile extends Module{
    public $table = 'i_cat';
    public $tableE = 'i_cat_elements';

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


        $idArray = $this->get_all();
        $sql = "select * from i_cat_elements where active=1 and id in(".join(',', $idArray).")";
        $elements = $this->sql->get($sql);


        $data = array(
            'meta' => array(
                'title'=> 'Сравнение товаров', 
                'h1'=> 'Сравнение товаров', 
                'descr' => '', 
                'keyw' => '',
            ),
            'elements' => $elements,
        );

        $this->view('elements', $data);
    }


    public function count()
    {
        $idArray = $this->get_all();
        $sql = "select id from i_cat_elements where active=1 and id in(".join(',', $idArray).")";
        $ids = A::$db->get($sql);
        return sizeof($ids);
    }

    
    public function get_all()
    {
        $elements_mass = explode(",", @$_COOKIE['compare']);
        $elements_mass = array_diff($elements_mass, array(''));
        return array_filter($elements_mass);
    }

    public function add($id, $count = 1, $params = '')
    {
        $id    = intval($id);
        $count = intval($count);

        $str = @$_COOKIE['compare'];

        @$str.= "$id,";

        setcookie("compare", $str, time() + 7776000, '/', str_replace('www.', '', '.'.$_SERVER["HTTP_HOST"]));
    }

    public function del($pos)
    {

        $elements_mass = explode(",", @$_COOKIE['compare']);

        foreach ($elements_mass as $key => $v) {

            if ($v != $pos) {
                @$new_content .= $v . ",";
            } else {
                $deleted = 1;
            }
        }

        setcookie("compare", $new_content, time() + 7776000, '/', str_replace('www.', '', '.'.$_SERVER["HTTP_HOST"]));
    }

    

    public function check($id)
    {
        $elements_mass = explode(",", @$_COOKIE['compare']);

        if (in_array($id, $elements_mass)) {
            return true;
        }

        return false;
    }

    public function clear()
    {
        setcookie("compare", $str, time() - 7776000, '/', str_replace('www.', '', '.'.$_SERVER["HTTP_HOST"]));
    }
    
}
