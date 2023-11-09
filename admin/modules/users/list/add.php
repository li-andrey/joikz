<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/header.php";

$nameRazdel = 'user';
$nameElement = 'user';
$nameModule = 'users';

if (@$_POST['hidden']) {

    if (@$_POST['hidden'] == 'save') {
        $page = "index.php?id_section=" . @$_GET['id_section'] . "&module=" . @$_GET['module'];
    } else { 
        $page = "";
    }

    $sql = "select * from i_" . $ob->pr($_GET['module']) . " where login='".sha1(@$_POST['login'])."' ";
    $res = $mysql->get($sql, 1);
    if (@$res["id"]>0){
        $erorr = 'Пользователь с таким логином уже добавлен.';        
    }

    if (!@$erorr) {

        $sql = "INSERT INTO i_" . $ob->pr($_GET['module']) . " ( id_group, login, password, active, name, last_name, email, timestamp_x) ";
        $sql.= "VALUES ('" . @$_POST['id_group'] . "','" . sha1(@$_POST['login']) . "','" . sha1(@$_POST['password']) . "', '" . @$_POST['active'] . "', '" . @$_POST['name'] . "', '" . @$_POST['last_name'] . "', '" . @$_POST['email'] . "', '".date('Y-m-d H:i:s')."')";

        $i = $mysql->query($sql);

        if (!$i) {
            $ob->alert("Ошибка добавления поля! ".$mysql->error);
        }else{
            $id = $mysql->insert_id;
        }

        $mysql->query("insert into i_logs values(0, CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','add','".$ob->pr(@$_GET['module'])."', '".@$_POST["name"]."', '".$id."', '".$ob->pr($sql)."')");
        if ($i) { 
            $ob->go($page);
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
            msg=msg+'* Имя \n';
        }
        if (fr.login.value==''){
            msg=msg+'* Логин \n';
        }
        if (fr.password.value==''){
            msg=msg+'* Пароль \n';
        }
        if(msg==''){
            fr.hidden.value=hidden_val;
            fr.submit();
        }else{
            msg='Необходимо заполнить обязательные поля:\n'+msg;
            alert(msg);
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
        <li class="crumb-active"> Добавление пользователя</li>
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
                 <span class="small_red_text">*</span> Пользовательская группа:
             </label>
             <div class="col-md-9">
                <select name="id_group" id="id_group" class="form-control">
                  <?
                  $select=$ob->select("i_user_group",array("active"=>1),"id_sort");
                  while($res=$select->fetch_array())
                  {
                    echo '<option value="'.$res['id'].'">'.$res['name'].'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="section row mb10">
      <label class="field-label col-md-3 text-right">
       <span class="small_red_text">*</span> Имя:
   </label>
   <div class="col-md-9">
      <input name="name" type="text" id="name" required="" size="35" class="form-control" />
  </div>
</div>
<div class="section row mb10">
    <label class="field-label col-md-3 text-right">
     <span class="small_red_text"></span> Фамилия:
 </label>
 <div class="col-md-9">
    <input name="last_name" type="text" id="last_name" size="35" class="form-control" />
</div>
</div>
<div class="section row mb10">
  <label class="field-label col-md-3 text-right">
   <span class="small_red_text"></span> E-mail:
</label>
<div class="col-md-9">
  <input name="email" type="text" id="email" size="35" class="form-control" />
</div>
</div>
<div class="section row mb10">
    <label class="field-label col-md-3 text-right">
     <span class="small_red_text">*</span> Логин:
 </label>
 <div class="col-md-9">
    <input name="login" type="text" id="login" size="35" class="form-control" required="" />
</div>
</div>
<div class="section row mb10">
  <label class="field-label col-md-3 text-right">
   <span class="small_red_text">*</span> Пароль:
</label>
<div class="col-md-9">
  <input name="password" type="password" id="password" required="" size="35" class="form-control" />
</div>
</div>
<?
$option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array("head"=>0));

while ($option_res = $option->fetch_array()) {
    if ($option_res['required_fields'] == 1) {$star = '<span class="small_red_text">*</span>';} else { $star = '';}

    if ($option_res['type_field'] == "i10") {
      echo '<div class="section row mb10">
      <label for="business-name" class="field-label col-md-3 text-left">' . $star . '&nbsp;' . $option_res['name_ru'] . ':</label>
      <div class="col-md-1">
      </div>
      </div>
      <div class="section row mb10">
      <div class="col-md-12">
      ' . $ob->input_view($option_res['id'], "", "", "", "") . '
      </div>

      </div>';
  } else {

      if ($option_res['name_en'] == 'link') {
        echo '<div class="section row mb10">
        <label class="field-label col-md-3 text-right">Введите ссылку:</label>
        <div class="col-md-9">
        ' . $ob->input_view($option_res['id'], "", "", "", "") . '
        </div>
        </div>';

        echo '<div class="section row mb10">
        <label class="field-label col-md-3 text-right">Или выберите страницу:</label>
        <div class="col-md-9">
        <select name="page_link" class="form-control" id="page_link">
        <option value="">Выберите страницу</option>
        <optgroup label="Текстовая страница">';

        $arr_page = array();

        $s = $mysql->query("select name, url, id from i_page where active=1 and (version='" . $_SESSION["version"] . "' or version='all')  and id_section=0 order by name asc");
        if ($s) {
          while ($r = $s->fetch_array()) {
            echo '<option value="/' . $_SESSION["version"] . '/page/' . $r["url"] . '">' . $r["name"] . '</option>';
            $s1 = $mysql->query("select name, url, id from i_page where active=1 and (version='" . $_SESSION["version"] . "' or version='all') and id_section=".$r["id"]." order by name asc");
            if ($s1) {
              while ($r1 = $s1->fetch_array()) {
                echo '<option value="/' . $_SESSION["version"] . '/page/' . $r1["url"] . '">--- ' . $r1["name"] . '</option>';
                $s2 = $mysql->query("select name, url, id from i_page where active=1 and (version='" . $_SESSION["version"] . "' or version='all') and id_section=".$r1["id"]." order by name asc");
                if ($s2) {
                  while ($r2 = $s2->fetch_array()) {
                    echo '<option value="/' . $_SESSION["version"] . '/page/' . $r2["url"] . '">--- --- ' . $r2["name"] . '</option>';

                }
            }
        }
    }
}
}
echo '</optgroup>';
$s = $mysql->query("select name, url, id from i_cat where active=1 and id_section=0 and (version='" . $_SESSION["version"] . "' or version='all') order by name asc");
if ($s && $s->num_rows>0) {
  echo '<optgroup label="Каталог">';
  while ($r = $s->fetch_array()) {
    echo '<option value="/' . $_SESSION["version"] . '/catalog/' . $r["url"] . '/">' . $r["name"] . '</option>';
    $ss = $mysql->query("select name, url, id from i_cat where active=1 and id_section=".$r["id"]." and (version='" . $_SESSION["version"] . "' or version='all') order by name asc");
    if ($ss) {
      while ($rr = $ss->fetch_array()) {
        echo '<option value="/' . $_SESSION["version"] . '/catalog/' . $rr["url"] . '/">--- ' . $rr["name"] . '</option>';
        $ss1 = $mysql->query("select name, url, id from i_cat where active=1 and id_section=".$rr["id"]." and (version='" . $_SESSION["version"] . "' or version='all') order by name asc");
        if ($ss1) {
          while ($rr1 = $ss1->fetch_array()) {
            echo '<option value="/' . $_SESSION["version"] . '/catalog/' . $rr1["url"] . '/">--- --- ' . $rr1["name"] . '</option>';
            $ss2 = $mysql->query("select name, url, id from i_cat where active=1 and id_section=".$rr1["id"]." and (version='" . $_SESSION["version"] . "' or version='all') order by name asc");
            if ($ss2) {
              while ($rr2 = $ss2->fetch_array()) {
                echo '<option value="/' . $_SESSION["version"] . '/catalog/' . $rr2["url"] . '/">--- --- --- ' . $rr2["name"] . '</option>';
            }
        }
    }
}
}
}
}
echo '</optgroup>';
}
echo '</select>
</div>
</div>';

echo '<div class="section row mb10">
<label class="field-label col-md-3 text-right">Или выберите файл:</label>
<div class="col-md-9">
<input type="file" class="form-control" name="page_file" id="page_file">
</div>
</div>';

} else {

    echo '<div class="section row mb10">
    <label class="field-label col-md-3 text-right">
    ' . $star . '&nbsp;' . $option_res['name_ru'] . ':
    </label>
    <div class="col-md-9">
    ' . $ob->input_view($option_res['id'], "", "", "", "") . '
    </div>
    </div>';
}
}
}

?>

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
