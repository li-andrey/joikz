<?php

class Comments extends Module
{
    public $table = 'i_comments';
    public $tableE = 'i_comments';
    
    
    public $link = 'comments';
    
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
        $this->path = realpath(dirname(__FILE__));
        $this->configLoad();
        
    }
    
    public function breadcrumb($title)
    {
        $breadcrumb = new breadcrumb();
        return $breadcrumb->init($this->table, $this->tableE, $this->link, $title);
    }
    
    public function init()
    {
    
    }
    
    public function add()
    {
        $data = date("Y-m-d H:i:s");
        $name = $_POST["name"];
        $mail = $_POST["email"];
        $text = $_POST["review"];
        $id = $_POST["idd"];
        $ids = $_POST["ids"];
        $module = $_POST["module"];
        if ($ids == 0) $ids = 0;
        
        $sql = "insert into i_comments set id_section='$ids', version='" . LANG . "', name='$name', ";
        $sql .= "mail='$mail', active=1, data='$data', com='$text', idd='$id', module='$module' ";
        
        
        $s = $this->sql->query($sql);
        if ($s) {
            echo json_encode(
                array(
                    'success' => 1,
                    'error' => '',
                    'msg' => 'Отзыв успешно отправлен',
                )
            );
        } else {
            echo json_encode(
                array(
                    'success' => 0,
                    'error' => 1,
                    'msg' => 'Ошибка отправки, попробуйте позднее',
                )
            );
        }
    }
    
    
    public function comments($id, $module)
    {
        $str = array();
        $num = 0;
        $sql = "select * from i_comments where idd='$id' and version='$this->lang' and id_section='0' and active=1 and module='$module' order by data asc";
        $res = $this->sql->get($sql);
        if (sizeof($res) > 0) {
            foreach ($res as $key => $value) {
                $data = array(
                    'com' => $value,
                    'num' => $num
                );
                $str[] = $this->viewJSON('element', $data);
                if ($this->replay($value["id"], $module)) {
                    $num = $num + 30;
                    $str[] = $this->subComments($id, $value["id"], $module, $num);
                    $num = $num - 30;
                }
            }
        }
        return join("\n", $str);
    }
    
    public function subComments($id, $ids, $module, $num)
    {
        $str = array();
        if ($num <= 150) {
            $sql = "select * from i_comments where idd='$id' and version='$this->lang' and id_section='$ids' and active=1 and module='$module' order by data asc";
            $res = $this->sql->get($sql);
            if (sizeof($res) > 0) {
                foreach ($res as $key => $value) {
                    $data = array(
                        'com' => $value,
                        'num' => $num
                    );
                    $str[] = $this->viewJSON('element', $data);
                    if ($this->replay($value["id"], $module)) {
                        $num = $num + 30;
                        $str[] = $this->subComments($id, $value["id"], $module, $num);
                        $num = $num - 30;
                    }
                }
            }
        }
        return join("\n", $str);
    }
    
    public function replay($id, $module)
    {
        $sql = "select * from i_comments where id_section=" . $id . " and version='" . $this->lang . "' and active=1";
        $res = A::$db->get($sql);
        if (sizeof($res) > 0)
            return true;
        else
            return false;
    }
    
    
    public function form($idd, $module)
    {
        $data = array(
            'idd' => $idd,
            'module' => $module,
        );
        return $this->viewJSON('form', $data);
    }
    
    public function count($id, $module)
    {
        $sql = "select count(id) as kol from " . $this->table . " ";
        $sql .= "where active=1 and idd='" . $id . "' and module='" . $module . "' group by idd";
        
        $res = A::$db->get($sql, 1);
        
        return intval(@$res["kol"]). ' комментар'.sklon(intval(@$res["kol"]), ["ий", "ия", "ев"]);
    }
    
    
    private function tree($id)
    {
        $ids = array($id);
        $cat = $this->categories($id);
        if (is_array($cat)) {
            foreach ($cat as $r) {
                $ids[] = $r["id"];
                $catSub = $this->categories($r["id"]);
                if (is_array($catSub)) {
                    foreach ($catSub as $rSub) {
                        $ids[] = $rSub["id"];
                        $catSub1 = $this->categories($rSub["id"]);
                        if (is_array($catSub1)) {
                            foreach ($catSub1 as $rSub1) {
                                $ids[] = $rSub1["id"];
                                $catSub2 = $this->categories($rSub1["id"]);
                                if (is_array($catSub2)) {
                                    foreach ($catSub2 as $rSub2) {
                                        $ids[] = $rSub2["id"];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $ids;
    }
    
    private function listSub($ids)
    {
        $cat = $this->categories($ids);
        if (is_array($cat) && sizeof($cat) > 0) {
            return $cat;
        } else {
            return array();
        }
        
    }
    
    private function filter()
    {
        $sql = array();
        return $sql;
    }
    
    private function perPage()
    {
        $perPage = $this->config["elements"];
        if (A::$app->get("show") != '') {
            $perPage = intval(A::$app->get("show"));
        }
        return $perPage;
    }
    
    private function orderBy()
    {
        return ' id desc ';
    }
}