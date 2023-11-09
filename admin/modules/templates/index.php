<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php")?>

<?
$_GET["id_section"]=0;
if(@$_POST['actions'])
{
	if(count(@$_POST['select_ch'])>0)
	{
		foreach($_POST['select_ch'] as $k=>$v)
		{
			if($_POST['actions']=="delete")
			{
			$ob->delete_tree($v,"templates");
			}
		}
	}else{$ob->alert("Необходимо выбрать элементы таблицы!");}
}


if(@$_GET['id'] AND isset($_GET['active']))
{
	$ob->update("i_templates",array("active"=>$_GET['active']),$_GET['id'],"");
}


//удаление
if(@$_GET['delete']=="true" AND $_GET['id'])
{
	$ob->delete_tree($_GET['id'],"templates");
}


//началась выборка
$field=array();
$field['#']="Полей";

$select=$ob->search_option("templates","",@$_GET['id_section'],array("select_fields"=>1));

$m=0;
while($res=$select->fetch_array())
{
	if($m==0){$hide_field_name=$res['name_en']; $m=1;}
	$field[''.$res['name_en'].'']=$res['name_ru'];
}

$where='';
foreach($field as $k=>$v)
{
	if(@$_POST[''.$k.''])
	{
		$where.="AND ".$k." LIKE '%".$ob->pr($_POST[''.$k.''])."%'";
	}
}

if(@$_GET['id_section'])
{
	$where.="AND id_section='".$ob->pr($_GET['id_section'])."'";
}else{
	$where.="AND id_section='0'";
}



if (!isset($_GET["order"])){
	if ($_SESSION["order"]=='')
	$_SESSION["order"]="id asc";
}else{
	$_SESSION["order"]=$ob->pr($_GET['order']);
}
if ($_SESSION["order"]=='') $_SESSION["order"]='id desc';

if (!isset($_GET["start"])){
	if ($_SESSION["start"]=='')
	$_SESSION["start"]=0;
}else{
	$_SESSION["start"]=$ob->pr($_GET['start']);
}
if ($_SESSION["start"]=='') $_SESSION["start"]=0;

if (!isset($_GET["number"])){
	if ($_SESSION["number"]=='')
	$_SESSION["number"]=10;
}else{
	$_SESSION["number"]=$ob->pr($_GET['number']);
}
if ($_SESSION["number"]=='') $_SESSION["number"]=10;


