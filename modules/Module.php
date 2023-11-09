<?

class Module extends Helper
{
    public $config;
    public $link = '';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function breadcrumb($title)
    {
    
    }
    
    public function element($where = array())
    {
        $id = @$this->request->url[sizeof($this->request->url) - 1];
        $sql = "select * from " . $this->tableE . " where active=1 and version='$this->lang' ";
        if (preg_match('/^\+?\d+$/', $id)) {
            $sql .= "and id = " . $id . " ";
        } else {
            $sql .= "and url = '" . $id . "' ";
        }
        $sql .= "" . (sizeof($where) > 0 ? " and " . join(" and ", $where) : "") . " order by id limit 1";
        
        
        $res = $this->sql->get($sql, 1);
        if (sizeof($res) > 0) {
    
            $res["furl"] = LINK.$this->link.'/'.$res["url"].'/';
    
            if (@$res["image"]!=''){
                $res["image"] = [
                    'o' => '/upload/images/'.$res["image"],
                    's' => '/upload/images/small/'.$res["image"],
                    'b' => '/upload/images/big/'.$res["image"],
                ];
            }
            for($i=1; $i<=10; $i++){
                if (@$res["image".$i]!=''){
                    $res["image".$i] = [
                        'o' => '/upload/images/'.$res["image".$i],
                        's' => '/upload/images/small/'.$res["image".$i],
                        'b' => '/upload/images/big/'.$res["image".$i],
                    ];
                }
            }
            
            return $res;
        } else {
            return false;
        }
    }
    
