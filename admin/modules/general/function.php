<?
class application extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cookie($str)
    {
        setcookie("web_auth", $str, time() + 7776000, '/', str_replace('www.', '', '.'.$_SERVER["HTTP_HOST"]));
    }

    public function pr_cookie()
    {
        $elements = explode("|", @$_COOKIE['web_auth']);
        $select = $this->select("i_user", array("login" => $elements['0'], "active" => 1), '');
        if ($select->num_rows == 1) {
            $res = $select->fetch_array();
            if ($res['password'] == $elements['1']) {
                $group = $this->select("i_user_group", array("id" => $res['id_group']), '');
                if ($group->num_rows > 0) {
                    $group_res = $group->fetch_array();
                    if ($group_res['active'] == "1") {
                        $_SESSION['user_id'] = $res['id'];
                        $_SESSION['id_group'] = $res['id_group'];
                        $version = $this->select("i_lang", array("active" => 1, "default" => 1), "id");
                        $version_res = $version->fetch_array();
                        if (!@$_SESSION['version']) {$_SESSION['version'] = $version_res['name_reduction'];}
                        if ($_SERVER['PHP_SELF'] == "/admin/index.php") {
                            header("LOCATION:/admin/modules/desktop.php");
                        }
                    }
                }
            }
        }
    }

    function getExtension($filename)
    {
        return @end(explode(".", $filename));
    }


    // убирем экранирование
    public function strip_slashes($str)
    {
        $str = str_replace("\'", "'", $str);
        $str = str_replace('\"', '"', $str);
        $str = str_replace("\\\\n", '\n', $str);
        $str = str_replace('\\\\r', '\r', $str);
        $str = str_replace("\\n", '\n', $str);
        $str = str_replace('\\r', '\r', $str);
        return $str;
    }

    public function admin()
    {
        if (@!$_SESSION['user_id'] and @!$_SESSION['id_group']) {
            header("Location: /admin/index.php");
            $this->go("/admin/index.php");
            exit;
        }
    }

    public function check_admin()
    {
        if (@$_SESSION['user_id'] and @$_SESSION['id_group']) {
            return true;
        } else {
            return false;
        }
    }

    public function pr($i)
    {
        $i = @strip_tags($i);
        $i = @htmlspecialchars($i, ENT_QUOTES);
        $i = @$this->sql->real_escape_string($i);
        return trim($i);
    }

    public function alert($i)
    {
        echo "<script>alert('" . addslashes($i) . "');</script>";
    }

    public function del_r($table, $ar)
    {

        if (count(@$ar) > 0) {
            $str = "WHERE ";
            $out = array();

            foreach ($ar as $k => $v) {
                $out[] = "`" . $this->pr($k) . "`='" . $this->pr($v) . "'";
            }

            $str .= implode(" AND ", $out);

        } else { $str = "";}

        $sql ="select slug from " . $table . " " . $str . "";
        $res = $this->sql->get($sql);
        foreach ($res as $key => $value) {
         $this->sql->query("delete from i_alias where slug='" . $value["slug"] . "' AND module='" . str_replace('i_','', $table) . "'");
     }

     $sql = "delete from " . $table . " " . $str . " ";
     $select = $this->sql->query($sql);
     if ($select) {
        $this->sql->query(
            "insert into i_logs values(0, CURRENT_TIMESTAMP, '" . $_SESSION['user_id'] . "', 'delete', '" . $table . "', '".$this->pr($sql)."')"
        );
        return $select;
    } else {
        $this->alert("Не возможно выполнить запрос!\\nREASON:" . $this->sql->error);
    }
}

    // выборка из таблицы
public function select($table, $ar, $order)
{
    if (count(@$ar) > 0) {
        foreach ($ar as $k => $v) {
            if (!@$str) {
                $str = "where " . $k . "='" . $v . "'";
            } else { 
                $str .= " AND `" . $this->pr($k) . "`='" . $this->pr($v) . "'";
            }
        }
    } else { 
        $str = "";
    }

    if (@$order) {
        $ord = "ORDER BY " . $this->pr($order);
    } else { 
        $ord = "";
    }
    if (strstr($_SERVER["REQUEST_URI"], 'import') && $table == 'i_option'){
        $select = $this->sql->query("select * from " . $table . " " . $str . " and head in(0,3) " . $ord . "");    
    }else{
        $select = $this->sql->query("select * from " . $table . " " . $str . " " . $ord . "");    
    }

    if ($select) {
        return $select;
    } else {
        $this->alert("Не возможно выполнить запрос!\\nREASON:" . $this->sql->error);
    }
}

public function go($i)
{
    echo "<meta http-equiv='refresh' content='0; url=" . $i . "'>";
}

public function insert($table, $field, $page)
{
    $field_ar = explode(",", $field);
    $field    = '';

    foreach ($field_ar as $k => $v) {
        if ($k == (count($field_ar) - 1)) {$ct = "";} else { $ct = ",";}
        $field .= ($v) . $ct;
    }
    $sql = "insert into $table values(0,$field)";
    $add = $this->sql->query($sql);
    $id = $this->sql->insert_id;
    if (!$add) {$this->alert("Record has not been added!\\nREASON:" . $this->sql->error);} else {
        $this->sql->query(
            "insert into i_logs values(0,CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','insert','" . $table . "', '".$this->pr($sql)."')"
        );

        if ($page != "") {
           echo '<section id="content" class="table-layout">
           <div class="row flex-column-reverse-before-md" style="padding-bottom:0px;">
           <div class="col-sm-12"><div class="alert alert-dark light alert-dismissable">
           <button type="button" data-dismiss="alert" aria-hidden="true" class="close"></button><i class="fa fa-cog pr10 hidden"></i><strong>Сохранение!</strong> Пожалуйста подождите</a>
           </div></div></div></section>';
           $this->go($page);
       }
   }
}

public function update($table, $field, $id, $page)
{
    $i = 0;
    foreach ($field as $k => $v) {
        if (($i != count($field)) and ($i != 0)) {@$str .= ",";}
        @$str .= '`' . $k . '`=\'' . ($v) . '\'';
        $i++;
    }
    $sql = "update " . $table . " set " . $str . " where id='" . intval($id) . "'";
    $update = $this->sql->query($sql);

    if (!$update) {$this->alert("Record has not update!\\nREASON:" . $this->sql->error);} else {
        $this->sql->query("insert into i_logs values(0, CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','update','" . $table . "', '".$this->pr($sql)."')");
        if ($page != "") {
            echo '<section id="content" class="table-layout">
            <div class="row flex-column-reverse-before-md" style="padding-bottom:0px;">
            <div class="col-sm-12"><div class="alert alert-dark light alert-dismissable">
            <button type="button" data-dismiss="alert" aria-hidden="true" class="close"></button><i class="fa fa-cog pr10 hidden"></i><strong>Сохранение!</strong> Пожалуйста подождите</a>
            </div></div></div></section>';
            $this->go($page);
        }
    }
}




public function gets_go($array_gets, $name, $value)

{



    $result_ar = array();

    if ($name != "") {array_push($result_ar, "$name=$value");}

    foreach ($array_gets as $k => $v) {

        if ($name != $k and $k != "delete") {

            if ($v !== "") {array_push($result_ar, "$k=$v");}

        }

    }

    $gets  = '?';

    $count = count($result_ar);

    $i     = 1;



    foreach ($result_ar as $k => $v) {

        if ($i == $count) {$gets .= $v;} else { $gets .= $v . "&";}

        $i++;

    }



    return $gets;

}



    // обновление данных в таблице




    // обновление данных в таблице

public function update_params($table, $field, $id, $page, $version)

{

    $i = 0;



    foreach ($field as $k => $v) {

        if (($i != count($field)) and ($i != 0)) {@$str .= ",";}

        @$str .= '`' . $k . '`=\'' . ($v) . '\'';

        $i++;

    }



    $update = $this->sql->query("update " . $table . " set " . $str . " where id='" . $this->pr($id) . "' and version='" . $version . "'");



    if (!$update) {$this->alert("Record has not update!\\nREASON:" . $this->sql->error);} else {



        $this->sql->query("insert into i_logs values(0,CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','update','" . $table . "')");



        if ($page != "") {

            echo '<div id="save_title" style="position:absolute; background-color:#FFFFFF;

            BORDER: #c4c5a6 1px solid; width:200px; height:50px;">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr>

            <td height="50" align="center" class="small_text"><img src="/admin/modules/theme/default/images/new.gif"  hspace="4" align="left" />подождите, идёт сохранение параметров</td>

            </tr>

            </table>

            </div>';

            $this->go($page);

        }

    }

}



    // ввывод поля для заполнения данных

public function input_view($type_id, $table, $field, $id, $tmp_name)

