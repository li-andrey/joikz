<?php

class Page extends Module
{
    public $table = 'i_page';
    public $tableE = 'i_page';

    public $link = '';

    public $allowed = [
        'banner'        => 'page',
        'register'      => 'user',
        'login'         => 'user',
        'profile'       => 'user',
        'account'       => 'user',
        'forgot'        => 'user',
        'success'       => 'user',
    ];

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
        if (@A::$url->url[0] != '') {
            $method = A::$url->url[0].'Action';
            if (method_exists($this, $method)) {
                if ($this->before() !== false) {
                    call_user_func_array([$this, $method], []);
                    $this->after();
                }
            } else {
                $method = 'pageAction';
                if ($this->before() !== false) {
                    call_user_func_array([$this, $method], []);
                    $this->after();
                }
            }
        }else{
            $method = 'indexAction';
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], []);
                $this->after();
            }
        }
    }

    public function indexAction()
    {

        $view = 'index';
        $data = array(
            'id' => @$this->request->url[sizeof($this->request->url) - 1]
        );

        $this->viewStatic($view, $data);
    }

    public function feedbackAction()
    {
        $page = $this->w(2);
        $this->data["meta"] = [
            'title' => @$page["title"]!=''?@$page["title"]:@$page["name"],
            'h1' => @$page["title1"]!=''?@$page["title1"]:@$page["name"],
            'description' => @$page["descr"],
            'keywords' => @$page["keyw"],
        ];

        $this->data["e"] = $page;

        $view = 'feedback';
        $this->view($view, $this->data);
    }



    public function pageAction()
    {
        $page = $this->element();

        if ($page) {
            $data = array(
                'meta' => array(
                    'title' => @$page["title"]!=''?@$page["title"]:@$page["name"],
                    'h1' =>  @$page["title1"]!=''?@$page["title1"]:@$page["name"],
                    'description' => @$page["descr"],
                    'keywords' => @$page["keyw"],
                ),
                'e' => $page,
            );
            $this->view('page', $data);

        } else {
            $view = @$this->request->url[sizeof($this->request->url) - 1];
            if ($view == '') {
                $view = 'index';
            }
            $path = 'static/';

            $page = is_file($this->path . '/view/static/' . $view . '.php');
            if ($page) {
                $data = array(
                    'id' => @$this->request->url[sizeof($this->request->url) - 1]
                );

                $this->viewStatic($view, $data, $path);
            } else {
                $path = explode('/', A::$app->path());
                if (array_key_exists(@$path[1], $this->allowed)) {
                    A::$app->{$this->allowed[$path[1]]}->viewStatic($path[1], [], '');
                } else {
                    $this->page404(array());
                }
            }
        }
    }


    public function logoutAction()
    {
        A::$app->user->logout();
        header("Location: ".A::$app->link());
        exit;
    }
}