    public function category($id = '', $where = array())
    {
        $cat = array();
        $sql = "select * from " . $this->table . " where active=1 " . ($id != '' ? "and id=" . $id : " ") . " and (version='$this->lang' or version='all') ";
        $sql .= "" . (is_array($where) && sizeof($where) > 0 ? " and " . join(" and ", $where) : "") . " order by id_sort limit 1";
        
        $s = $this->sql->query($sql);
        if ($s && $s->num_rows > 0) {
            while ($r = $s->fetch_assoc()) {
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
                $cat = $r;
            }
        }
        
        return $cat;
    }
    
    
    public function categories($ids, $where = array(), $limit = '')
    {
        $cats = array();
        $sql = "select * from " . $this->table . " where active=1 and id_section=" . $ids . " and (version='$this->lang' or version='all') ";
        $sql .= "" . (is_array($where) && sizeof($where) > 0 ? " and " . join(" and ", $where) : "") . " order by id_sort ";
        
        if ($limit != '') {
            $sql .= "limit " . $limit;
        }
        $s = $this->sql->query($sql);
        
        if ($s && $s->num_rows > 0) {
            while ($r = $s->fetch_assoc()) {
    
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
                
                $cats[] = $r;
            }
        }
        return $cats;
    }
    
    
    public function elements($ids, $where = array(), $limit = '')
    {
        $elements = array();
        if ($this->tableE == 'i_news_elements') {
            $id_sort = ' data desc ';
        } else if ($this->tableE == 'i_payments') {
            $this->tableE = 'i_shop_elements';
            $id_sort = ' id_sort ';
        } else if ($this->tableE == 'i_gallery_elements') {
            $id_sort = ' id desc ';
        } else {
            $id_sort = ' id_sort ';
        }
        $sql = "select * from " . $this->tableE . " where active=1 and id_section=" . $ids . " and version='$this->lang' ";
        $sql .= "" . (is_array($where) && sizeof($where) > 0 ? " and " . join(" and ", $where) : "") . " order by $id_sort ";
        if ($limit != '') {
            $sql .= "limit " . $limit;
        }
        
        
        $s = $this->sql->query($sql);
        if ($s && $s->num_rows > 0) {
            while ($r = $s->fetch_assoc()) {
               
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
        
        return $elements;
    }
    
    public function e($slug, $id = false, $where = array())
    {
        $sql = "select * from " . $this->tableE . " where active=1 and version='$this->lang' ";
        if ($id) {
            $sql .= "and id = " . $slug . " ";
        } else {
            $sql .= "and slug = " . $slug . " ";
        }
        $sql .= "" . (sizeof($where) > 0 ? " and " . join(" and ", $where) : "") . " order by id limit 1";
        
        
        $res = $this->sql->get($sql, 1);
        
        if (@$res["id"] > 0) {
    
            $res["furl"] = LINK.$this->link.'/'.$res["url"].'/';
    
            if (@$res["image"]!=''){
                $res["image"] = [
                    'o' => '/upload/images/'.$res["image"],
                    's' => '/upload/images/small/'.$res["image"],
                    'b' => '/upload/images/big/'.$res["image"],
                ];
            }
            for($i=1; $i<=10; $i++){
                if (@$res["image".$i]!=''){
                    $res["image".$i] = [
                        'o' => '/upload/images/'.$res["image".$i],
                        's' => '/upload/images/small/'.$res["image".$i],
                        'b' => '/upload/images/big/'.$res["image".$i],
                    ];
                }
            }
            
            return $res;
        } else {
            return false;
        }
    }
    
    public function b($slug, $id = false, $where = array())
    {
        $sql = "select * from " . $this->table . " where active=1 and version='$this->lang' ";
        if ($id) {
            $sql .= "and id = " . $slug . " ";
        } else {
            $sql .= "and slug = " . $slug . " ";
        }
        $sql .= "" . (sizeof($where) > 0 ? " and " . join(" and ", $where) : "") . " order by id limit 1";
        
        $res = $this->sql->get($sql, 1);
        
        if (@$res["id"] > 0) {
    
            $res["furl"] = LINK.$this->link.'/'.$res["url"].'/';
    
            if (@$res["image"]!=''){
                $res["image"] = [
                    'o' => '/upload/images/'.$res["image"],
                    's' => '/upload/images/small/'.$res["image"],
                    'b' => '/upload/images/big/'.$res["image"],
                ];
            }
            for($i=1; $i<=10; $i++){
                if (@$res["image".$i]!=''){
                    $res["image".$i] = [
                        'o' => '/upload/images/'.$res["image".$i],
                        's' => '/upload/images/small/'.$res["image".$i],
                        'b' => '/upload/images/big/'.$res["image".$i],
                    ];
                }
            }
            
            return $res;
        } else {
            return false;
        }
    }
    
    public function bs($slug, $e = false, $limit = '', $where = array())
    {
        $sql = "select * from " . $this->table . " where active=1 and version='$this->lang' ";
        $sql .= "and slug = " . $slug . " ";
        $sql .= "" . (sizeof($where) > 0 ? " and " . join(" and ", $where) : "") . " order by id limit 1";
        $res = $this->sql->get($sql);
        if (sizeof($res) > 0) {
            
            if ($e) {
                return $this->elements($res[0]['id'], $where, $limit);
            } else {
                return $this->categories($res[0]['id'], $where, $limit);
            }
            
        } else {
            return false;
        }
    }
    
    public function bb($slug, $e = false, $limit = '', $where = array())
    {
        if ($e) {
            return $this->elements($slug, $where, $limit);
        } else {
            return $this->categories($slug, $where, $limit);
        }
    }
    
    public function w($id, $params = [])
    {
        $s = A::$db->query("select * from " . $this->table . " where slug='" . $id . "' and version='" . A::$app->lang() . "' limit 1");
        
        if ($s && $s->num_rows == 1) {
            $r = $s->fetch_assoc();
    
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
            
            if (@$r["active"] == 1) {
                if (isset($params["f"]) && $params["f"] != '') {
                    return @$r[$params["f"]];
                } else {
                    if (isset($params["r"]) && $params["r"] != '') {
                        $r["text"] = str_replace(['<' . $params["r"] . '>', '</' . $params["r"] . '>'], '', $r);
                        return $r["text"];
                    } else {
                        return $r;
                    }
                }
            } else {
                return [];
            }
            
        }
        return [];
    }
    
    public function we($id, $params = [])
    {
        $s = A::$db->query("select * from " . $this->tableE . " where slug='" . $id . "' and version='" . A::$app->lang() . "' limit 1");
        
        if ($s && $s->num_rows == 1) {
            $r = $s->fetch_assoc();
    
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
            
            if (@$r["active"] == 1) {
                if (isset($params["f"]) && $params["f"] != '') {
                    if (isset($params["r"]) && $params["r"] != '') {
                        $r[$params["f"]] = str_replace(['<' . $params["r"] . '>', '</' . $params["r"] . '>'], '', $r[$params["f"]]);
                    }
                    
                    if ($params["f"] == 'image') {
                        if (@$r[$params["f"]]["o"] != '') {
                            return  @$r[$params["f"]]["o"];
                        } else {
                            return '/upload/js/fancybox/blank.gif';
                        }
                    }
                    
                    return @$r[$params["f"]];
                    
                } else {
                    if (isset($params["r"]) && $params["r"] != '') {
                        $r["text"] = str_replace(['<' . $params["r"] . '>', '</' . $params["r"] . '>'], '', $r["text"]);
                        return $r["text"];
                    } else {
                        return $r;
                    }
                }
            }
        }
        return;
    }
    
    
    public function m($id, $params = [])
    {
        $sql = "select * from " . $this->table . " where slug='" . $id . "' and version='" . A::$app->lang() . "' limit 1";
        $s = A::$db->query($sql);
        if ($s && $s->num_rows == 1) {
            $r = $s->fetch_assoc();
    
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
            
            $r['e'] = [];
            $r['b'] = [];
           
            
            if (@$r["active"] == 1) {
                
                $r["e"] = $this->elements($r["id"], @$params["where"], @$params["limit"]);
                $r["b"] = $this->categories($r["id"], @$params["where"], @$params["limit"]);
                
                return $r;
            }
        }
        return false;
    }

    public function all($ids, $params = [])
    {
        $elements = array();
        if ($this->tableE == 'i_news_elements') {
            $id_sort = ' data desc ';
        } else if ($this->tableE == 'i_payments') {
            $this->tableE = 'i_shop_elements';
            $id_sort = ' id_sort ';
        } else if ($this->tableE == 'i_gallery_elements') {
            $id_sort = ' id desc ';
        } else {
            $id_sort = ' id_sort ';
        }
        
        $tree = $this->tree($ids);
        
        $sql = "select * from " . $this->tableE . " where active=1 and id_section in (" . join(',', $tree) . ") ";
        $sql .="and version='$this->lang' ";
        $sql .= "" . (is_array(@$params["where"]) && sizeof($params["where"]) > 0 ? " and " . join(" and ", $params["where"]) : "") . " order by $id_sort ";
        if (@$params["limit"] != '') {
            $sql .= "limit " . $params["limit"];
        }
        
        $s = $this->sql->query($sql);
        if ($s && $s->num_rows > 0) {
            while ($r = $s->fetch_assoc()) {
                
                $r["furl"] = LINK.$this->link.'/'.$r["url"].'/';
                
                if (@$r["image"]!=''){
                    $r["image"] = [
                        'o' => '/upload/images/'.$r["image"],
                        's' => '/upload/images/small/'.$r["image"],
                        'b' => '/upload/images/big/'.$r["image"],
                    ];
                }else{
                    $r["image"] = [
                        'o' => '/upload/noimagein.gif',
                        's' => '/upload/noimagein.gif',
                        'b' => '/upload/noimagein.gif',
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
        
        return $elements;
    }
    
    public function setData($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getData()
    {
        return $this->data;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function success($msg)
    {
        $this->setSuccess(1);
        $this->setData(['msg' => $msg]);
        $this->response();
    }

    public function error($msg)
    {
        $this->setSuccess(0);
        $this->setData(['msg' => $msg]);
        $this->response();
    }

    public function response()
    {
        $result = [
            'success' => $this->getSuccess(),
            'result' => $this->getData(),
        ];

        echo json_encode($result);
    }
    
    public function t($name)
    {
        $langFile = $this->path . '/lang/' . $this->lang . '.ini';
        $ini_array = parse_ini_file($langFile);
        return isset($ini_array[$name]) ? $ini_array[$name] : $name;
    }
    
    public function configLoad()
    {
        $configFile = $this->path . '/config/config.ini';
        $ini_array = parse_ini_file($configFile);
        $this->config = $ini_array;
    }

    protected function before()
    {
    }

    protected function after()
    {
    }
}
