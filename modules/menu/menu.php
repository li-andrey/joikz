<?php
class Menu extends Module{
	// Инфо для установки
	public $table = 'i_menu';
    public $tableE = 'i_menu';
	
	private static $_instance;

    public static function getInstance($request) 
    {
        if(!self::$_instance) { 
            self::$_instance = new self($request);
        }
        return self::$_instance;
    }

	public function __construct($request){
		parent::__construct($request);
		$this->path =  realpath(dirname(__FILE__));
		$this->configLoad();
	}
	
	function menuLi($id)
	{
		$links = $this->get_fields($id);
		$data = array(
			'links'=>$links
		);
		return $this->viewJSON('simple_li', $data);
	}
	
	function menuA($id)
	{
		$links = $this->get_fields($id);
		$data = array(
			'links'=>$links
		);
		return $this->viewJSON('simple_a', $data);
	}
	
	function main($id)
	{
		$links = $this->get_fields($id);
		
		$data = array(
			'links'=>$links
		);
		return $this->viewJSON('main', $data);
	}

	function top($id)
	{
		$links = $this->get_fields($id);
		
		$data = array(
			'links'=>$links
		);
		return $this->viewJSON('top', $data);
	}
	

	function mobile($id)
	{
		$links = $this->get_fields($id);

		$data = array(
			'links'=>$links
		);
		return $this->viewJSON('mobile', $data);
	}
	
	
	function sitemap($id)
	{
		$links = $this->get_fields($id);
		$data = array(
			'links'=>$links
		);
		return $this->viewJSON('sitemap', $data);
	}

	function get_fields($id)
	{
		$fields = array();
		$sql = "select * from i_menu where slug='$id' and (version='all' or version='$this->lang')";
		$sql.= "and active=1 limit 1";
		$res = $this->sql->get($sql, 1);

		$sql = "select * from ".$this->table." where id_section='".$res["id"]."' and (version='all' or version='$this->lang') ";
		$sql.= "and active=1 order by id_sort asc limit ".$this->config["max_links"];

		
		$s = $this->sql->query($sql);
		if ($s && $s->num_rows>0){
			while($r = $s->fetch_array()){
				
				$fields_array = array();
				$fields_array["name"] = $r["name"];
				$fields_array["link"] = $r["link"];
				$fields_array["blank"] = @$r["blank"];
				
				if (strstr($fields_array["link"], 'from:')){
					
					$module = explode(':', $fields_array["link"]);
					$fields_array["link"] = A::$app->link().$module[3].'/';
					$sql = "select * from i_".$module[1]." where id_section=".$module[2]." and (version='all' or version='$this->lang') ";
					$sql.= "and active=1 order by id_sort asc";
					$ss = $this->sql->query($sql);
					$fields_sub = array();
					if ($ss && $ss->num_rows>0){
						while($rr = $ss->fetch_array()){
					
							$fields_array_sub = array();
							$fields_array_sub["name"] = $rr["name"];
							$fields_array_sub["blank"] = @$rr["blank"];	
							$fields_array_sub["link"] = '/catalog/'.$rr["url"].'/';	
							
							$sql = "select * from i_".$module[1]." where id_section=".$rr["id"]." ";
							$sql.= "and (version='all' or version='$this->lang') ";
							$sql.= "and active=1 order by id_sort asc";
							$sss = $this->sql->query($sql);
							$fields_array_sub_sub = array();
							if ($sss && $sss->num_rows>0){
								while($rrr = $sss->fetch_array()){
									$fields_array_sub_sub[] = array(
										'name'=>$rrr["name"], 
										'blank'=>@$rrr["blank"], 
										'link'=>'/'.$module[3].'/'.$rrr["url"].'/'
									);	

								}
								if (sizeof(@$fields_array_sub_sub)>0) $fields_array_sub["sub"] = $fields_array_sub_sub;
							}
							
							
							if (sizeof(@$fields_array_sub)>0) $fields_sub[]=$fields_array_sub;
						}
					}
				}else{
					$sql = "select * from ".$this->table." where id_section=".$r["id"]." and (version='all' or version='$this->lang') ";
					$sql.= "and active=1 order by id_sort asc";
					$ss = $this->sql->query($sql);
					$fields_sub = array();
					if ($ss && $ss->num_rows>0){
						while($rr = $ss->fetch_array()){
					
							$fields_array_sub = array();
							$fields_array_sub["name"] = $rr["name"];	
							$fields_array_sub["blank"] = @$rr["blank"];	
							$fields_array_sub["link"] = $rr["link"];	
							
							$sql = "select * from ".$this->table." where id_section=".$rr["id"]." ";
							$sql.= "and (version='all' or version='$this->lang') ";
							$sql.= "and active=1 order by id_sort asc";
							$sss = $this->sql->query($sql);
							$fields_array_sub_sub = array();
							if ($sss && $sss->num_rows>0){

								while($rrr = $sss->fetch_array()){
									
									$fields_array_sub_sub[] = array('name'=>$rrr["name"],'link'=>$rrr["link"],'blank'=>@$rrr["blank"]);	
								}
							}
							
							if (sizeof(@$fields_array_sub_sub)>0) $fields_array_sub["sub"] = $fields_array_sub_sub;
							if (sizeof(@$fields_array_sub)>0) $fields_sub[] = $fields_array_sub;
						}
					}
				}
				
				if (sizeof(@$fields_sub)>0) $fields_array["sub"] = $fields_sub;
				if (sizeof(@$fields_array)>0) $fields[] = $fields_array;	
			}	
		}
		
		
		return $fields; 
	}
	
	
}
?>