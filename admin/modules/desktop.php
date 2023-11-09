<? 
require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php"); 
?>
<!-- Start: Topbar-->
<header id="topbar" class="alt">
  <div class="topbar-left pull-left">
    <h1 class="mn">Панель управления</h1>
  </div>
</header>
<!-- Begin: Content-->
<section id="content" class="table-layout animated fadeIn">
  <!-- begin: .tray-center-->
  <div class="tray tray-center">
    <div class="tray-inner">
      <div class="row flex-column-reverse-before-md">
        <div class="col-sm-12">
          <div class="panel panel-system dark">
            <div class="panel-heading"><span class="panel-title">Контент</span>
              <div class="widget-menu pull-right"></div>
            </div>
            <div class="panel-body">
              <div id="topbar-dropmenu" class="alt" style="display: block; background-color: #fff">
                <div class="topbar-menu row">
                  <?
                  $select = $ob->select("i_modules", array("section"=>0, 'id_head' => 0),"id_head asc, id_sort asc");
                  $i = 0;
                  while($res = $select->fetch_array()){
                    $sub = $ob->select("i_modules",array("id_head"=>$res['id'],"section"=>0,"install"=>1),"id_sort");
                    $sub_res = $sub->fetch_array();
                    if ($sub->num_rows>0){
                      $link = '/admin/modules/'.$res['folders'].'/'.$sub_res['folders']."/";
                    }else{
                      $link = '/admin/modules/'.$res['folders'].'/';
                    }
                    if(in_array($res['id'],$head) ||in_array($res['id'],$sub_head)){
                      if ($sub->num_rows==0){
                        ?>
                        <div class="col-xs-4 col-sm-2">
                          <a href="<?=$link?>" class="metro-tile bg-default light" style="text-align: center; color: #444; background-color: #E1EBF0">
                            <span class="<?=$res["icon"]?>"></span>
                            <span class="metro-title"><?=$res["name"]?></span>
                          </a>
                        </div>
                        <? 
                      }

                      $selectSub = $ob->select("i_modules", array("section"=>0, 'id_head' => $res['id']),"id_head asc, id_sort asc");
                      $i = 0;
                      while($resSub = $selectSub->fetch_array()){

                        $link = '/admin/modules/'.$res['folders'].'/'.$resSub['folders']."/";

                        if(in_array($resSub['id'],$head) ||in_array($resSub['id'],$sub_head)){
                          ?>
                          <div class="col-xs-4 col-sm-2">
                            <a href="<?=$link?>" class="metro-tile bg-default light" style="text-align: center; color: #444; height: 116px; background-color: #E1EBF0">
                              <span class="<?=$resSub["icon"]?>"></span>
                              <span class="metro-title"><?=$res["name"]?><br><?=$resSub["name"]?></span>
                            </a>
                          </div>
                          <? 
                        } 
                      }  
                    }  
                  } 
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-system dark">
            <div class="panel-heading"><span class="panel-title">Настройка</span>
              <div class="widget-menu pull-right"></div>
            </div>
            <div class="panel-body">
              <div id="topbar-dropmenu" class="alt" style="display: block; background-color: #fff">
                <div class="topbar-menu row">
                  <?
                  $select = $ob->select("i_modules", array("section"=>1, 'id_head' => 0),"id_head asc, id_sort asc");
                  $i = 0;
                  while($res = $select->fetch_array()){
                    $sub = $ob->select("i_modules",array("id_head"=>$res['id'],"section"=>1,"install"=>1),"id_sort");
                    $sub_res = $sub->fetch_array();
                    if ($sub->num_rows>0){
                      $link = '/admin/modules/'.$res['folders'].'/'.$sub_res['folders']."/";
                    }else{
                      $link = '/admin/modules/'.$res['folders'].'/';
                    }
                    if(in_array($res['id'],$head) ||in_array($res['id'],$sub_head)){
                      if ($sub->num_rows==0){
                        ?>
                        <div class="col-xs-4 col-sm-2">
                          <a href="<?=$link?>" class="metro-tile bg-default light" style="text-align: center; color: #444; background-color: #E1EBF0">
                            <span class="<?=$res["icon"]?>"></span>
                            <span class="metro-title"><?=$res["name"]?></span>
                          </a>
                        </div>
                        <? 
                      }

                      $selectSub = $ob->select("i_modules", array("section"=>1, 'id_head' => $res['id']),"id_head asc, id_sort asc");
                      $i = 0;
                      while($resSub = $selectSub->fetch_array()){

                        $link = '/admin/modules/'.$res['folders'].'/'.$resSub['folders']."/";

                        if(in_array($resSub['id'],$head) ||in_array($resSub['id'],$sub_head)){
                          ?>
                          <div class="col-xs-4 col-sm-2">
                            <a href="<?=$link?>" class="metro-tile bg-default light" style="text-align: center; color: #444; height: 116px; background-color: #E1EBF0">
                              <span class="<?=$resSub["icon"]?>"></span>
                              <span class="metro-title"><?=$res["name"]?><br><?=$resSub["name"]?></span>
                            </a>
                          </div>
                          <? 
                        } 
                      }  
                    }  
                  }  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/footer.php")?>