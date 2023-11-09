<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/header.php";

$nameRazdel = 'cat';
$nameElement = 'cat_elements';
$nameModule = 'catalog';

$_GET["id_section"] = intval(@$_GET["id_section"]);
$_GET["module"] = $nameRazdel;
if (@$_POST['actions']) {
    if (count(@$_POST['select_ch']) > 0) {
        foreach ($_POST['select_ch'] as $k => $v) {
            if ($_POST['actions'] == "delete") {
                $ob->delete_tree($v, $nameRazdel);
            }
        }
    } else { 
        $ob->alert("Необходимо выбрать элементы таблицы!");
    }
}

if (@$_GET['id'] and isset($_GET['active'])) {
    $ob->update("i_".$nameRazdel, array("active" => $_GET['active']), $_GET['id'], "");
}

if (@$_GET['copyr'] == "true" and $_GET['id']) {
    $return = duplicate('i_'.$nameRazdel, 'id', $_GET["id"]);
    $ob->go("index.php?module=".$nameRazdel."&id_section=" . $_GET["id_section"]);
}

if (@$_GET['delete'] == "true" and $_GET['id']) {
    $ob->delete_tree($_GET['id'], $nameRazdel);
}

$field      = array('id'=>"ID");
$field['#'] = "Разделов / Элементов";

$select = $ob->search_option($nameRazdel, "", @$_GET['id_section'], array("select_fields" => 1));

$m = 0;
while ($res = $select->fetch_array()) {
    if ($m == 0) {
        $hide_field_name = $res['name_en'];
        $m               = 1;
    }
    $field['' . $res['name_en'] . ''] = $res['name_ru'];
}

foreach ($field as $k => $v) {
    if ($k == 'page_name') {
        $field["url"] = 'Ссылка';
    }
}

$where = '';
foreach ($field as $k => $v) {
    if (@$_POST['' . $k . '']) {
        $where .= "AND " . $k . " LIKE '%" . $ob->pr($_POST['' . $k . '']) . "%'";
    }
}

if (@$_GET['id_section']) {
    $where .= "AND id_section='" . $ob->pr($_GET['id_section']) . "'";
} else {
    $where .= "AND id_section='0'";
}

if (!isset($_GET["order"])) {
    if (@$_SESSION[$nameRazdel]["order"] == '') {
        $_SESSION[$nameRazdel]["order"] = "id asc";
    }
} else {
    $_SESSION[$nameRazdel]["order"] = $ob->pr($_GET['order']);
}

if ($_SESSION[$nameRazdel]["order"] == '') {
    $_SESSION[$nameRazdel]["order"] = 'id asc';
}

if (!isset($_GET["start"])) {
    if (@$_SESSION[$nameRazdel]["start"] == '') {
        $_SESSION[$nameRazdel]["start"] = 0;
    }
} else {
    $_SESSION[$nameRazdel]["start"] = $ob->pr($_GET['start']);
}

if ($_SESSION[$nameRazdel]["start"] == '') {
    $_SESSION[$nameRazdel]["start"] = 0;
}

if (!isset($_GET["number"])) {
    if (@$_SESSION[$nameRazdel]["number"] == '') {
        $_SESSION[$nameRazdel]["number"] = 20;
    }
} else {
    $_SESSION[$nameRazdel]["number"] = $ob->pr($_GET['number']);
}
if ($_SESSION[$nameRazdel]["number"] == '' || $_SESSION[$nameRazdel]["number"] == 0) {
    $_SESSION[$nameRazdel]["number"] = 20;
}

$sql = "select * from i_$nameRazdel where (version='all' OR version='" . $_SESSION['version'] . "') ";
$sql.= "" . $where . " ORDER BY " . $_SESSION[$nameRazdel]["order"] . ",'puttime' DESC LIMIT " . $_SESSION[$nameRazdel]["start"] . "," . $_SESSION[$nameRazdel]["number"] . "";

$select = $mysql->query($sql);

if ($select->num_rows == 0) {
    $_SESSION[$nameRazdel]["start"] = 0;
    $sql = "select * from i_$nameRazdel where (version='all' OR version='" . $_SESSION['version'] . "') ";
    $sql.= "" . $where . " ORDER BY " . $_SESSION[$nameRazdel]["order"] . ",'puttime' DESC ";
    $sql.= "LIMIT " . $_SESSION[$nameRazdel]["start"] . "," . $_SESSION[$nameRazdel]["number"] . "";
    $select = $mysql->query($sql);
}

