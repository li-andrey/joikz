<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/modules/Module.php');

class Paginator extends Module {
    private $_connection;
    private $_paginator = 'page';
    private $_sql;
    public  $_limit_perpage = 10;
    private $_range = 5;
	public $lang;
	public $page;

    private static $_instance;

    public static function getInstance() 
    {
        if(!self::$_instance) { 
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct( $lang='ru', $page = 1, $per_page=10)
	{
		parent::__construct();
		
		$this->path =  realpath(dirname(__FILE__));
        $this->configLoad();
        $this->lang = $lang;
        $this->page = $page;
        $this->_limit_perpage = $per_page;
        $this->_range = $this->config["range"];
        $this->getPager();
    }
	
   public function getTotalOfResults()
       {

           $result = $this->sql->query(
               str_replace( ' * ', ' COUNT(*) ', str_replace( 'a.*', 'COUNT(*)', $this->_sql ))
           );
           if ($result){
               if (strstr($this->_sql, 'a.*')){
                   $count = $result->num_rows;
               }else {
                   $count = $result->fetch_array();
               }
           }else{
               @$result->error;
           }

           if (strstr($this->_sql, 'a.*')){
               return @$count;
           }else {
               return (int) @$count[0];
           }
       }

    public function total() 
    {         
        
        if( $this->getTotalOfResults() > 0 ) { 

           $onpage = ($this->getCurrentPage()-1)*$this->getLimitPerPage() + $this->getLimitPerPage();

           printf("<span id=\"result-bar\">Показано %s-%s из %s элементов</span>"
               , ($this->getCurrentPage()-1)*$this->getLimitPerPage() + 1 
               , $onpage>$this->getTotalOfResults()?$this->getTotalOfResults():$onpage
               , $this->getTotalOfResults()
           );
        } else { 
            print "<span id=\"result-bar-not-found\">Нет элементов</span>"; 
        }     
        
    } 
    
    public function printNavigationBar()  
    {
        
        $currentpage = $this->getCurrentPage();
        $total_ofpages = $this->getTotalOfPages();
        $paginator = $this->getPaginator();
        $query_string = $this->rebuildQueryString( $paginator );
        $range = $this->getRange();
        
        if($this->getTotalOfResults() > 0) {         
            if ( $currentpage > 1 ) { 
                echo " <li class=\"page-item \"><a href=\"?" . $paginator . "=1"
                        . $query_string 
                        . "\" class=\"first page-link\"><i class=\"fa fa-angle-double-left\"></i></a></li> ";
                $previous = $currentpage - 1; 
                print " <li class=\"page-item \"><a href=\"?" . $paginator . "="
                        . $previous . $query_string 
                        . "\" class=\"previous page-link\"><i class=\"fa fa-angle-left\"></i></a></li>  ";
            }else{
                print " <li class=\"page-item \"><a href=\"javascript:;"
                        . $query_string 
                        . "\" class=\"first page-link\" style='color:#aaa;'><i class=\"fa fa-angle-double-left\"></i></a></li>  ";
                $previous = $currentpage - 1; 
                print " <li class=\"page-item \"><a href=\"javascript:;\" class=\"previous page-link\" style='color:#aaa;'><i class=\"fa fa-angle-left\"></i></a></li>  ";
            } 
             
            for ( 
                $x = ( $currentpage - $range ); 
                $x < ( ( $currentpage + $range ) + 1 ); 
                $x++ 
            ) { 
                if ( ( $x > 0 ) && ( $x <= $total_ofpages ) ) { 
                    if ( $x == $currentpage ) { 
                        print " <li class=\"page-item active\"><span class=\"current page-link\">$x</span></li>  ";
                    } else { 
                        print " <li class=\"page-item \"><a href=\"?" . $paginator . "=" . $x
                              . $query_string . "\" class=\"others page-link\">$x</a> </li> ";
                    } 
                } 
            } 
             
            if ( $currentpage != $total_ofpages ) { 
                $next = $currentpage + 1; 
                print "<li class=\"page-item \"> <a href=\"?" . $paginator . "="
                        . $next . $query_string 
                        . "\" class=\"next page-link\"><i class=\"fa fa-angle-right\"></i></a> ";
                print "<li class=\"page-item \"> <a href=\"?" . $paginator . "="
                        . $total_ofpages . $query_string 
                        . "\" class=\"last page-link\"><i class=\"fa fa-angle-double-right\"></i></a> ";
            }else{
                print "<li class=\"page-item \"> <a href=\"javascript:;\" class=\"next page-link\" style='color:#aaa;'><i class=\"fa fa-angle-right\"></i></a></li>  ";
                print "<li class=\"page-item \"> <a href=\"javascript:;\" class=\"last page-link\" style='color:#aaa;'><i class=\"fa fa-angle-double-right\"></i></a> </li> ";
            } 
        }     
    }          
    

    public function getTotalOfPages() 
    {        
        
        return ceil( $this->getTotalOfResults() / $this->getLimitPerPage() );
        
    } 

    public function getCurrentPage() 
    { 
        $total_ofpages = $this->getTotalOfPages();
        $pager = $this->getPager();
        
        if ( isset( $pager ) && is_numeric( $pager ) ) {          
            $currentPage = $pager; 
        } else { 
            $currentPage = 1; 
        } 

        if ( $currentPage > $total_ofpages ) { 
            $currentPage = $total_ofpages; 
        } 

        if ($currentPage < 1) { 
            $currentPage = 1; 
        } 

        return (int) $currentPage; 
         
    } 
    

    private function getOffset() 
    {       
       
        return  ( $this->getCurrentPage() - 1 ) * $this->getLimitPerPage();  
        
    } 
    
    public function setSQL( $string ) 
    {
        
        if ( strlen( $string ) < 0 ) {
            throw new Exception( "<<THE QUERY NEEDS A SQL STRING>>" );
        } 
        
				
        $this->_sql = $string;
        
    }


    public function getSQL() 
    {
		
        $limit_perpage = $this->getLimitPerPage();
        $offset = $this->getOffset();
        
        return $this->_sql .  " LIMIT {$limit_perpage} OFFSET {$offset} "; 
        
    }
    

    public function setPaginator( $paginator ) 
    {
        
        if( !is_string( $paginator ) ) {
            throw new Exception("<<PAGINATOR MUST BE OF TYPE STRING>>");
        } 
        
        $this->_paginator = $paginator;
        
    }
    
    private function getPaginator()
    {
        return $this->_paginator;
    }


    public function getPager() 
    {
        
         return $this->page; 
        
    }


    public function setLimitPerPage( $limit ) 
    {
        
        if( !is_int( $limit ) ) {
            throw new Execption( "<<THE LIMIT MUST BE AN INTEGER>>" );
        }
        
        $this->_limit_perpage = $limit;
        
        
    }


    public function getLimitPerPage() 
    {
        
        return $this->_limit_perpage;
        
    }


    public function setRange( $range ) 
    {
        
        if( !is_int( $range ) ) {
            throw new Execption( "<<THE RANGE MUST BE AN INTEGER>>" );
        }
        
        $this->_range = $range;
        
    }


    public function getRange() 
    {
        
        return $this->_range;
        
    }
    
    public function rebuildQueryString ( $query_string ) 
    { 
        $old_query_string = explode('?',$_SERVER['REQUEST_URI']);
        
        if ( strlen( @$old_query_string[1] ) > 0 ) { 
            
            $parts = explode("&", $old_query_string[1] ); 
            $new_array = array();
            
            foreach ($parts as $val) { 
                if ( stristr( $val, $query_string ) == false)  { 
                    array_push( $new_array , $val ); 
                } 
            } 
            
            if ( count( $new_array ) != 0 ) { 
                $new_query_string = "&".implode( "&", $new_array ); 
            } else { 
                return false; 
            }
            
            return $new_query_string;
            
        } else { 
            return false;  
        } 
        
    }
	
	public function loadScript(){
	}
	
	public function show(){
		include($_SERVER["DOCUMENT_ROOT"].'/modules/paginator/views/'.$this->lang.'/paginator.php');
	}     

}