{


    $select = $this->select("i_option", array("id" => $type_id), "");

    $res    = $select->fetch_array();




    if ($tmp_name != "") {$tmp_name = "_" . $tmp_name;} else { $tmp_name = '';}



    if ($table != "" and $field != "" and $id != "") {

        $value     = $this->select($table, array($field => $id), "");

        $value_res = $value->fetch_array();

    }



    $value_res['' . $res['name_en'] . ''] = str_replace('"', '&quot;', @$value_res['' . $res['name_en'] . '']);

    $value_res['' . $res['name_en'] . ''] = str_replace("'", '&#039;', @$value_res['' . $res['name_en'] . '']);



    switch ($res['type_field']) {

            // текстовое поле

        case "i1":

        $input = '<input name="' . $res['name_en'] . $tmp_name . '" type="text" id="' . $res['name_en'] . $tmp_name . '"

        size="' . $res['width'] . '" class="form-control" value="' . @$value_res['' . $res['name_en'] . ''] . '" />';

        break;



            // поле дата

        case "i2":

        $input = '<input name="' . $res['name_en'] . $tmp_name . '" class="form-control" type="text" id="' . $res['name_en'] . $tmp_name . '"

        size="' . $res['width'] . '" value="';

        if (@$value_res['' . $res['name_en'] . '']) {

            $input .= $value_res['' . $res['name_en'] . ''];

        } else { $input .= date($res['format_date']);}



        $input .= '"/>';



        $calendar_button = rand(1000, 9999);



        $input .= '<img src="/upload/js/calendar_data/calendar.jpg" width="20" height="18" border="0"

        id="calendar_' . $res['name_en'] . $tmp_name . '" style="    margin-left: 5px;    float: right;    margin-top: -32px;    margin-right: 14px;" />';



        $input .= '<script type="text/javascript">

        Calendar.setup({

          inputField     :    "' . $res['name_en'] . $tmp_name . '",      // id of the input field

          ifFormat       :    "%Y-%m-%d %H:%M:%S",       // format of the input field

          showsTime      :    true,            // will display a time selector

          button         :    "calendar_' . $res['name_en'] . $tmp_name . '", // trigger for the calendar (button ID)

          singleClick    :    true,           // double-click mode

          step           :    1                // show all years in drop-down boxes

          });

          </script>';

          break;



            // поле логин

          case "i3":

          $input = '<input name="' . $res['name_en'] . $tmp_name . '" class="form-control" type="text" id="' . $res['name_en'] . $tmp_name . '"

          size="' . $res['width'] . '" />';

          break;



            // поле пароль

          case "i4":

          $input = '<input name="' . $res['name_en'] . $tmp_name . '" class="form-control" type="password" id="' . $res['name_en'] . $tmp_name . '"

          size="' . $res['width'] . '" />';

          break;



            // поле числовое

          case "i5":

          $input = '<input name="' . $res['name_en'] . $tmp_name . '" type="text" class="form-control" id="' . $res['name_en'] . $tmp_name . '"

          size="' . $res['width'] . '"  value="' . @$value_res['' . $res['name_en'] . ''] . '" />';

          break;



            // много текста textarea

          case "i6":

          $value_res['' . $res['name_en'] . ''] = str_replace('&quot;', '"', $value_res['' . $res['name_en'] . '']);

          $value_res['' . $res['name_en'] . ''] = str_replace('&#039;', "'", $value_res['' . $res['name_en'] . '']);



          $input = '<textarea class="form-control" name="' . $res['name_en'] . $tmp_name . '" cols="' . $res['width'] . '"

          rows="' . $res['height'] . '">' . @$value_res['' . $res['name_en'] . ''] . '</textarea>';

          break;



            // поле чекбокс

          case "i7":

          $input = '<label style="line-height: 42px;"><input name="' . $res['name_en'] . $tmp_name . '" type="checkbox" value="1"';



          if (@$value_res['' . $res['name_en'] . ''] == 1) {$input .= ' checked';}



          $input .= ' /></label>';

          break;



            // селект

          case "i8":

          $input = '<select name="' . $res['name_en'] . $tmp_name . '"  class="form-control" style="width:' . $res['width'] . 'px">';

          $elem  = explode("\n", $res['select_elements']);



          foreach ($elem as $k => $v) {

            if ($v != "") {

                if (trim($v) == @trim($value_res['' . $res['name_en'] . ''])) {$sel = 'selected';} else { $sel = '';}

                $input .= '<option value="' . trim($v) . '" ' . $sel . '>' . trim($v) . '</option>';

            }

        }



        $input .= '</select>';

        break;



            // радио батоны

        case "i9":

        $elem = explode("\n", $res['select_elements']);



        foreach ($elem as $k => $v) {

            if ($v != "") {

                if (trim($v) == @trim($value_res['' . $res['name_en'] . ''])) {$sel = 'checked';} else { $sel = '';}

                $input .= '<label><input type="radio" name="' . $res['name_en'] . $tmp_name . '"

                id="' . $res['name_en'] . $tmp_name . '" value="' . trim($v) . '" ' . $sel . '>' . trim($v) . '</label>';

            }

        }

        break;



            // html редактор

        case "i10":

        $input = '<div style="margin: 0px -8px;">

        <textarea id="' . $res['name_en'] . $tmp_name . '" name="' . $res['name_en'] . $tmp_name . '"

        >' . @$value_res['' . $res['name_en'] . ''] . '</textarea>

        <script>

        var edit_' . $res['name_en'] . $tmp_name . ' = CKEDITOR.replace( \'' . $res['name_en'] . $tmp_name . '\',{

            filebrowserBrowseUrl : \'/admin/modules/manager/ckfinder.html\',
            
            contentsCss : [\'/admin/template/main/css/style.css\'],

            filebrowserImageBrowseUrl : \'/admin/modules/manager/ckfinder.html?type=Images\',

            filebrowserFlashBrowseUrl : \'/admin/modules/manager/ckfinder.html?type=Flash\',

            filebrowserUploadUrl : \'/admin/modules/manager/core/connector/php/connector.php?command=QuickUpload&type=Files\',

            filebrowserImageUploadUrl : \'/admin/modules/manager/core/connector/php/connector.php?command=QuickUpload&type=Images\',

            filebrowserFlashUploadUrl : \'/admin/modules/manager/core/connector/php/connector.php?command=QuickUpload&type=Flash\'

            } );

            </script>

            </div>';

            break;



            // поле рисунок

            case "i11":

            if (@$value_res['' . $res['name_en'] . ''] != "") {

                $input = '<table width="100%"  border="0" cellspacing="0" cellpadding="2" align="left">

                <tr>

                <td align="left" class="left_menu">

                <img src="/admin/resize.php?url=' . $_SERVER['DOCUMENT_ROOT'] . '/upload/images/' . $value_res['' . $res['name_en'] . ''] . '&w=200&h=190">

                </td>

                </tr>

                <tr>

                <td align="left"><table width="20%"  border="0" cellspacing="0" cellpadding="0">

                <tr>

                <td width="39%" align="center">

                <input type="checkbox" name="delete' . $res['id'] . '" value="1"

                onClick="SectionClick(\'delete_forms' . $res['id'] . '\')">

                </td>

                <td width="61%" align="left" class="small_text">удалить</td>

                </tr>

                </table>

                </td>

                </tr>

                <tr>

                <td class="small_text">

                <DIV id="delete_forms' . $res['id'] . '" style="DISPLAY:none">

                <input name="' . $res['name_en'] . $tmp_name . '" type="file" class="form-control" />

                <br>форматы(' . $res['format_file'] . ')/размер(' . $res['size_file'] . ' bytes)

                </div>

                </td>

                </tr>

                </table>';



            } else {

                $input = '<input name="' . $res['name_en'] . $tmp_name . '" class="form-control" type="file" multiple="multiple" maxlength="10" />

                <br>форматы(' . $res['format_file'] . ')/размер(' . $res['size_file'] . ' bytes)

                ';

            }



            break;



            // поле для файла

            case "i12":

            if (@$value_res['' . $res['name_en'] . ''] != "") {

                $input = '<table width="100%"  border="0" cellspacing="0" cellpadding="2" align="left">

                <tr>

                <td align="left" class="small_text"><a href="/upload/files/' . $value_res['' . $res['name_en'] . ''] . '" target="_blank"><b>' . $value_res['' . $res['name_en'] . ''] . '</b></a></td>

                </tr>

                <tr>

                <td align="left"><table width="20%"  border="0" cellspacing="0" cellpadding="0">

                <tr>

                <td width="39%" align="center">

                <input type="checkbox" name="delete' . $res['id'] . '" value="1"

                onClick="SectionClick(\'delete_forms' . $res['id'] . '\')"></td>

                <td width="61%" align="left" class="small_text">удалить</td>

                </tr>

                </table>

                </td>

                </tr><tr><td class="small_text"><DIV id="delete_forms' . $res['id'] . '" style="DISPLAY:none">

                <input name="' . $res['name_en'] . $tmp_name . '" type="file" class="form-control" /><br>

                форматы(' . $res['format_file'] . ')/размер(' . $res['size_file'] . ' bytes)</div></td></tr>

                </table>';

            } else {

                $input = '<input name="' . $res['name_en'] . $tmp_name . '" class="form-control" type="file" /><br>

                форматы(' . $res['format_file'] . ')/размер(' . $res['size_file'] . ' bytes)';

            }



            break;

            case "i13":

            if ($res['name_en']=='dost'){
             $s = $this->sql->query("select id, name from i_shop_elements where id_section=3 order by id_sort asc");   
             $input = '';
             if ($s && $s->num_rows>0){


                $kl = '';
                while($r = $s->fetch_array()){

                   if ($r["".$res['format_file'].""] != "") {

                    $vlll = explode("|", @trim($value_res['dost']));

                    $dost = array();
                    $pr = array();
                    foreach ($vlll as $key => $v) {
                        $a = explode('-', $v);
                        $dost[] = @$a[0];
                        $pr[] = @$a[1];
                    }
                    
                    

                    if (@in_array(trim($r["".$res['format_file'].""]),$dost) ) {$sel = 'checked'; $kl = array_search (trim($r["".$res['format_file'].""]), $dost); } else { $sel = '';}

                    $input .= '<label> <input type="hidden" name="dost[]" value="' . trim($r["".$res['format_file'].""]) . '">  <input type="text" name="dprice[]" style="width:100px; padding:2px 5px; margin-bottom:5px;height: 32px; line-height: 32px; display:inline-block;" '.($sel == 'checked'?'value="'.@$pr[$kl].'"':'').' placeholder="Цена" class="form-control "> ' . trim($r["name"]) . ' </label><br>';

                }

            }
        }

    }else{

        $input = '<select name="' . $res['name_en'] . $tmp_name . '" class="form-control" style="width:243px"><option value=""></option>';

        if ($res['name_en']=='user'         ){
            $s = $this->sql->query("select ".$res['format_file'].", name from i_user where active=1 order by ".$res['format_file']." asc");
        }else if ($res['name_en']=='citym'         ){
            $s = $this->sql->query("select ".$res['format_file'].", name from i_block where active=1 and id_section=2 order by ".$res['format_file']." asc");
        }else if ($res['name_en']=='gallery'         ){
            $s = $this->sql->query("select ".$res['format_file'].", name from i_gallery where active=1 and id_section=1 order by ".$res['format_file']." asc");
        }else if ($res['name_en']=='shop'){
            $s = $this->sql->query("select ".$res['format_file'].", name from i_cat where id_section in (2) group by ".$res['format_file']." order by ".$res['format_file']." asc");  
        }else if ($res['name_en']=='cat'){
            $s = $this->sql->query("select ".$res['format_file'].", name from i_cat where id_section in (1) group by ".$res['format_file']." order by ".$res['format_file']." asc");  
        }else if ($res['name_en']=='izdat'){
            $s = $this->sql->query("select ".$res['format_file'].", name from i_block_elements where id_section in (select id from i_block where (id_section='".$res['size_file']."' or id_section=49)) group by ".$res['format_file']." order by ".$res['format_file']." asc");  
        } else{
            $s = $this->sql->query("select ".$res['format_file'].", name from i_block_elements where id_section='".$res['size_file']."' order by ".$res['format_file']." asc");    
        }

        

        if ($s && $s->num_rows>0){



            while($r = $s->fetch_array()){

               if ($r["".$res['format_file'].""] != "") {

                if (trim($r["".$res['format_file'].""]) == @trim($value_res['' . $res['name_en'] . $tmp_name . ''])) {$sel = 'selected';} else { $sel = '';}

                $input .= '<option value="' . trim($r["".$res['format_file'].""]) . '" ' . $sel . '>' . trim($r["name"]) . '</option>';

            }

        }



    }



    $input .= '</select>';

}


break;

}

return stripslashes($input);

}



    // ввывод поля для парамметров