$select=$mysql->query("select * from i_templates 
					ORDER BY ".$_SESSION["order"].",'puttime' DESC");

$select_all=$mysql->query("select * from  i_templates");

?>
<SCRIPT src="/admin/modules/general/script.js"></SCRIPT>

<script type="text/javascript">
function del(i)
{
	if (confirm("Вы действительно хотите удалить выбранный элемент?")) 
	{
		document.location.href='?start=<?=@$_GET['start']?>
		&id_section=<?=@$_GET['id_section']?>&module=<?=@$_GET['module']?>&delete=true&id='+i;
	}
}

function del_pr()
{
	if(document.form_index.actions.options.value!='delete')
	{
		document.form_index.submit();
	}else
	{
		if (confirm("Вы действительно хотите удалить выбранные элементы?")) 
		{
			document.form_index.submit();
		}
	}
	
}
</script>

<link href="/admin/modules/theme/default/style.css" rel="stylesheet" type="text/css" />

<form id="form_index" name="form_index" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="35" class="title_text">Шаблоны модулей</td>
    </tr>
   
    <tr>
      <td height="10" ></td>
    </tr>
    <tr>
      <td height="29" align="center" class="panel_back" style="border: 1px solid #c4c5a6;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
          
          <tr>
            <td width="1%" height="26" align="left"><span class="begin">||</span></td>
            <td width="20%" align="center" onmouseover="this.className='cell_border_small';" onmouseout="this.className='small_text';"><table width="90%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="16%" align="center"><a href="add.php<?=$ob->gets_go($_GET,"module","templates")?>"><img src="/admin/modules/theme/default/images/blank.gif" alt="добавить шаблон" width="16" height="21" border="0" /></a></td>
                  <td width="84%" align="center" class="small_text"><a href="add.php<?=$ob->gets_go($_GET,"module","templates")?>" class="small_text">Добавить шаблон</a></td>
                </tr>
            </table></td>
            
            
            
            

            <td width="79%" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="right"><select name="actions" id="actions" style="width:130px">
                      <?
            			$actions=array(""=>"выбор действия","delete"=>"удалить");
						foreach($actions as $k=>$v)
						{
							echo '<option value="'.$k.'">'.$v.'</option>';
						}
			?>
                    	</select>                  
                    </td>
                  <td align="right" width="120"><input type="button" name="send" id="send" value="Отправить" onclick="del_pr()" /></td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
   <?
   
   if(!@$_GET['id_section'])
   {
   		echo '<tr><td height="10"></td></tr>';
   }else
   {
   
   echo'<tr><td height="10"></td></tr>
		<tr>
      	<td class="small_red_text">';
	  		admin_print_dir();
   echo'</td></tr><tr><td height="10"></td></tr>';   
   }
   ?>
    
   
    <tr>
      <td style="border: 1px solid #c4c5a6;"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#ecebcf" style="border-collapse:collapse">
          <tr>
            <td  height="35" align="center"  class="check_table_title"><input type="checkbox" name="allbox" id="allbox" onclick="CheckAll()" /></td>
            <td width="65" align="center" class="check_table_title" >Действия</td>
            <td width="45" align="center" class="check_table_title" >ID</td>
            <?
		foreach($field as $k=>$v)
		{
			if(@$_GET['order']==$k OR @$_GET['order']==$k." DESC")
			{
				$style='top_table_title_back'; 
				if($_GET['order']!=$k." DESC"){$k.=" DESC";}
			}else{$style='top_table_title';}
			
			if($k=="#"){$width='width="20%"';}else{$width='';}
		
			echo'<td  class="'.$style.'" '.$width.' onmouseover="this.className=\'top_table_title_back\';" 
				onmouseout="this.className=\''.$style.'\';" 
				onclick="document.location.href=\'index.php'.$ob->gets_go($_GET,"order",$k).'\'" 
				title="сортировка по полю '.$v.'">'.$v.'</td>';
		}
		?>
          </tr>
<?
while($res=$select->fetch_array())
{
	$section=$ob->search_option("templates","",@$res['id'],array("category_id"=>$res["id"]));
	
	  
	$li_style='style="height:23px;background-image: url(/admin/modules/theme/default/images/menu_03.gif);
					  background-repeat: repeat-y;
					  background-position: left top; text-align:left;	padding-left: 23px;"';
					  
		
	echo'<tr onMouseOver="this.bgColor=\'#f3f2da\'" 
		onMouseOut="this.bgColor=\'#fafaf0\'" onmouseup="if (!stopMoving()) if (!otherClicks(event)) return CheckTR(this);" 
		onmousedown="startMoving()" id="str'.$res['id'].'" 
		ondblclick="javascript:document.location.href=\'edit.php'.$ob->gets_go($_GET,"id",$res['id']).'&module=templates\'" >
	  
	    <td width="40" align="center" height="25"><INPUT id="chb'.$res['id'].'" type="checkbox" onclick="CheckTR(this);" 
		value="'.$res['id'].'" name="select_ch[]"></td>
        
		<td align="center">
			<ul id="actions_menu'.$res['id'].'" class="MenuBarHorizontal">
				<li ><img src="/admin/modules/theme/default/images/icons_edit.png" border="0"/>
      				<ul>
						
						<li '.$li_style.'><a href="option.php?module=templates&id_section='.$res['id'].'">настройка</a></li>
						<li '.$li_style.'><a href="edit.php'.$ob->gets_go($_GET,"id",$res['id']).'&module=block">редактировать</a></li>
						<li '.$li_style.'><a href="javascript:del(\''.$res['id'].'\')">удалить</a></li>
	
					</ul>
  				</li>
		
		 </ul>
		 <script type="text/javascript">
			<!--
			var MenuBar1 = new Spry.Widget.MenuBar("actions_menu'.$res['id'].'", {imgDown:"", imgRight:""});
			//-->
		</script>
		</td>
		<td class="id_block_style" align="center">'.$res['id'].'</td>
		<td class="table_value"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
    	<td  align="center" style="border-right: 2px solid #ecebcf;" >
		<a href="option.php?module=templates&id_section='.$res['id'].'" style="color:#72ad0f;font-weight:bold;">'.$section->num_rows.'</a>
		</td>
    	</tr>
		</table></td>';
		
		$field2=$ob->search_option("templates","",@$_GET['id_section'],array("select_fields"=>1));
		while($field_res=$field2->fetch_array())
		{
			//если рисунок
			if($res[''.$field_res['name_en'].'']!="" AND ($field_res['type_field']=="i10" OR $field_res['type_field']=="i11"))
			{
				if($field_res['type_field']=="i11"){
					$text='<img src="/admin/resize.php?url='.$_SERVER['DOCUMENT_ROOT'].'/upload/images/'.$res[''.$field_res['name_en'].''].'&w=100&h=90">';
				}
				
				if($field_res['type_field']=="i12"){}
				
			}else{$text='';}
			
			//если checkbox
			if($field_res['type_field']=="i7")
			{
				if($res[''.$field_res['name_en'].'']==1){$text='Да';}else{$text='Нет';}
			}
			
			if(!@$text)
			{
				echo '<td class="table_value">'.substr(strip_tags($res[''.$field_res['name_en'].'']),0,200).'&nbsp;</td>';
      		}else
			{
				echo '<td class="table_value">'.$text.'&nbsp;</td>';
      		}
		}
	  echo '</tr>';
	  }
	  ?>
      </table></td>
    </tr>
    <tr>
      <td height="30" align="right" bgcolor="#ecebcf" class="small_text" style="border: 1px solid #c4c5a6;border-top: 0px solid ;">Всего:
        <?=$select_all->num_rows?>
        &nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
   
  </table>
</form>

<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/footer.php")?>
