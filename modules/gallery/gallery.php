<?php
class Gallery extends Module{
    
    public $table = 'i_gallery';
    public $tableE = 'i_gallery_elements';
    
    
    public $link = 'gallery';
    
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
		$e = $this->request->url[sizeof($this->request->url)-1];
		$element = $this->element();

		$data = array(
			'meta' => array(
				'title'=> $element["title"]!=''?$element["title"]:$element["name"], 
				'h1'=> $element["name"], 
				'descr' => $element["descr"], 
				'keyw' => $element["keyw"]
			),
			'e' => $element,

		);

		$this->view('element', $data);
		
	}
	
	public function listElements($ids)
	{
		


		if (@$this->config["show_module_elements"]==0){

			$block = $this->category($ids);
			$subBlock = $this->categories($ids);
			
			if (sizeof($subBlock)==0){
				$elements = $this->elementsQuery($block["id"]);
			}else{
				$elements['elements'] = array();
				$elements['pagination'] = array();
			}
			
			$data = array(
				'meta' => array(
					'title'=> $block["title"]!=''?$block["title"]:$block["name"], 
					'h1'=> $block["name"], 
					'descr' => $block["descr"], 
					'keyw' => $block["keyw"]
				),
				'block' => $block,
				'subBlock' => $subBlock,
				'elements' => $elements['elements'],
				'pagination' => $elements['pagination'],
			);



			$this->view('elements', $data);

		}else{

			$block = $this->category($ids);

			$subBlock = $this->categories($ids);

			$elements = $this->elementsQuery($block["id"]);

			$data = array(
				'meta' => array('title'=> $block["title"]!=''?$block["title"]:$block["name"], 'descr' => $block["descr"], 'keyw' => $block["keyw"]),
				'block' => $block,
				'subBlock' => $subBlock,
				'elements' => $elements['elements'],
				'pagination' => $elements['pagination'],
			);



			$this->view('elements', $data);			
		}
	}

	public function elementsQuery($ids){
		$tree = $this->tree($ids);

		$pagination = new paginator(
			$this->request,
			$this->lang, 
			A::$app->get("p"), 
			$this->perPage()
		);


		$sql = "SELECT * FROM ".$this->tableE." WHERE id_section IN (".join(',', $tree).") ";
		$sql.= "".(sizeof(@$sql_array)>0?" AND ".(join(" AND ", $sql_array))."":'')." ";
		$sql.= "AND `version`='$this->lang' AND active=1 ";
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

		return array(
			'elements' => $elements, 
			'pagination' => $pagination
		);
	}

	private function tree($id)
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
		}
		return $perPage;
	}

	private function orderBy()
	{
		return ' id desc ';
	}

}



?>