public function input_params($type_id, $table, $field, $id, $tmp_name)

{

    $select = $this->select("i_params", array("id" => $type_id), "");

    $res    = $select->fetch_array();



    if ($tmp_name != "") {$tmp_name = "_" . $tmp_name;} else { $tmp_name = '';}



    if ($table != "" and $field != "" and $id != "") {

        $value     = $this->select($table, array($field => $id), "");

        $value_res = $value->fetch_array();

    }



    $value_res['' . $res['name_en'] . ''] = str_replace('"', '&quot;', @$value_res['' . $res['name_en'] . '']);

    $value_res['' . $res['name_en'] . ''] = str_replace("'", '&#039;', @$value_res['' . $res['name_en'] . '']);



    switch ($res['type']) {

            // текстовое поле

        case "i1":

        $input = '<input name="' . $res['name_en'] . $tmp_name . '" type="text" id="' . $res['name_en'] . $tmp_name . '"

        size="30" value="' . @$res['value'] . '" onchange="save_param(\'' . $res['name_en'] . '\',\'i1\',' . $res["id"] . ');" />';

        break;



            // поле чекбокс

        case "i7":

        $input = '<input name="' . $res['name_en'] . $tmp_name . '" id="' . $res['name_en'] . $tmp_name . '"

        onclick="save_param(\'' . $res['name_en'] . '\',\'i7\',' . $res["id"] . ')" type="checkbox" value="1"';



        if (@$res['value'] == 1) {$input .= ' checked';}



        $input .= ' />';

        break;

    }

    return stripslashes($input);

}



    // показ скрипта для обязательных полей

public function script_view($module, $sub_module, $id_section, $array)

{

    echo '<script>

    function pr(hidden_val)

    {

      var msg;

      var fr;

      msg=\'\';

      fr=document.form;';



      $input = $this->search_option($module, $sub_module, $id_section, $array);



      while ($input_res = $input->fetch_array()) {

        switch ($input_res['type_field']) {

            case 'i9':

            $elem = explode("\n", $input_res['select_elements']);



            foreach ($elem as $k => $v) {

                if ($v != "") {

                    if ($k == (count($elem) - 1)) {$pl = '';} else { $pl = '&&';}

                    @$str .= 'fr.' . $input_res['name_en'] . '[\'' . $k . '\'].checked==false ' . $pl . ' ';

                }

            }



            echo 'if(' . $str . '){msg=msg+\'* ' . $input_res['name_ru'] . ' \n\';}';



            break;



            default:



            echo 'if (fr.' . $input_res['name_en'] . '.value==\'\'){msg=msg+\'* ' . $input_res['name_ru'] . ' \n\';}';



            break;

        }

    }



    echo 'if(msg==\'\')

    {

        fr.hidden.value=hidden_val;

        fr.submit();

    }

    else

    {

        msg=\'Необходимо заполнить обязательные поля:\n\'+msg;

        alert(msg);

    }

}

</script>';

}



    // поиск полей для блоков

public function search_option($module, $sub_module, $id_section, $array)

{

    $id_field = '';

    if ($sub_module == "") {$sub_module = $module;}


    $where_con = array("category" => $module, "category_id" => $id_section);



    foreach ($array as $k => $v) {

        if ($k != "") {$where_con[$k] = $v;}

    }



    for ($i = 0; $i <= 4; $i++) {

        if (!@$option) {

            $option = $this->select("i_option", $where_con, "head asc, id_sort asc");

        }



        if ($module != "forms" && $module != "templates") {

            if ($option->num_rows == 0) {

                $search_field             = $this->select("i_" . $sub_module, array("id" => $id_section), "");

                @$search_res              = $search_field->fetch_array();

                @$where_con['category_id'] = @$search_res['id_section'];

                $option                   = $this->select("i_option", $where_con, "head asc, id_sort asc");

                if ($option->num_rows == 0) {$id_section = @$search_res['id_section'];}

            }

        }

    }

    return $option;

}



    // поиск парамметров для блока

public function search_params($module, $sub_module, $id_section, $array)

{

    $id_field = '';

    if ($sub_module == "") {$sub_module = $module;}

    $where_con = array("id_block" => $id_section);



    foreach ($array as $k => $v) {

        if ($k != "") {$where_con[$k] = $v;}

    }



    for ($i = 0; $i <= 4; $i++) {

        if (!@$option) {

            $option = $this->select("i_params", $where_con, "id");

        }

        if ($module != "forms") {

            if ($option->num_rows == 0) {

                $search_field          = $this->select("i_" . $sub_module, array("id" => $id_section), "");

                @$search_res           = $search_field->fetch_array();

                $where_con['id_block'] = $search_res['id_section'];

                $option                = $this->select("i_params", $where_con, "id");

                if ($option->num_rows == 0) {$id_section = $search_res['id_section'];}



            }

        }

    }

    return $option;

}



    // удаление дерева блоков

public function delete_tree($id, $block_name)

{

    $array_block = array();
    $array_slug = array();

    $id_section  = '';

        //добавляем в массив

    for ($i = 0; $i <= 4; $i++) {

        if ($i == 0) {

            array_push($array_block, $id);
            $select = $this->select("i_" . $block_name, array("id" => $id), "");
            $res = $select->fetch_array();
            $array_slug[] = $res["slug"];
            $id_section = $id;

        } else {

            $select = $this->select("i_" . $block_name, array("id_section" => $id_section), "");

            if ($select->num_rows > 0) {

                while ($res = $select->fetch_array()) {

                    $id_section = $res['id'];

                    array_push($array_block, $res['id']);
                    $array_slug[] = $res["slug"];

                    $select2 = $this->select("i_" . $block_name, array("id_section" => $id_section), "");

                    if ($select2->num_rows > 0) {

                        while ($res2 = $select2->fetch_array()) {

                            array_push($array_block, $res2['id']);
                            $array_slug[] = $res2["slug"];

                        }

                    }

                }

            }

        }

    }



    krsort($array_block);



    foreach ($array_block as $k => $v) {

        $select = $this->select("i_option", array("category" => $block_name, "category_id" => $v), "");

        while ($res = $select->fetch_array()) {

            $elements = $this->select("i_" . $block_name, array("id" => $v), "");

            while ($elements_res = $elements->fetch_array()) {

                $this->sql->query("delete from i_alias where slug='" . $elements_res["slug"] . "' AND module='" . $block_name . "_elements'");

                if ($res['type_field'] == "i11") {

                    @unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/images/" . $elements_res['' . $res['name_en'] . '']);

                }

                if ($res['type_field'] == "i12") {

                    @unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/files/" . $elements_res['' . $res['name_en'] . '']);

                }

            }

                //$this->sql->query("ALTER TABLE i_".$block_name." DROP ".$res['name_en']."");



            $this->sql->query("delete from i_option where category_id='" . $v . "' AND category='" . $block_name . "'");


        }



        $select = $this->select("i_option", array("category" => $block_name . "_elements", "category_id" => $v), "");

        while ($res = $select->fetch_array()) {

            $elements = $this->select("i_" . $block_name . "_elements", array("id_section" => $v), "");

            while ($elements_res =$elements->fetch_array()) {

               $this->sql->query("delete from i_alias where slug='" . $elements_res["slug"] . "' AND module='" . $block_name . "_elements'");

               if ($res['type_field'] == "i11") {

                @unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/images/" . $elements_res['' . $res['name_en'] . '']);

            }

            if ($res['type_field'] == "i12") {

                @unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/files/" . $elements_res['' . $res['name_en'] . '']);

            }



        }



                //$this->sql->query("ALTER TABLE i_".$block_name."_elements DROP ".$res['name_en']."");

        $this->sql->query("delete from i_option where category_id='" . $v . "' AND category='" . $block_name . "_elements'");


    }



    if ($block_name == "photo") {

        $elements = $this->select("i_" . $block_name . "_elements", array("id_section" => $v), "");

        while ($elements_res =$elements->fetch_array()) {

           $this->sql->query("delete from i_alias where slug='" . $elements_res["slug"] . "' AND module='" . $block_name . "_elements'");

           @unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/images/small/" . $elements_res['photo_name']);

           @unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/images/big/" . $elements_res['photo_name']);

       }

   }



   $this->sql->query("delete from i_" . $block_name . " where id='" . $v . "'");

   $this->sql->query("delete from i_" . $block_name . " where id_section='" . $v . "'");

   $this->sql->query("delete from i_" . $block_name . "_elements where id_section='" . $v . "'");

}

foreach ($array_slug as $key => $value) {
    $this->sql->query("delete from i_alias where slug='" . $value . "' AND module='" . $block_name . "'");
}



$this->sql->query("insert into i_logs values(0,CURRENT_TIMESTAMP,'" . $_SESSION['user_id'] . "','delete','" . $block_name . "')");

}

    // кодировка

public function encode($a)

