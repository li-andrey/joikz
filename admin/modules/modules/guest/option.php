<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/header.php";

$nameRazdel = 'guest';
$nameElement = 'guest_elements';
$nameModule = 'guest';

// если удалить поле
if (@$_GET['delete'] == "true" and $_GET['id_field']) {
    $select = $ob->select("i_option", array("id" => @$_GET['id_field']), "");
    $res = $select->fetch_array();
    
    $select = $ob->select("i_option", array("name_en" => $res['name_en']), "");
    
    if ($select->num_rows > 1) {
        $mysql->query("delete from i_option where id='" . $_GET['id_field'] . "'");
    } else {
        $res = $select->fetch_array();
        $mysql->query("ALTER TABLE i_" . $_GET['module'] . " DROP " . $res['name_en'] . "");
        $i = $mysql->query("delete from i_option where id='" . $_GET['id_field'] . "'");
    }
}

if (@$_POST["app_templates"]) {
    if ($_POST["module"] != '' && $_POST["templates"] != '' && $_POST["ids"] != '') {
        
        $s = $mysql->query("select * from i_option where category='templates' and category_id=" . intval($_POST["templates"]) . "");
        if ($s) {
            while ($r = $s->fetch_array()) {
                $type_res = "varchar(250)";
                
                $test = $mysql->query("select * from i_option where category='" .@$_POST["module"] . "'
                  and category_id='" .@$_POST["ids"] . "' and name_en='" . $r["name_en"] . "'");
                if ($test->num_rows == 0) {
                    $i = $mysql->query("insert into i_option values ('','" .@$_POST["module"] . "','" .@$_POST["ids"] . "',
                      '" . $r["required_fields"] . "','" . $r["select_fields"] . "','" . $r["id_sort"] . "','" . $ob->pr($r["name_ru"]) . "',
                      '" . $r["name_en"] . "','" . $r["type_field"] . "','" . $r["width"] . "','" . $r["format_date"] . "',
                      '" . $r["height"] . "','" . $r["select_elements"] . "','" . $r["size_file"] . "','" . $r["format_file"] . "',
                      '" . $r["type_resize"] . "','" . $r["w_resize_small"] . "','" . $r["h_resize_small"] . "','" . $r["watermark"] . "',
                      '" . $r["w_resize_big"] . "','" . $r["h_resize_big"] . "','" . $r["filter"] . "','" . $r["search"] . "',
                      '" . $r["translit"] . "',
                      '" . $r["head"] . "')");
                }
                
                if (!$i) echo $mysql->error;
                
                if ($r['type_field'] == "i1" or $r['type_field'] == "i3" or $r['type_field'] == "i4"
                    or $r['type_field'] == "i7") {
                    
                    $type_res = "varchar(250)";
                    
                }
                
                if ($r['type_field'] == "i11" or $r['type_field'] == "i12" or $r['type_field'] == "i13"){
                    $type_res = "text";
                }
                
                
                
                if ($r['type_field'] == "i2") {$type_res = "datetime";}
                
                if ($r['type_field'] == "i5") {$type_res = "int(11)";}
                
                if ($r['type_field'] == "i6" or $r['type_field'] == "i8" or $r['type_field'] == "i9"
                    or $r['type_field'] == "i10") {$type_res = "LONGTEXT";}
                
                if ($r['type_field'] == "i7") {$type_res = "tinyint(4)";}
                
                $i = @$mysql->query("ALTER TABLE i_" .@$_POST['module'] . " ADD " . $ob->pr($r['name_en']) . " " . $type_res . "");
                
                
                
            }
        }
    }
}



if (@$_POST['lang']) {
    $langFile = $_SERVER["DOCUMENT_ROOT"].'/modules/'.$nameModule.'/lang/'.$_SESSION["version"].'.ini';
    $langArray = array();
    foreach ($_POST["key"] as $key => $value) {
        if ($value!=''){
            $langArray[$value] = $_POST["value"][$key];
        }
    }
    
    $inisave = arr2ini($langArray);
    //vd($inisave);
    $file_handle = fopen($langFile, "w");
    fwrite($file_handle, $inisave);
    fclose($file_handle);
}



if (@$_POST['config']) {
    
    $configFile = $_SERVER["DOCUMENT_ROOT"].'/modules/'.$nameModule.'/config/config.ini';
    $configArray = array();
    foreach ($_POST["key"] as $key => $value) {
        if ($value!=''){
            $configArray[$value] = $_POST["value"][$key];
        }
    }
    
    $inisave = arr2ini($configArray);
    $file_handle = fopen($configFile, "w");
    fwrite($file_handle, $inisave);
    fclose($file_handle);
}

// если отправлены данные
if (@$_POST['hidden']) {
    
    
    
    // если сохранить то вернуться на предыдущую страницу
    if ($_POST['hidden'] == 'save') {$page = $_POST['reffer'];} else { $page = "?module=" . @$_GET['module'] . "&id_section=" . (int)@$_GET['id_section'];}
    
    // если не обновление
    if (!@$_GET['id']) {
        
        $select = $ob->select("i_option", array("category" => @$_GET['module'], "category_id" => (int)@$_GET['id_section'], "name_en" => $_POST['name_en']), "");
        
        // если обновление
    } else {
        
        $select = $mysql->query("select * from i_option where category='" . @$_GET['module'] . "' and category_id='" . (int)@$_GET['id_section'] . "' AND name_en='" . @$ob->pr($_POST['name_en']) . "' AND id<>'" . $ob->pr($_GET['id']) . "'");
        
    }
    
    if ($_POST['type_field'] == "i1" or $_POST['type_field'] == "i3" or $_POST['type_field'] == "i4"
        or $_POST['type_field'] == "i7") {
        
        $type_res = "varchar(250)";
        
    }
    
    if ($_POST['type_field'] == "i11" or $_POST['type_field'] == "i12" or $_POST['type_field'] == "i13"){
        $type_res = "text";
    }
    
    
    if ($_POST['type_field'] == "i2") {$type_res = "datetime";}
    
    if ($_POST['type_field'] == "i5") {$type_res = "int(11)";}
    
    if ($_POST['type_field'] == "i6" or $_POST['type_field'] == "i8" or $_POST['type_field'] == "i9" or $_POST['type_field'] == "i10") {$type_res = "LONGTEXT";}
    
    if ($_POST['type_field'] == "i7") {$type_res = "tinyint(4)";}
    
    if ($_POST["w_resize_small"] == '') {$_POST["w_resize_small"] = 200;}
    if ($_POST["h_resize_small"] == '') {$_POST["h_resize_small"] = 200;}
    if ($_POST["w_resize_big"] == '') {$_POST["w_resize_big"] = 600;}
    if ($_POST["h_resize_big"] == '') {$_POST["h_resize_big"] = 600;}
    
    
    
    // если нет такого
    if ($select->num_rows == 0) {
        //  если выбрано поле
        if ($_POST['type_field'] != "i0") {
            
            //если выбрано удаление
            if (@$_POST['delete' . $res['id'] . ''] == 1) {
                $delete     = $ob->select("i_option", array("id" => @$_GET['id']), "");
                $delete_res = $delete->fetch_array();
                unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/images/watermark/" . $res["watermark"] . "");
                $watermark = '';
            }
            
            //если не редактирование
            if (!@$_GET['id']) {
                
                // добавляем поле
                $i = $mysql->query("ALTER TABLE i_" . $_GET['module'] . " ADD " . $ob->pr($_POST['name_en']) . " " . $type_res . "");
                
                if (!empty($_FILES['watermark']['tmp_name'])) {
                    
                    if ($_FILES['watermark']['size'] <= 1000000) {
                        
                        $format = array('png', 'PNG');
                        
                        if (in_array($ob->getExtension($_FILES['watermark']['name']), $format)) {
                            
                            $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H") . "." . $ob->getExtension($_FILES['watermark']['name']);
                            
                            $upload = move_uploaded_file($_FILES['watermark']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/watermark/" . $text);
                            
                            $watermark = $text;
                            
                            if (!$upload) {
                                
                                $ob->alert('Не возможно загрузить файл!');
                                $watermark = '';
                            }
                            
                        } else {
                            
                            $ob->alert('Не верный формат файла');
                        }
                        
                    } else {
                        
                        $ob->alert("Файл превышает размер 1000000 байт");
                        
                    }
                    
                }
                
                $field = "'" . @$_GET['module'] . "','" . @$_GET['id_section'] . "','" . @$_POST['required_fields'] . "','" . @$_POST['select_fields'] . "','" .@$_POST['id_sort'] . "','" .@$_POST['name_ru'] . "','" .@$_POST['name_en'] . "','" .@$_POST['type_field'] . "','" . @$_POST['' .@$_POST['type_field'] . '_width'] . "','" . @$_POST['' .@$_POST['type_field'] . '_format_date'] . "','" . @$_POST['' .@$_POST['type_field'] . '_height'] . "','" . @$_POST['' .@$_POST['type_field'] . '_select_elements'] . "','" . @$_POST['' .@$_POST['type_field'] . '_size_file'] . "','" . @$_POST['' .@$_POST['type_field'] . '_format_file'] . "','" .@$_POST["type_resize"] . "','" .@$_POST["w_resize_small"] . "','" .@$_POST["h_resize_small"] . "','" . @$watermark . "','" .@$_POST["w_resize_big"] . "','" .@$_POST["h_resize_big"] . "','" .@$_POST["filter"] . "','" .@$_POST["search"] . "','" .@$_POST["translit"] . "','" .@$_POST["section"] . "'";
                
                $ob->insert("i_option", $field, $page);
                
                // если редактирование
            } else {
                
                $select = $ob->select("i_option", array("id" => @$_GET['id']), "");
                $res    = $select->fetch_array();
                
                $select1 = $mysql->query("select * from i_option where category='" . $_GET['module'] . "' AND name_en='" . $res['name_en'] . "' ");
                
                
                
                
                
                if ($select1->num_rows == 1) {
                    $i = $mysql->query("ALTER TABLE i_" . $_GET['module'] . " CHANGE " . $res['name_en'] . " " . $ob->pr($_POST['name_en']) . " " . @$type_res . "");
                } else {
                    $i = $mysql->query("ALTER TABLE i_" . $_GET['module'] . " ADD " . $ob->pr($_POST['name_en']) . " " . @$type_res . "");
                }
                
                if (!$i) {
                    $i = $mysql->query("ALTER TABLE i_" . $_GET['module'] . " ADD " . $ob->pr($_POST['name_en']) . " " . @$type_res . "");
                }
                
                if ($i || $select1->num_rows > 0) {
                    
                    
                    if (!empty($_FILES['watermark']['tmp_name'])) {
                        
                        if ($_FILES['watermark']['size'] <= 1000000) {
                            
                            $format = array('png', 'PNG');
                            
                            if (in_array($ob->getExtension($_FILES['watermark']['name']), $format)) {
                                
                                $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H") . "." . $ob->getExtension($_FILES['watermark']['name']);
                                
                                $upload = move_uploaded_file($_FILES['watermark']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/watermark/" . $text);
                                
                                $watermark = $text;
                                
                                if (!$upload) {
                                    
                                    $ob->alert('Не возможно загрузить файл!');
                                    $watermark = '';
                                }
                                
                            } else {
                                
                                $ob->alert('Не верный формат файла');
                            }
                            
                        } else {
                            
                            $ob->alert("Файл превышает размер 1000000 байт");
                            
                        }
                        
                    }
                    
                    $field = array("required_fields" => @$_POST['required_fields'], "select_fields" => @$_POST['select_fields'], "id_sort" => $_POST['id_sort'], "name_ru" => $_POST['name_ru'], "name_en" => $_POST['name_en'], "type_field" => $_POST['type_field'], "width" => @$_POST['' .@$_POST['type_field'] . '_width'], "format_date" => @$_POST['' .@$_POST['type_field'] . '_format_date'], "height" => @$_POST['' .@$_POST['type_field'] . '_height'], "select_elements" => @$_POST['' .@$_POST['type_field'] . '_select_elements'], "size_file" => @$_POST['' .@$_POST['type_field'] . '_size_file'], "format_file" => @$_POST['' .@$_POST['type_field'] . '_format_file'], "type_resize" => @$_POST['type_resize'], "w_resize_small" => @$_POST['w_resize_small'], "h_resize_small" => @$_POST['h_resize_small'], "watermark" => @$watermark, "w_resize_big" => @$_POST['w_resize_big'], "h_resize_big" => @$_POST['h_resize_big'], "filter" => @$_POST['filter'], "search" => @$_POST['search'], "translit" => @$_POST['translit'], "head" => @$_POST['section']);
                    
                    $ob->update("i_option", $field, $_GET['id'], $page);
                    
                    $field2 = array("name_en" => $_POST['name_en'], "type_field" => $_POST['type_field'], "width" => @$_POST['' .@$_POST['type_field'] . '_width'], "format_date" => @$_POST['' .@$_POST['type_field'] . '_format_date'], "height" => @$_POST['' .@$_POST['type_field'] . '_height'], "select_elements" => @$_POST['' .@$_POST['type_field'] . '_select_elements'], "size_file" => @$_POST['' .@$_POST['type_field'] . '_size_file'], "format_file" => @$_POST['' .@$_POST['type_field'] . '_format_file'], "type_resize" => @$_POST['type_resize'], "w_resize_small" => @$_POST['w_resize_small'], "h_resize_small" => @$_POST['h_resize_small'], "watermark" => @$watermark, "w_resize_big" => @$_POST['w_resize_big'], "h_resize_big" => @$_POST['h_resize_big'], "filter" => @$_POST['filter'], "search" => @$_POST['search'], "translit" => @$_POST['translit'], "head" => @$_POST['section']);
                    
                    $i = 0;
                    foreach ($field2 as $k => $v) {
                        
                        if (($i != count($field2)) and ($i != 0)) {@$str2 .= ",";}
                        @$str2 .= '`' . $k . '`=\'' . $v . '\'';
                        $i++;
                    }
                    
                    //$update=$mysql->query("update `i_option` set ".$str2." where `name_en`='".$res['name_en']."'");
                    
                } else {
                    
                    $ob->alert("Ошибка создания поля!" . $mysql->error());
                    
                }
            }
            
        } else {
            
            $ob->alert("Необходимо выбрать тип файла!");
            
        }
        
    } else {
        //$ob->alert("Извените, но данное поле уже существует!");
        $qr = $mysql->query("select * from `i_option` where `category`='" . $_GET['module'] . "' AND `name_en`='" . $ob->pr($_POST['name_en']) . "' AND `category_id`='" . $ob->pr($_GET['id_section']) . "'" . (@$_GET['id'] ? ' AND `id`!=' . $_GET['id'] : ''));
        
        $select1 = $mysql->query("select * from i_option where category='" . $_GET['module'] . "' AND name_en='" . $ob->pr($_POST['name_en']) . "' " . (@$_GET['id'] ? ' AND `id`!=' . @$_GET['id'] : ''));
        
        if ($qr->num_rows < 1) {
            if ($_POST['type_field'] != "i0") {
                
                if (!@$_GET['id']) {
                    $i = $mysql->query("ALTER TABLE i_" . $_GET['module'] . " CHANGE " .@$_POST['name_en'] . " " . $ob->pr($_POST['name_en']) . " " . $type_res . "");
                    if ($i) {
                        
                        if (!empty($_FILES['watermark']['tmp_name'])) {
                            
                            if ($_FILES['watermark']['size'] <= 1000000) {
                                
                                $format = array('png', 'PNG');
                                
                                if (in_array($ob->getExtension($_FILES['watermark']['name']), $format)) {
                                    
                                    $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H") . "." . $ob->getExtension($_FILES['watermark']['name']);
                                    
                                    $upload = move_uploaded_file($_FILES['watermark']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/watermark/" . $text);
                                    
                                    $watermark = $text;
                                    
                                    if (!$upload) {
                                        
                                        $ob->alert('Не возможно загрузить файл!');
                                        $watermark = '';
                                    }
                                    
                                } else {
                                    
                                    $ob->alert('Не верный формат файла');
                                }
                                
                            } else {
                                
                                $ob->alert("Файл превышает размер 1000000 байт");
                                
                            }
                            
                        }
                        
                        $field = "'" . $_GET['module'] . "','" . $_GET['id_section'] . "','" .@$_POST['required_fields'] . "','" .@$_POST['select_fields'] . "','" .@$_POST['id_sort'] . "','" .@$_POST['name_ru'] . "','" .@$_POST['name_en'] . "','" .@$_POST['type_field'] . "','" . @$_POST['' .@$_POST['type_field'] . '_width'] . "','" . @$_POST['' .@$_POST['type_field'] . '_format_date'] . "','" . @$_POST['' .@$_POST['type_field'] . '_height'] . "','" . @$_POST['' .@$_POST['type_field'] . '_select_elements'] . "','" . @$_POST['' .@$_POST['type_field'] . '_size_file'] . "','" . @$_POST['' .@$_POST['type_field'] . '_format_file'] . "','" .@$_POST["type_resize"] . "','" .@$_POST["w_resize_small"] . "','" .@$_POST["h_resize_small"] . "','" . $watermark . "','" .@$_POST["w_resize_big"] . "','" .@$_POST["h_resize_big"] . "','" .@$_POST["filter"] . "', '" .@$_POST["search"] . "','" .@$_POST["translit"] . "','" .@$_POST["section"] . "'";
                        
                        $ob->insert("i_option", $field, $page);
                        ///////////////////////////////////////////////////
                        $field2 = array("name_en" => $_POST['name_en'], "type_field" => $_POST['type_field'], "width" => @$_POST['' .@$_POST['type_field'] . '_width'], "format_date" => @$_POST['' .@$_POST['type_field'] . '_format_date'], "height" => @$_POST['' .@$_POST['type_field'] . '_height'], "select_elements" => @$_POST['' .@$_POST['type_field'] . '_select_elements'], "size_file" => @$_POST['' .@$_POST['type_field'] . '_size_file'], "format_file" => @$_POST['' .@$_POST['type_field'] . '_format_file'], "type_resize" => @$_POST['type_resize'], "w_resize_small" => @$_POST['w_resize_small'], "h_resize_small" => @$_POST['h_resize_small'], "watermark" => @$watermark, "w_resize_big" => @$_POST['w_resize_big'], "h_resize_big" => @$_POST['h_resize_big'], "filter" => @$_POST['filter'], "search" => @$_POST['search'], "translit" => @$_POST['translit'], "head" => @$_POST['section']);
                        $i      = 0;
                        foreach ($field2 as $k => $v) {
                            if (($i != count($field2)) and ($i != 0)) {@$str2 .= ",";}
                            @$str2 .= '`' . $k . '`=\'' . $v . '\'';
                            $i++;
                        }
                        //$update=$mysql->query("update `i_option` set ".$str2." where `name_en`='".@$_POST['name_en']."'");
                        if (!$update) {
                            $ob->alert("Record has not update!\\nREASON:" . $mysql->error);
                        }
                    } else {
                        $ob->alert("Record has not update!\\nREASON:" . $mysql->error);
                    }
                } else {
                    $select = $ob->select("i_option", array("id" => @$_GET['id']), "");
                    $res    = $select->fetch_array();
                    //vd("ALTER TABLE i_".$_GET['module']." CHANGE ".$res['name_en']." ".$ob->pr($_POST['name_en'])." ".$type_res."");exit;ALTER TABLE i_cat_elements CHANGE news_full news_full varchar(100)
                    if ($select1->num_rows == 0) {
                        $i = $mysql->query("ALTER TABLE i_" . $_GET['module'] . " CHANGE " . $res['name_en'] . " " . $ob->pr($_POST['name_en']) . " " . $type_res . "");
                    } else {
                        $i = $mysql->query("ALTER TABLE i_" . $_GET['module'] . " ADD " . $ob->pr($_POST['name_en']) . " " . $type_res . "");
                    }
                    
                    if ($i) {
                        
                        if (!empty($_FILES['watermark']['tmp_name'])) {
                            
                            if ($_FILES['watermark']['size'] <= 1000000) {
                                
                                $format = array('png', 'PNG');
                                
                                if (in_array($ob->getExtension($_FILES['watermark']['name']), $format)) {
                                    
                                    $text = rand(0, 100000) . "_" . rand(500, 1000000) . "_" . date("H") . "." . $ob->getExtension($_FILES['watermark']['name']);
                                    
                                    $upload = move_uploaded_file($_FILES['watermark']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/watermark/" . $text);
                                    
                                    $watermark = $text;
                                    
                                    if (!$upload) {
                                        
                                        $ob->alert('Не возможно загрузить файл!');
                                        
                                        $watermark = '';
                                    }
                                    
                                } else {
                                    
                                    $ob->alert('Не верный формат файла');
                                    
                                }
                                
                            } else {
                                
                                $ob->alert("Файл превышает размер 1000000 байт");
                                
                            }
                            
                        }
                        
                        $field  = array("required_fields" => @$_POST['required_fields'], "select_fields" => @$_POST['select_fields'], "id_sort" => $_POST['id_sort'], "name_ru" => $_POST['name_ru'], "name_en" => $_POST['name_en'], "type_field" => $_POST['type_field'], "width" => @$_POST['' .@$_POST['type_field'] . '_width'], "format_date" => @$_POST['' .@$_POST['type_field'] . '_format_date'], "height" => @$_POST['' .@$_POST['type_field'] . '_height'], "select_elements" => @$_POST['' .@$_POST['type_field'] . '_select_elements'], "size_file" => @$_POST['' .@$_POST['type_field'] . '_size_file'], "format_file" => @$_POST['' .@$_POST['type_field'] . '_format_file'], "type_resize" => @$_POST['type_resize'], "w_resize_small" => @$_POST['w_resize_small'], "h_resize_small" => @$_POST['h_resize_small'], "watermark" => @$watermark, "w_resize_big" => @$_POST['w_resize_big'], "h_resize_big" => @$_POST['h_resize_big'], "filter" => @$_POST['filter'], "search" => @$_POST['search'], "translit" => @$_POST['translit'], "head" => @$_POST['section']);
                        $field2 = array("name_en" => $_POST['name_en'], "type_field" => $_POST['type_field'], "width" => @$_POST['' .@$_POST['type_field'] . '_width'], "format_date" => @$_POST['' .@$_POST['type_field'] . '_format_date'], "height" => @$_POST['' .@$_POST['type_field'] . '_height'], "select_elements" => @$_POST['' .@$_POST['type_field'] . '_select_elements'], "size_file" => @$_POST['' .@$_POST['type_field'] . '_size_file'], "format_file" => @$_POST['' .@$_POST['type_field'] . '_format_file'], "type_resize" => @$_POST['type_resize'], "w_resize_small" => @$_POST['w_resize_small'], "h_resize_small" => @$_POST['h_resize_small'], "watermark" => @$watermark, "w_resize_big" => @$_POST['w_resize_big'], "h_resize_big" => @$_POST['h_resize_big'], "filter" => @$_POST['filter'], "search" => @$_POST['search'], "translit" => @$_POST['translit'], "head" => @$_POST['section']);
                        
                        //$ob->update("i_option",$field,$_GET['id'],$page);
                        //////////////////////////////////////////////////////////////////////////function option
                        $i    = 0;
                        $str  = '';
                        $str2 = '';
                        foreach ($field as $k => $v) {
                            if (($i != count($field)) and ($i != 0)) {@$str .= ",";}
                            @$str .= '`' . $k . '`=\'' . $v . '\'';
                            $i++;
                        }
                        $i = 0;
                        foreach ($field2 as $k => $v) {
                            if (($i != count($field2)) and ($i != 0)) {@$str2 .= ",";}
                            @$str2 .= '`' . $k . '`=\'' . $v . '\'';
                            $i++;
                        }
                        $update = $mysql->query("update `i_option` set " . $str . " where `id`=" . $_GET['id'] . "");
                        //$update=$mysql->query("update `i_option` set ".$str2." where `name_en`='".$res['name_en']."'");
                        //echo "update ".$table." set ".$str." where id='".$this->pr($id)."'<br>";
                        if (!$update) {
                            $ob->alert("Record has not update!\\nREASON:" . $mysql->error);
                        } else {
                            if ($page != "") {
                                echo '<section id="content" class="table-layout">
                         <div class="row flex-column-reverse-before-md" style="padding-bottom:0px;">
                         <div class="col-sm-12"><div class="alert alert-dark light alert-dismissable">
                         <button type="button" data-dismiss="alert" aria-hidden="true" class="close"></button><i class="fa fa-cog pr10 hidden"></i><strong>Сохранение!</strong> Пожалуйста подождите</a>
                         </div></div></div></section>';
                                $ob->go($page);
                            }
                        }
                        //////////////////////////////////////////////////////////////////////////////
                    } else { $ob->alert("Ошибка создания поля!\\nREASON:" . $mysql->error);}
                }
                
            } else {
                $ob->alert("Необходимо выбрать тип файла!");
            }
        } else {
            $ob->alert("Извените, но данное поле уже существует!");
        }
        
    }
}
$field = array("id_sort" => "ID сорт.", "name_ru" => "Наимен. поля", "name_en" => "Наимен. поля(eng)", "type_field" => "Тип файла", "head" => "Секция");
?>
<script>
    function select_type(id)
    {
        var mas=new Array('i0','i1','i2','i5','i6','i7','i8','i9','i10','i11','i12','i13');

        for(var i=0;i<mas.length;i++)
        {
            var div = document.getElementById(mas[i]);
            if(mas[i]==id)
            {
                div.style.display='block';
            }else
            {
                div.style.display='none';
            }
        }

        if(id == 'i10' || id == 'i11' || id == 'i12' || id == 'i13'){
            document.getElementById('required_fields').disabled = 'disabled'
        }else{
            document.getElementById('required_fields').disabled = ''
        }

    }

    function pr(hidden_val)
    {
        var msg;
        var fr;
        msg='';
        fr=document.form;
        if (fr.name_ru.value==''){msg=msg+'* Наименование поля \n';}
        if (fr.name_en.value==''){msg=msg+'* Наименование поля (eng) \n';}

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
<script type="text/javascript">
    function del(i)
    {
        if (confirm("Вы действительно хотите удалить выбранный элемент?"))
        {document.location.href='<?=$ob->gets_go($_GET, "delete", "true")?>&id_field='+i;}
    }
</script>
<?


$sql = "select name from i_modules where folders='$nameModule'";
$resN = $mysql->get($sql, 1);
?>

<header id="topbar" class="alt" style="padding-bottom: 0px;">
    <div class="topbar-left pull-left">
        <ol class="breadcrumb">
            <li class="crumb-icon"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="crumb-active"><a href="index.php"><?=@$resN["name"]?></a></li>
            <?
            admin_print_dir(@$_GET["module"], @$_GET["sub_module"]);
            ?>
            <li class="crumb-active">Настройка</li>
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
                                <li <?=$_GET["module"]==$nameRazdel || $_GET["module"]==''?'class="active"':''?>>
                                    <a href="#tab2_1" data-toggle="tab" aria-expanded="true">Поля разделов</a>
                                </li>
                                <li <?=$_GET["module"]==$nameElement ?'class="active"':''?>>
                                    <a href="#tab2_2" data-toggle="tab" <?=$_GET["module"]==$nameElement?'aria-expanded="true"':'aria-expanded="false"'?>>Поля элементов</a>
                                </li>
                                <li <?=$_GET["module"]==$nameRazdel.'_lang' ?'class="active"':''?>>
                                    <a href="#tab2_3" data-toggle="tab" <?=$_GET["module"]==$nameRazdel.'_lang'?'aria-expanded="true"':'aria-expanded="false"'?>>Языковые переменные</a>
                                </li>
                                <li <?=$_GET["module"]==$nameRazdel.'_config' ?'class="active"':''?>>
                                    <a href="#tab2_4" data-toggle="tab" <?=$_GET["module"]==$nameRazdel.'_config'?'aria-expanded="true"':'aria-expanded="false"'?>>Настройки модуля</a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content pn br-n">
                                <div id="tab2_1" class="tab-pane <?=$_GET["module"]==$nameRazdel || $_GET["module"]==''?'active':''?>">
                                    <?
                                    $_GET["module"] = $nameRazdel;
                                    $select = $ob->select("i_option", array("id" => @$_GET['id'], "category" => @$_GET['module']), "");
                                    $res    = $select->fetch_array();
                                    ?>
                                    <form id="form" name="form" method="post" action="<?=$ob->gets_go($_GET, "", "")?>" enctype="multipart/form-data">
                                        <input type="hidden" name="module" id="module" value="<?=$nameRazdel?>" />
                                        <input type="hidden" name="reffer" id="reffer" value="<?
                                        if (!@$_POST['reffer']) {
                                            echo @$_SERVER['HTTP_REFERER'];
                                        } else {
                                            echo @$_POST['reffer'];
                                        }
                                        ?>" />
                                        <div class="panel  top mb35">

                                            <div class="panel-body bg-white">
                                                <div class="admin-form">
                                                    <div class="section row mb10">
                                                        <label for="business-name" class="field-label col-md-3 text-right">Обязательное поле:</label>
                                                        <div class="col-md-1">
                                                            <label for="business-name" class="field " style="top: 11px;">
                                                                <input name="required_fields" type="checkbox" id="required_fields" value="1" <?if (@$res['required_fields'] == 1) {echo "checked";}
                                                                ?>>
                                                            </label>
                                                        </div>
                                                        <label for="business-name" class="field-label col-md-2 text-right">Выделить поле:</label>
                                                        <div class="col-md-1">
                                                            <label for="business-name" class="field " style="top: 11px;">
                                                                <input name="select_fields" type="checkbox" id="select_fields" value="1" <?if (@$res['select_fields'] == 1) {echo "checked";}
                                                                ?>>
                                                            </label>
                                                        </div>
                                                        <label for="business-name" class="field-label col-md-2 text-right">Добавить в фильтр:</label>
                                                        <div class="col-md-1">
                                                            <label for="business-name" class="field " style="top: 11px;">
                                                                <input name="filter" type="checkbox" id="select_fields" value="1" <?if (@$res['filter'] == 1) {echo "checked";}
                                                                ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Сортировка:</label>
                                                        <div class="col-md-8">
                                                            <label for="business-phone" class="field ">
                                                                <input name="id_sort" class="gui-input" type="text" id="id_sort" value="<?=@$res['id_sort']?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10">
                                                        <label for="business-phone" class="field-label col-md-3 text-right"><span class="small_red_text">*</span> Наименование поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="name_ru" type="text" class="gui-input" id="name_ru" value="<?=@$res['name_ru']?>" size="35" />
                                                            </label>
                                                        </div>

                                                        <label for="business-phone" class="field-label col-md-2 text-right">Транслит:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="name_en" class="gui-input" type="text" id="name_en" value="<?=@$res['name_en']?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10">
                                                        <label for="business-phone" class="field-label col-md-3 text-right"><span class="small_red_text">*</span> Тип поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field select">
                                                                <select name="type_field"  id="type_field" onChange="select_type(this.value)">
                                                                    <?
                                                                    $ar = array("i0" => "выберите из списка", "i1" => "текстовое (text)", "i2" => "текстовое (дата)", "i5" => "текстовое цена (числовое)", "i6" => "много текста (textarea)", "i7" => "выбор (checkbox)", "i8" => "список (select)", "i9" => "список (radio button)", "i10" => "html редактор", "i11" => "рисунок (file)", "i12" => "файл (file)", "i13" => "справочник");
                                                                    foreach ($ar as $k => $v) {
                                                                        if (@$res['type_field'] == $k) {$sel = 'selected';} else { $sel = '';}
                                                                        echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <i class="arrow double"></i>
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Секция:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field select">
                                                                <select name="section"  id="section">
                                                                    <?
                                                                    $ar1 = array("0" => "Основная инфомация", "1" => "Meta теги", "2" => "Изображения", "3" => "Дополнительные поля");
                                                                    foreach ($ar1 as $k => $v) {
                                                                        if (@$res['head'] == $k) {$sel = 'selected';} else { $sel = '';}
                                                                        echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <i class="arrow double"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i0" style="display:none">
                                                    </div>
                                                    <div class="section row mb10" id="i1" style="display:<?=(@$res['type_field'] == "i1") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i1_width" class="form-control" type="text" id="i1_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i2" style="display:<?=(@$res['type_field'] == "i2") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i2_width" class="form-control" type="text" id="i2_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Формат даты:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i2_format_date" class="form-control" type="text" id="i2_format_date" value="Y-m-d H:i:s" size="35" disabled="disabled" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i3" style="display:<?=(@$res['type_field'] == "i3") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i3_width" class="form-control" type="text" id="i3_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i4" style="display:<?=(@$res['type_field'] == "i4") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i4_width" class="form-control" type="text" id="i4_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i5" style="display:<?=(@$res['type_field'] == "i5") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i5_width" class="form-control" type="text" id="i5_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i6" style="display:<?=(@$res['type_field'] == "i6") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i6_width" class="form-control" type="text" id="i6_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="45" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Высота:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i6_height" class="form-control" type="text" id="i6_height" value="<?if (@$_GET['id']) {echo $res['height'] ? $res['height'] : "5";} else {echo "5";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i7" style="display:<?=(@$res['type_field'] == "i7") ? "block": "none" ?>">
                                                    </div>
                                                    <div class="section row mb10" id="i8" style="display:<?=(@$res['type_field'] == "i8") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i8_width" class="form-control" type="text" id="i8_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "100";} else {echo "100";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Элементы:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                        <textarea name="i8_select_elements" class="form-control" id="i8_select_elements" cols="45" rows="5"><?
                                            if (@$_GET['id']) {
                                                echo $res['select_elements'];
                                            } else {
                                                echo "";
                                            }
                                            ?></textarea><div style="font-size: 12px; margin-top: 10px;">С новой строки</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i9" style="display:<?=(@$res['type_field'] == "i9") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Элементы:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                        <textarea name="i9_select_elements" class="form-control" id="i9_select_elements" cols="45" rows="5"><?
                                            if (@$_GET['id']) {
                                                echo $res['select_elements'];
                                            } else {
                                                echo "";
                                            }
                                            ?></textarea><div style="font-size: 12px; margin-top: 10px;">С новой строки</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i10" style="display:<?=(@$res['type_field'] == "i10") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i10_width" type="text" class="form-control" id="i10_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "700";} else {echo "700";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Высота:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i10_height" type="text" class="form-control" id="i10_height" value="<?if (@$_GET['id']) {echo $res['height'] ? $res['height'] : "400";} else {echo "400";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i11" style="display:<?=(@$res['type_field'] == "i11") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Максимальный размер:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i11_size_file" class="form-control" type="text" id="i11_size_file" value="<?if (@$_GET['id']) {echo $res['size_file'] ? $res['size_file'] : "3000000";} else {echo "3000000";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Форматы:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i11_format_file" class="form-control"   type="text" id="i11_format_file" value="<?if (@$_GET['id']) {echo $res['format_file'] ? $res['format_file'] : "jpg|jpeg|gif|png";} else {echo "jpg|jpeg|gif|png";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <div class="col-md-1"></div><br style="clear: left">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Тип обработки:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <select class="form-control" name="type_resize" >
                                                                    <option value="auto" <?=@$res["type_resize"] == 'auto' ? 'selected' : ''?>>Авто</option>
                                                                    <option value="landscape" <?=@$res["type_resize"] == 'landscape' ? 'selected' : ''?>>По ширине</option>
                                                                    <option value="portrait" <?=@$res["type_resize"] == 'portrait' ? 'selected' : ''?>>По высоте</option>
                                                                    <option value="exact" <?=@$res["type_resize"] == 'exact' ? 'selected' : ''?>>Точная</option>
                                                                    <option value="crop" <?=@$res["type_resize"] == 'crop' ? 'selected' : ''?>>Обрезка</option>
                                                                </select>
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Водяной знак:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <?
                                                                if (@$res['watermark'] != "") {
                                                                    echo '<table width="100%"  border="0" cellspacing="0" cellpadding="2" align="left">
                                            <tr>
                                            <td align="left" class="left_menu"><img src="/upload/images/watermark/' . $res["watermark"] . '"></td>
                                            </tr>
                                            <tr>
                                            <td align="left"><table width="20%"  border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                            <td width="39%" align="center"><input type="checkbox" name="delete' . $res['id'] . '" value="1" onClick="SectionClick(\'delete_forms' . $res['id'] . '\')"></td>
                                            <td width="61%" align="left" class="small_text">удалить</td>
                                            </tr>
                                            </table>
                                            </td>
                                            </tr><tr><td class="small_text"><DIV id="delete_forms' . $res['id'] . '" style="DISPLAY:none"><input name="watermark" class="form-control" type="file" /></div></td></tr>
                                            </table>';
                                                                } else {
                                                                    echo '<input class="form-control" name="watermark" type="file" />';
                                                                }
                                                                ?>
                                                            </label>
                                                        </div><br style="clear: left">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Размеры миниатюры:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input type="text" name="w_resize_small" class="form-control" size="10" placeholder="Ширина" value="<?=@$res["w_resize_small"]?>">
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right"></label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input type="text" name="h_resize_small" size="10" class="form-control" placeholder="Высота" value="<?=@$res["h_resize_small"]?>">
                                                            </label>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <br style="clear: left">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Основное изображение:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input type="text" name="w_resize_big" size="10" class="form-control" placeholder="Ширина" value="<?=@$res["w_resize_big"]?>">
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right"></label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input type="text" name="h_resize_big" size="10" class="form-control" placeholder="Высота" value="<?=@$res["h_resize_big"]?>">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="i12" style="display:<?=(@$res['type_field'] == "i12") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Максимальный размер:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i12_size_file" type="text" class="form-control" id="i12_size_file" value="<?if (@$_GET['id']) {echo $res['size_file'] ? $res['size_file'] : "10000000";} else {echo "10000000";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Форматы:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i12_format_file" type="text" class="form-control" id="i12_format_file" value="<?if (@$_GET['id']) {echo $res['format_file'] ? $res['format_file'] : "doc|xls";} else {echo "doc|xls|docx|xlsx|pdf";} ?>" size="35" />
                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div class="section row mb10" id="i13" style="display:<?=(@$res['type_field'] == "i13") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Справочник:</label>
                                                        <div class="col-md-8">
                                                            <label for="business-phone" class="field ">
                                                                <select class="form-control" name="i13_size_file" id="i13_size_file">
                                                                    <?
                                                                    $s = $mysql->query("select * from i_block where name!='' and id_section=0 and (version='all' or version='".@$_SESSION["version"]."') order by id_sort asc");
                                                                    if ($s && $s->num_rows>0){
                                                                        while($r = $s->fetch_array()){
                                                                            echo '<option value="'.$r["id"].'" '.(@$res['size_file']==$r["id"] ? 'selected' : "").'>'.$r["name"].'</option>';
                                                                            $ss = $mysql->query("select * from i_block where name!='' and id_section=".$r["id"]." and (version='all' or version='".@$_SESSION["version"]."') order by id_sort asc");
                                                                            if ($ss && $ss->num_rows>0){
                                                                                while($rr = $ss->fetch_array()){
                                                                                    echo '<option value="'.$rr["id"].'" '.($res['size_file']==$rr["id"] ? 'selected' : "").'>--- '.$rr["name"].'</option>';
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </label>
                                                        </div>
                                                        <br style="clear: left">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Значение:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i13_format_file" type="text" class="form-control" id="i13_format_file" value="<?if (@$_GET['id']) {echo $res['format_file'] ? $res['format_file'] : "";} else {echo "id";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Название</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i13_select_elements" type="text" class="form-control" id="i13_select_elements" value="<?if (@$_GET['id']) {echo $res['select_elements'] ? $res['select_elements'] : "";} else {echo "name";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div >
                                            <input type="button" class="btn btn-system" name="button" id="button" value="Сохранить" onClick="document.getElementById('i2_format_date').disabled = false; pr('save')" />
                                            <input type="button" class="btn btn-success" name="button2" id="button2" value="Применить"  onclick="document.getElementById('i2_format_date').disabled = false; pr('apply')" />
                                            <input type="reset"  class="btn btn-warning"  name="button3" id="button3" value="Отменить"  />
                                            <input type="hidden" name="hidden" id="hidden" />
                                        </div>
                                    </form>
                                    <form id="templates" name="templates" method="post" action="<?=$ob->gets_go($_GET, "", "")?>" enctype="multipart/form-data" class="small_text" style="float:right; white-space: nowrap; margin-top: -45px;" >
                                        <input type="hidden" value="<?=$nameRazdel?>" name="module">
                                        <input type="hidden" value="<?=@$_GET["id_section"]?>" name="ids">
                                        <select name="templates" class="form-control" id="templates" style="display: inline-block; width: auto;">
                                            <option value="">Выберите шаблон</option>
                                            <?
                                            $s = $mysql->query("select * from i_templates order by sort_templates asc");
                                            if ($s) {
                                                while ($r = $s->fetch_array()) {
                                                    echo '<option value="' . $r["id"] . '">' . $r["name_templates"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                        <input type="submit" class="btn btn-dark" name="app_templates" id="app_templates" value="Применить шаблон" style="    vertical-align: middle;  margin-top: -5px;  padding: 11px 12px 12px;">
                                    </form>
                                    <br><br>
                                    <table width="100%" class="table table-responsive table-hover table-bordered" style="color: #000; ">
                                        <tr class="dark">
                                            <td width="45" height="35" align="center" class="check_table_title" >
                                                Действия
                                            </td>
                                            <?
                                            foreach ($field as $k => $v) {
                                                if (@$_GET['order'] == $k or @$_GET['order'] == $k . " DESC") {
                                                    $style = 'top_table_title_back';
                                                    if ($_GET['order'] != $k . " DESC") {$k .= " DESC";}
                                                } else { $style = 'top_table_title';}
                                                echo '<td  class="' . $style . '" onmouseover="this.className=\'top_table_title_back\';" onmouseout="this.className=\'' . $style . '\';" onclick="document.location.href=\'option.php' . $ob->gets_go($_GET, "order", $k) . '\'" title="сортировка по полю ' . $v . '">' . $v . '</td>
                ';
                                            }
                                            ?>
                                        </tr>
                                        <?
                                        
                                        $select = $ob->select("i_option", array("category" => $nameRazdel, "category_id" => @$_GET['id_section']), "head asc, id_sort asc");
                                        while ($res = $select->fetch_array()) {
                                            $li_style = 'style="height:23px;background-image: url(/admin/modules/theme/default/images/menu_03.gif);
            background-repeat: repeat-y;
            background-position: left top; text-align:left; padding-left: 23px;"';
                                            
                                            echo '<tr onMouseOver="this.bgColor=\'#f3f2da\'" onMouseOut="this.bgColor=\'#fafaf0\'" >

            <td align="center"><ul id="actions_menu' . $res['id'] . '" class="MenuBarHorizontal">
            <li ><span class="glyphicon glyphicon-align-justify" style="font-size:20px; color:#333;"></span>
            <ul>
            <li ' . $li_style . '><a href="' . $ob->gets_go($_GET, "id", $res['id']) . '">редактировать</a></li>
            <li ' . $li_style . '><a href="javascript:del(\'' . $res['id'] . '\')">удалить</a></li>

            </ul>
            </li>

            </ul><script type="text/javascript">
            <!--
            var MenuBar1 = new Spry.Widget.MenuBar("actions_menu' . $res['id'] . '", {imgDown:"", imgRight:""});
//-->
            </script></td>';
                                            foreach ($field as $k => $v) {
                                                if ($k != "type_field" && $k != "head") {
                                                    echo '<td class="table_value">' . $res['' . $k . ''] . '&nbsp;</td>';
                                                } else if ($k == "head") {
                                                    echo '<td class="table_value">' . $ar1['' . $res['' . $k . ''] . ''] . '&nbsp;</td>';
                                                } else {
                                                    echo '<td class="table_value">' . $ar['' . $res['' . $k . ''] . ''] . '&nbsp;</td>';
                                                }
                                            }
                                            echo '</tr>';
                                        }
                                        ?>
                                    </table>
                                </div>
                                <div id="tab2_2" class="tab-pane ">
                                    <?
                                    $_GET["module"] = $nameElement;
                                    $select = $ob->select("i_option", array("id" => @$_GET['id'], "category" => @$_GET['module']), "");
                                    $res    = $select->fetch_array();
                                    ?>
                                    <script>
                                        function selecte_type(id)
                                        {
                                            var mas=new Array('ei0','ei1','ei2','ei5','ei6','ei7','ei8','ei9','ei10','ei11','ei12','ei13');

                                            for(var i=0;i<mas.length;i++)
                                            {
                                                var div = document.getElementById(mas[i]);
                                                if(mas[i]=='e'+id)
                                                {
                                                    div.style.display='block';
                                                }else
                                                {
                                                    div.style.display='none';
                                                }
                                            }

                                            if(id == 'i10' || id == 'i11' || id == 'i12' || id == 'i13'){
                                                document.getElementById('erequired_fields').disabled = 'disabled'
                                            }else{
                                                document.getElementById('erequired_fields').disabled = ''
                                            }

                                        }

                                        function pre(hidden_val)
                                        {
                                            var msg;
                                            var fr;
                                            msg='';
                                            fr=document.eform;
                                            if (fr.ename_ru.value==''){msg=msg+'* Наименование поля \n';}
                                            if (fr.ename_en.value==''){msg=msg+'* Наименование поля (eng) \n';}

                                            if(msg=='')
                                            {
                                                fr.ehidden.value=hidden_val;
                                                fr.submit();
                                            }
                                            else
                                            {
                                                msg='Необходимо заполнить обязательные поля:\n'+msg;
                                                alert(msg);
                                            }
                                        }
                                    </script>
                                    <script type="text/javascript">
                                        function dele(i)
                                        {
                                            if (confirm("Вы действительно хотите удалить выбранный элемент?"))
                                            {document.location.href='<?=$ob->gets_go($_GET, "delete", "true")?>&id_field='+i;}
                                        }
                                    </script>
                                    <form id="eform" name="eform" method="post" action="<?=$ob->gets_go($_GET, "", "")?>" enctype="multipart/form-data">
                                        <input type="hidden" name="module" id="emodule" value="<?=$nameElement?>" />
                                        <input type="hidden" name="reffer" id="ereffer" value="<?
                                        if (!@$_POST['reffer']) {
                                            echo @$_SERVER['HTTP_REFERER'];
                                        } else {
                                            echo @$_POST['reffer'];
                                        }
                                        ?>" />
                                        <div class="panel  top mb35">

                                            <div class="panel-body bg-white">
                                                <div class="admin-form">
                                                    <div class="section row mb10">
                                                        <label for="business-name" class="field-label col-md-3 text-right">Обязательное поле:</label>
                                                        <div class="col-md-1">
                                                            <label for="business-name" class="field " style="top: 11px;">
                                                                <input name="required_fields" type="checkbox" id="erequired_fields" value="1" <?if (@$res['required_fields'] == 1) {echo "checked";}
                                                                ?>>
                                                            </label>
                                                        </div>
                                                        <label for="business-name" class="field-label col-md-2 text-right">Выделить поле:</label>
                                                        <div class="col-md-1">
                                                            <label for="business-name" class="field " style="top: 11px;">
                                                                <input name="select_fields" type="checkbox" id="eselect_fields" value="1" <?if (@$res['select_fields'] == 1) {echo "checked";}
                                                                ?>>
                                                            </label>
                                                        </div>
                                                        <label for="business-name" class="field-label col-md-2 text-right">Добавить в фильтр:</label>
                                                        <div class="col-md-1">
                                                            <label for="business-name" class="field " style="top: 11px;">
                                                                <input name="filter" type="checkbox" id="eselect_fields" value="1" <?if (@$res['filter'] == 1) {echo "checked";}
                                                                ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Сортировка:</label>
                                                        <div class="col-md-8">
                                                            <label for="business-phone" class="field ">
                                                                <input name="id_sort" class="gui-input" type="text" id="eid_sort" value="<?=@$res['id_sort']?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10">
                                                        <label for="business-phone" class="field-label col-md-3 text-right"><span class="small_red_text">*</span> Наименование поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="name_ru" type="text" class="gui-input" id="ename_ru" value="<?=@$res['name_ru']?>" size="35" />
                                                            </label>
                                                        </div>

                                                        <label for="business-phone" class="field-label col-md-2 text-right">Транслит:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="name_en" class="gui-input" type="text" id="ename_en" value="<?=@$res['name_en']?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10">
                                                        <label for="business-phone" class="field-label col-md-3 text-right"><span class="small_red_text">*</span> Тип поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field select">
                                                                <select name="type_field"  id="etype_field" onChange="selecte_type(this.value)">
                                                                    <?
                                                                    $ar = array("i0" => "выберите из списка", "i1" => "текстовое (text)", "i2" => "текстовое (дата)", "i5" => "текстовое цена (числовое)", "i6" => "много текста (textarea)", "i7" => "выбор (checkbox)", "i8" => "список (select)", "i9" => "список (radio button)", "i10" => "html редактор", "i11" => "рисунок (file)", "i12" => "файл (file)", "i13" => "справочник");
                                                                    foreach ($ar as $k => $v) {
                                                                        if (@$res['type_field'] == $k) {$sel = 'selected';} else { $sel = '';}
                                                                        echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <i class="arrow double"></i>
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Секция:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field select">
                                                                <select name="section"  id="esection">
                                                                    <?
                                                                    $ar1 = array("0" => "Основная инфомация", "1" => "Meta теги", "2" => "Изображения", "3" => "Дополнительные поля");
                                                                    foreach ($ar1 as $k => $v) {
                                                                        if (@$res['head'] == $k) {$sel = 'selected';} else { $sel = '';}
                                                                        echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <i class="arrow double"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei0" style="display:none">
                                                    </div>
                                                    <div class="section row mb10" id="ei1" style="display:<?=(@$res['type_field'] == "i1") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i1_width" class="form-control" type="text" id="ei1_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei2" style="display:<?=(@$res['type_field'] == "i2") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i2_width" class="form-control" type="text" id="ei2_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Формат даты:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i2_format_date" class="form-control" type="text" id="ei2_format_date" value="Y-m-d H:i:s" size="35" disabled="disabled" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei3" style="display:<?=(@$res['type_field'] == "i3") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i3_width" class="form-control" type="text" id="ei3_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei4" style="display:<?=(@$res['type_field'] == "i4") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i4_width" class="form-control" type="text" id="ei4_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei5" style="display:<?=(@$res['type_field'] == "i5") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i5_width" class="form-control" type="text" id="ei5_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei6" style="display:<?=(@$res['type_field'] == "i6") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i6_width" class="form-control" type="text" id="ei6_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "30";} else {echo "30";} ?>" size="45" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Высота:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i6_height" class="form-control" type="text" id="ei6_height" value="<?if (@$_GET['id']) {echo $res['height'] ? $res['height'] : "5";} else {echo "5";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei7" style="display:<?=(@$res['type_field'] == "i7") ? "block": "none" ?>">
                                                    </div>
                                                    <div class="section row mb10" id="ei8" style="display:<?=(@$res['type_field'] == "i8") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина поля:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i8_width" class="form-control" type="text" id="ei8_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "100";} else {echo "100";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Элементы:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                <textarea name="i8_select_elements" class="form-control" id="ei8_select_elements" cols="45" rows="5"><?
                    if (@$_GET['id']) {
                        echo $res['select_elements'];
                    } else {
                        echo "";
                    }
                    ?></textarea><div style="font-size: 12px; margin-top: 10px;">С новой строки</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei9" style="display:<?=(@$res['type_field'] == "i9") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Элементы:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                <textarea name="i9_select_elements" class="form-control" id="ei9_select_elements" cols="45" rows="5"><?
                    if (@$_GET['id']) {
                        echo $res['select_elements'];
                    } else {
                        echo "";
                    }
                    ?></textarea><div style="font-size: 12px; margin-top: 10px;">С новой строки</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei10" style="display:<?=(@$res['type_field'] == "i10") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Длина:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i10_width" type="text" class="form-control" id="ei10_width" value="<?if (@$_GET['id']) {echo $res['width'] ? $res['width'] : "700";} else {echo "700";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Высота:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i10_height" type="text" class="form-control" id="ei10_height" value="<?if (@$_GET['id']) {echo $res['height'] ? $res['height'] : "400";} else {echo "400";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei11" style="display:<?=(@$res['type_field'] == "i11") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Максимальный размер:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i11_size_file" class="form-control" type="text" id="ei11_size_file" value="<?if (@$_GET['id']) {echo $res['size_file'] ? $res['size_file'] : "3000000";} else {echo "3000000";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Форматы:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i11_format_file" class="form-control"   type="text" id="ei11_format_file" value="<?if (@$_GET['id']) {echo $res['format_file'] ? $res['format_file'] : "jpg|jpeg|gif|png";} else {echo "jpg|jpeg|gif|png";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <div class="col-md-1"></div><br style="clear: left">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Тип обработки:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <select class="form-control" name="type_resize" >
                                                                    <option value="auto" <?=@$res["type_resize"] == 'auto' ? 'selected' : ''?>>Авто</option>
                                                                    <option value="landscape" <?=@$res["type_resize"] == 'landscape' ? 'selected' : ''?>>По ширине</option>
                                                                    <option value="portrait" <?=@$res["type_resize"] == 'portrait' ? 'selected' : ''?>>По высоте</option>
                                                                    <option value="exact" <?=@$res["type_resize"] == 'exact' ? 'selected' : ''?>>Точная</option>
                                                                    <option value="crop" <?=@$res["type_resize"] == 'crop' ? 'selected' : ''?>>Обрезка</option>
                                                                </select>
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Водяной знак:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <?
                                                                if (@$res['watermark'] != "") {
                                                                    echo '<table width="100%"  border="0" cellspacing="0" cellpadding="2" align="left">
                    <tr>
                    <td align="left" class="left_menu"><img src="/upload/images/watermark/' . $res["watermark"] . '"></td>
                    </tr>
                    <tr>
                    <td align="left"><table width="20%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                    <td width="39%" align="center"><input type="checkbox" name="delete' . $res['id'] . '" value="1" onClick="SectionClick(\'delete_forms' . $res['id'] . '\')"></td>
                    <td width="61%" align="left" class="small_text">удалить</td>
                    </tr>
                    </table>
                    </td>
                    </tr><tr><td class="small_text"><DIV id="edelete_forms' . $res['id'] . '" style="DISPLAY:none"><input name="watermark" class="form-control" type="file" /></div></td></tr>
                    </table>';
                                                                } else {
                                                                    echo '<input class="form-control" name="watermark" type="file" />';
                                                                }
                                                                ?>
                                                            </label>
                                                        </div><br style="clear: left">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Размеры миниатюры:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input type="text" name="w_resize_small" class="form-control" size="10" placeholder="Ширина" value="<?=@$res["w_resize_small"]?>">
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right"></label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input type="text" name="h_resize_small" size="10" class="form-control" placeholder="Высота" value="<?=@$res["h_resize_small"]?>">
                                                            </label>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <br style="clear: left">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Основное изображение:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input type="text" name="w_resize_big" size="10" class="form-control" placeholder="Ширина" value="<?=@$res["w_resize_big"]?>">
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right"></label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input type="text" name="h_resize_big" size="10" class="form-control" placeholder="Высота" value="<?=@$res["h_resize_big"]?>">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="section row mb10" id="ei12" style="display:<?=(@$res['type_field'] == "i12") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Максимальный размер:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i12_size_file" type="text" class="form-control" id="ei12_size_file" value="<?if (@$_GET['id']) {echo $res['size_file'] ? $res['size_file'] : "10000000";} else {echo "10000000";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Форматы:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i12_format_file" type="text" class="form-control" id="ei12_format_file" value="<?if (@$_GET['id']) {echo $res['format_file'] ? $res['format_file'] : "doc|xls";} else {echo "doc|xls|docx|xlsx|pdf";} ?>" size="35" />
                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div class="section row mb10" id="ei13" style="display:<?=(@$res['type_field'] == "i13") ? "block": "none" ?>">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Справочник:</label>
                                                        <div class="col-md-8">
                                                            <label for="business-phone" class="field ">
                                                                <select class="form-control" name="i13_size_file" id="ei13_size_file">
                                                                    <?
                                                                    $s = $mysql->query("select * from i_block where name!='' and id_section=0 and (version='all' or version='".@$_SESSION["version"]."') order by id_sort asc");
                                                                    if ($s && $s->num_rows>0){
                                                                        while($r = $s->fetch_array()){
                                                                            echo '<option value="'.$r["id"].'" '.(@$res['size_file']==$r["id"] ? 'selected' : "").'>'.$r["name"].'</option>';
                                                                            $ss = $mysql->query("select * from i_block where name!='' and id_section=".$r["id"]." and (version='all' or version='".@$_SESSION["version"]."') order by id_sort asc");
                                                                            if ($ss && $ss->num_rows>0){
                                                                                while($rr = $ss->fetch_array()){
                                                                                    echo '<option value="'.$rr["id"].'" '.($res['size_file']==$rr["id"] ? 'selected' : "").'>--- '.$rr["name"].'</option>';
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </label>
                                                        </div>
                                                        <br style="clear: left">
                                                        <label for="business-phone" class="field-label col-md-3 text-right">Значение:</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i13_format_file" type="text" class="form-control" id="ei13_format_file" value="<?if (@$_GET['id']) {echo $res['format_file'] ? $res['format_file'] : "";} else {echo "id";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                        <label for="business-phone" class="field-label col-md-2 text-right">Название</label>
                                                        <div class="col-md-3">
                                                            <label for="business-phone" class="field ">
                                                                <input name="i13_select_elements" type="text" class="form-control" id="ei13_select_elements" value="<?if (@$_GET['id']) {echo $res['select_elements'] ? $res['select_elements'] : "";} else {echo "name";} ?>" size="35" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div >
                                            <input type="button" class="btn btn-system" name="button" id="ebutton" value="Сохранить" onClick="document.getElementById('ei2_format_date').disabled = false; pre('save')" />
                                            <input type="button" class="btn btn-success" name="button2" id="ebutton2" value="Применить"  onclick="document.getElementById('ei2_format_date').disabled = false; pre('apply')" />
                                            <input type="reset"  class="btn btn-warning"  name="button3" id="ebutton3" value="Отменить"  />
                                            <input type="hidden" name="hidden" id="ehidden" />
                                        </div>
                                    </form>
                                    <form id="etemplates" name="templates" method="post" action="<?=$ob->gets_go($_GET, "", "")?>" enctype="multipart/form-data" class="small_text" style="float:right; white-space: nowrap; margin-top: -45px;" >
                                        <input type="hidden" value="<?=$nameElement?>" name="module">
                                        <input type="hidden" value="<?=@$_GET["id_section"]?>" name="ids">
                                        <select name="templates" class="form-control" id="etemplates" style="display: inline-block; width: auto;">
                                            <option value="">Выберите шаблон</option>
                                            <?
                                            $s = $mysql->query("select * from i_templates order by sort_templates asc");
                                            if ($s) {
                                                while ($r = $s->fetch_array()) {
                                                    echo '<option value="' . $r["id"] . '">' . $r["name_templates"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                        <input type="submit" class="btn btn-dark" name="app_templates" id="eapp_templates" value="Применить шаблон" style="    vertical-align: middle;  margin-top: -5px;  padding: 11px 12px 12px;">
                                    </form>
                                    <br><br>
                                    <table width="100%" class="table table-responsive table-hover table-bordered" style="color: #000; ">
                                        <tr class="dark">
                                            <td width="45" height="35" align="center" class="check_table_title" >
                                                Действия
                                            </td>
                                            <?
                                            foreach ($field as $k => $v) {
                                                if (@$_GET['order'] == $k or @$_GET['order'] == $k . " DESC") {
                                                    $style = 'top_table_title_back';
                                                    if ($_GET['order'] != $k . " DESC") {$k .= " DESC";}
                                                } else { $style = 'top_table_title';}
                                                echo '<td  class="' . $style . '" onmouseover="this.className=\'top_table_title_back\';" onmouseout="this.className=\'' . $style . '\';" onclick="document.location.href=\'option.php' . $ob->gets_go($_GET, "order", $k) . '\'" title="сортировка по полю ' . $v . '">' . $v . '</td>
        ';
                                            }
                                            ?>
                                        </tr>
                                        <?
                                        
                                        $select = $ob->select("i_option", array("category" => $nameElement, "category_id" => @$_GET['id_section']), "head asc, id_sort asc");
                                        while ($res = $select->fetch_array()) {
                                            $li_style = 'style="height:23px;background-image: url(/admin/modules/theme/default/images/menu_03.gif);
    background-repeat: repeat-y;
    background-position: left top; text-align:left; padding-left: 23px;"';
                                            
                                            echo '<tr onMouseOver="this.bgColor=\'#f3f2da\'" onMouseOut="this.bgColor=\'#fafaf0\'" >

    <td align="center"><ul id="eactions_menu' . $res['id'] . '" class="MenuBarHorizontal">
    <li ><span class="glyphicon glyphicon-align-justify" style="font-size:20px; color:#333;"></span>
    <ul>
    <li ' . $li_style . '><a href="' . $ob->gets_go($_GET, "id", $res['id']) . '">редактировать</a></li>
    <li ' . $li_style . '><a href="javascript:dele(\'' . $res['id'] . '\')">удалить</a></li>

    </ul>
    </li>

    </ul><script type="text/javascript">
    <!--
    var MenuBar1 = new Spry.Widget.MenuBar("eactions_menu' . $res['id'] . '", {imgDown:"", imgRight:""});
//-->
    </script></td>';
                                            foreach ($field as $k => $v) {
                                                if ($k != "type_field" && $k != "head") {
                                                    echo '<td class="table_value">' . $res['' . $k . ''] . '&nbsp;</td>';
                                                } else if ($k == "head") {
                                                    echo '<td class="table_value">' . $ar1['' . $res['' . $k . ''] . ''] . '&nbsp;</td>';
                                                } else {
                                                    echo '<td class="table_value">' . $ar['' . $res['' . $k . ''] . ''] . '&nbsp;</td>';
                                                }
                                            }
                                            echo '</tr>';
                                        }
                                        ?>
                                    </table>
                                </div>
                                <script type="text/javascript">
                                    function prl(hidden_val)
                                    {
                                        var msg;
                                        var fr;
                                        msg='';
                                        fr=document.formlang;

                                        fr.lhidden.value=hidden_val;
                                        fr.submit();

                                    }
                                </script>
                                <?
                                $_GET["module"] = $nameRazdel.'_lang';
                                ?>
                                <div id="tab2_3" class="tab-pane ">
                                    <form method="post" action="<?=$ob->gets_go($_GET, "", "")?>" name="formlang" id="formlang">
                                        <input type="hidden" name="module" id="lmodule" value="<?=$nameRazdel?>_lang" />
                                        <input type="hidden" name="reffer" id="lreffer" value="<?
                                        if (!@$_POST['reffer']) {
                                            echo @$_SERVER['HTTP_REFERER'];
                                        } else {
                                            echo @$_POST['reffer'];
                                        }
                                        ?>" />
                                        <div class="panel  top mb35">
                                            <div class="panel-body bg-white">
                                                <div class="admin-form">
                                                    <div class="add_more">
                                                        <div class="section row mb10 langrow">
                                                            <div class="col-md-4">
                                                                <input type="text" name="key[]" class="form-control" value="">
                                                            </div>
                                                            <div class="col-md-7">
                                                                <input type="text" name="value[]" class="form-control" value="">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <a href="javascript:;" class="text-primary"><span class="glyphicon glyphicon-plus-sign" style="font-size: 28px; margin-top: 8px;"></span></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?
                                                    $langFile = $_SERVER["DOCUMENT_ROOT"].'/modules/'.$nameModule.'/lang/'.$_SESSION["version"].'.ini';
                                                    $ini_array = parse_ini_file($langFile);
                                                    $i=1;
                                                    foreach ($ini_array as $key => $value) {
                                                        ?>
                                                        <div class="section row mb10 langrow<?=$i?>">
                                                            <div class="col-md-4">
                                                                <input type="text" name="key[]" class="form-control" value="<?=$key?>">
                                                            </div>
                                                            <div class="col-md-7">
                                                                <input type="text" name="value[]" class="form-control" value="<?=$value?>">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <a href="javascript:;" class="text-danger"><span class="glyphicon glyphicon-remove-circle" style="font-size: 28px; margin-top: 8px;"></span></a>
                                                            </div>
                                                        </div>
                                                        <?
                                                        $i++;
                                                    }
                                                    
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div >
                                            <input type="button" class="btn btn-system" name="button" id="lbutton" value="Сохранить" onClick=" prl('save')" />
                                            <input type="button" class="btn btn-success" name="button2" id="lbutton2" value="Применить"  onclick=" prl('apply')" />
                                            <input type="reset"  class="btn btn-warning"  name="button3" id="lbutton3" value="Отменить"  />
                                            <input type="hidden" name="lang" id="lhidden" />
                                        </div>
                                    </form>
                                </div>
                                <script type="text/javascript">
                                    function prf(hidden_val)
                                    {
                                        var msg;
                                        var fr;
                                        msg='';
                                        fr=document.formconfig;

                                        fr.fhidden.value=hidden_val;
                                        fr.submit();

                                    }
                                </script>
                                <?
                                $_GET["module"] = $nameRazdel.'_config';
                                ?>
                                <div id="tab2_4" class="tab-pane ">
                                    <form method="post" action="<?=$ob->gets_go($_GET, "", "")?>" name="formconfig" id="formconfig">
                                        <input type="hidden" name="module" id="fhidden" value="<?=$nameRazdel?>_config" />
                                        <input type="hidden" name="reffer" id="freffer" value="<?
                                        if (!@$_POST['reffer']) {
                                            echo @$_SERVER['HTTP_REFERER'];
                                        } else {
                                            echo @$_POST['reffer'];
                                        }
                                        ?>" />
                                        <div class="panel  top mb35">
                                            <div class="panel-body bg-white">
                                                <div class="admin-form">
                                                    <div class="add_moref">
                                                        <div class="section row mb10 configrow">
                                                            <div class="col-md-4">
                                                                <input type="text" name="key[]" class="form-control" value="">
                                                            </div>
                                                            <div class="col-md-7">
                                                                <input type="text" name="value[]" class="form-control" value="">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <a href="javascript:;" class="text-primary"><span class="glyphicon glyphicon-plus-sign" style="font-size: 28px; margin-top: 8px;"></span></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?
                                                    $configFile = $_SERVER["DOCUMENT_ROOT"].'/modules/'.$nameModule.'/config/config.ini';
                                                    $ini_array = parse_ini_file($configFile);
                                                    $i=1;
                                                    foreach ($ini_array as $key => $value) {
                                                        ?>
                                                        <div class="section row mb10 configrow<?=$i?>">
                                                            <div class="col-md-4">
                                                                <input type="text" name="key[]" class="form-control" value="<?=$key?>">
                                                            </div>
                                                            <div class="col-md-7">
                                                                <input type="text" name="value[]" class="form-control" value="<?=$value?>">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <a href="javascript:;" class="text-danger"><span class="glyphicon glyphicon-remove-circle" style="font-size: 28px; margin-top: 8px;"></span></a>
                                                            </div>
                                                        </div>
                                                        <?
                                                        $i++;
                                                    }
                                                    
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div >
                                            <input type="button" class="btn btn-system" name="button" id="fbutton" value="Сохранить" onClick=" prf('save')" />
                                            <input type="button" class="btn btn-success" name="button2" id="fbutton2" value="Применить"  onclick=" prf('apply')" />
                                            <input type="reset"  class="btn btn-warning"  name="button3" id="fbutton3" value="Отменить"  />
                                            <input type="hidden" name="config" id="fhidden" value="1" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <?require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/footer.php"?>
