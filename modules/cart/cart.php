<?php
class Cart extends Module
{
    public $table = 'i_cat';
    public $tableE = 'i_cat_elements';

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

    public function init()
    {
        $data = array(
            'meta' => array(
                'title' => 'Моя корзина',
                'h1' => 'Моя корзина',
                'descr' => '',
                'keyw' => '',
            ),
        );
        $this->view('basket', $data);
    }

    public function count()
    {
        $elements = json_decode(@$_COOKIE['userbasket'], true);

        $elementsTemp = array();


        $count = 0;
        if (sizeof($elements) > 0) {
            foreach (@$elements as $key => $value) {
                $item = $this->b($value["id"], true);

                if ($item) {
                    $params = explode("Цвет:", $value["params"]);
                    if (isset($params[1])) {
                        $color = explode(',', $params[1]);

                        if (isset($color[0])) {
                            $sql = "select * from i_cat where id_section='" . $item["id"] . "' and name='" . trim($color[0]) . "'  limit 1";
                            $color = A::$db->get($sql, 1);
                        }
                    }
                    $params = explode("Размер:", $value["params"]);

                    if (isset($params[1]) && isset($color["id"])) {
                        $size = trim($params[1]);

                        $sql = "select * from i_cat_elements where id_section ='" . $color["id"] . "' and size='" . $size . "' limit 1";
                        $size = A::$db->get($sql, 1);
                    }

                    if (isset($size["id"])) {
                        if ($size["active"] == 1) {
                            $count = $count + $value["count"];
                        }
                    } else {
                        $count = $count + $value["count"];
                    }
                }
            }
        }

        return $count;
    }


    public function sum()
    {
        $elements = json_decode(@$_COOKIE['userbasket'], true);

        $elementsTemp = array();

        $sum = 0;
        if (sizeof($elements) > 0)
            foreach (@$elements as $key => $value) {
                $item = $this->b($value["id"], true);
                if ($item) {
                    $amount  = $value["count"] * A::$api->price($item["price"]);
                    $sum = $sum + $amount;
                }
            }

        return $sum;
    }

    public function get_all()
    {
        $elements = json_decode(@$_COOKIE['userbasket'], true);

        $elementsTemp = array();


        $count = 0;
        if (sizeof($elements) > 0) {
            foreach (@$elements as $key => $value) {
                $item = $this->b($value["id"], true);

                if ($item) {
                    $params = explode("Цвет:", $value["params"]);
                    if (isset($params[1])) {
                        $color = explode(',', $params[1]);

                        if (isset($color[0])) {
                            $sql = "select * from i_cat where id_section='" . $item["id"] . "' and name='" . trim($color[0]) . "'  limit 1";
                            $color = A::$db->get($sql, 1);
                        }
                    }
                    $params = explode("Размер:", $value["params"]);

                    if (isset($params[1]) && isset($color["id"])) {
                        $size = trim($params[1]);

                        $sql = "select * from i_cat_elements where id_section ='" . $color["id"] . "' and size='" . $size . "' limit 1";
                        $size = A::$db->get($sql, 1);
                    }

                    if (isset($size["id"])) {
                        if ($size["active"] == 1) {
                            $elementsTemp[] = $value;
                        }
                    } else {
                        $elementsTemp[] = $value;
                    }
                }
            }
        }

        return $elementsTemp;
    }

    public function add($id, $count = 1, $params = array())
    {

        $elements = json_decode(@$_COOKIE['userbasket'], true);
        $elements[] = array(
            'id' => $id,
            'count' => $count,
            'params' => $params,
        );
        $str = json_encode($elements);

        setcookie("userbasket", $str, time() + 7776000, '/', str_replace('www.', '', '.' . $_SERVER["HTTP_HOST"]));
    }

    public function del($id, $params)
    {

        $elements = json_decode(@$_COOKIE['userbasket'], true);

        $elementsTemp = array();


        foreach ($elements as $key => $value) {
            if ($id != $value["id"]) {
                $elementsTemp[] = $value;
            } else {
                if ($params != $value["params"]) {
                    $elementsTemp[] = $value;
                }
            }
        }


        $str = json_encode($elementsTemp);
        setcookie("userbasket", $str, time() + 7776000, '/', str_replace('www.', '', '.' . $_SERVER["HTTP_HOST"]));
    }

    public function update($id, $count = '', $params = array())
    {
        $elements = json_decode(@$_COOKIE['userbasket'], true);

        $elementsTemp = array();

        foreach ($elements as $key => $value) {
            if ($id == $value["id"] && $params == $value["params"]) {
                $elementsTemp[] = array(
                    'id' => $id,
                    'count' => $count != '' ? $count : $value["count"],
                    'params' => $params,
                );
            } else {
                $elementsTemp[] = $value;
            }
        }

        $str = json_encode($elementsTemp);
        setcookie("userbasket", $str, time() + 7776000, '/', str_replace('www.', '', '.' . $_SERVER["HTTP_HOST"]));
    }

    public function check($id, $params)
    {
        $elements = json_decode(@$_COOKIE['userbasket'], true);
        if (is_array($elements))
            foreach ($elements as $key => $value) {
                if ($id == $value["id"] && $params == $value["params"]) {
                    return true;
                }
            }
        return false;
    }

    # Очистить корзину
    public function clear()
    {
        setcookie("userbasket", "", time() - 7776000, '/', str_replace('www.', '', '.' . $_SERVER["HTTP_HOST"]));
    }
}