{

    $_win1251utf8 = array("\xC0" => "\xD0\x90", "\xC1" => "\xD0\x91", "\xC2" => "\xD0\x92", "\xC3" => "\xD0\x93", "\xC4" => "\xD0\x94",

        "\xC5"                       => "\xD0\x95", "\xA8" => "\xD0\x81", "\xC6" => "\xD0\x96", "\xC7" => "\xD0\x97", "\xC8" => "\xD0\x98",

        "\xC9"                       => "\xD0\x99", "\xCA" => "\xD0\x9A", "\xCB" => "\xD0\x9B", "\xCC" => "\xD0\x9C", "\xCD" => "\xD0\x9D",

        "\xCE"                       => "\xD0\x9E", "\xCF" => "\xD0\x9F", "\xD0" => "\xD0\x20", "\xD1" => "\xD0\xA1", "\xD2" => "\xD0\xA2",

        "\xD3"                       => "\xD0\xA3", "\xD4" => "\xD0\xA4", "\xD5" => "\xD0\xA5", "\xD6" => "\xD0\xA6", "\xD7" => "\xD0\xA7",

        "\xD8"                       => "\xD0\xA8", "\xD9" => "\xD0\xA9", "\xDA" => "\xD0\xAA", "\xDB" => "\xD0\xAB", "\xDC" => "\xD0\xAC",

        "\xDD"                       => "\xD0\xAD", "\xDE" => "\xD0\xAE", "\xDF" => "\xD0\xAF", "\xAF" => "\xD0\x87", "\xB2" => "\xD0\x86",

        "\xAA"                       => "\xD0\x84", "\xA1" => "\xD0\x8E", "\xE0" => "\xD0\xB0", "\xE1" => "\xD0\xB1", "\xE2" => "\xD0\xB2",

        "\xE3"                       => "\xD0\xB3", "\xE4" => "\xD0\xB4", "\xE5" => "\xD0\xB5", "\xB8" => "\xD1\x91", "\xE6" => "\xD0\xB6",

        "\xE7"                       => "\xD0\xB7", "\xE8" => "\xD0\xB8", "\xE9" => "\xD0\xB9", "\xEA" => "\xD0\xBA", "\xEB" => "\xD0\xBB",

        "\xEC"                       => "\xD0\xBC", "\xED" => "\xD0\xBD", "\xEE" => "\xD0\xBE", "\xEF" => "\xD0\xBF", "\xF0" => "\xD1\x80",

        "\xF1"                       => "\xD1\x81", "\xF2" => "\xD1\x82", "\xF3" => "\xD1\x83", "\xF4" => "\xD1\x84", "\xF5" => "\xD1\x85",

        "\xF6"                       => "\xD1\x86", "\xF7" => "\xD1\x87", "\xF8" => "\xD1\x88", "\xF9" => "\xD1\x89", "\xFA" => "\xD1\x8A",

        "\xFB"                       => "\xD1\x8B", "\xFC" => "\xD1\x8C", "\xFD" => "\xD1\x8D", "\xFE" => "\xD1\x8E", "\xFF" => "\xD1\x8F",

        "\xB3"                       => "\xD1\x96", "\xBF" => "\xD1\x97", "\xBA" => "\xD1\x94", "\xA2" => "\xD1\x9E");



    if (is_array($a)) {

        foreach ($a as $k => $v) {

            if (is_array($v)) {

                $a[$k] = encode($v);

            } else {

                $a[$k] = strtr($v, $_win1251utf8);

            }

        }



        return $a;

    } else {

        return strtr($a, $_win1251utf8);

    }

}



public function str_date($t)

{

    $arr = array(1 => "января",

        "февраля",

        "марта",

        "апреля",

        "мая",

        "июня",

        "июля",

        "августа",

        "сентября",

        "октября",

        "ноября",

        "декабря",

    );

    if (preg_match("/^(\d+)-0?(\d+)-0?(\d+)(\s+(\d+):(\d+):(\d+))?$/", $t, $ok)) {

        return $ok[3] . " " . $arr[$ok[2]] . " " . $ok[1];

    } else if (is_numeric($t)) {

        return date("d", $t) . " " . $arr[date("n", $t)] . " " . $ok[1];

    }

}

}



// конец класса aplication



// дополнительные функции



// вывод массива

function vd($var)

{

    echo '<pre style="text-align: left;">';

    print_r($var);

    echo '</pre>';

}



// очистка гет запроса

function clear_GET()

{

    global $ob;

    $tmp;

    foreach ($_GET as $key => $value) {

        $key = $ob->pr($key);

        if (is_array($value)) {

            $value = $value;

            foreach ($value as $key1 => $value1) {

                $key1 = $ob->pr($key1);

                if (is_string($value1)) {

                    $value1 = $ob->pr($value1);

                } elseif (is_int($value1)) {

                    $value1 = (int) $value1;

                }

                $tmp[$key][$key1] = $value1;

            }

        } elseif (is_string($value)) {

            $value     = $ob->pr($value);

            $tmp[$key] = $value;

        } elseif (is_int($value)) {

            $value     = (int) $value;

            $tmp[$key] = $value;

        }

    }

    $_GET = $tmp;

}



// очистка пост запроса

function clear_POST()

{

    global $ob;

    $tmp;

    foreach ($_POST as $key => $value) {

        $key = $ob->pr($key);

        if (is_array($value)) {

            $value = $value;

            foreach ($value as $key1 => $value1) {

                $key1 = $ob->pr($key1);

                if (is_string($value1)) {

                    $value1 = $ob->pr($value1);

                } elseif (is_int($value1)) {

                    $value1 = (int) $value1;

                }

                $tmp[$key][$key1] = $value1;

            }

        } elseif (is_string($value)) {

            $value     = $ob->pr($value);

            $tmp[$key] = $value;

        } elseif (is_int($value)) {

            $value     = (int) $value;

            $tmp[$key] = $value;

        }

    }

    $_POST = $tmp;

}



// получение элемнтов

