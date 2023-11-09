<?php
class Wishlist extends Module{
    public $table = 'i_cat';
    public $tableE = 'i_cat_elements';
    public $link = 'wishlist';
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
    public function breadcrumb($title)
    {
        $breadcrumb = new breadcrumb();
        return $breadcrumb->init($this->table, $this->tableE, $this->link, $title);
    }
    public function init(){


        $idArray = $this->get_all();
        $sql = "select * from i_cat where active=1 and id in(".join(',', $idArray).")";
        $elements = $this->sql->get($sql);


    }


    public function count()
    {
        $idArray = $this->get_all();
        $sql = "select id from i_cat where active=1 and id in(".join(',', $idArray).")";
        $ids = A::$db->get($sql);
        return sizeof($ids);
    }

    
    public function get_all()
    {
        $elements_mass = explode(",", @$_COOKIE['wishlist']);
        $elements_mass = array_diff($elements_mass, array(''));
        return array_filter($elements_mass);
    }

    public function add($id, $count = 1, $params = '')
    {
        $id    = intval($id);
        $count = intval($count);

        $str = @$_COOKIE['wishlist'];

        @$str.= "$id,";

        setcookie("wishlist", $str, time() + 7776000, '/', str_replace('www.', '', '.'.$_SERVER["HTTP_HOST"]));
    }

    public function del($pos)
    {

        $elements_mass = explode(",", @$_COOKIE['wishlist']);

        foreach ($elements_mass as $key => $v) {

            if ($v != $pos) {
                @$new_content .= $v . ",";
            } else {
                $deleted = 1;
            }
        }

        setcookie("wishlist", $new_content, time() + 7776000, '/', str_replace('www.', '', '.'.$_SERVER["HTTP_HOST"]));
    }

    

    public function check($id)
    {
        $elements_mass = explode(",", @$_COOKIE['wishlist']);
        if (in_array($id, $elements_mass)) {
            return true;
        }

        return false;
    }

    public function clear()
    {
        setcookie("wishlist", $str, time() - 7776000, '/', str_replace('www.', '', '.'.$_SERVER["HTTP_HOST"]));
    }
    
}
