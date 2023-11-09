<?php
class Project extends Module{
	
	public $table = 'i_cat';
	public $tableE = 'i_cat_elements';


	public $link = 'project';

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

	public function breadcrumb($title)
	{
		$breadcrumb = new breadcrumb();
		return $breadcrumb->init($this->table, $this->tableE, $this->link, $title);
	}

	public function init()
	{
		if ($this->aliasModule(str_replace('i_', '',$this->table), $this->link)=='module'){
			$this->listBlock();
		}else if ($this->aliasModule(str_replace('i_', '',$this->tableE), $this->link)){
			$this->listElement();
		}else{
			$this->page404(array());
		}
	}


	public function listBlock()
	{
		if (strstr($_SERVER["REQUEST_URI"], 'search')){
			$this->search();	
		}else{
			$id_block = 2;
			$e = $this->request->url[sizeof($this->request->url)-1];
			if ($e==$this->link){
				$this->listElements($id_block);	
			}else{
				$block = $this->category('', array(" url='$e' "));
				$this->listElements($block["id"]);
			}
		}
	}

	public function listElement()
	{
		$e = $this->request->url[sizeof($this->request->url)-1];
		$element = $this->element();
		$block = $this->category(@$element["id_section"]);

		

		$data = array(
			'meta' => array(
				'title'=> $element["title"]!=''?$element["title"]:$element["name"], 
				'h1'=> $element["name"], 
				'descr' => $element["descr"], 
				'keyw' => $element["keyw"]
			),
			'e' => $element,
			'block' => $block,

		);

		
		$this->view('element', $data);	
		
		
		if (!in_array($element["id"], $_SESSION["views"])){
			$_SESSION["views"][] = $element["id"];

			$sql = "update i_cat_elements set views=views+1 where id='".$element["id"]."'";
			A::$db->query($sql);
		}
		
	}

	
	
	public function listElements($ids)
	{
		$block = $this->category($ids);
		$subblock = $this->categories($ids);
		$tree = $this->tree(@$block["id"]);	
		
		if (@A::$app->get('ids')!=''){
			$tree = $this->tree(A::$app->get('ids'));	
		}

		





		$pagination = new paginator(
			$this->lang, 
			A::$app->get("p"), 
			@$this->config["elements"]
		);

		$sql_array = array();

		if (@A::$app->get('s')!=''){
			$sql_array[] = " (name like '%".A::$app->get('s')."%' or anounce like '%".A::$app->get('s')."%' or text like '%".A::$app->get('s')."%') ";
		}



		if(A::$app->get('price_from')>0){
			$sql_array[] = " price >= ".intval(A::$app->get('price_from'))."";
		}

		if(A::$app->get('price_to')>0){
			$sql_array[] = " price <= ".intval(A::$app->get('price_to'))."";
		}

		if(A::$app->get('photos')==1){
			$sql_array[] = " image!='' ";
		}

		if (@A::$app->get('min_price')!=''){
			$price[0] = @A::$url->get["min_price"];
			$price[1] = @A::$url->get["max_price"];
			$ido = array();
			$sql = "select id from i_cat_elements where active=1 and price>=".trim(@$price[0])." and price<=".trim(@$price[1])." group by id";
			$res = A::$db->get($sql);
			foreach ($res as $key => $v) {
				$ido[] = $v["id"];
			}
			if (sizeof($ido)>0){
				$sql_array[] = " id in (".join(', ', $ido).") ";
			}
		}

		if (isset(A::$url->get["brand"])){
			$brandArray = array();
			if (is_array(A::$url->get["brand"])){
				foreach (A::$url->get["brand"] as $key => $v) {
					
					$brandArray[] = "'".str_replace('*', '&', $v)."'";
					
				}
			}else{
				$brandArray[] = "'".str_replace('*', '&',A::$url->get["brand"])."'";
			}


			if (sizeof($brandArray)>0){
				$sql_array[] = " brand in (".join(',', $brandArray).") ";    
			}

		}


		$filterFields = array();


		


		$sql = "SELECT * FROM ".$this->tableE." WHERE id_section IN (".join(',', $tree).") ";
		$sql.= "".(sizeof(@$sql_array)>0?" AND ".(join(" AND ", $sql_array))."":'')." ";
		$sql.= "AND `version`='$this->lang' AND active=1  ";
		$sql.= "ORDER BY ".$this->orderBy();



		$pagination->setSQL($sql);
		$pagination->setPaginator('p');		
		$query = $this->sql->query($pagination->getSQL());

		$elements = array();
		if ($query->num_rows>0){
			while($r = $query->fetch_array()){
				$elements[] = $r;
			}
		}

		if (!isset(A::$url->get["type"])) A::$url->get["type"] = 'grid';
  

		$data = array(
			'meta' => array(
				'title'=> $block["title"]!=''?$block["title"]:$block["name"], 
				'h1'=> $block["name"], 
				'descr' => $block["descr"], 
				'keyw' => $block["keyw"]
			),
			'block' => $block,
			'subblock' => $subblock,
			'tree' => $tree,
			'filterFields' => $filterFields,
			'elements' => $elements,
			'pagination' => $pagination,
		);



		$this->view('elements', $data);				
	}



	public function parent($id){

		$sql = "select * from ".$this->table." where id=$id and active=1 and version='".$this->lang."' limit 1";
		$res = $this->sql->get($sql, 1);


		if (@$res["id_section"]!=0){

			$sql = "select * from ".$this->table." where id=".$res["id_section"]." and active=1 and version='".$this->lang."' limit 1";
			$res = $this->sql->get($sql, 1);

			if ($res["id_section"]!=0){

				$sql = "select * from ".$this->table." where id=".$res["id_section"]." and active=1 and version='".$this->lang."' limit 1";
				$res = $this->sql->get($sql, 1);
				if ($res["id_section"]!='0'){

					$sql = "select * from ".$this->table." where id=".$res["id_section"]." and active=1 and version='".$this->lang."' limit 1";
					$res = $this->sql->get($sql, 1);

					return $res["id"];

				}else{
					return $res["id"];
				}

			}else{
				return $res["id"];
			}
		}else{
			return @$res["id"];
		}
	}



	public function tree($id)
	{
		$ids = array($id);
		$cat = $this->categories($id);
		if (is_array($cat)){
			foreach ($cat as $r) {
				$ids[] = $r["id"];
				$catSub = $this->categories($r["id"]);
				if (is_array($catSub)){
					foreach ($catSub as $rSub) {
						$ids[] = $rSub["id"];
						$catSub1 = $this->categories($rSub["id"]);
						if (is_array($catSub1)){
							foreach ($catSub1 as $rSub1) {
								$ids[] = $rSub1["id"];
								$catSub2 = $this->categories($rSub1["id"]);
								if (is_array($catSub2)){
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
		if (is_array($cat) && sizeof($cat)>0){
			return $cat;	
		}else{
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

		if (A::$app->get("show")!=''){ 
			$perPage = intval(A::$app->get("show"));
		}else{
			A::$url->get["show"] = $perPage;
		}
		return $perPage;
	}

	private function orderBy()
	{
		if (@A::$app->get('order')!=''){
			return ' '.@A::$app->get('order');
		}else{
			return ' id_sort asc ';
		}
	}

}