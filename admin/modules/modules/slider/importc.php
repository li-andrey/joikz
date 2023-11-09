<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php")?>
<?

$nameRazdel = 'slider';
$nameElement = 'slider_elements';
$nameModule = 'slider';

$_GET["id_section"] = intval(@$_GET["id_section"]);
$_GET["module"] = $nameRazdel;
$_GET["sub_module"] = $nameElement;
if(@$_POST['hidden'])
{
  if($_POST['hidden']=='save'){$page="index.php".$ob->gets_go($_GET,"","");}else{$page="";}

  if(!empty($_FILES['file']['tmp_name']))
  {
   $upload=move_uploaded_file($_FILES['file']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/admin/tmp/".date("d.m.Y").".csv");
   $file=fopen($_SERVER['DOCUMENT_ROOT']."/admin/tmp/".date("d.m.Y").".csv","r");
   $j=0;
   while ($data=fgets($file,100000))
   {
     $data = explode(";", $data);
     $option=$ob->search_option($_GET['module'],$_GET['sub_module'],$_GET['id_section'],array(""));
     $i=0;

     while($option_res=$option->fetch_array())
     {
      if($i==0)
      {
        $max=$mysql->query("select MAX(id) from i_".$ob->pr($_GET['module'])."");
        $max_res=$max->fetch_array();
        $mysql->query("INSERT INTO i_".$ob->pr($_GET['module'])." (id_section,version,url,".$option_res['name_en'].") VALUES ('".@$_GET['id_section']."','".$_SESSION['version']."','-".$max_res['MAX(id)']."','".$ob->encode($data[''.$_POST['number'.$option_res['id'].'']])."')");
        $i++;
        $j++;
      }else
      {
        $max=$mysql->query("select MAX(id) from i_".$ob->pr($_GET['module'])."");
        $max_res=$max->fetch_array();
        $ob->update("i_".$_GET['module'],array($option_res['name_en']=>$ob->encode(@$data[''.@$_POST['number'.$option_res['id'].'']])),$max_res['MAX(id)'],"");
      }
    }
  }
  $ob->alert("Загружено строк - ".$j);
}else{$ob->alert("Необходимо выбрать экспортируемый файл!");}
}
?>

<script>
  function pr(hidden_val)
  {
    var msg;
    var fr;
    msg='';
    fr=document.form;

    if(msg=='')
    {
     fr.hidden.value=hidden_val;
     fr.submit();
   }
   else
   {
     msg='Необходимо заполнить обязательные поля:\n'+msg;
     alert(msg);
   }
 }
</script>
<?
$sql = "select name from i_modules where folders='$nameModule'";
$res = $mysql->get($sql, 1);
?>
<!-- Start: Topbar-->
<header id="topbar" class="alt" style="padding-bottom: 0px;">
  <div class="topbar-left pull-left">
    <ol class="breadcrumb">
      <li class="crumb-icon"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
      <li class="crumb-active"><a href="index.php"><?=$res["name"]?></a></li>
      <?
      admin_print_dir(@$_GET["module"], @$_GET["sub_module"]);
      ?>
      <li class="crumb-active"> Импорт</li>
    </ol>
  </div>
</header>
<section id="content" class="table-layout">
  <div class="tray tray-center" style="padding-top: 0px;">
    <div class="tray-inner">
      <div class="row flex-column-reverse-before-md">
        <div class="col-sm-12">
          <form action="" method="post" enctype="multipart/form-data" name="form" id="form" >
            <div class="panel  top mb35">
              <div class="panel-body bg-white">
                <div class="admin-form">
                  <div class="section row mb10">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                      Необходимо сопоставить столбцы загружаемого *.csv файла (с разделяющими запятыми), путём указания номера столбца =&gt; наименованию столбца в базе сайта. <br><br><span class="small_red_text">* номер начинается с нуля
                      </span>
                    </div>
                  </div>
                  <?
                  $option=$ob->search_option($_GET['module'],$_GET['sub_module'],$_GET['id_section'],array(""));
                  $i=0;
                  while($option_res=$option->fetch_array())
                  {
                    echo '<div class="section row mb10">
                    <label for="business-name" class="field-label col-md-3 text-right">' . $option_res['name_ru'] . ':</label>
                    <div class="col-md-8"><input name="number'.$option_res['id'].'" class="form-control" type="text" id="number'.$option_res['id'].'" size="10" value="'.$i++.'"></div>
                    </div>';
                  }
                  ?>
                  <div class="section row mb10">
                   <label for="business-name" class="field-label col-md-3 text-right"><span class="small_red_text">*</span> Файл для загрузки:</label>
                   <div class="col-md-8"><input type="file"  class="form-control" name="file" id="file"></div>
                 </div>

              </div>

            </div>

          </div>
          <div style="padding: 0px 20px 20px; margin-top: 0px; position: relative;">
                  <input type="button" class="btn btn-system" name="button" id="button" value="Сохранить" onClick=" pr('save')" />
                  <input type="button" class="btn btn-success" name="button2" id="button2" value="Применить"  onclick=" pr('apply')" />
                  <input type="reset"  class="btn btn-warning"  name="button3" id="button3" value="Отменить"  />
                  <input type="hidden" name="hidden" id="hidden" />
                </div>
        </form>
      </div>
    </div>
  </div>
</div>
</section>

<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/footer.php")?>
