<?php
class A 
{
    public static $app;
    public static $api;
    public static $db;
    public static $url;
    public static $page;
}

class Admin
{
    protected $data = [];
    protected $error;
    protected $success;

    private static $_instance;
    public static $classes = array(
        'catalog',
        'project',
        'page',
        'info',
        'breadcrumb',
        'menu',
        'news',
        'guest',
        'faq',
        'comments',
        'gallery',
        'video',
        'paginator',
        'shop',
        'slider',
        'meta',
        'actions',
        'redirect',
        'wishlist',
        'simile',
        'articles',
        'cart',
        'user',
        'settings',
    );

    public static function getInstance() 
    {
        if(!self::$_instance) { 
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __get( $property )
    {
        $request = Request::getInstance();
        $module = $property;
        $class = $module::getInstance($request);
        return $class;
    }

    public static function autoload($className)
    {
        if (in_array($className, self::$classes)) {
            $classFile = dirName(__FILE__).'/'.$className.'/'.$className.'.php';
        } else {
            return;
        }
        include($classFile);
    }

    public function mb_ucfirst($str, $enc = 'utf-8')
    {
        return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc) . mb_substr($str, 1, mb_strlen($str, $enc), $enc);
    }

    public function classes()
    {
        return self::$classes;
    }

    public function link()
    {
        if (A::$url->lang == A::$url->defultVersion){
            return '/';
        }else{
            return '/'.A::$url->lang.'/';
        }
    }

    public function url()
    {
        return A::$url->url;
    }

    public function get($name='')
    {
        return @A::$url->get[$name];
    }

    public function path()
    {
        return str_replace('//', '/', '/'.join('/',A::$url->url).'/');
    }
    

    public function main()
    {
        return str_replace('//', '/',str_replace('/ru/', '/', '/'.A::$url->lang.'/').$this->path());
    }

    public function lang()
    {
        return A::$url->lang;
    }

    public function viewJSON($view, $data)
    {
        global $ob, $api;
        $array = array(
            'ob' => $ob,
            'api' => $api,
        );
        extract(array_merge($data, $array), EXTR_PREFIX_SAME, 'i');
        ob_start();
        include  $view . '.php';
        $html = ob_get_clean();
        return $html;
    }

    
    public function wf($id, $params = [])
    {
        return A::$app->info->we($id, $params);
    }

    public function counts($table, $where="", $what='*')
    {
        $s = A::$db->query("SELECT COUNT(".$what.") as kol FROM ".$table." ".$where);
        $r = $s->fetch_array();
        return $r["kol"]; 
    }

    public function show()
    {
        $request = Request::getInstance();
        if (in_array(@$request->url[0], self::$classes)){
            $module = $request->url[0]::getInstance();
            $module->init();
        }else{
            $page = page::getInstance();
            $page->init();
        }
    }

    public function breadcrumb($title)
    {
        if (in_array(@$this->url()[0], self::$classes)){
            $module = $this->url()[0];
            $breadcrumb = A::$app->{$module}->breadcrumb($title);
        }else{
            $breadcrumb =  A::$app->page->breadcrumb($title);
        }

        return $breadcrumb;
    }

    public function t($name, $module='')
    {
        $langFile = $_SERVER["DOCUMENT_ROOT"].'/modules/'.($module==''?'page':$module).'/lang/'.LANG.'.ini';
        $ini_array = @parse_ini_file($langFile);
        return isset($ini_array[$name]) ? $ini_array[$name] : $name;
    }

     function css(){
        include(TEMPLATE.'/css.php');
    }

    function script(){
        include(TEMPLATE.'/script.php');
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
}
class ML{
    function __construct()
    {

    }

    public static function msg($id)
    {
        
        $sql = "select name, title, text, email, doobel from i_mail where id=" . $id . " limit 1";
        $s = A::$db->query($sql);
        if ($s && $s->num_rows == 1) {
            $r = $s->fetch_assoc();
            return $r;
        }
        return '';
    }
    
    public static function param($value){
        $sql = "select value from i_mail_settings where name='" . $value . "' limit 1";
        $res = A::$db->get($sql, 1);
        if (@$res["value"] != '') {
            return str_replace('{{site}}', $_SERVER["HTTP_HOST"], $res["value"]);
        }
        return '';
    }
    
    public static function s($to, $title, $msg, $from = '', $admin)
    {

        $headers  = "Content-type: text/html; charset=utf-8\n";
        if ($from==''){
            $configFrom = self::param('E-mail отправителя');
            $headers .= "From: ".$configFrom." ";
            $headers .= "<".$configFrom.">\n";
        }else{ 
            $headers .= "From: ".$from." ";
            $headers .= "<".$from.">\n";
        }
        if (is_array($to)){
            foreach ($to as $t){
                $s = mail($t, self::mime($title), $msg, $headers);
            }
            if ($s){
                return true;
            }else{
                return false;
            }
        }else{

            if (mail($to, self::mime($title), $msg, $headers)){
                if ($admin==1){
                    $configEmail = self::param('E-mail администратора');
                    mail($configEmail, self::mime($title), $msg, $headers);
                }
                return true;
            }else{
                return false;
            }
        }
    }
    
    public static function mime($str, $data_charset = 'utf-8', $send_charset = 'utf-8')
    {
        if ($data_charset != $send_charset) {
            $str = iconv($data_charset, $send_charset, $str);
        }
        return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
    }
}
spl_autoload_register(['Admin', 'autoload'], true, true);

A::$app = Admin::getInstance();
A::$api = Api::getInstance();
A::$db = $mysql;
A::$url = Request::getInstance();

define("LANG", A::$url->lang);
define("LINK", A::$app->link());