<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php")?>
<?
$nameRazdel = 'logs';
$nameElement = 'logs';
$nameModule = 'logs';


//началась выборка
$field=array("timestamp_x"=>"Дата/Время","id_user"=>"Пользователь","table"=>"Раздел","action"=>"Действие","name"=>"Название","idd"=>"URL");
$where='';
foreach($field as $k=>$v)
{
	if(@$_POST[''.$k.''])
	{
	$where.="AND ".$k." LIKE '%".$ob->pr($_POST[''.$k.''])."%'";
	}
}


if(!@$_GET['order']){$ord="id DESC";}else{$ord=$ob->pr($_GET['order']);}
if(!@$_GET['start']){$start=0;}else{$start=$ob->pr($_GET['start']);} 
if(!@$_GET['number']){$number=10;}else{$number=$ob->pr($_GET['number']);}

$select=$mysql->query("select * from i_logs where id<>0 ".$where." ORDER BY ".$ord.",'puttime' DESC LIMIT ".$start.",".$number."");
$select_all=$mysql->query("select * from  i_logs where id<>0 ".$where."");
?>
<?
$sql = "select name from i_modules where folders='$nameModule'  limit 1";
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

                         <!--<a href="<?=$ob->gets_go($_GET, "excel", "true")?>" target="_blank" class="btn btn-system btn-sm" style="margin-right: 0px;">
                           <span class="glyphicon glyphicon-open"></span> Export
                         </a>-->

                        </div>
                        <div class="widget-menu pull-right">
                            
                          
                           

                           Показывать по
                           <select name="jumpMenu" id="jumpMenu" class="form-control" style="display: inline-block; width: auto;" onchange="MM_jumpMenu('parent',this,0)">
                            <?
                            $elements = array(10, 20, 40, 80, 100, 1000);
                            foreach ($elements as $k => $v) {
                                if ($v == $_SESSION["number"]) {$sel = 'selected';} else { $sel = '';}
                                echo '<option value="' . $ob->gets_go($_GET, "number", $v) . '" ' . $sel . '>' . $v . '</option>';
                            }
                            ?>
                        </select>
                        
                        </select>
                        <input type="button" class="btn btn-dark btn-sm" name="send" id="send" value="Отправить" onclick="del_pr()" />
                    </div>
                </div>
                <div class="panel-body">

                    <table width="100%" class="table table-responsive table-hover table-bordered" style="color: #000; ">
                        <thead>
                            <tr class="dark">
                                
                                <?
                                foreach ($field as $k => $v) {
                                    if (@$_GET['order'] == $k or @$_GET['order'] == $k . " DESC") {
                                        $style = 'top_table_title_back';
                                        if ($_GET['order'] != $k . " DESC") {$k .= " DESC";}
                                    } else { $style = 'top_table_title';}
                                    if ($k == "#") {$width = 'width="20%"';} else { $width = '';}
                                    echo '<td  class="' . $style . '" ' . $width . ' onmouseover="this.className=\'top_table_title_back\';"
                                    onmouseout="this.className=\'' . $style . '\';"
                                    onclick="document.location.href=\'index.php' . $ob->gets_go($_GET, "order", $k) . '\'"
                                    title="сортировка по полю ' . $v . '">' . $v . '</td>';
                                }
                                ?>
                            </tr>
                        </thead>


          <?
		  $table_name=array("i_badlist"=>"Обработка адресов","i_block"=>"Информ. блоки","i_block_elements"=>"Элементы блоков","i_lang"=>"Языковые версии","i_menu"=>"Элементы меню","i_option"=>"Настройки полей","i_template"=>"Шаблоны сайтов","i_user"=>"Пользователи",
		  
		  
		  "cat"=>"Категории каталога",
		  "cat_elements"=>"Элементы каталога",
		  "comments"=>"Комментарии",
		  "gallery"=>"Категории фотогалереи",
		  "gallery_elements"=>"Элементы фотогалереи",
		  "guest"=>"Отзывы",
		  "menu"=>"Меню",
      "user"=>"Пользователи",
      "user_group"=>"Группы пользователей",
		  "news"=>"Новостные блоки",
		  "news_elements"=>"Элменты новостных блоков",
      "slider"=>"Новостные блоки",
      "slider_elements"=>"Элменты новостных блоков",
		  "page"=>"Текстовые страницы",
		  "quest"=>"Вопрос ответ",
		  "video"=>"Категории видеогалереи",
		  "video_elements"=>"Элементы видеогалереи",
		  
		  
		  );
		$action=array("delete"=>"удаление","insert"=>"добавление","add"=>"добавление","update"=>"обновление");
      while($res=$select->fetch_array())
	  {
	  $log_us=$ob->select("i_user",array("id"=>$res['id_user']),"");
	  $log_usres=$log_us->fetch_array();
	  
	  $li_style='style="height:23px;background-image: url(/admin/modules/theme/default/images/menu_03.gif);
	background-repeat: repeat-y;
	background-position: left top; text-align:left;	padding-left: 23px;"';
	
	  echo '<tr onMouseOver="this.bgColor=\'#f3f2da\'" onMouseOut="this.bgColor=\'#fafaf0\'">
		<td class="table_value"  height="25">'.$res['timestamp_x'].'&nbsp;</td>
        <td class="table_value">'.$log_usres['last_name'].'&nbsp;'.$log_usres['name'].'</td>
        <td class="table_value">'.@$table_name[$res['table']].'&nbsp;</td>
        <td class="table_value">'.@$action[$res['action']].'&nbsp;</td>
		    <td class="table_value">'.$res['name'].'&nbsp;</td>
		    <td class="table_value">'.$res['idd'].'&nbsp;</td>
      </tr>';
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
                $por   = ceil($count / $_SESSION["number"]);
                $link  = 0;
                $right = 0;

                for ($i = 1; $i <= $por; $i++) {
                    if ($_SESSION["start"] <= $link) {
                        $right++;
                        if (($right == 1) and $_SESSION["start"] > 0) {
                            if (($i - 5) > 0) {$minus = $i - 5;} else { $minus = 0;}
                            $link_main = $link - ($_SESSION["number"] * 5);
                            if ($link_main < 0) {$link_main = 0;}
                            echo '<a href="' . $ob->gets_go($_GET, "start", $link_main) . '" class="small_text">
                            [' . $minus . ".." . ($i - 1) . ']
                            </a> &nbsp;';
                        }

                        if ($right <= 5) {
                            if ($_SESSION["start"] == $link) {$style = 'style="font-weight:bold"';} else { $style = '';}
                            echo '<a href="' . $ob->gets_go($_GET, "start", $link) . '" class="small_text" ' . $style . '>' . $i . '</a>&nbsp;';
                        }
                        if (($right == 5) and (($link + $_SESSION["number"]) < $count)) {
                            echo '<a href="' . $ob->gets_go($_GET, "start", ($link + $_SESSION["number"])) . '" class="small_text" >[' . ($i + 1) . ".." . ($i + 5) . ']</a>';
                        }
                    }
                    $link = $_SESSION["number"] + $link;
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

<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/footer.php")?>
