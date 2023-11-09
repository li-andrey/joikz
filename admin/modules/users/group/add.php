<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/header.php";

$nameRazdel = 'user';
$nameElement = 'user';
$nameModule = 'group';

if (@$_POST['hidden']) {

  if (@$_POST['hidden'] == 'save') {
    $page = "index.php?id_section=" . @$_GET['id_section'] . "&module=" . @$_GET['module'];
  } else { 
    $page = "";
  }

  @$_POST["blocks"] = @join('|',@$_POST["blocks"]);
  $modules = $ob->select("i_modules",array("id_head"=>0),"id_sort");  
  while($modules_res = $modules->fetch_array()){
    if(@$_POST[''.$modules_res['id'].'']==1){
      @$priv.=$modules_res['id'].'=';
      $sub_modules = $ob->select("i_modules",array("id_head"=>$modules_res['id']),"id_sort");
      $i=0;
      while($sub_res=$sub_modules->fetch_array()){
       if(@$_POST[''.$modules_res['id'].'_'.$i.'']==1){
        $priv.=$sub_res['id']; 
        if($sub_modules->num_rows!=($m=1+$i)){$priv.=',';}
      }
      $i++;
    }
  }
  $priv.='|';
}
if (!@$erorr) {

  $sql = "INSERT INTO i_" . $ob->pr($_GET['module']) . " ( active, name, info, privileges) ";
  $sql.= "VALUES ('" . @$_POST['active'] . "', '" . @$_POST['name'] . "', '" . @$_POST['info'] . "', '" . @$priv . "')";

  $i = $mysql->query($sql);

  if (!$i) {
    $ob->alert("Ошибка добавления поля! ".$mysql->error);
  }else{
    $id = $mysql->insert_id;
  }

  $mysql->query("insert into i_logs values(0, CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','add','".$ob->pr(@$_GET['module'])."', '".@$_POST["name"]."', '".@$id."', '".$ob->pr($sql)."')");
  if ($i) { 
    //$ob->go($page);
  }
} else {
  $ob->alert($erorr);
}
}


$sql = "select name from i_modules where folders='$nameModule' limit 1";
$res = $mysql->get($sql, 1);
?>
<script>
  function pr(hidden_val){
    var msg;
    var fr;
    msg = '';
    fr = document.form;
    if (fr.name.value==''){
      msg=msg+'* Название \n';
    }

    if(msg==''){
      fr.hidden.value=hidden_val;
      fr.submit();
    }else{
      msg='Необходимо заполнить обязательные поля:\n'+msg;
      alert(msg);
    }
  }


  function main(id,count)
  {
    general_box = document.getElementById(id);
    for(var i=0;i<count;i++)
    {
     thisCheckbox = document.getElementById(id+'_'+i);
     if(general_box.checked){thisCheckbox.checked=true;}else{thisCheckbox.checked=false;}
   }
 }
</script>
<form action="" method="post" enctype="multipart/form-data" name="form" id="form" >
  <!-- Start: Topbar-->
  <header id="topbar" class="alt" style="padding-bottom: 0px;">
    <div class="topbar-left pull-left">
      <ol class="breadcrumb">
        <li class="crumb-icon"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li class="crumb-active"><a href="index.php"><?=$res["name"]?></a></li>
        <?
        admin_print_dir(@$_GET["module"], @$_GET["sub_module"]);
        ?>
        <li class="crumb-active"> Добавление группы</li>
      </ol>
    </div>
  </header>
  <section id="content" class="table-layout">
    <div class="tray tray-center" style="padding-top: 0px;">
      <div class="tray-inner">
        <div class="row flex-column-reverse-before-md">
          <div class="col-sm-12">
           <div class="panel">
            <div class="br-b panel-heading">
              <ul class="nav panel-tabs-border panel-tabs panel-tabs-left">
                <li class="active">
                  <a href="#tab2_1" data-toggle="tab" aria-expanded="true">Основная информация</a>
                </li>

              </ul>
            </div>
            <div class="panel-body">
              <div class="tab-content pn br-n">
                <div id="tab2_1" class="tab-pane active">
                  <div class="panel  top mb35">
                    <div class="panel-body bg-white">
                      <div class="admin-form">
                        <div class="section row mb10">
                          <label class="field-label col-md-3 text-right">
                           Активен:
                         </label>
                         <div class="col-md-9">
                          <label style="line-height: 42px;"><input name="active" type="checkbox" id="active"  value="1" /></label>
                        </div>
                      </div>

                      <div class="section row mb10">
                        <label class="field-label col-md-3 text-right">
                          <span class="small_red_text">*</span> Название:
                        </label>
                        <div class="col-md-9">
                          <input name="name" type="text" id="name" required="" size="35" class="form-control" />
                        </div>
                      </div>
                      <div class="section row mb10">
                        <label class="field-label col-md-3 text-right">
                         <span class="small_red_text"></span> Описание:
                       </label>
                       <div class="col-md-9">
                        <input name="info" type="text" id="info" size="35" class="form-control" />
                      </div>
                    </div>

                    <div class="section row mb10">
                      <label class="field-label col-md-3 text-right">
                       <span class="small_red_text"></span> Привилегии на модули:
                     </label>
                     <div class="col-md-9">
                      <?

                      $modules=$ob->select("i_modules",array("id_head"=>0),"id_sort");

                      while($modules_res = $modules->fetch_array()) {

                       $sub_modules=$ob->select("i_modules",array("id_head"=>$modules_res['id']),"id_sort");

                       echo '<div class="form_group">

                      

                       <p align="left" valign="middle" class="small_text"><input type="checkbox" name="'.$modules_res['id'].'" onclick="main(\''.$modules_res['id'].'\',\''.$sub_modules->num_rows.'\')" checked value="1" >

                       '.$modules_res['name'];

                       if($sub_modules->num_rows>0){echo "<br>";}

                       $i=0;

                       while($sub_res=$sub_modules->fetch_array())

                       {

                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" checked name="'.$modules_res['id'].'_'.$i.'" value="1">

                        '.$sub_res['name'].'<br>';

                        $i++;

                      }

                      echo'</p>

                      </div>';

                    }

                    ?>
                  </div>
                </div>



              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div style="padding: 0px 20px 20px; margin-top: -70px; position: relative;">
      <input type="button" class="btn btn-system" name="button" id="button" value="Сохранить" onClick=" pr('save')" />
      <input type="button" class="btn btn-success" name="button2" id="button2" value="Применить"  onclick=" pr('apply')" />
      <input type="reset"  class="btn btn-warning"  name="button3" id="button3" value="Отменить"  />
      <input type="hidden" name="hidden" id="hidden" />
    </div>
  </div>

</div>
</div>
</div>

</section>


</form>

<?require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/footer.php"?>
