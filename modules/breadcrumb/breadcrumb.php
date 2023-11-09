<?php
class Breadcrumb extends Module{

    public $breadcrumb = array();

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

    public function init($table, $tableE, $link, $title){

        $this->breadcrumb = $this->bread($table, $tableE, $link, $title);

        if (!is_array($this->breadcrumb)) $this->breadcrumb = array();
        $bread = array_reverse($this->breadcrumb);
        if ($bread[0]["name"]=='Категории'){
            unset($bread[0]);
            $bread = array_values( $bread ) ;
        }
        $data = array(
            'e' => $bread,
        );  
        return $this->viewJSON('breadcrumb', $data);
    }

    public function bread($table, $tableE, $link, $title){
        if (sizeof($this->request->url)>0){
            $e = $this->request->url[sizeof($this->request->url)-1];

            if ($e==''){
                return array();
            }

            if ($table == $tableE){
                $sql = "select id, id_section, name, url from $table where url='$e' and (version='$this->lang' or version='all') limit 1";
                $res = $this->sql->get($sql, 1);



                if (@$res["id"]>0){
                    $ids = $res["id_section"];
                    if ($ids == 0){
                        return array(
                            array(
                                'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                'name' => $res["name"]
                            ),
                        );
                    }else{
                        $array = array(
                            array(
                                'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                'name' => $res["name"]
                            )
                        );
                        $i=1;
                        while($ids!=0){
                            $sql = "select id, id_section, name, url from $table where id='".$res["id_section"]."' and (version='$this->lang' or version='all') limit 1";
                            $res = $this->sql->get($sql, 1);
                            $array[] = array(
                                'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                'name' => $res["name"]
                            );

                            $ids = $res["id_section"];


                            if ($i==10) break;
                            $i++;
                        }

                        return $array;
                    }
                }else{

                    $sql = "select id, id_section, name, url from $tableE where url='$e' and (version='$this->lang' or version='all') limit 1";

                    $res = $this->sql->get($sql, 1);

                    if (@$res["id"]>0){
                        $ids = $res["id_section"];
                        if ($ids == 0){
                            return array(
                                array(
                                    'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                    'name' => $res["name"]
                                ),
                            );
                        }
                    }else{
                        return array(
                            array(
                                'link' => '', 
                                'name' => $title
                            ),
                        );
                    }

                }
            }else{
                $sql = "select id, id_section, name, url from $tableE where url='$e' and (version='$this->lang' or version='all') limit 1";
                $res = $this->sql->get($sql, 1);



                if (@$res["id"]>0){
                    $ids = $res["id_section"];
                    if ($ids == 0){
                        return array(
                            array(
                                'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                'name' => $res["name"]
                            ),
                        );
                    }else{
                        $array = array(
                            array(
                                'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                'name' => $res["name"]
                            )
                        );
                        $i=1;
                        while($ids!=0){
                            $sql = "select id, id_section, name, url from $table where id='".$res["id_section"]."' and (version='$this->lang' or version='all') limit 1";
                            $res = $this->sql->get($sql, 1);
                            $array[] = array(
                                'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                'name' => $res["name"]
                            );

                            $ids = $res["id_section"];


                            if ($i==10) break;
                            $i++;
                        }

                        return $array;
                    }
                }else{
                    $sql = "select id, id_section, name, url from $table where url='$e' and (version='$this->lang' or version='all') limit 1";
                    $res = $this->sql->get($sql, 1);


                    if (@$res["id"]>0){

                        $ids = $res["id_section"];
                        if (@$res["cat"]>0){
                            $ids = $res["cat"];
                        }

                        if ($ids == 0){
                            return array(
                                array(
                                    'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                    'name' => $res["name"]
                                ),
                            );
                        }else{
                            $array = array(
                                array(
                                    'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                    'name' => $res["name"]
                                )
                            );
                            $i=1;
                            while($ids!=0){
                                if (@$res["cat"]>0){
                                    $res["id_section"] = $res["cat"];
                                }
                                $sql = "select id, id_section, name, url from $table where id='".$res["id_section"]."' and (version='$this->lang' or version='all') limit 1";

                                $res = $this->sql->get($sql, 1);
                                if ($res["id"]!=1){
                                    $array[] = array(
                                        'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                        'name' => $res["name"]
                                    );
                                }

                                $ids = $res["id_section"];


                                if ($i==10) break;
                                $i++;
                            }

                            return $array;
                        }
                    }else{

                        $sql = "select id, id_section, name, url from $tableE where url='$e' and (version='$this->lang' or version='all') limit 1";

                        $res = $this->sql->get($sql, 1);

                        if (@$res["id"]>0){
                            $ids = $res["id_section"];
                            if ($ids == 0){
                                return array(
                                    array(
                                        'link' => A::$app->link().$link.'/'.$res["url"].'/', 
                                        'name' => $res["name"]
                                    ),
                                );
                            }
                        }else{
                            return array(
                                array(
                                    'link' => '', 
                                    'name' => $title
                                ),
                            );
                        }

                    }
                }
            }

        }else{
            return array( 
                array(
                    'link' => '', 
                    'name' => $title
                ),
            );
        }
    }
}
