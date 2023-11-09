<?php
class Request extends Query
{

    private static $_instance;

    public $url = array();
    public $get = array();
    public $lang;
    public $version = [
        'ru',
        'kz',
        'en',
    ];

    public $defultVersion = 'ru';

    public static function getInstance() 
    {
        if(!self::$_instance) { 
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->lang = $this->defultVersion;
        parent::__construct();
        $this->parseUrl($_SERVER["REQUEST_URI"]);
    }

    public function parseUrl($url)
    {
        $url = parse_url($url);
        $path = explode('/', $url["path"]);
        if (isset($path[1])){
            if (in_array($path[1], $this->version)){
                $this->lang = $this->pr($path[1]);
                unset($path[1]);
            }
        }
        
        $this->url = array_values(array_diff($path, array('')));

        if (isset($url["query"])){
            parse_str($url["query"], $query);
            foreach ($query as $name => $value) {

                if (is_array($value)) {
                    foreach ($value as $name1 => $value1) {
                        if (is_array($value1)) {
                            foreach ($value1 as $name2 => $value2) {
                                $query[@$name][@$name1][@$name2] = $this->pr(@$value2);
                            }
                        } else {
                            $query[@$name][@$name1] = $this->pr(@$value1);
                        }
                    }
                } else {
                    $query[@$name] = $this->pr(@$value);
                }
            }
            $this->get = $query;
        }
        
        return;
    }
}