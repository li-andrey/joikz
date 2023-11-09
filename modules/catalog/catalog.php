<?php

class Catalog extends Module
{

    public $table = 'i_cat';
    public $tableE = 'i_cat_elements';
    
    
    public $link = 'catalog';
    
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
        if ($this->aliasModule(str_replace('i_', '', $this->table), $this->link) == 'module') {
            $this->listBlock();
        } else if ($this->aliasModule(str_replace('i_', '', $this->tableE), $this->link)) {
            $this->listElement();
        } else {
            $this->page404(array());
        }
    }
    
    
    public function listBlock()
    {

        $id_block = 1;
        $e = $this->request->url[sizeof($this->request->url) - 1];
        if ($e == $this->link) {
            $this->listElements($id_block);
        } else {
            $block = $this->category('', array(" url='$e' "));
            $this->listElements($block["id"]);
        }
    }
    
    public function listElement()
    {
        $e = $this->request->url[sizeof($this->request->url) - 1];
        $element = $this->category('', ["url = '".$e."'"  ]);

        $sql = "select * from i_cat where id in (select id_cat from i_shop_cat where id_shop='".$element["id"]."')";
        $cat = A::$db->get($sql);

        $cats = [];
        foreach($cat as $r){
            $cats[] = $r["id"];
        }

        $sql = "select * from i_cat where id_section=2 and active=1 and id in (select id_shop from i_shop_cat where id_cat in (".join(',',$cats).")) and id!='".$element["id"]."' limit 4";
        $other = A::$db->get($sql);


        $block = $this->category(@$element["id_section"]);
        
        
        $data = array(
            'meta' => array(
                'title' => $element["title"] != '' ? $element["title"] : $element["name"],
                'h1' => $element["name"],
                'descr' => $element["descr"],
                'keyw' => $element["keyw"]
            ),
            'e' => $element,
            'block' => $block,
            'other' => $other,

        );
        
        
        $this->view('element', $data);
        
        
        if (!in_array($element["id"], @$_SESSION["views"])) {
            @$_SESSION["views"][] = $element["id"];
            
            $sql = "update i_cat_elements set views=views+1 where id='" . $element["id"] . "'";
            A::$db->query($sql);
        }
        
    }
    
    
    public function listElements($ids)
    {
        $block = $this->category($ids);

        if ($block["id_section"] == 2) {
            $this->listElement($ids);
            exit;
        }

        $subblock = $this->categories($ids);
        $tree = $this->tree(@$block["id"]);
        
        if (@A::$app->get('ids') != '') {
            $tree = $this->tree(A::$app->get('ids'));
        }
        
        
        $pagination = new paginator(
            $this->lang,
            A::$app->get("p"),
            @$this->perPage()
        );
        
        $sql_array = array();
        
        if (@A::$app->get('search') != '') {
            $sql_array[] = " (a.name like '%" . A::$app->get('search') . "%' or a.anounce like '%" . A::$app->get('search') . "%' or a.text like '%" . A::$app->get('search') . "%') ";
        }
        
        
        if (A::$app->get('price_from') > 0) {
            $sql_array[] = " a.price >= " . intval(A::$app->get('price_from')) . "";
        }
        
        if (A::$app->get('price_to') > 0) {
            $sql_array[] = " a.price <= " . intval(A::$app->get('price_to')) . "";
        }
        

        $filter = [];
        
        if (isset(A::$url->get["size"])) {

            $sizeArray = array();
            if (is_array(A::$url->get["size"])) {
                foreach (A::$url->get["size"] as $key => $v) {

                    $sizeArray[] = "'" . str_replace('*', '&', $v) . "'";

                }
            } else {
                $sizeArray[] = "'" . str_replace('*', '&', A::$url->get["size"]) . "'";
            }


            $ido = array();
            $sql = "select id_section from i_cat_elements where active=1 and size in (" . join(',', $sizeArray) . ") group by id";

            $res = A::$db->get($sql);
            foreach ($res as $key => $v) {
                $sql1 = "select id_section from i_cat where active=1 and id = '".$v["id_section"]."' group by id";
                $res1 = A::$db->get($sql1);
                foreach ($res1 as $key1 => $v1) {
                    $ido[] = $v1["id_section"];
                }
            }
            $sql_array[] = " a.id in (" . join(', ', $ido) . ") ";

            $filter["size"] = " a.id in (" . join(', ', $ido) . ") ";
        }

        if (isset(A::$url->get["color"])) {

            $colorArray = array();
            if (is_array(A::$url->get["color"])) {
                foreach (A::$url->get["color"] as $key => $v) {

                    $colorArray[] = "'" . str_replace('*', '&', $v) . "'";

                }
            } else {
                $colorArray[] = "'" . str_replace('*', '&', A::$url->get["color"]) . "'";
            }


            $ido = array();
            $sql = "select id_section from i_cat where active=1 and color in (" . join(',', $colorArray) . ") group by id";
            $res = A::$db->get($sql);
            foreach ($res as $key => $v) {
                $ido[] = $v["id_section"];
            }
            $sql_array[] = " a.id in (" . join(', ', $ido) . ") ";

            $filter["color"] = " a.id in (" . join(', ', $ido) . ") ";
        }

        if (isset(A::$url->get["brand"])) {
            if (sizeof(A::$url->get["brand"]) > 0) {
                $sql_array[] = " a.brand in (" . join(',', A::$url->get["brand"]) . ") ";

                $filter["brand"] = " a.brand in (" . join(',', A::$url->get["brand"]) . ") ";
            }
        }

        if (isset(A::$url->get["material"])) {
            if (sizeof(A::$url->get["material"]) > 0) {
                $sql_array[] = " a.material in (" . join(',', A::$url->get["material"]) . ") ";
                $filter["material"] = " a.material in (" . join(',', A::$url->get["material"]) . ") ";
            }
        }

        $sql = "SELECT a.* FROM " . $this->table . " a left join i_shop_cat d on a.id=d.id_shop WHERE d.id_cat IN (" . join(',', $tree) . ") ";
        $sql .= "" . (sizeof(@$sql_array) > 0 ? " AND " . (join(" AND ", $sql_array)) . "" : '') . " ";
        $sql .= "AND a.`version`='$this->lang' AND a.active=1 and a.id_section=2 group by a.id ";
        $sql .= "ORDER BY " . $this->orderBy();


        $pagination->setSQL($sql);
        $pagination->setPaginator('p');
        $query = $this->sql->query($pagination->getSQL());

        $elements = array();
        if ($query && $query->num_rows > 0) {
            while ($r = $query->fetch_assoc()) {

                $r["furl"] = LINK.$this->link.'/'.$r["url"].'/';

                if (@$r["image"]!=''){
                    $r["image"] = [
                        'o' => '/upload/images/'.$r["image"],
                        's' => '/upload/images/small/'.$r["image"],
                        'b' => '/upload/images/big/'.$r["image"],
                    ];
                }
                for($i=1; $i<=10; $i++){
                    if (@$r["image".$i]!=''){
                        $r["image".$i] = [
                            'o' => '/upload/images/'.$r["image".$i],
                            's' => '/upload/images/small/'.$r["image".$i],
                            'b' => '/upload/images/big/'.$r["image".$i],
                        ];
                    }
                }
                
                $elements[] = $r;
            }
        }
        
        if (!isset(A::$url->get["type"])) A::$url->get["type"] = 'grid';
        

        $color = [];
        $colorFilter = $filter;
        unset($colorFilter["color"]);
        $sql = "select color from i_cat where active=1 and id_section in (";
        $sql .= "SELECT a.id FROM " . $this->table . " a left join i_shop_cat d on a.id=d.id_shop WHERE d.id_cat IN (" . join(',', $tree) . ") ";
        $sql .= "" . (sizeof(@$colorFilter) > 0 ? " AND " . (join(" AND ", $colorFilter)) . "" : '') . " ";
        $sql .= "AND a.`version`='$this->lang' AND a.active=1 and a.id_section=2 group by a.id ";
        $sql .= ") group by color ";
        $sql .= "ORDER BY color asc";

        $color = A::$db->get($sql);

        $brands = [];
        $brandsFilter = $filter;
        unset($brandsFilter["brand"]);
        $sql = "select id, name from i_block_elements where active=1 and id in (";
        $sql .= "SELECT a.brand FROM " . $this->table . " a left join i_shop_cat d on a.id=d.id_shop WHERE d.id_cat IN (" . join(',', $tree) . ") ";
        $sql .= "" . (sizeof(@$brandsFilter) > 0 ? " AND " . (join(" AND ", $brandsFilter)) . "" : '') . " ";
        $sql .= "AND a.`version`='$this->lang' AND a.active=1 and a.id_section=2 group by a.id ";
        $sql .= ") group by name ";
        $sql .= "ORDER BY name asc";

        $brands = A::$db->get($sql);

        $material = [];
        $materialFilter = $filter;
        unset($colorFilter["material"]);
        $sql = "select id, name from i_block_elements where active=1 and id in (";
        $sql .= "SELECT a.material FROM " . $this->table . " a left join i_shop_cat d on a.id=d.id_shop WHERE d.id_cat IN (" . join(',', $tree) . ") ";
        $sql .= "" . (sizeof(@$materialFilter) > 0 ? " AND " . (join(" AND ", $materialFilter)) . "" : '') . " ";
        $sql .= "AND a.`version`='$this->lang' AND a.active=1 and a.id_section=2 group by a.id ";
        $sql .= ") group by name ";
        $sql .= "ORDER BY name asc";

        $material = A::$db->get($sql);


        $size = [];
        $sizelFilter = $filter;
        unset($sizelFilter["size"]);
        $sql = "select id, size from i_cat_elements where active=1 and id_section in ( select id from i_cat where active=1 and id_section in (";
        $sql .= "SELECT a.id FROM " . $this->table . " a left join i_shop_cat d on a.id=d.id_shop WHERE d.id_cat IN (" . join(',', $tree) . ") ";
        $sql .= "" . (sizeof(@$sizelFilter) > 0 ? " AND " . (join(" AND ", $sizelFilter)) . "" : '') . " ";
        $sql .= "AND a.`version`='$this->lang' AND a.active=1 and a.id_section=2 group by a.id ";
        $sql .= ")) group by size ";
        $sql .= "ORDER BY size asc";

        $size = A::$db->get($sql);


        
        $data = array(
            'meta' => array(
                'title' => $block["title"] != '' ? $block["title"] : $block["name"],
                'h1' => $block["name"],
                'descr' => $block["descr"],
                'keyw' => $block["keyw"]
            ),
            'block' => $block,
            'color' => $color,
            'brands' => $brands,
            'material' => $material,
            'size' => $size,
            'subblock' => $subblock,
            'tree' => $tree,
            'elements' => $elements,
            'pagination' => $pagination,
        );
        
        
        $this->view('elements', $data);
    }
    
    
    public function parent($id)
    {

        $sql = "select * from " . $this->table . " where id=$id and active=1 and version='" . $this->lang . "' limit 1";
        $res = $this->sql->get($sql, 1);
        
        
        if (@$res["id_section"] != 0) {

            $sql = "select * from " . $this->table . " where id=" . $res["id_section"] . " and active=1 and version='" . $this->lang . "' limit 1";
            $res = $this->sql->get($sql, 1);
            
            if ($res["id_section"] != 0) {

                $sql = "select * from " . $this->table . " where id=" . $res["id_section"] . " and active=1 and version='" . $this->lang . "' limit 1";
                $res = $this->sql->get($sql, 1);
                if ($res["id_section"] != '0') {

                    $sql = "select * from " . $this->table . " where id=" . $res["id_section"] . " and active=1 and version='" . $this->lang . "' limit 1";
                    $res = $this->sql->get($sql, 1);
                    
                    return $res["id"];
                    
                } else {
                    return $res["id"];
                }
                
            } else {
                return $res["id"];
            }
        } else {
            return @$res["id"];
        }
    }
    
    
    public function tree($id)
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
        $perPage = 1000;
        
        if (A::$app->get("number") != '') {
            $perPage = intval(A::$app->get("number"));
        } else {
            A::$url->get["number"] = $perPage;
        }
        return $perPage;
    }
    
    private function orderBy()
    {
        if (@A::$app->get('order') != '') {
            return ' ' . @A::$app->get('order');
        } else {
            return ' id_sort asc ';
        }
    }
    
}