$select_all = $mysql->query("select * from  i_$nameRazdel where (version='all' OR version='" . $_SESSION['version'] . "') " . $where . "");
?>
<script type="text/javascript">
    function del(i){
        if (confirm("Вы действительно хотите удалить выбранный элемент?")){
            document.location.href='?start=<?=@$_GET['start']?>&id_section=<?=@$_GET['id_section']?>&module=<?=@$_GET['module']?>&delete=true&id='+i;
        }
    }
    function copyr(i){
        document.location.href='<?=$ob->gets_go($_GET, "start", @$_GET['start'])?>&copyr=true&id='+i;
    }
    function del_pr(){
        if(document.form_index.actions.options.value!='delete'){
            document.form_index.submit();
        }else{
            if (confirm("Вы действительно хотите удалить выбранные элементы?")){
                document.form_index.submit();
            }
        }
    }
</script>
<?
$sql = "select name from i_modules where folders='$nameModule'";
$res = $mysql->get($sql, 1);
?>
<form id="form_index" name="form_index" method="post" action="">
    <!-- Start: Topbar-->
    <header id="topbar" class="alt" style="padding-bottom: 0px;">
        <div class="topbar-left pull-left">
            <ol class="breadcrumb">
                <li class="crumb-icon"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
                <li class="crumb-active"><a href="index.php"><?=$res["name"]?></a></li>
                <?
                admin_print_dir(@$_GET["module"], @$_GET["sub_module"]);
                ?>
            </ol>
        </div>
        <div class="topbar-right">

            <ul id="filter_menu" class="MenuBarHorizontal" style="list-style: none;">
                <li class="dropdown menu-merge">
                    <div class="navbar-btn btn-group" style="margin-top: 0px;">
                        <button type="button" data-toggle="dropdown" class="btn btn-sm dropdown-toggle">Поиск</button>
                        <div role="menu" class="dropdown-menu dropdown-persist w400 animated animated-shorter fadeIn" style="left: auto; right: 0px;">
                            <div class="panel mbn">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ol style="list-style: none; margin-left: 0px; padding-left: 20px;">
                                            <?
                                            $i = 0;
                                            foreach ($field as $k => $v) {
                                                if (@$_POST['field_' . $k . ''] == 1) {$view = "checked";} else { $view = "";}
                                                if ($i != 1) {
                                                    ?>
                                                    <li>
                                                        <label>
                                                            <input name="field_<?=$k?>" type="checkbox" value="1" onclick="SectionClick('tr_<?=$k?>')" <?=$view?> />
                                                            <?=$v?>
                                                        </label>
                                                    </li>
                                                    <?
                                                }
                                                $i++;
                                            }
                                            ?>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <?
                                        $i = 0;
                                        foreach ($field as $k => $v) {
                                            if ($i != 1) {
                                                if (@$_POST['field_' . $k . ''] == 1) {$view = "block";} else { $view = "none";}
                                                ?>
                                                <div id="tr_<?=$k?>" style="DISPLAY:<?=$view?>; padding-bottom: 5px;">
                                                    <input name="<?=$k?>" type="text" id="textfield" size="20" value="<?=@$_POST[$k]?>" placeholder="<?=$v?>" />
                                                </div>
                                                <?
                                            }
                                            $i++;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="panel-footer text-center p7">
                                    <button type="submit" class="btn btn-xs btn-dark ">Поиск</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

        </div>
    </header>
    <section id="content" class="table-layout">
        <div class="tray tray-center" style="padding-top: 0px;">
            <div class="tray-inner">
              <div class="row flex-column-reverse-before-md">
                <div class="col-sm-12">
                    <div class="panel">
                      <div class="panel-heading">
                        <div class="widget-menu pull-left">

                            <a href="add.php<?=$ob->gets_go($_GET, "module", $nameRazdel)?>" class="btn btn-system btn-sm">
                                <span class="fa fa-plus"></span> Добавить категорию
                            </a>

                        </div>
                        <div class="widget-menu pull-right">
                            <a href="option.php?module=<?=$nameRazdel?>&amp;id_section=<?=@$_GET['id_section']?>" target="_self" class="btn btn-system  btn-sm">
                             <span class="fa fa-cog"></span> Настроить
                         </a>
                         <a href="<?=$ob->gets_go($_GET, "excel", "true")?>" target="_self" class="btn btn-system btn-sm" style="margin-right: 0px;">
                             <span class="glyphicon glyphicon-open"></span> Export
                         </a>
                         <a href="importc.php<?=$ob->gets_go($_GET, "", "")?>" target="_self" class="btn btn-system btn-sm" style="margin-right: 20px;">
                             <span class="glyphicon glyphicon-save"></span> Import
                         </a>

                         Показывать по
                         <select name="jumpMenu" id="jumpMenu" class="form-control" style="display: inline-block; width: auto;" onchange="MM_jumpMenu('parent',this,0)">
                            <?
                            $elements = array(10, 20, 40, 80, 100, 1000);
                            foreach ($elements as $k => $v) {
                                if ($v == $_SESSION[$nameRazdel]["number"]) {$sel = 'selected';} else { $sel = '';}
                                echo '<option value="' . $ob->gets_go($_GET, "number", $v) . '" ' . $sel . '>' . $v . '</option>';
                            }
                            ?>
                        </select>
                        | <select name="actions" id="actions" style="width:130px; display: inline-block;" class="form-control">
                            <?
                            $actions = array("" => "выбор действия", "active" => "Показывать", "notactive" => "Не показывать", "delete" => "Удалить");
                            foreach ($actions as $k => $v) {
                                echo '<option value="' . $k . '">' . $v . '</option>';
                            }
                            ?>
                        </select>
                        <input type="button" class="btn btn-dark btn-sm" name="send" id="send" value="Отправить" onclick="del_pr()" />
                    </div>
                </div>
                <div class="panel-body">

                    <table width="100%" class="table table-responsive table-hover table-bordered" style="color: #000; ">
                        <thead>
                            <tr class="dark">
                                <td  height="35" align="center"  class="check_table_title">
                                    <input type="checkbox" name="allbox" id="allbox" onclick="CheckAll()" />
                                </td>
                                <td width="65" align="center" class="check_table_title" >
                                    Действия
                                </td>
                                <?
                                foreach ($field as $k => $v) {
                                    if (@$_GET['order'] == $k or @$_GET['order'] == $k . " DESC") {
                                        $style = 'top_table_title_back';
                                        if ($_GET['order'] != $k . " DESC") {$k .= " DESC";}
                                    } else { $style = 'top_table_title';}
                                    if ($k == "#") {$width = 'width="20%"';} else { $width = '';}
                                    if ($k!='#'){
                                        echo '<td  class="' . $style . '" ' . $width . ' onmouseover="this.className=\'top_table_title_back\';"
                                        onmouseout="this.className=\'' . $style . '\';"
                                        onclick="document.location.href=\'index.php' . $ob->gets_go($_GET, "order", $k) . '\'"
                                        title="сортировка по полю ' . $v . '">' . $v . '</td>';
                                    }else{
                                        echo '<td  class="' . $style . '" ' . $width . ' onmouseover="this.className=\'top_table_title_back\';"
                                        onmouseout="this.className=\'' . $style . '\';"
                                        >' . $v . '</td>';
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <?

                        while ($res = $select->fetch_array()) {

                            $section = $mysql->query("select * from i_$nameRazdel where (version='all' OR version='" . $_SESSION['version'] . "')

                              AND id_section='" . $res['id'] . "'");

                            $elements = $ob->select("i_$nameElement", array("id_section" => $res['id'], "version" => $_SESSION['version']), "");



                            $li_style = 'style="height:23px;background-image: url(/admin/modules/theme/default/images/menu_03.gif);

                            background-repeat: repeat-y;

                            background-position: left top; text-align:left; padding-left: 23px;"';



                            echo '<tr onMouseOver="this.bgColor=\'#f3f2da\'"

                            onMouseOut="this.bgColor=\'#fafaf0\'" onmouseup="if (!stopMoving()) if (!otherClicks(event)) return CheckTR(this);"

                            onmousedown="startMoving()" id="str' . $res['id'] . '"

                            ondblclick="javascript:document.location.href=\'edit.php' . $ob->gets_go($_GET, "id", $res['id']) . '&module='.$nameRazdel.'\'" >



                            <td width="40" align="center" height="25"><INPUT id="chb' . $res['id'] . '" type="checkbox" onclick="CheckTR(this);"

                            value="' . $res['id'] . '" name="select_ch[]"></td>



                            <td align="center">

                            <ul id="actions_menu' . $res['id'] . '" class="MenuBarHorizontal">

                            <li ><span class="glyphicon glyphicon-align-justify" style="font-size:20px; color:#333;"></span>

                            <ul>

                            <li ' . $li_style . '><a href="'.$ob->gets_go($_GET, "active", 1).'&id=' . $res['id'] . '" >Показывать</a></li>

                            <li ' . $li_style . '><a href="'.$ob->gets_go($_GET, "active", 0).'&id=' . $res['id'] . '"> Не показывать</a></li>

                            <li ' . $li_style . '><a href="javascript:copyr(\'' . $res['id'] . '\')">Дублировать</a></li>

                            <li ' . $li_style . '><a href="edit.php' . $ob->gets_go($_GET, "id", $res['id']) . '&module='.$nameRazdel.'">Редактировать</a></li>
                            <li ' . $li_style . '><a href="option.php' . $ob->gets_go($_GET, "id", $res['id']) . '&module='.$nameElement.'&id_section='.$res['id'].'">Настроить</a></li>

                            <li ' . $li_style . '><a href="javascript:del(\'' . $res['id'] . '\')">Удалить</a></li>



                            </ul>

                            </li>



                            </ul>

                            <script type="text/javascript">

                            <!--

                            var MenuBar1 = new Spry.Widget.MenuBar("actions_menu' . $res['id'] . '", {imgDown:"", imgRight:""});

      //-->

                            </script>

                            </td>

                            <td class="id_block_style" align="center">' . $res['id'] . '</td>

                            <td class="table_value"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                            <tr>

                            <td width="371" align="center" style="border-right: 2px solid #ecebcf;" >

                            <a href="?id_section=' . $res['id'] . '&module='.$nameRazdel.'" style="font-weight:bold;">' . $section->num_rows . '</a>

                            </td>

                            <td width="372" align="center"><a

                            href="elements.php?module='.$nameElement.'&sub_module='.$nameRazdel.'&id_section=' . $res['id'] . '" style="font-weight:bold;">

                            ' . $elements->num_rows . '</a></td></tr>

                            </table></td>';



                            $field2 = $ob->search_option($nameRazdel, "", @$_GET['id_section'], array("select_fields" => 1));

                            while ($field_res = $field2->fetch_array()) {



                                if ($res['' . $field_res['name_en'] . ''] != "" and ($field_res['type_field'] == "i10" or $field_res['type_field'] == "i11")) {

                                    if ($field_res['type_field'] == "i11") {

                                        $text = '<img src="/admin/resize.php?url=' . $_SERVER['DOCUMENT_ROOT'] . '/upload/images/' . $res['' . $field_res['name_en'] . ''] . '&w=100&h=90">';

                                    }



                                    if ($field_res['type_field'] == "i12") {}



                                } else { $text = '';}



                            if ($field_res['type_field'] == "i7") {

                                if ($res['' . $field_res['name_en'] . ''] == 1) {$text = 'Да';} else { $text = 'Нет';}

                            }



                            if (!@$text) {

                                echo '<td class="table_value">' . substr(strip_tags($res['' . $field_res['name_en'] . '']), 0, 200) . '&nbsp;</td>';

                            } else {

                                echo '<td class="table_value">' . $text . '&nbsp;</td>';

                            }

                        }

                        foreach ($field as $k => $v) {

                            if ($k == 'url') {

                             echo '<td class="table_value"><a href="/' .($_SESSION["version"]!='ru'?$_SESSION["version"].'/':'')  . '' . $res['url'] . '/">/' .($_SESSION["version"]!='ru'?$_SESSION["version"].'/':'')  . '' . $res['url'] . '/</a>&nbsp;</td>';

                         }

                     }

                     echo '</tr>';

                 }

                 ?>
                 <tfoot>
                    <tr class="dark">
                      <td height="30" align="right" colspan="<?=(sizeof($field)+2)?>">
                        Всего: <?=$select_all->num_rows?>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="pull-center" style="margin-top: 10px; text-align: center;">
            <div class="pagination" style="display: inline-block; padding: 0px 0px; margin: 0px 0px 10px 0;">
                <?
                $count = $select_all->num_rows;
                $por   = ceil($count / $_SESSION[$nameRazdel]["number"]);
                $link  = 0;
                $right = 0;

                for ($i = 1; $i <= $por; $i++) {
                    if ($_SESSION[$nameRazdel]["start"] <= $link) {
                        $right++;
                        if (($right == 1) and $_SESSION[$nameRazdel]["start"] > 0) {
                            if (($i - 5) > 0) {$minus = $i - 5;} else { $minus = 0;}
                            $link_main = $link - ($_SESSION[$nameRazdel]["number"] * 5);
                            if ($link_main < 0) {$link_main = 0;}
                            echo '<a href="' . $ob->gets_go($_GET, "start", $link_main) . '" class="small_text">
                            [' . $minus . ".." . ($i - 1) . ']
                            </a> &nbsp;';
                        }

                        if ($right <= 5) {
                            if ($_SESSION[$nameRazdel]["start"] == $link) {$style = 'style="font-weight:bold"';} else { $style = '';}
                            echo '<a href="' . $ob->gets_go($_GET, "start", $link) . '" class="small_text" ' . $style . '>' . $i . '</a>&nbsp;';
                        }
                        if (($right == 5) and (($link + $_SESSION[$nameRazdel]["number"]) < $count)) {
                            echo '<a href="' . $ob->gets_go($_GET, "start", ($link + $_SESSION[$nameRazdel]["number"])) . '" class="small_text" >[' . ($i + 1) . ".." . ($i + 5) . ']</a>';
                        }
                    }
                    $link = $_SESSION[$nameRazdel]["number"] + $link;
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
</section>
</form>

<?

if (@$_GET['excel'] == "true") {

    $select = $mysql->query("select * from i_$nameRazdel where (version='all' OR version='" . $_SESSION['version'] . "') " . $where . " ORDER BY " . $_SESSION[$nameRazdel]["order"] . ",'puttime' DESC ");



    $str = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#ecebcf">

    <tr>

    ';

    $field1 = $field;

    foreach ($field as $k => $v) {

        if (@$_GET['order'] == $k or @$_GET['order'] == $k . " DESC") {

            $style = 'top_table_title_back';

            if ($_GET['order'] != $k . " DESC") {$k .= " DESC";}



        } else { $style = 'top_table_title';}



        if ($k == "#") {$width = 'width="20%"';} else { $width = '';}



        $str .= '<td  class="' . $style . '" ' . $width . '>' . $v . '</td>';

    }

    $str .= '</tr>';



    while ($res = $select->fetch_array()) {

        $section  = $mysql->query("select * from i_$nameRazdel where (version='all' OR version='" . $_SESSION['version'] . "') AND id_section='" . $res['id'] . "'");

        $elements = $ob->select("i_$nameElement", array("id_section" => $res['id'], "version" => $_SESSION['version']), "");



        $str .= '<tr >

        <td class="id_block_style" align="center">' . $res['id'] . '</td>

        <td class="table_value">

        <a href="?id_section=' . $res['id'] . '&module='.$nameRazdel.'" style="color:#72ad0f;font-weight:bold;">

        ' . $section->num_rows . '</a> / <a href="elements.php?module='.$nameElement.'&id_section=' . $res['id'] . '" style="color:#72ad0f;font-weight:bold;">' . $elements->num_rows . '</a></td>';



        $field = $ob->search_option($nameRazdel, "", @$_GET['id_section'], array("select_fields" => 1));

        while ($field_res =$field->fetch_array()) {

            //если рисунок

            if ($res['' . $field_res['name_en'] . ''] != "" and ($field_res['type_field'] == "i10" or $field_res['type_field'] == "i11")) {

                if ($field_res['type_field'] == "i11") {$text = '<img src="/admin/resize.php?url=' . $_SERVER['DOCUMENT_ROOT'] . '/upload/images/' . $res['' . $field_res['name_en'] . ''] . '&w=100&h=90">';}

                if ($field_res['type_field'] == "i12") {}

            } else { $text = '';}

            //если checkbox

        if ($field_res['type_field'] == "i7") {

            if ($res['' . $field_res['name_en'] . ''] == 1) {$text = 'Да';} else { $text = 'Нет';}

        }



        if (!@$text) {

            $str .= '<td class="table_value">' . substr(strip_tags($res['' . $field_res['name_en'] . '']), 0, 200) . '&nbsp;</td>';

        } else {

            $str .= '<td class="table_value">' . $text . '</td>';

        }

    }



    foreach ($field1 as $k => $v) {

        if ($k == 'url') {

            $str .= '<td class="table_value"><a href="/' .($_SESSION["version"]!='ru'?$_SESSION["version"].'/':'')  . '' . $res['url'] . '/">/' .($_SESSION["version"]!='ru'?$_SESSION["version"].'/':'')  . '' . $res['url'] . '/</a></td>';

        }

    }



    $str .= '</tr>';

}

$str .= '</table>';

$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/admin/tmp/export.xls", "w+");

$wr   = fputs($file, $str);

if ($wr) {$ob->go("/admin/tmp/export.xls");} else { $ob->alert("Ошибка экспорта!");}

}

?>

<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/footer.php"
?>