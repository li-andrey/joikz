<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/header.php";

$nameRazdel = 'cat';
$nameElement = 'cat_elements';
$nameModule = 'catalog';

$parent = A::$app->catalog->parent($_GET["id_section"]);
$colorField = ['active', 'name', 'id_sort', 'image', 'image1', 'image2', 'image3', 'image4','image5','image6', 'image7', 'image8', 'image9', 'color'];

if (@$_POST['hidden']) {


    if (isset($_POST["cat"])) {
        $sql = "delete from i_shop_cat where id_shop=".intval($_GET["id"])."";
        A::$db->query($sql);
        foreach ($_POST["cat"] as $key => $r) {
            if ($r!=''){
                $sql = "insert into i_shop_cat set id_shop='0', id_cat='".$r."'";
                A::$db->query($sql);
            }
        }
        $cat = $_POST["cat"][0];
        $_POST["cat"] = @join(',', @$_POST["cat"]);
    }else{
        $cat = intval($_GET["id_section"]);
    }

    if (isset($_POST["cat"])){

        //$_GET["id_section"] = $cat;
    }

    if (isset($_POST["link"])) {

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

        if (!empty($_FILES['page_file']['tmp_name'])) {
            $text   = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H");
            $upload = move_uploaded_file(
                $_FILES['page_file']['tmp_name'], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/files/" . $text . '.' . $ob->getExtension($_FILES['page_file']['name'])
            );

            if ($upload) {
                $_POST["link"] = "/upload/files/" . $text . '.' . $ob->getExtension($_FILES['page_file']['name']);
            }
        }
    }

    if (@$_POST['hidden'] == 'save') {
        $page = "index.php?id_section=" . @$_GET['id_section'] . "&module=" . @$_GET['module'];
    } else { 
        $page = "";
    }

    $option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array(""));
    $i = 1;
    $url = '';


    
    while ($option_res = $option->fetch_array()) {

        if ($parent==2 && $_GET["id_section"]!=2){
            if (!in_array($option_res["name_en"], $colorField)){
                continue;
            }
        }

        if (!@$field) {$field = ',';}

        @$field_name .= $option_res['name_en'];
        if ($option->num_rows != $i) {$field_name .= ',';}

        switch ($option_res['type_field']) {

            case $option_res['type_field'] == "i11" or $option_res['type_field'] == "i12":

            if ($option_res['type_field'] == "i11") {
                $folder = "images";
            }
            if ($option_res['type_field'] == "i12") {
                $folder = "files";
            }

            if (!empty($_FILES['' . $option_res['name_en'] . '']['tmp_name'])) {
                if ($_FILES['' . $option_res['name_en'] . '']['size'] <= $option_res['size_file']) {

                    $format = explode("|", $option_res['format_file']);
                    $name = $_FILES['' . $option_res['name_en'] . '']['name'];
                    $ext = $ob->getExtension($name);

                    if (in_array($ext, $format)) {
                        $text   = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H");

                        $upload = move_uploaded_file(
                            $_FILES['' . $option_res['name_en'] . '']['tmp_name'], 
                            $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $folder . "/" . $text . '.' . $ext
                        );

                        $field .= "'" . $text . '.' . $ext . "'";

                        if (!$upload) {
                            $erorr = 'Не возможно загрузить файл!';
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
                } else { 
                    $erorr = 'Не верный формат файла';
                }
            } else { 
                $erorr = "Файл превышает размер " . $option_res['size_file'] . " б";
            }
        } else { 
            $field .= "''";
        }

        break;
        default:
        $field .= "'" . addslashes(@$_POST['' . $option_res['name_en'] . '']) . "'";
        break;
    }

    $translit = translit(trim(@$_POST['name']));

    if ($option->num_rows != ($i++)) {$field .= ',';}
}


if (!@$erorr) {
    if (@$_POST['version']) {
        $ver = @$_POST['version'];
    } else { 
        $ver = $_SESSION['version'];
    }

    if (@$_POST["url"]!=''){
        $translit = translit(trim($_POST["url"]));
    }



    $sql = "INSERT INTO i_" . $ob->pr($_GET['module']) . " (id_section,version,url," . rtrim($field_name, ',') . ") ";
    $sql.= "VALUES ('" . @$_GET['id_section'] . "','" . $ver . "','" . @$translit . "'" . rtrim(@$field, ',') . ")";


    $i = $mysql->query($sql);
    
    if (!$i) {
        $ob->alert("Ошибка добавления поля! ".$mysql->error);
    }else{
        $id = $mysql->insert_id;

        $sql = "update i_shop_cat set id_shop='$id' where id_shop=0";
        A::$db->query($sql);

        $translit = $translit.'-'.$id;
        $mysql->query("update i_$nameRazdel set url='" . $translit . "', slug='$id' where id='" . $id . "'");
        alias($id, $nameRazdel, $translit, 1);
    }

    $mysql->query("insert into i_logs values(0, CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','add','".$ob->pr(@$_GET['module'])."', '".@$_POST["name"]."', '".$translit."', '".$ob->pr($sql)."')");

    if ($i) { $ob->go($page);}
} else {
    $ob->alert($erorr);
}
}
$ob->script_view($_GET['module'], "", @$_GET['id_section'], array("required_fields" => 1));
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
                <li class="crumb-active"> Добавление категории</li>
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
                        $option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array("head"=>1));
                        if ($option->num_rows>0){
                            ?>
                            <li style="<?=($parent==2 && $_GET["id_section"]!=2?'display: none;':'')?>">
                                <a href="#tab2_2" data-toggle="tab" aria-expanded="false">Meta информация</a>
                            </li>
                        <? } ?>
                        <?
                        $option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array("head"=>2));
                        if ($option->num_rows>0){
                            ?>
                            <li >
                                <a href="#tab2_3" data-toggle="tab" aria-expanded="false">Изображения</a>
                            </li>
                        <? } ?>
                        <?
                        $option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array("head"=>3));
                        if ($option->num_rows>0){
                            ?>
                            <li style="<?=($parent==2 && $_GET["id_section"]!=2?'display: none;':'')?>">
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
                                $option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array("head"=>0));

                                while ($option_res = $option->fetch_array()) {

                                    if ($parent==2 && $_GET["id_section"]!=2){
                                        if (!in_array($option_res["name_en"], $colorField)){
                                            continue;
                                        }
                                    }

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


                                            if ($option_res['name_en']=='cat'){

                                            $cat = array();

                                            echo '<div class="section row mb10">
                                            <label class="field-label col-md-3 text-right">
                                            ' . $star . '&nbsp;' . $option_res['name_ru'] . ':
                                            </label>
                                            <div class="col-md-9"><div style="max-height:200px; border:1px solid #aaa; background:#fff; border-radius:8px; padding:10px; margin-bottom:10px; overflow:auto;">';
                                            $sql = "select * from i_cat where id_section=1 and active=1 and version='".$_SESSION["version"]."' order by id_sort asc";
                                            $res = A::$db->get($sql);
                                            foreach ($res as $key => $v) {
                                                echo '<p style="font-size:12px;"><label style="font-size:12px;"><input type="checkbox" name="cat[]" value="'.$v["id"].'" '.(in_array($v["id"], $cat)?'checked':'').'> '.$v["name"].'</label></p>';
                                                $sql1 = "select * from i_cat where id_section=".$v["id"]." and active=1 and version='".$_SESSION["version"]."' order by id_sort asc";
                                                $res1 = A::$db->get($sql1);
                                                foreach ($res1 as $key1 => $v1) {
                                                    echo '<p style="font-size:14px; padding-left:20px; font-weight:bold; text-transform:uppercase; " ><input type="checkbox" name="cat[]" value="'.$v1["id"].'" '.(in_array($v1["id"], $cat)?'checked':'').'> '.$v1["name"].'</label></p>';
                                                    $sql2 = "select * from i_cat where id_section=".$v1["id"]." and active=1 and version='".$_SESSION["version"]."' order by id_sort asc";
                                                    $res2 = A::$db->get($sql2);
                                                    foreach ($res2 as $key2 => $v2) {
                                                        echo '<p style="font-size:12px; padding-left:40px; font-style:italic;"><label style="font-size:12px;"><input type="checkbox" name="cat[]" value="'.$v2["id"].'" '.(in_array($v2["id"], $cat)?'checked':'').'> '.$v2["name"].'</label></p>';
                                                        $sql3 = "select * from i_cat where id_section=".$v2["id"]." and active=1 and version='".$_SESSION["version"]."' order by id_sort asc";
                                                        $res3 = A::$db->get($sql3);
                                                        foreach ($res3 as $key3 => $v3) {
                                                            echo '<p style="font-size:12px; padding-left:60px; font-style:italic;"><label style="font-size:12px;"><input type="checkbox" name="cat[]" value="'.$v3["id"].'" '.(in_array($v3["id"], $cat)?'checked':'').'> '.$v3["name"].'</label></p>';
                                                            $sql4 = "select * from i_cat where id_section=".$v3["id"]." and active=1 and version='".$_SESSION["version"]."' order by id_sort asc";
                                                            $res4 = A::$db->get($sql4);
                                                            foreach ($res4 as $key4 => $v4) {
                                                                echo '<p style="font-size:12px; padding-left:80px; font-style:italic;"><label style="font-size:12px;"><input type="checkbox" name="cat[]" value="'.$v4["id"].'" '.(in_array($v4["id"], $cat)?'checked':'').'> '.$v4["name"].'</label></p>';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            echo  '</div></div></div>';
                                        }else {

                                            echo '<div class="section row mb10 field_'.$option_res['name_en'].'">
                                            <label class="field-label col-md-3 text-right">
                                            ' . $star . '&nbsp;' . $option_res['name_ru'] . ':
                                            </label>
                                            <div class="col-md-9 ">
                                            ' . $ob->input_view($option_res['id'], "", "", "", "") . '
                                            </div>
                                            </div>';
                                        }
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
                        echo '<div class="section row mb10">
                        <label class="field-label col-md-3 text-right">
                        URL:
                        </label>
                        <div class="col-md-9">
                        <input name="url" type="text" id="url" size="30" class="form-control" value="">
                        </div>
                        </div>';
                        $option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array("head"=>1));

                        while ($option_res = $option->fetch_array()) {
                            if ($parent==2 && $_GET["id_section"]!=2){
                                if (!in_array($option_res["name_en"], $colorField)){
                                    continue;
                                }
                            }
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
        <div id="tab2_3" class="tab-pane ">
            <div class="panel  top mb35">
                <div class="panel-body bg-white">
                  <div class="admin-form">
                    <?
                    $option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array("head"=>2));

                    while ($option_res = $option->fetch_array()) {
                        if ($parent==2 && $_GET["id_section"]!=2){
                            if (!in_array($option_res["name_en"], $colorField)){
                                continue;
                            }
                        }
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
    <div id="tab2_4" class="tab-pane ">
        <div class="panel  top mb35">
            <div class="panel-body bg-white">
              <div class="admin-form">
                <?
                $option = $ob->search_option($_GET['module'], "", @$_GET['id_section'], array("head"=>3));

                while ($option_res = $option->fetch_array()) {
                    if ($parent==2 && $_GET["id_section"]!=2){
                        if (!in_array($option_res["name_en"], $colorField)){
                            continue;
                        }
                    }
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
<style>
<?  if ($parent==2 && $_GET["id_section"]!=2){ ?>
    .field_color{
        display: block;
    }
    .field_name{
        display: none;
    }
<? }else{ ?>
    .field_color{
        display: none;
    }
    .field_name{
        display: block;
    }
<? } ?>
</style>

<script>
    (function($){
        $('.field_color select').on("change", function(e) {
            e.preventDefault()

            $('.field_name input').val($(this).val())
        });
    })(jQuery)
</script>
<?require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/footer.php"?>
