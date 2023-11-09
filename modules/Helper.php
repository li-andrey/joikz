<?php
class Helper extends Query
{
    public $lang;
    public $uri;
    public $title;
    public $h1;
    public $description;
    public $keywords;
    public $templateName;
    public $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = A::$url;
        $this->lang = $this->request->lang;
        $this->template();
    }

    public function template()
    {
        $select_template = $this->sql->query("select * from i_template where active=1 and version='$this->lang'");
        if ($select_template && $select_template->num_rows==1){
            $res_template = $select_template->fetch_array();
        }else{
            $select_template = $this->sql->query("select * from i_template where active=1 order by id asc");
            if ($select_template && $select_template->num_rows==1){
                $res_template = $select_template->fetch_array();
            }else{
                die('Нет шаблона');
            }
        }
        
        $select_template->close();


        if ($this->title==''){
            $this->title = $res_template['title'];
        }
        if ($this->title==''){
            $this->h1 = $res_template['title'];
        }
        if ($this->description==''){
            $this->description = $res_template['description'];
        }
        if ($this->keywords==''){
            $this->keywords = $res_template['keywords'];
        }
        $this->templateName = $res_template['folders'];
    }

    public function view($view, $data)
    {
        global $ob, $api;
        @define('ASSETS', "/admin/template/" . $this->templateName.'/');
        $array = array(
            'ob' => $ob,
            'api' => $api,
        );
        extract(array_merge($data, $array), EXTR_PREFIX_SAME, 'i');
        $this->meta(@$data["meta"]);
        $this->header();
        include $this->path . '/view/' . $view . '.php';
        $this->footer();
    }

    public function viewStatic($view, $data)
    {
        global $ob, $api;
        @define('ASSETS', "/admin/template/" . $this->templateName.'/');
        $array = array(
            'ob' => $ob,
            'api' => $api,
        );
        extract(array_merge($data, $array), EXTR_PREFIX_SAME, 'i');
        include $this->path . '/view/static/' . $view . '.php';
    }

    public function viewJSON($view, $data)
    {
        global $ob, $api;
        #define('ASSETS', "/admin/template/" . $this->templateName.'/');
        $array = array(
            'ob' => $ob,
            'api' => $api,
        );
        extract(array_merge($data, $array), EXTR_PREFIX_SAME, 'i');
        ob_start();
        include $this->path . '/view/' . $view . '.php';
        $html = ob_get_clean();
        return $html;
    }

    public function header()
    {
        $tpl = $_SERVER['DOCUMENT_ROOT'] . "/admin/template/" . $this->templateName;
        $tpl .= "/header.php";

        if (@A::$app->meta->config["active"]==1){
            $res = A::$app->meta->seo();
            if (@$res["title"]!=''){
                $this->title = $res['title'];
            }
            if (@$res["h1"]!=''){
                $this->h1 = $res['h1'];
            }
            if (@$res["descr"]!=''){
                $this->description = $res['descr'];
            }
            if (@$res["keyw"]!=''){
                $this->keywords = $res['keyw'];
            }

            if (@$res["text"]!=''){
                $this->seotext = $res['text'];
            }
        }

        include_once $tpl;
    }

    public function footer()
    {
        $tpl = $_SERVER['DOCUMENT_ROOT'] . "/admin/template/" . $this->templateName;
        $tpl .= "/footer.php";
        if (@A::$app->meta->config["active"]==1){
            $res = A::$app->meta->seo();
            if (@$res["title"]!=''){
                $this->title = $res['title'];
            }
            if (@$res["h1"]!=''){
                $this->h1 = $res['h1'];
            }
            if (@$res["descr"]!=''){
                $this->description = $res['descr'];
            }
            if (@$res["keyw"]!=''){
                $this->keywords = $res['keyw'];
            }
            if (@$res["text"]!=''){
                $this->seotext = $res['text'];
            }
        }
        include_once $tpl;
    }

    public function meta($e){
        if (@$e["title"]!='') $this->title = $e["title"];
        if (@$e["h1"]!='') $this->h1 = $e["h1"];
        if (@$e["descr"]!='') $this->description = $e["descr"];
        if (@$e["keyw"]!='') $this->keywords = $e["keyw"];
    }

    public function alias()
    {
        if (sizeof($this->request->url)>0){
            $e = $this->request->url[sizeof($this->request->url)-1];
            if ($e==''){
                $e = 'index';
            }

            $sql = "select id, module from i_alias where name='$e' limit 1";
            $res = $this->sql->get($sql, 1);

            if (@$res["id"]>0){
                return true;
            }else{

                $page = is_file($this->path . '/view/static/' . $e . '.php');    
                return $page;
            }
        }else{
            $e = 'index';
            $page = is_file($this->path . '/view/static/' . $e . '.php');    
            return $page;
        }

        return false;
    }

    public function aliasModule($table, $link)
    {
        if (sizeof($this->request->url)>0){
            $e = $this->request->url[sizeof($this->request->url)-1];

            if ($e==$link){
                return 'module';
            }

            $sql = "select id, module from i_alias where name='$e' and module='$table' limit 1";

            $res = $this->sql->get($sql, 1);

            if (@$res["id"]>0){
                return true;
            }
        }

        return false;
    }

    public function page404($data)
    {
        global $ob, $api;
        define('ASSETS', "/admin/template/" . $this->templateName.'/');
        $array = array(
            'ob' => $ob,
            'api' => $api,
        );
        extract(array_merge($data, $array), EXTR_PREFIX_SAME, 'i');
        header("HTTP/1.0 404 Not Found");
        $this->title = A::$app->t('Title404'); 
        $this->h1 = A::$app->t('Title404'); 
        $this->header();
        $tpl = $_SERVER['DOCUMENT_ROOT'] . "/admin/template/" . $this->templateName;
        $tpl .= "/404.php";
        include_once $tpl;
        $this->footer();
    }

}

