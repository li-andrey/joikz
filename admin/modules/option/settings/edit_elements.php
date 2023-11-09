<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php"); ?>
<?

$nameRazdel = 'settings';
$nameElement = 'settings_elements';
$nameModule = 'settings';

$_GET["id_section"] = intval(@$_GET["id_section"]);
$_GET["module"] = $nameElement;
$_GET["sub_module"] = $nameRazdel;
if(@$_POST['hidden']){
    
    if (isset($_POST["link"])){
        if (@$_POST["page_link"]!=''){
            $_POST["link"]=$_POST["page_link"]; 

            if ($_SESSION["version"]=='ru'){
                $_POST["link"] = str_replace('/ru/', '/', $_POST["link"]);                
            }
            $_POST["link"] = str_replace('page/', '', $_POST["link"]);  

            if (substr($_POST["link"], strlen($_POST["link"])-1, 1)!='/'){
                $_POST["link"] = $_POST["link"].'/';
            }
        }
        
        if(!empty($_FILES['page_file']['tmp_name'])){
            
            $text = rand(0,100000)."_".rand(500,1000000)."_".date("H");

            $upload = move_uploaded_file(
                $_FILES['page_file']['tmp_name'],
                $_SERVER['DOCUMENT_ROOT']."/upload/files/".$text.'.'.$ob->getExtension($_FILES['page_file']['name']));

            if ($upload){
                $_POST["link"] = "/upload/files/".$text.'.'.$ob->getExtension($_FILES['page_file']['name']);
            }
        }

    }


    if ($_POST['hidden']=='save'){
        $page = "elements.php".$ob->gets_go($_GET,"","");
    }else{
        $page = "";
    }
    
    $field = array();

    $translit='';

    $option = $ob->search_option($_GET['module'],$_GET["sub_module"],@$_GET['id_section'],array(""));
    while ($option_res = $option->fetch_array()){
        if ($option_res['type_field']=="i11"){
            $folder = "images";
        }
        if ($option_res['type_field']=="i12"){
            $folder="files";
        }

        switch($option_res['type_field']){
            case $option_res['type_field']=="i11" OR $option_res['type_field']=="i12":
            if(@$_POST['delete'.$option_res['id'].'']==1){
                $delete = $ob->select("i_".$_GET['module'],array("id"=>$_GET['id']),"");
                $delete_res = $delete->fetch_array();
                @unlink($_SERVER['DOCUMENT_ROOT']."/upload/".$folder."/".$delete_res[''.$option_res['name_en'].'']);
                if ($folder=='images'){
                    @unlink($_SERVER['DOCUMENT_ROOT']."/upload/".$folder."/small/".$delete_res[''.$option_res['name_en'].'']);
                    @unlink($_SERVER['DOCUMENT_ROOT']."/upload/".$folder."/big/".$delete_res[''.$option_res['name_en'].'']);
                }
                @$field[$option_res['name_en']]="";
            }
            if(!empty($_FILES[''.$option_res['name_en'].'']['tmp_name'])){
                if($_FILES[''.$option_res['name_en'].'']['size']<=$option_res['size_file']){

                    $format = explode("|", $option_res['format_file']);
                    $name = $_FILES['' . $option_res['name_en'] . '']['name'];
                    $ext = $ob->getExtension($name);

                    if (in_array($ext, $format)) {
                        $text   = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H");

                        $upload = move_uploaded_file(
                            $_FILES['' . $option_res['name_en'] . '']['tmp_name'], 
                            $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $folder . "/" . $text . '.' . $ext
                        );

                        $field[''.$option_res['name_en'].''] = $text. '.' . $ext;

                        if(!$upload){
                            $erorr='Не возможно загрузить файл!';
                        } else {
                            if ($folder=='images'){

                               $resizeObj_small = new resize(
                                '/upload/images/' . $text . '.' . $ext
                            );
                               $resizeObj_small->resizeImage(
                                $option_res['w_resize_small'], 
                                $option_res['h_resize_small'], 
                                $option_res['type_resize']
                            );
                               $resizeObj_small->saveImage(
                                '/upload/images/small/' . $text . '.' . $ext, 
                                100
                            );

                               $resizeObj_big = new resize(
                                '/upload/images/' . $text . '.' . $ext
                            );
                               $resizeObj_big->resizeImage(
                                $option_res['w_resize_big'], 
                                $option_res['h_resize_big'], 
                                $option_res['type_resize']
                            );
                               $resizeObj_big->saveImage(
                                '/upload/images/big/' . $text . '.' . $ext, 
                                100
                            );

                               if ($option_res['watermark'] != '') {
                                $resizeObj_big = new resize(
                                    '/upload/images/big/' . $text . '.' . $ext
                                );
                                $resizeObj_big->watermark(
                                    $_SERVER['DOCUMENT_ROOT'] . '/upload/images/big/' . $text . '.' . $ext, 
                                    $_SERVER['DOCUMENT_ROOT'] . '/upload/images/watermark/' . $option_res['watermark']
                                );
                            }
                        }
                    }
                }else{
                    $erorr='Не верный формат файла';
                }
            }else{
                $erorr="Файл превышает размер ".$option_res['size_file']." байт";
            }
        }
        break;
        case $option_res['type_field']=="i3":
        $login_search=$mysql->query("select * from i_".$_GET['module']." where `".$option_res['name_en']."`='".sha1($_POST[''.$option_res['name_en'].''])."' AND id<>'".$ob->pr($_GET['id'])."'");
        if($login_search->num_rows>0){$erorr="Извените, но данный логин уже существует!";}else{$field[''.$option_res['name_en'].'']=sha1($_POST[''.$option_res['name_en'].'']);}
        break;

        case $option_res['type_field']=="i4":
        $field[''.$option_res['name_en'].'']=sha1(@$_POST[''.$option_res['name_en'].'']);
        break;

        default:
        $field[''.$option_res['name_en'].'']=addslashes(@$_POST[''.$option_res['name_en'].'']);
        break;
    }


    $translit = translit(trim(@$_POST['name']));
    $translit = $translit.'-'.(intval($_GET["id"]));    
    if ($_POST["url"]!=''){
        $translit = translit(str_replace('-'.intval($_GET["id"]), '', trim($_POST["url"]))).'-'.(intval($_GET["id"]));    
    }

    $s = $mysql->query("select * from i_$nameElement where id=".intval($_GET["id"])."");
    $r = $s->fetch_array();

    if ($r["active"]==1 && (!in_array(($r["url"]),A::$app->classes()))){

        $mysql->query("update i_menu set link='/".$_SESSION["version"]."/$nameModule/".$translit."/' where link='/".$_SESSION["version"]."/$nameModule/".$r["url"]."/'");
    }

    $s = $mysql->query("select * from i_$nameElement where id=".intval($_GET["id"])."");
    $r = $s->fetch_array();

    if (!in_array(A::$app->mb_ucfirst($r["url"]),A::$app->classes())){
        $field["url"] = @$translit;
    }

}

if(!@$erorr)
{   
    if(@$_POST['version']){$ver=$_POST['version'];}else{$ver=$_SESSION['version'];}
    $field['version']=$ver;
    $ob->update("i_".$_GET['module'],$field,$_GET['id'],$page);
    alias($_GET['id'], $nameElement, $translit, 0);
    
    $mysql->query("insert into i_logs values(0, CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','update','".$ob->pr($_GET['module'])."', '".@$_POST["name"]."', '".$translit."')");

    
}else
{
    $ob->alert($erorr);
}
}
//проверка на валидность
$ob->script_view($_GET['module'],$_GET['sub_module'],@$_GET['id_section'],array("required_fields"=>1));
?>
<?
$sql = "select name from i_modules where folders='$nameModule'";
$res = $mysql->get($sql, 1);
?>
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
                <li class="crumb-active"> Редактирование элемента</li>
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
                            <?
                            $option = $ob->search_option($_GET['module'], $_GET["sub_module"], @$_GET['id_section'], array("head"=>1));
                            if ($option->num_rows>0){
                            ?>
                            <li >
                                <a href="#tab2_2" data-toggle="tab" aria-expanded="false">Meta информация</a>
                            </li>
                            <? } ?>
                            <?
                            $option = $ob->search_option($_GET['module'], $_GET["sub_module"], @$_GET['id_section'], array("head"=>2));
                            if ($option->num_rows>0){
                            ?>
                            <li >
                                <a href="#tab2_3" data-toggle="tab" aria-expanded="false">Изображения</a>
                            </li>
                            <? } ?>
                            <?
                            $option = $ob->search_option($_GET['module'], $_GET["sub_module"], @$_GET['id_section'], array("head"=>3));
                            if ($option->num_rows>0){
                            ?>
                            <li >
                                <a href="#tab2_4" data-toggle="tab" aria-expanded="false">Дополнительные поля</a>
                            </li>
                            <? } ?>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content pn br-n">
                          <div id="tab2_1" class="tab-pane active">
                            <div class="panel  top mb35">
                                <div class="panel-body bg-white">
                                  <div class="admin-form">
                                    <?
                                    $option = $ob->search_option($_GET['module'], $_GET["sub_module"], @$_GET['id_section'], array("head"=>0));

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
                                            ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
                                            </div>

                                            </div>';
                                        } else {

                                            if ($option_res['name_en'] == 'link') {
                                                echo '<div class="section row mb10">
                                                <label class="field-label col-md-3 text-right">Введите ссылку:</label>
                                                <div class="col-md-9">
                                                ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
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
                                                ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
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
                    <div id="tab2_2" class="tab-pane ">
                        <div class="panel  top mb35">
                            <div class="panel-body bg-white">
                              <div class="admin-form">
                                <?
                                $sql = "select url from i_$nameElement where id='".intval($_GET["id"])."'";
                                $res = A::$db->get($sql, 1);
                                echo '<div class="section row mb10">
                                <label class="field-label col-md-3 text-right">
                                URL:
                                </label>
                                <div class="col-md-9">
                                <input name="url" type="text" id="url" size="30" class="form-control" value="'.$res["url"].'">
                                </div>
                                </div>';
                                $option = $ob->search_option($_GET['module'], $_GET["sub_module"], @$_GET['id_section'], array("head"=>1));

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
                                        ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
                                        </div>

                                        </div>';
                                    } else {

                                        if ($option_res['name_en'] == 'link') {
                                                echo '<div class="section row mb10">
                                                <label class="field-label col-md-3 text-right">Введите ссылку:</label>
                                                <div class="col-md-9">
                                                ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
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
                                            ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
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
                <div id="tab2_3" class="tab-pane ">
                    <div class="panel  top mb35">
                        <div class="panel-body bg-white">
                          <div class="admin-form">
                            <?
                            $option = $ob->search_option($_GET['module'], $_GET["sub_module"], @$_GET['id_section'], array("head"=>2));

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
                                    ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
                                    </div>

                                    </div>';
                                } else {

                                    if ($option_res['name_en'] == 'link') {
                                                echo '<div class="section row mb10">
                                                <label class="field-label col-md-3 text-right">Введите ссылку:</label>
                                                <div class="col-md-9">
                                                ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
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
                                        ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
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
            <div id="tab2_4" class="tab-pane ">
                <div class="panel  top mb35">
                    <div class="panel-body bg-white">
                      <div class="admin-form">
                        <?
                        $option = $ob->search_option($_GET['module'], $_GET["sub_module"], @$_GET['id_section'], array("head"=>3));

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
                                ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
                                </div>

                                </div>';
                            } else {

                                if ($option_res['name_en'] == 'link') {
                                                echo '<div class="section row mb10">
                                                <label class="field-label col-md-3 text-right">Введите ссылку:</label>
                                                <div class="col-md-9">
                                                ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
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
                                    ' . $ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"") . '
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