function getElements($id, $limit = 0, $order_direction = 'ASC', $order = 'id', $search_rows = '', $search_keyword = '',

    $advanced_search = '') {

  global $mysql;

  if ($limit === 0) {

    $limit = '';

} else {

    $limit = 'LIMIT ' . $limit;

}



$search = '';

if ($search_keyword && $search_rows) {

    $search_rows    = explode(',', $search_rows);

    $search_keyword = explode(' ', $search_keyword);

    for ($i = 0; $i < sizeof($search_rows); $i++) {

        $search_rows[$i] = trim($search_rows[$i]);

        if ($i != 0) {

            $search .= ' OR ';

        }



        for ($j = 0; $j < sizeof($search_keyword); $j++) {

            $search_keyword[$j] = trim($search_keyword[$j]);

            if ($j != 0) {

                $search .= ' OR ';

            }



            $search .= '`' . $search_rows[$i] . '` LIKE \'%' . $search_keyword[$j] . '%\'';

        }

    }

    $search = 'AND (' . $search . ')';

}



if ($advanced_search) {

    $search .= ' AND (' . $advanced_search . ')';

}



$qr = $mysql->query('SELECT `name_en`, `name_ru` FROM `i_option`

   WHERE `category`="block_elements" AND `category_id`=' . $id . ' ORDER BY `id_sort`');



if (!$qr->num_rows) {

    $id_parent = getPrentWAttrs($id);

    $qr        = $mysql->query('SELECT `name_en`, `name_ru` FROM `i_option`

     WHERE `category`="block_elements" AND `category_id`=' . $id_parent . ' ORDER BY `id_sort`');

}



$attrs_ru  = array();

$attrs_en  = array();

$attrs_str = '';

$i         = 0;



while ($obj = $qr->fetch_object()) {

    if ($i != 0) {

        $attrs_str .= ', ';

    }



    $attrs_ru[] = $obj->name_ru;

    $attrs_en[] = $obj->name_en;

    $attrs_str .= '`' . $obj->name_en . '`';

    $i++;

}



$attrs_str = trim($attrs_str);

$objects   = array();

$i         = 0;



$qr = $mysql->query('SELECT `id`, `id_section`, ' . $attrs_str . ' FROM `i_block_elements`

   WHERE `id_section`=' . $id . ' ' . $search . ' ORDER BY ' . $order . ' ' . $order_direction . ' ' . $limit);



if (!$qr->num_rows) {

    return array();

}



while ($obj = $qr->fetch_array()) {

    $objects[$i]               = array();

    $objects[$i]['id']         = $obj['id'];

    $objects[$i]['id_section'] = $obj['id_section'];

    for ($j = 0; $j < sizeof($attrs_ru); $j++) {

        $objects[$i][$attrs_ru[$j]] = stripslashes($obj[$attrs_en[$j]]);

    }

    $i++;

}



return $objects;

}



// получение элемента

function getElement($id)

{

	global $mysql;

    $qr         = $mysql->query('SELECT `id_section` FROM `i_block_elements` WHERE `id`=' . $id . ' LIMIT 1');

    $obj        = $qr->fetch_object();

    $id_section = $obj->id_section;



    $qr = $this->sql->query('SELECT `name_en`, `name_ru` FROM `i_option` WHERE `category`="block_elements" AND `category_id`=' . $id_section . ' ORDER BY `id_sort`');



    if (!$qr->num_rows) {

        $id_parent = getPrentWAttrs($id_section);

        $qr        = $mysql->query('SELECT `name_en`, `name_ru` FROM `i_option`

         WHERE `category`="block_elements" AND `category_id`=' . $id_parent . ' ORDER BY `id_sort`');

    }



    $attrs_ru  = array();

    $attrs_en  = array();

    $attrs_str = '';

    $i         = 0;



    while ($obj = $qr->fetch_object()) {

        if ($i != 0) {

            $attrs_str .= ', ';

        }



        $attrs_ru[] = $obj->name_ru;

        $attrs_en[] = $obj->name_en;

        $attrs_str .= '`' . $obj->name_en . '`';

        $i++;

    }



    $attrs_str = trim($attrs_str);

    $qr        = $mysql->query('SELECT `id`, `id_section`, ' . $attrs_str . ' FROM `i_block_elements` WHERE `id`=' . $id . ' LIMIT 1');



    if (!$qr->num_rows) {

        return array();

    }



    $obj    = $qr->fetch_array();

    $object = array();



    $object['id']         = $obj['id'];

    $object['id_section'] = $obj['id_section'];

    for ($j = 0; $j < sizeof($attrs_ru); $j++) {

        $object[$attrs_ru[$j]] = stripslashes($obj[$attrs_en[$j]]);

    }



    return $object;

}



// получение блоков

function getBlocks($id, $limit = 0, $order_direction = 'ASC', $order = 'id', $search_rows = '', $search_keyword = '', $advanced_search = '')

{

	global $mysql;

    if ($limit === 0) {

        $limit = '';

    } else {

        $limit = 'LIMIT ' . $limit;

    }



    $search = '';

    if ($search_keyword && $search_rows) {

        $search_rows    = explode(',', $search_rows);

        $search_keyword = explode(' ', $search_keyword);

        for ($i = 0; $i < sizeof($search_rows); $i++) {

            $search_rows[$i] = trim($search_rows[$i]);

            if ($i != 0) {

                $search .= ' OR ';

            }



            for ($j = 0; $j < sizeof($search_keyword); $j++) {

                $search_keyword[$j] = trim($search_keyword[$j]);

                if ($j != 0) {

                    $search .= ' OR ';

                }



                $search .= '`' . $search_rows[$i] . '` LIKE \'%' . $search_keyword[$j] . '%\'';

            }

        }

        $search = 'AND (' . $search . ')';

    }

    if ($advanced_search) {

        $search .= ' AND (' . $advanced_search . ')';

    }



    $qr = $mysql->query('SELECT `name_en`, `name_ru` FROM `i_option` WHERE `category`="block" AND `category_id`=' . $id . ' ORDER BY `id_sort`');

    if (!$qr->num_rows) {

        $id_parent = getBlockPrentWAttrs($id);

        $qr        = $mysql->query('SELECT `name_en`, `name_ru` FROM `i_option` WHERE `category`="block" AND `category_id`=' . $id_parent . ' ORDER BY `id_sort`');

    }

    $attrs_ru  = array();

    $attrs_en  = array();

    $attrs_str = '';

    $i         = 0;

    while ($obj = $qr->fetch_object()) {

        if ($i != 0) {

            $attrs_str .= ', ';

        }



        $attrs_ru[] = $obj->name_ru;

        $attrs_en[] = $obj->name_en;

        $attrs_str .= '`' . $obj->name_en . '`';

        $i++;

    }

    $attrs_str = trim($attrs_str);

    $objects   = array();

    $i         = 0;

    $qr        = $mysql->query('SELECT `id`, `id_section`, ' . $attrs_str . ' FROM `i_block` WHERE `id_section`=' . $id . ' ' . $search . ' ORDER BY `' . $order . '` ' . $order_direction . ' ' . $limit);

    if (!$qr->num_rows) {

        return array();

    }



    while ($obj = $qr->fetch_array()) {

        $objects[$i] = array();



        $objects[$i]['id']         = $obj['id'];

        $objects[$i]['id_section'] = $obj['id_section'];

        for ($j = 0; $j < sizeof($attrs_ru); $j++) {

            $objects[$i][$attrs_ru[$j]] = stripslashes($obj[$attrs_en[$j]]);

        }

        $i++;

    }

    return $objects;

}



// получение блока

function getBlock($id)

{

	global $mysql;

    $qr         = $mysql->query('SELECT `id_section` FROM `i_block` WHERE `id`=' . $id . ' LIMIT 1');

    $obj        = $qr->fetch_object();

    $id_section = $obj->id_section;

    $qr         = $mysql->query('SELECT `name_en`, `name_ru` FROM `i_option` WHERE `category`="block" AND `category_id`=' . $id_section . ' ORDER BY `id_sort`');

    if (!$qr->num_rows) {

        $id_parent = getBlockPrentWAttrs($id_section);

        $qr        = $mysql->query('SELECT `name_en`, `name_ru` FROM `i_option` WHERE `category`="block" AND `category_id`=' . $id_parent . ' ORDER BY `id_sort`');

    }

    $attrs_ru  = array();

    $attrs_en  = array();

    $attrs_str = '';

    $i         = 0;

    while ($obj = $qr->fetch_object()) {

        if ($i != 0) {

            $attrs_str .= ', ';

        }



        $attrs_ru[] = $obj->name_ru;

        $attrs_en[] = $obj->name_en;

        $attrs_str .= '`' . $obj->name_en . '`';

        $i++;

    }

    $attrs_str = trim($attrs_str);

    $qr        = $mysql->query('SELECT `id`, `id_section`, ' . $attrs_str . ' FROM `i_block` WHERE `id`=' . $id . ' LIMIT 1');

    if (!$qr->num_rows) {

        return array();

    }



    $obj    = $qr->fetch_array();

    $object = array();



    $object['id']         = $obj['id'];

    $object['id_section'] = $obj['id_section'];

    for ($j = 0; $j < sizeof($attrs_ru); $j++) {

        $object[$attrs_ru[$j]] = stripslashes($obj[$attrs_en[$j]]);

    }

    return $object;

}



// определение родителя

function getParent($id, $table = 'i_block_elements', $row = 'id_section')

{

	global $mysql;

    $qr  = $mysql->query('SELECT `' . $row . '` FROM `' . $table . '` WHERE `id`=' . $id . ' LIMIT 1');

    $obj = $qr->fetch_array(MYSQL_ASSOC);

    if ($obj) {

        return $obj[$row];

    }



    return false;

}

// получение аттрибутов родителя

function getPrentWAttrs($id)

{

	global $mysql;

    $qr  = $mysql->query('SELECT `id_section` FROM `i_block` WHERE `id`=' . $id . ' LIMIT 1');

    $obj = $qr->fetch_object();

    if (!$obj) {

        return false;

    }



    $parent_id = $obj->id_section;

    $id        = $obj->id;

    $qr        = $mysql->query('SELECT `id` FROM `i_option` WHERE `category`="block_elements" AND `category_id`=' . $parent_id . ' LIMIT 1');

    $obj       = $qr->fetch_object();

    if ($obj) {

        return $parent_id;

    } else {

        return getPrentWAttrs($parent_id);

    }



}

// получение аттрибутов блока родителя

function getBlockPrentWAttrs($id)

{

	global $mysql;

    $qr  = $mysql->query('SELECT `id_section` FROM `i_block` WHERE `id`=' . $id . ' LIMIT 1');

    $obj = $qr->fetch_object();

    if (!$obj) {

        return false;

    }



    $parent_id = $obj->id_section;

    $id        = $obj->id;

    $qr        = $mysql->query('SELECT `id` FROM `i_option` WHERE `category`="block" AND `category_id`=' . $parent_id . ' LIMIT 1');

    $obj       = $qr->fetch_object();

    if ($obj) {

        return $parent_id;

    } else {

        return getPrentWAttrs($parent_id);

    }



}



// расширение файла

function get_file_extension($file)

{

    $matches = array();

    $reg     = preg_match('/^.+\.(.+)$/', $file, $matches);

    return $matches[1];

}



// склонение слов

function sklon($num, $arr)

{

    //echo "Комментар".sklon(34, array('ий', 'ия', 'иев'));

    if ($num == 1) {

        return $arr[0];

    } else if ($num >= 2 && $num <= 4) {

        return $arr[1];

    } else if (($num >= 5 && $num <= 19) or $num == 0) {

        return $arr[2];

    } else {

        $num1 = substr($num, -1, 1);

        $num2 = substr($num, -2, 1);

        if ($num2 == 1) {

            return $arr[2];

        } else if ($num1 == 1) {

            return $arr[0];

        } else if ($num1 >= 2 && $num1 <= 4) {

            return $arr[1];

        } else if (($num1 >= 5 && $num1 <= 9) or $num1 == 0) {

            return $arr[2];

        }

    }

}



// получение даты из sql date

function get_sql_date_array($d)

{

    $arr            = array();

    //$arr['year']    = $d{0} . $d{1} . $d{2} . $d{3};

    //$arr['month']   = $d{5} . $d{6};

    //$arr['day']     = $d{8} . $d{9};

    //$arr['hours']   = $d{11} . $d{12};

    //$arr['minutes'] = $d{14} . $d{15};

    //$arr['seconds'] = $d{17} . $d{18};

    return $arr;

}



// дата в unix формате

function get_timestamp_from_sql($date)

{

    $date = get_sql_date_array($date);

    return mktime($date['hours'], $date['minutes'], $date['seconds'], $date['month'], $date['day'], $date['year']);

}



// время unix в обычный

function sql_to_date($date, $format = 'Y-m-d H:i:s')

{

    return date($format, get_timestamp_from_sql($date));

}



// определение родителей

$recurse_parent = array();

function getRecurseParent($module, $id, $recurse_parent)

{

    global $recurse_parent, $mysql;

    array_unshift($recurse_parent, $id);

    

    if ($id == 0) {

        return true;

    }


    //if(!isset($_GET["id"]) && strstr($module, 'elements')){ 
    $module = str_replace('_elements', '', $module); 
    $module = str_replace('shop_users', 'shop_group', $module); 
    //}
    $q   = 'SELECT * FROM `i_'.$module.'` WHERE `id`=' . $id;

    //echo $q;

    $qr  = $mysql->query($q);
    if(@$qr){
        $obj = $qr->fetch_object();

    //vd($obj);

        if ($obj){
            getRecurseParent($module, @$obj->id_section, $recurse_parent);
        }
    }

}



// получение имен блоков

function getObjNamesById($module,$id, $param = 'name')

{

	global $mysql;

    $module = str_replace('_elements', '', $module);
    $q   = 'SELECT * FROM `i_'.$module.'` WHERE `id`=' . $id;

    $qr  = $mysql->query($q);
    if (@$qr){
        $obj = $qr->fetch_array();





        return @$obj[$param];
    }

}



// крошка админки

function admin_print_dir($module, $sub_module='', $curr_link = false)
{
    global $recurse_parent, $mysql;
    if (@$_GET['id_section'] && @$_GET['id']=='') {
        $id = @$_GET['id_section'];
    } else {
        $id = @$_GET['id'];
        if( strstr($module , 'elements')){
            $sql = "select id_section from i_$module where id=$id";
            $res =$mysql->get($sql, 1);
            $id = @$res["id_section"];
        }
    }

    getRecurseParent($module, $id, null);



    for ($i = 0; $i < sizeof($recurse_parent); $i++) {
        if (!$id) {
            break;
        }

        if ($recurse_parent[$i] != $id || $curr_link) {

            echo '<a href="' . (strstr($_SERVER["REQUEST_URI"] , 'add_elements') || strstr($_SERVER["REQUEST_URI"] , 'edit_elements')|| strstr($_SERVER["REQUEST_URI"] , 'import.')  ? 'elements.php' : 'index.php') . '?id_section=' . $recurse_parent[$i] . '&module=' . ($sub_module != '' ? $sub_module.'' : $module) . '" class="small_red_text">' . getObjNamesById($module, $recurse_parent[$i]) . '</a>';

        } else {

         echo '<span class="hover_bread"><a href="' . (strstr($_SERVER["REQUEST_URI"] , 'add_elements') || strstr($_SERVER["REQUEST_URI"] , 'edit_elements') || strstr($_SERVER["REQUEST_URI"] , 'import.') ? 'elements.php' : 'index.php') . '?id_section=' . $recurse_parent[$i] . '&module=' . ($sub_module != '' ? $sub_module.'' : $module) . '" class="small_red_text">' . getObjNamesById($module, $recurse_parent[$i]) . '</a>';
         echo '<span class="bread_down" style="'.($module=='menu' || $module=='guest' || $module=='quest' || $module=='subscribes'  || $module=='page' || $module=='comments' ? 'opacity:0' : '').'">';
         echo '<a href="index.php?id_section=' . $recurse_parent[$i] . '&module=' . str_replace('_elements','',$module) . '" class="small_red_text">Категории</a>';
         echo '<a href="elements.php?id_section=' . $recurse_parent[$i] . '&module=' . str_replace('_elements','',$module) . '_elements" class="small_red_text">Элементы</a>';
         echo '</span>';
         echo '</span>';
         echo ' / ';

     }

     if ($recurse_parent[$i] != $id) {

        echo ' / ';

    }



}

}



// json

function json($a = false)

{

    if (is_null($a)) {

        return 'null';

    }



    if ($a === false) {

        return 'false';

    }



    if ($a === true) {

        return 'true';

    }



    if (is_scalar($a)) {

        if (is_float($a)) {

            return floatval(str_replace(",", ".", strval($a)));

        } else if (is_numeric($a)) {

            return $a;

        } else {

            $jsonReplaces = array("\\" => '\\\\', "/" => '\\/', "\n" => '\\n', "\t" => '\\t', "\r" => '\\r', "\b" => '\\b', "\f" => '\\f', '"' => '\"');

            return '"' . strtr($a, $jsonReplaces) . '"';

        }

    } else if (!is_array($a)) {

        return false;

    }



    $isList = true;

    foreach ($a as $k => $v) {

        if (!is_numeric($k)) {

            $isList = false;

            break;

        }

    }

    $result = array();

    if ($isList) {

        foreach ($a as $v) {

            $result[] = json($v);

        }



        return '[' . join(',', $result) . ']';

    } else {

        foreach ($a as $k => $v) {

            $result[] = json($k) . ':' . json($v);

        }



        return '{' . join(',', $result) . '}';

    }

}



// конвертиация размеров

function convertBytes($bytes)

{

    $types = array(1 => "K", "M", "G", "T");

    $index = 0;

    while (($check = $bytes / 1024) >= 1 && $index++ <= count($types)) {

        $bytes = round($check, 2);

    }

    return ($bytes ? $bytes : 0) . " " . $types[$index] . "B";

}



// иконка документа

function getIcon($ext)

{

    $icons = array(

        'word.gif'  => array('doc', 'docx'),

        'excel.gif' => array('xls', 'csv'),

        'rar.gif'   => array('rar', 'zip'),

        'pdf.gif'   => array('pdf'),

        'image.gif' => array('gif', 'jpg', 'jpeg'),

    );

    foreach ($icons as $ico => $arr) {

        if (in_array($ext, $arr)) {

            return $ico;

        }



    }

    return 'word.gif';

}



// пагинация

function drawPages($total_elements, $onepage, $pg = 0, $page_atributes = array())

{

    $print_pages = '';

    if (ceil($total_elements / $onepage) > 1) {

        $print_pages .= "<div align=\"center\" class=\"pages-div\"><b>Страницы:</b> ";

        if ($pg > 0) {

            $print_pages .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?pg=" . $pg . ($page_atributes ? '&' . join('&', $page_atributes) : '') . "\">&larr;</a> ";

        }



        $index = $pg >= 6 ? ($pg + 1 < ceil($total_elements / $onepage) - 5 ? $pg - 5 : (ceil($total_elements / $onepage) > 11 ? ceil($total_elements / $onepage) - 11 : 0)) : 0;

        for ($i = 1; $i <= (ceil($total_elements / $onepage) < 11 ? ceil($total_elements / $onepage) : 11); $i++) {

            $index++;

            if ($index > ceil($total_elements / $onepage)) {

                break;

            }



            if ($index == $pg + 1) {

                $print_pages .= $index . " ";

            } else {

                $print_pages .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?pg=" . $index . ($page_atributes ? '&' . join('&', $page_atributes) : '') . "\">" . $index . "</a> ";

            }

        }

        if ($pg + 1 < ceil($total_elements / $onepage)) {

            $print_pages .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?pg=" . ($pg + 2) . ($page_atributes ? '&' . join('&', $page_atributes) : '') . "\">&rarr;</a> ";

        }



        $print_pages .= '</div>';

    }

    return $print_pages;

}



// показ сообщения

function showMsg($content, $class = '', $tag = 'div')

{

    echo '<' . $tag . ' class="message-conteiner ' . $class . '">' . $content . '</' . $tag . '>';

    echo '<script type="text/javascript">setTimeout(function(){$(\'.message-conteiner\').hide("normal", function(){ $(this).remove() })}, 5000);</script>';

}



// слово маленькими буквами

function lower($text)

{

    $UP_CASE = array('A' => 'a', 'B' => 'b', 'C' => 'c', 'D' => 'd', 'E' => 'e', 'F' => 'f', 'G' => 'g', 'H' => 'h', 'I' => 'i', 'J' => 'j', 'K' => 'k', 'L' => 'l', 'M' => 'm', 'N' => 'n', 'O' => 'o', 'P' => 'p', 'Q' => 'q', 'R' => 'r', 'S' => 's', 'T' => 't', 'U' => 'u', 'V' => 'v', 'W' => 'w', 'X' => 'x', 'Y' => 'y', 'Z' => 'z', 'А' => 'а', 'Б' => 'б', 'В' => 'в', 'Г' => 'г', 'Д' => 'д', 'Е' => 'е', 'Ё' => 'ё', 'Ж' => 'ж', 'З' => 'з', 'И' => 'и', 'Й' => 'й', 'К' => 'к', 'Л' => 'л', 'М' => 'м', 'Н' => 'н', 'О' => 'о', 'П' => 'п', 'Р' => 'р', 'С' => 'с', 'Т' => 'т', 'У' => 'у', 'Ф' => 'ф', 'Х' => 'х', 'Ц' => 'ц', 'Ч' => 'ч', 'Ш' => 'ш', 'Щ' => 'щ', 'Ъ' => 'ъ', 'Ы' => 'ы', 'Ь' => 'ь', 'Э' => 'э', 'Ю' => 'ю', 'Я' => 'я');

    return strtr($text, $UP_CASE);

}



// транслит

function translit($string)

{

    $converter = array(

        'а' => 'a', 'б'  => 'b', 'в'  => 'v',

        'г' => 'g', 'д'  => 'd', 'е'  => 'e',

        'ё' => 'e', 'ж'  => 'zh', 'з' => 'z',

        'и' => 'i', 'й'  => 'y', 'к'  => 'k',

        'л' => 'l', 'м'  => 'm', 'н'  => 'n',

        'о' => 'o', 'п'  => 'p', 'р'  => 'r',

        'с' => 's', 'т'  => 't', 'у'  => 'u',

        'ф' => 'f', 'х'  => 'h', 'ц'  => 'c',

        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',

        'ь' => "", 'ы'   => 'y', 'ъ'  => "",

        'э' => 'e', 'ю'  => 'yu', 'я' => 'ya',

        ' '  => '-', 'ә'  => 'a', 'і'  => 'i',

        'ң' => 'n', 'ғ'  => 'g', 'ү'  => 'u',

        'ұ' => 'u', 'қ'  => 'k', 'ө'  => 'o',

        'һ' => 'kh',



        'А' => 'A', 'Б'  => 'B', 'В'  => 'V',

        'Г' => 'G', 'Д'  => 'D', 'Е'  => 'E',

        'Ё' => 'E', 'Ж'  => 'Zh', 'З' => 'Z',

        'И' => 'I', 'Й'  => 'Y', 'К'  => 'K',

        'Л' => 'L', 'М'  => 'M', 'Н'  => 'N',

        'О' => 'O', 'П'  => 'P', 'Р'  => 'R',

        'С' => 'S', 'Т'  => 'T', 'У'  => 'U',

        'Ф' => 'F', 'Х'  => 'H', 'Ц'  => 'C',

        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',

        'Ь' => "", 'Ы'   => 'Y', 'Ъ'  => "",

        'Э' => 'E', 'Ю'  => 'Yu', 'Я' => 'Ya',

        ' '  => '-', 'Ә'  => 'A', 'І'  => 'I',

        'Ң' => 'N', 'Ғ'  => 'G', 'Ү'  => 'U',

        'Ұ' => 'U', 'Қ'  => 'K', 'Ө'  => 'O',

        'Һ' => 'KH',

    );

    $string = str_replace(' ', '-', $string);

    $string = preg_replace('/[^a-zа-яё0-9\-]+/iu', '', $string);



    return lower(strtr($string, $converter));

}



function price($price, $f = 0)

{

	

	

	$kurs   = 1;

	

	

	$curr = explode("\n", @A::$app->catalog->config["currency"]);

	

	foreach ($curr as $v){

		$e = explode(' - ', $v);

		

		if (isset($_SESSION["currency"]) && $_SESSION["currency"]==$e[0]){

			$kurs   = floatval(str_replace(',','.',str_replace(' ', '' , $e[1])));

		}

	}

	



    $price = $price / $kurs;



    if ($f == 0) {

        if ($kurs>1){

            return number_format($price, '2', '.', '');

        }else{

            return number_format($price, '0', '.', '');

        }

    } else {

        if ($kurs>1){

            return number_format($price, '2', '.', ' ');

        }else{

            return number_format($price, '0', '.', ' ');

        }

    }



}


function duplicate($table, $id_field, $id, $lang='')
{
    global $mysql;

    if (strstr($table, '_elements')){
        duplicateE($table, $id_field, $id, '', $lang);
    }else{
        $result          = $mysql->query("SELECT * FROM {$table} WHERE {$id_field}={$id}");
        $original_record = $result->fetch_assoc();
        $ids = duplicateE($table, $id_field, $id, '', $lang);

        $resultE = $mysql->query("SELECT * FROM {$table}_elements WHERE id_section='".$original_record["id"]."'");
        if ($resultE && $resultE->num_rows>0){
            while($original_recordE = $resultE->fetch_assoc()){
                duplicateE($table.'_elements', 'id', $original_recordE["id"], $ids, $lang);
            }
        }

        $resultB = $mysql->query("SELECT * FROM {$table} WHERE id_section='".$original_record["id"]."'");
        if ($resultB && $resultB->num_rows>0){
            while($original_recordB = $resultB->fetch_assoc()){
                $ids1 = duplicateE($table, 'id', $original_recordB["id"], $ids, $lang);
                
                $resultE = $mysql->query("SELECT * FROM {$table}_elements WHERE id_section='".$original_recordB["id"]."'");
                if ($resultE && $resultE->num_rows>0){
                    while($original_recordE = $resultE->fetch_assoc()){
                        duplicateE($table.'_elements', 'id', $original_recordE["id"], $ids1, $lang);
                    }
                }

                $resultB1 = $mysql->query("SELECT * FROM {$table} WHERE id_section='".$original_recordB["id"]."'");
                if ($resultB1 && $resultB1->num_rows>0){
                    while($original_recordB1 = $resultB1->fetch_assoc()){
                        $ids2 = duplicateE($table, 'id', $original_recordB1["id"], $ids1, $lang);

                        $resultE1 = $mysql->query("SELECT * FROM {$table}_elements WHERE id_section='".$original_recordB1["id"]."'");
                        if ($resultE1 && $resultE1->num_rows>0){
                            while($original_recordE1 = $resultE1->fetch_assoc()){
                                duplicateE($table.'_elements', 'id', $original_recordE1["id"], $ids2, $lang);
                            }
                        }

                        $resultB2 = $mysql->query("SELECT * FROM {$table} WHERE id_section='".$original_recordB1["id"]."'");
                        if ($resultB2 && $resultB2->num_rows>0){
                            while($original_recordB2 = $resultB2->fetch_assoc()){
                                $ids3 = duplicateE($table, 'id', $original_recordB2["id"], $ids2, $lang);

                                $resultE2 = $mysql->query("SELECT * FROM {$table}_elements WHERE id_section='".$original_recordB2["id"]."'");
                                if ($resultE2 && $resultE2->num_rows>0){
                                    while($original_recordE2 = $resultE2->fetch_assoc()){
                                        duplicateE($table.'_elements', 'id', $original_recordE2["id"], $ids3, $lang);
                                    }
                                }


                            }
                        }
                    }
                }
            }
        }
    }
}


function duplicateE($table, $id_field, $id, $ids='', $lang='')

{

    global $mysql;

    $result          = $mysql->query("SELECT * FROM {$table} WHERE {$id_field}={$id}");

    $original_record = $result->fetch_assoc();



    $s               = $mysql->query("INSERT INTO {$table} (`{$id_field}`) VALUES (NULL)");

    if (!$s) {

        echo $mysql->error;

    }
    $newid = $mysql->insert_id;


    if ($original_record["id_section"]==2 && $table == 'i_cat'){
        $sql = "select * from i_shop_cat where id_shop='".$original_record["id"]."'";
        $sh = A::$db->get($sql);
        foreach($sh as $hs){
            $sql = "INSERT INTO i_shop_cat set id_shop='".$newid."', id_cat='".$hs["id_cat"]."'";
            A::$db->query($sql);
        }
    }



    $query = "UPDATE {$table} SET ";

    foreach ($original_record as $key => $value) {

        if (

            $key == 'cat_img1' ||

            $key == 'cat_img2' ||

            $key == 'cat_img3' ||

            $key == 'cat_img4' ||

            $key == 'cat_img5' ||

            $key == 'cat_img6' ||

            $key == 'catalog_img' ||

            $key == 'catalog_img1' ||

            $key == 'catalog_img2' ||

            $key == 'catalog_img3' ||

            $key == 'catalog_img4' ||

            $key == 'catalog_img5' ||

            $key == 'catalog_img6' ||

            $key == 'brand_img' ||

            $key == 'ban_img' ||

            $key == 'slider_img' ||

            $key == 'news_img' ||

            $key == 'article_img' ||

            $key == 'page_img' ||

            $key == 'gal_img' ||

            $key == 'image' ||
            $key == 'image1' ||
            $key == 'image2' ||
            $key == 'image3' ||
            $key == 'image4' ||
            $key == 'image5' ||
            $key == 'image6' ||
            $key == 'icon' ||
            $key == 'fon' ||
            $key == 'flag' ||

            $key == 'video_img'

        ) {



            if ($value != '') {

                $ext  = @end(@explode('.', @$value));

                $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H") . "." . $ext;

                $pic1 = @file_get_contents('http://' . $_SERVER["HTTP_HOST"] . '/upload/images/' . $value);

                @file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/upload/images/' . $text, $pic1);

                $pic2 = @file_get_contents('http://' . $_SERVER["HTTP_HOST"] . '/upload/images/small/' . $value);

                @file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/upload/images/small/' . $text, $pic2);

                $pic3 = @file_get_contents('http://' . $_SERVER["HTTP_HOST"] . '/upload/images/big/' . $value);

                @file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/upload/images/big/' . $text, $pic3);

                $value = $text;

            }

        }





        if (

            $key == 'video' ||

            $key == 'pdf' ||

            $key == 'all_files'

        ) {



            if ($value != '') {

                $ext  = @end(@explode('.', @$value));

                $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H") . "." . $ext;

                $pic1 = @file_get_contents('http://' . $_SERVER["HTTP_HOST"] . '/upload/files/' . $value);

                @file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/upload/files/' . $text, $pic1);

                

            }

        }





        if ($key != $id_field) {
            if ($ids!=''){
                if ($key=='id_section'){
                    $query .= "`" . $key . "` = '" . $mysql->real_escape_string($ids) . "', ";
                }else{
                    if($key=='url'){
                        $query .= "`" . $key . "` = '-".$newid."', ";  
                    }else {
                        if($key=='slug'){
                            if ($lang==''){
                                $query .= "`" . $key . "` = '".$newid."', ";  
                            }else{
                                $query .= "`" . $key . "` = '".$mysql->real_escape_string($value)."', ";  
                            }
                        }else{
                            if ($lang!='' && $key=='version'){
                                $query .= "`" . $key . "` = '" . $mysql->real_escape_string($lang) . "', ";  
                            }else{
                                $query .= "`" . $key . "` = '" . $mysql->real_escape_string($value) . "', ";      
                            }
                        } 
                    }
                }
            }else{
                if($key=='url'){
                    $query .= "`" . $key . "` = '-".$newid."', ";  
                }else {
                    if($key=='slug'){
                        if ($lang==''){
                            $query .= "`" . $key . "` = '".$newid."', ";  
                        }else{
                            $query .= "`" . $key . "` = '".$mysql->real_escape_string($value)."', ";  
                        }
                    }else{
                        if ($lang!='' && $key=='version'){
                            $query .= "`" . $key . "` = '" . $mysql->real_escape_string($lang) . "', ";  
                        }else{
                            $query .= "`" . $key . "` = '" . $mysql->real_escape_string($value) . "', ";      
                        }

                    } 
                }
            }
        }
    }

    $query = substr($query, 0, strlen($query) - 2);

    $query .= " WHERE {$id_field}={$newid}";




    $s = $mysql->query($query);

    $sql = "select * from ".$table." where id='".$newid."'";
    
    $resOne = $mysql->get($sql, 1);

    

    if ($table!='i_option'){
        if (strstr($table, '_elements')){
            $sql = "select * from i_option where category='".str_replace("i_", '', $table)."' and category_id='".$original_record["id_section"]."'";
            $option_res = $mysql->get($sql);
            foreach ($option_res as $key => $v) {
                duplicateO(str_replace("i_", '', $table), 'id', $v["id"], $resOne["id_section"]);
            }
        }else{
            $sql = "select * from i_option where category='".str_replace("i_", '', $table)."' and category_id='".$original_record["id"]."'";
            $option_res = $mysql->get($sql);
            foreach ($option_res as $key => $v) {
                duplicateO(str_replace("i_", '', $table), 'id', $v["id"], $resOne["id"]);
            }
        }
    }


    alias($newid, str_replace('i_','', $table), '-'.$newid, (strstr($table, '_elements')?0:1));

    return $newid;

}



function duplicateO($table, $id_field, $id, $ids)
{

    global $mysql;
    $result          = $mysql->query("SELECT * FROM i_option WHERE {$id_field}={$id}");
    $original_record = $result->fetch_assoc();

    

    $sql = "select * from i_option where category_id='".$ids."' and category='".$table."' and name_en='".$original_record["name_en"]."'";
    $res = $mysql->get($sql, 1);

    

    if (@$res["id"]>0){

    }else{
        $s = $mysql->query("INSERT INTO i_option (`{$id_field}`) VALUES (NULL)");
        if (!$s) {
            echo $mysql->error;
        }
        
        $newid = $mysql->insert_id;

        $query = "UPDATE i_option SET ";
        foreach ($original_record as $key => $value) {
            if ($key != $id_field) {
                if ($ids!=''){
                    if ($key=='category_id'){
                        $query .= "`" . $key . "` = '" . $mysql->real_escape_string($ids) . "', ";
                    }else{

                        $query .= "`" . $key . "` = '" . $mysql->real_escape_string($value) . "', ";      
                    }
                }
            }
        }

        $query = substr($query, 0, strlen($query) - 2);
        $query .= " WHERE {$id_field}={$newid}";
        $s = $mysql->query($query);
        return $newid;
    }

}



function t($name, $module='')
{
    $langFile = $_SERVER["DOCUMENT_ROOT"].'/modules/'.($module==''?'page':$module).'/lang/'.LANG.'.ini';
    $ini_array = @parse_ini_file($langFile);
    return isset($ini_array[$name]) ? $ini_array[$name] : $name;
}

function arr2ini(array $a, array $parent = array())
{
    $out = '';
    foreach ($a as $k => $v)  {
        if (is_array($v)){
            $sec = array_merge((array) $parent, (array) $k);
            $out .= '[' . join('.', $sec) . ']' . PHP_EOL;
            $out .= arr2ini($v, $sec);
        }else{
            $out .= "$k='$v'" . PHP_EOL;
        }
    }
    return $out;
}


function alias($id, $module, $name, $block = 1){
    $sql = "select id from i_alias where slug=$id and module='$module'";
    $res = A::$db->get($sql);
    if (sizeof($res)>0){
        $sql = "update i_alias set name='$name' where slug='$id' and module='$module'";
        A::$db->query($sql);
    }else{
        $sql = "insert into i_alias set name='$name', slug='$id', version='".$_SESSION["version"]."', ";
        $sql.= "module='$module', block='$block'";
        A::$db->query($sql);
    }
}

function uploadImage($name, $key, $isArray){
    $types = array('image/gif', 'image/png', 'image/jpeg');
    $size = 1024000;

    $success = 0;
    $error = '';

    if ($isArray==0){
        if (!in_array($_FILES[$name]['type'], $types)){
            $error = "Неверный тип изображения";
        }
        if ($_FILES[$name]['size'] > $size){
            $error = "Размер файла превышает 1Мб";
        }
    }else{
        if (!in_array($_FILES[$name]['type'][$key], $types)){
            $error = "Неверный тип файла ".$_FILES[$name]['name'][$key];
        }
        if ($_FILES[$name]['size'][$key] > $size){
            $error = "Размер файла ".$_FILES[$name]['name'][$key]." превышает 1Мб";
        }
    }

    if ($error==''){
        $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H");
        if ($isArray==0){
            $ext = @end(explode('.', $_FILES[$name]['name']));
            $upload = move_uploaded_file(
                $_FILES[$name]['tmp_name'], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/images/".$text.'.'.$ext
            );
            $image = $text.'.'.$ext;
            if (!$upload) {
                $error = 'Не возможно загрузить файл '.$_FILES[$name]['name'].'!<br>';
            }else{
                $resizeObj_small = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_small->resizeImage(400, 200, 'auto');
                $resizeObj_small->saveImage('/upload/images/small/'.$text.'.'.$ext, 100);
                $resizeObj_big = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_big->resizeImage(200, 400, 'auto');
                $resizeObj_big->saveImage('/upload/images/big/'.$text.'.'.$ext, 100);
                $success = 1;
            }
        }else{
            $ext = @end(explode('.', $_FILES[$name]['name'][$key]));
            $upload = move_uploaded_file(
                $_FILES[$name]['tmp_name'][$key], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/images/".$text.'.'.$ext
            );
            $image = $text.'.'.$ext;
            if (!$upload) {
                $error = 'Не возможно загрузить файл '.$_FILES[$name]['name'][$key].'!<br>';
            }else{
                $resizeObj_small = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_small->resizeImage(730, 425, 'crop');
                $resizeObj_small->saveImage('/upload/images/small/'.$text.'.'.$ext, 100);
                $resizeObj_big = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_big->resizeImage(494, 728, 'crop');
                $resizeObj_big->saveImage('/upload/images/big/'.$text.'.'.$ext, 100);
                $success = 1;
            }
        }

        if ($error==''){
            return array(
                'success' => $success,
                'name' => $image,
            );
        }else{
            return array(
                'success' => $success,
                'error' => $error,
            );
        }

    }else{
        return array(
            'success' => $success,
            'error' => $error,
        );
    }
}



function uploadPreview($name, $key, $isArray){
    $types = array('image/png');
    $size = 1024000;

    $success = 0;
    $error = '';

    if ($isArray==0){
        if (!in_array($_FILES[$name]['type'], $types)){
            $error = "Неверный тип изображения";
        }
        if ($_FILES[$name]['size'] > $size){
            $error = "Размер файла превышает 1Мб";
        }
    }else{
        if (!in_array($_FILES[$name]['type'][$key], $types)){
            $error = "Неверный тип файла ".$_FILES[$name]['name'][$key];
        }
        if ($_FILES[$name]['size'][$key] > $size){
            $error = "Размер файла ".$_FILES[$name]['name'][$key]." превышает 1Мб";
        }
    }

    if ($error==''){
        $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H");
        if ($isArray==0){
            $ext = @end(explode('.', $_FILES[$name]['name']));
            $upload = move_uploaded_file(
                $_FILES[$name]['tmp_name'], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/images/".$text.'.'.$ext
            );
            $image = $text.'.'.$ext;
            if (!$upload) {
                $error = 'Не возможно загрузить файл '.$_FILES[$name]['name'].'!<br>';
            }else{
                $resizeObj_small = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_small->resizeImage(287, 287, 'auto');
                $resizeObj_small->saveImage('/upload/images/small/'.$text.'.'.$ext, 100);
                $resizeObj_big = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_big->resizeImage(310, 310, 'auto');
                $resizeObj_big->saveImage('/upload/images/big/'.$text.'.'.$ext, 100);
                $success = 1;
            }
        }else{
            $ext = @end(explode('.', $_FILES[$name]['name'][$key]));
            $upload = move_uploaded_file(
                $_FILES[$name]['tmp_name'][$key], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/images/".$text.'.'.$ext
            );
            $image = $text.'.'.$ext;
            if (!$upload) {
                $error = 'Не возможно загрузить файл '.$_FILES[$name]['name'][$key].'!<br>';
            }else{
                $resizeObj_small = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_small->resizeImage(287, 287, 'auto');
                $resizeObj_small->saveImage('/upload/images/small/'.$text.'.'.$ext, 100);
                $resizeObj_big = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_big->resizeImage(310, 310, 'auto');
                $resizeObj_big->saveImage('/upload/images/big/'.$text.'.'.$ext, 100);
                $success = 1;
            }
        }

        if ($error==''){
            return array(
                'success' => $success,
                'name' => $image,
            );
        }else{
            return array(
                'success' => $success,
                'error' => $error,
            );
        }

    }else{
        return array(
            'success' => $success,
            'error' => $error,
        );
    }
}



function uploadPreviewWJPG($name, $key, $isArray){
    $types = array('image/png', 'image/jpeg');
    $size = 1024000;

    $success = 0;
    $error = '';

    if ($isArray==0){
        if (!in_array($_FILES[$name]['type'], $types)){
            $error = "Неверный тип изображения";
        }
        if ($_FILES[$name]['size'] > $size){
            $error = "Размер файла превышает 1Мб";
        }
    }else{
        if (!in_array($_FILES[$name]['type'][$key], $types)){
            $error = "Неверный тип файла ".$_FILES[$name]['name'][$key];
        }
        if ($_FILES[$name]['size'][$key] > $size){
            $error = "Размер файла ".$_FILES[$name]['name'][$key]." превышает 1Мб";
        }
    }

    if ($error==''){
        $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H");
        if ($isArray==0){
            $ext = @end(explode('.', $_FILES[$name]['name']));
            $upload = move_uploaded_file(
                $_FILES[$name]['tmp_name'], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/images/".$text.'.'.$ext
            );
            $image = $text.'.'.$ext;
            if (!$upload) {
                $error = 'Не возможно загрузить файл '.$_FILES[$name]['name'].'!<br>';
            }else{
                $resizeObj_small = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_small->resizeImage(287, 287, 'auto');
                $resizeObj_small->saveImage('/upload/images/small/'.$text.'.'.$ext, 100);
                $resizeObj_big = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_big->resizeImage(310, 310, 'auto');
                $resizeObj_big->saveImage('/upload/images/big/'.$text.'.'.$ext, 100);
                $success = 1;
            }
        }else{
            $ext = @end(explode('.', $_FILES[$name]['name'][$key]));
            $upload = move_uploaded_file(
                $_FILES[$name]['tmp_name'][$key], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/images/".$text.'.'.$ext
            );
            $image = $text.'.'.$ext;
            if (!$upload) {
                $error = 'Не возможно загрузить файл '.$_FILES[$name]['name'][$key].'!<br>';
            }else{
                $resizeObj_small = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_small->resizeImage(287, 287, 'auto');
                $resizeObj_small->saveImage('/upload/images/small/'.$text.'.'.$ext, 100);
                $resizeObj_big = new resize('/upload/images/'.$text.'.'.$ext);
                $resizeObj_big->resizeImage(310, 310, 'auto');
                $resizeObj_big->saveImage('/upload/images/big/'.$text.'.'.$ext, 100);
                $success = 1;
            }
        }

        if ($error==''){
            return array(
                'success' => $success,
                'name' => $image,
            );
        }else{
            return array(
                'success' => $success,
                'error' => $error,
            );
        }

    }else{
        return array(
            'success' => $success,
            'error' => $error,
        );
    }
}


function uploadTiff($name, $key, $isArray){
    $types = array('image/tiff');
    $size = 104857600;

    $success = 0;
    $error = '';

    if ($isArray==0){
        if (!in_array($_FILES[$name]['type'], $types)){
            $error = "Неверный тип изображения";
        }
        if ($_FILES[$name]['size'] > $size){
            $error = "Размер файла превышает 100Мб";
        }
    }else{
        if (!in_array($_FILES[$name]['type'][$key], $types)){
            $error = "Неверный тип файла ".$_FILES[$name]['name'][$key];
        }
        if ($_FILES[$name]['size'][$key] > $size){
            $error = "Размер файла ".$_FILES[$name]['name'][$key]." превышает 100Мб";
        }
    }

    if ($error==''){
        $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H");
        if ($isArray==0){
            $ext = @end(explode('.', $_FILES[$name]['name']));
            $upload = move_uploaded_file(
                $_FILES[$name]['tmp_name'], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/files/".$text.'.'.$ext
            );
            $image = $text.'.'.$ext;
            if (!$upload) {
                $error = 'Не возможно загрузить файл '.$_FILES[$name]['name'].'!<br>';
            }else{
                $success = 1;
            }
        }else{
            $ext = @end(explode('.', $_FILES[$name]['name'][$key]));
            $upload = move_uploaded_file(
                $_FILES[$name]['tmp_name'][$key], 
                $_SERVER['DOCUMENT_ROOT'] . "/upload/files/".$text.'.'.$ext
            );
            $image = $text.'.'.$ext;
            if (!$upload) {
                $error = 'Не возможно загрузить файл '.$_FILES[$name]['name'][$key].'!<br>';
            }else{
                $success = 1;
            }
        }

        if ($error==''){
            return array(
                'success' => $success,
                'name' => $image,
            );
        }else{
            return array(
                'success' => $success,
                'error' => $error,
            );
        }

    }else{
        return array(
            'success' => $success,
            'error' => $error,
        );
    }
}
