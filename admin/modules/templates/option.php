<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php")?>
<?
function getExtension($filename) {
    return end(explode(".", $filename));
  }
// если удалить поле
if(@$_GET['delete']=="true" AND $_GET['id_field'])
{
	$select=$ob->select("i_option",array("id"=>$_GET['id_field']),"");
	$res=$select->fetch_array();
	
	$select=$ob->select("i_option",array("name_en"=>$res['name_en']),"");
	
	if($select->num_rows > 1){
		
		$mysql->query("delete from i_option where id='".$_GET['id_field']."'");
		
	}else{
		
		$res=$select->fetch_array();
		$mysql->query("ALTER TABLE i_".$_GET['module']." DROP ".$res['name_en']."");
		$i=$mysql->query("delete from i_option where id='".$_GET['id_field']."'");
	}
}

// если отправлены данные
if(@$_POST['hidden'])
{
	// если сохранить то вернуться на предыдущую страницу
	if($_POST['hidden']=='save'){$page=$_POST['reffer'];}else{$page="?module=".@$_GET['module']."&id_section=".@$_GET['id_section'];}

	// если не обновление
	if(!@$_GET['id']){
	
		$select=$ob->select("i_option",array("category"=>$_GET['module'],"name_en"=>$_POST['name_en']),"");
	
	// если обновление	
	}else{
		
		$select=$mysql->query("select * from i_option where category='".$_GET['module']."' AND name_en='".$ob->pr($_POST['name_en'])."' AND id<>'".$ob->pr($_GET['id'])."'");
	
	}

	if($_POST['type_field']=="i1" OR $_POST['type_field']=="i3" OR $_POST['type_field']=="i4" OR $_POST['type_field']=="i7"  OR $_POST['type_field']=="i11" OR $_POST['type_field']=="i12"){
		
		$type_res="varchar(250)";
		
	}
	
	if($_POST['type_field']=="i2"){$type_res="datetime";}
	
	if($_POST['type_field']=="i5"){$type_res="int(11)";}
	
	if($_POST['type_field']=="i6" OR $_POST['type_field']=="i8" OR $_POST['type_field']=="i9" OR $_POST['type_field']=="i10"){$type_res="LONGTEXT";}
	
	if($_POST['type_field']=="i7"){$type_res="tinyint(4)";}
	
	if($_POST["w_resize_small"]==''){$_POST["w_resize_small"]=200;}
	if($_POST["h_resize_small"]==''){$_POST["h_resize_small"]=200;}
	if($_POST["w_resize_big"]==''){$_POST["w_resize_big"]=600;}
	if($_POST["h_resize_big"]==''){$_POST["h_resize_big"]=600;}
	
	// если нет такого
	if($select->num_rows==0)
	{
		//	если выбрано поле	
		if($_POST['type_field']!="i0"){
			
				//если выбрано удаление
				if(@$_POST['delete'.$res['id'].'']==1)
				{
				$delete=$ob->select("i_option",array("id"=>$_GET['id']),"");
				$delete_res=$delete->fetch_array();
				unlink($_SERVER['DOCUMENT_ROOT']."/upload/images/watermark/".$res["watermark"]."");
				$watermark='';
				}
			
			//если не редактирование
			if(!@$_GET['id']){
				
				// добавляем поле
				$i=$mysql->query("ALTER TABLE i_".$_GET['module']." ADD ".$ob->pr($_POST['name_en'])." ".$type_res."");
				
				if($i){
					
				if(!empty($_FILES['watermark']['tmp_name'])){
					
					if($_FILES['watermark']['size']<=1000000){
						
						$format=array('png','PNG');
						
						if(in_array(getExtension($_FILES['watermark']['name']),$format)){
							
							$text=rand(0,100000)."_".rand(500,1000000)."_".date("H").".".getExtension($_FILES['watermark']['name']);
						
							$upload=move_uploaded_file($_FILES['watermark']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/upload/images/watermark/".$text);
						
							$watermark=$text;
						
							if(!$upload){
							
								$ob->alert('Не возможно загрузить файл!');
						$watermark='';
							}
						
						}else{
						
							$ob->alert('Не верный формат файла');
						}
				
					}else{
					
						$ob->alert("Файл превышает размер 1000000 байт");
				
					}
					
				}
					
			
			$field="'".$_GET['module']."','".$_GET['id_section']."','".$_POST['required_fields']."','".$_POST['select_fields']."','".$_POST['id_sort']."','".$_POST['name_ru']."','".$_POST['name_en']."','".$_POST['type_field']."','".@$_POST[''.$_POST['type_field'].'_width']."','".@$_POST[''.$_POST['type_field'].'_format_date']."','".@$_POST[''.$_POST['type_field'].'_height']."','".@$_POST[''.$_POST['type_field'].'_select_elements']."','".@$_POST[''.$_POST['type_field'].'_size_file']."','".@$_POST[''.$_POST['type_field'].'_format_file']."','".$_POST["type_resize"]."','".$_POST["w_resize_small"]."','".$_POST["h_resize_small"]."','".$watermark."','".$_POST["w_resize_big"]."','".$_POST["h_resize_big"]."','".$_POST["filter"]."','".$_POST["search"]."','".$_POST["translit"]."'";
			
	
			$ob->insert("i_option",$field,$page);
					
					
			}else{
					
				$ob->alert("Ошибка создания поля!1");
			
			}
				
			// если редактирование
			}else{
				
				$select=$ob->select("i_option",array("id"=>$_GET['id']),"");
				$res=$select->fetch_array();
				
				$i=$mysql->query("ALTER TABLE i_".$_GET['module']." CHANGE ".$res['name_en']." ".$ob->pr($_POST['name_en'])." ".$type_res."");
				
					
					if(!empty($_FILES['watermark']['tmp_name'])){
					
					if($_FILES['watermark']['size']<=1000000){
						
						$format=array('png','PNG');
						
						if(in_array(getExtension($_FILES['watermark']['name']),$format)){
							
							$text=rand(0,100000)."_".rand(500,1000000)."_".date("H").".".getExtension($_FILES['watermark']['name']);
						
							$upload=move_uploaded_file($_FILES['watermark']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/upload/images/watermark/".$text);
						
							$watermark=$text;
						
							if(!$upload){
							
								$ob->alert('Не возможно загрузить файл!');
						$watermark='';
							}
						
						}else{
						
							$ob->alert('Не верный формат файла');
						}
				
					}else{
					
						$ob->alert("Файл превышает размер 1000000 байт");
				
					}
					
				}
					
					$field=array("required_fields"=>@$_POST['required_fields'],"select_fields"=>@$_POST['select_fields'],"id_sort"=>$_POST['id_sort'],"name_ru"=>$_POST['name_ru'],"name_en"=>$_POST['name_en'],"type_field"=>$_POST['type_field'],"width"=>@$_POST[''.$_POST['type_field'].'_width'],"format_date"=>@$_POST[''.$_POST['type_field'].'_format_date'],"height"=>@$_POST[''.$_POST['type_field'].'_height'],"select_elements"=>@$_POST[''.$_POST['type_field'].'_select_elements'],"size_file"=>@$_POST[''.$_POST['type_field'].'_size_file'],"format_file"=>@$_POST[''.$_POST['type_field'].'_format_file'],"type_resize"=>@$_POST['type_resize'],"w_resize_small"=>@$_POST['w_resize_small'],"h_resize_small"=>@$_POST['h_resize_small'],"watermark"=>@$watermark,"w_resize_big"=>@$_POST['w_resize_big'],"h_resize_big"=>@$_POST['h_resize_big'],"filter"=>@$_POST['filter'],"search"=>@$_POST['search'],"translit"=>@$_POST['translit']);
				
					$ob->update("i_option",$field,$_GET['id'],$page);
				
									
					$field2=array("name_en"=>$_POST['name_en'],"type_field"=>$_POST['type_field'],"width"=>@$_POST[''.$_POST['type_field'].'_width'],"format_date"=>@$_POST[''.$_POST['type_field'].'_format_date'],"height"=>@$_POST[''.$_POST['type_field'].'_height'],"select_elements"=>@$_POST[''.$_POST['type_field'].'_select_elements'],"size_file"=>@$_POST[''.$_POST['type_field'].'_size_file'],"format_file"=>@$_POST[''.$_POST['type_field'].'_format_file'],"type_resize"=>@$_POST['type_resize'],"w_resize_small"=>@$_POST['w_resize_small'],"h_resize_small"=>@$_POST['h_resize_small'],"watermark"=>@$watermark,"w_resize_big"=>@$_POST['w_resize_big'],"h_resize_big"=>@$_POST['h_resize_big'],"filter"=>@$_POST['filter'],"search"=>@$_POST['search'],"translit"=>@$_POST['translit']);
					
					$i=0;
					foreach($field2 as $k=>$v){
						
						if(($i!=count($field2)) AND ($i!=0)){@$str2.=",";}
						@$str2.='`'.$k.'`=\''.$v.'\'';
						$i++;
					}
					
					$update=$mysql->query("update `i_option` set ".$str2." where `name_en`='".$res['name_en']."'");
				
				
			}
			
		}else{
				
			$ob->alert("Необходимо выбрать тип файла!");
			
		}
		
	}else{
		//$ob->alert("Извените, но данное поле уже существует!");
		$qr = $mysql->query("select * from `i_option` where `category`='".$_GET['module']."' AND `name_en`='".$ob->pr($_POST['name_en'])."' AND `category_id`='".$ob->pr($_GET['id_section'])."'".($_GET['id'] ? ' AND `id`!='.$_GET['id'] : ''));
		if($qr->num_rows<1){
			if($_POST['type_field']!="i0")
			{
			
			if(!@$_GET['id'])
			{
				$i=$mysql->query("ALTER TABLE i_".$_GET['module']." CHANGE ".$_POST['name_en']." ".$ob->pr($_POST['name_en'])." ".$type_res."");
				if($i){
					
					if(!empty($_FILES['watermark']['tmp_name'])){
					
					if($_FILES['watermark']['size']<=1000000){
						
						$format=array('png','PNG');
						
						if(in_array(getExtension($_FILES['watermark']['name']),$format)){
							
							$text=rand(0,100000)."_".rand(500,1000000)."_".date("H").".".getExtension($_FILES['watermark']['name']);
						
							$upload=move_uploaded_file($_FILES['watermark']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/upload/images/watermark/".$text);
						
							$watermark=$text;
						
							if(!$upload){
							
								$ob->alert('Не возможно загрузить файл!');
								$watermark='';
							}
						
						}else{
						
							$ob->alert('Не верный формат файла');
						}
				
					}else{
					
						$ob->alert("Файл превышает размер 1000000 байт");
				
					}
					
				}
					
					$field="'".$_GET['module']."','".$_GET['id_section']."','".$_POST['required_fields']."','".$_POST['select_fields']."','".$_POST['id_sort']."','".$_POST['name_ru']."','".$_POST['name_en']."','".$_POST['type_field']."','".@$_POST[''.$_POST['type_field'].'_width']."','".@$_POST[''.$_POST['type_field'].'_format_date']."','".@$_POST[''.$_POST['type_field'].'_height']."','".@$_POST[''.$_POST['type_field'].'_select_elements']."','".@$_POST[''.$_POST['type_field'].'_size_file']."','".@$_POST[''.$_POST['type_field'].'_format_file']."','".$_POST["type_resize"]."','".$_POST["w_resize_small"]."','".$_POST["h_resize_small"]."','".$watermark."','".$_POST["w_resize_big"]."','".$_POST["h_resize_big"]."','".$_POST["filter"]."', '".$_POST["search"]."','".$_POST["translit"]."'";
					
					
					$ob->insert("i_option",$field,$page);
					///////////////////////////////////////////////////
					$field2=array("name_en"=>$_POST['name_en'],"type_field"=>$_POST['type_field'],"width"=>@$_POST[''.$_POST['type_field'].'_width'],"format_date"=>@$_POST[''.$_POST['type_field'].'_format_date'],"height"=>@$_POST[''.$_POST['type_field'].'_height'],"select_elements"=>@$_POST[''.$_POST['type_field'].'_select_elements'],"size_file"=>@$_POST[''.$_POST['type_field'].'_size_file'],"format_file"=>@$_POST[''.$_POST['type_field'].'_format_file'],"type_resize"=>@$_POST['type_resize'],"w_resize_small"=>@$_POST['w_resize_small'],"h_resize_small"=>@$_POST['h_resize_small'],"watermark"=>@$watermark,"w_resize_big"=>@$_POST['w_resize_big'],"h_resize_big"=>@$_POST['h_resize_big'],"filter"=>@$_POST['filter'],"search"=>@$_POST['search'],"translit"=>@$_POST['translit']);
					$i=0;
					foreach($field2 as $k=>$v){
						if(($i!=count($field2)) AND ($i!=0)){@$str2.=",";}
						@$str2.='`'.$k.'`=\''.$v.'\'';
						$i++;
					}
					$update=$mysql->query("update `i_option` set ".$str2." where `name_en`='".$_POST['name_en']."'");
					if(!$update){
						$ob->alert("Record has not update!\\nREASON:".$mysql->error);
					}
				}else{
					$ob->alert("Record has not update!\\nREASON:".$mysql->error);
				}
			}else
			{
				$select=$ob->select("i_option",array("id"=>$_GET['id']),"");
				$res=$select->fetch_array();
				//vd("ALTER TABLE i_".$_GET['module']." CHANGE ".$res['name_en']." ".$ob->pr($_POST['name_en'])." ".$type_res."");exit;ALTER TABLE i_block_elements CHANGE news_full news_full varchar(100)
				$i=$mysql->query("ALTER TABLE i_".$_GET['module']." CHANGE ".$res['name_en']." ".$ob->pr($_POST['name_en'])." ".$type_res."");
				if($i){
					
					if(!empty($_FILES['watermark']['tmp_name'])){
					
					if($_FILES['watermark']['size']<=1000000){
						
						$format=array('png','PNG');
						
						if(in_array(getExtension($_FILES['watermark']['name']),$format)){
							
							$text=rand(0,100000)."_".rand(500,1000000)."_".date("H").".".getExtension($_FILES['watermark']['name']);
						
							$upload=move_uploaded_file($_FILES['watermark']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/upload/images/watermark/".$text);
						
							$watermark=$text;
						
							if(!$upload){
							
								$ob->alert('Не возможно загрузить файл!');
								
								$watermark='';
							}
						
						}else{
						
							$ob->alert('Не верный формат файла');

						}
				
					}else{
					
						$ob->alert("Файл превышает размер 1000000 байт");
				
					}
					
				}
					
					$field=array("required_fields"=>@$_POST['required_fields'],"select_fields"=>@$_POST['select_fields'],"id_sort"=>$_POST['id_sort'],"name_ru"=>$_POST['name_ru'],"name_en"=>$_POST['name_en'],"type_field"=>$_POST['type_field'],"width"=>@$_POST[''.$_POST['type_field'].'_width'],"format_date"=>@$_POST[''.$_POST['type_field'].'_format_date'],"height"=>@$_POST[''.$_POST['type_field'].'_height'],"select_elements"=>@$_POST[''.$_POST['type_field'].'_select_elements'],"size_file"=>@$_POST[''.$_POST['type_field'].'_size_file'],"format_file"=>@$_POST[''.$_POST['type_field'].'_format_file'],"type_resize"=>@$_POST['type_resize'],"w_resize_small"=>@$_POST['w_resize_small'],"h_resize_small"=>@$_POST['h_resize_small'],"watermark"=>@$watermark,"w_resize_big"=>@$_POST['w_resize_big'],"h_resize_big"=>@$_POST['h_resize_big'],"filter"=>@$_POST['filter'],"search"=>@$_POST['search'],"translit"=>@$_POST['translit']);
					$field2=array("name_en"=>$_POST['name_en'],"type_field"=>$_POST['type_field'],"width"=>@$_POST[''.$_POST['type_field'].'_width'],"format_date"=>@$_POST[''.$_POST['type_field'].'_format_date'],"height"=>@$_POST[''.$_POST['type_field'].'_height'],"select_elements"=>@$_POST[''.$_POST['type_field'].'_select_elements'],"size_file"=>@$_POST[''.$_POST['type_field'].'_size_file'],"format_file"=>@$_POST[''.$_POST['type_field'].'_format_file'],"type_resize"=>@$_POST['type_resize'],"w_resize_small"=>@$_POST['w_resize_small'],"h_resize_small"=>@$_POST['h_resize_small'],"watermark"=>@$watermark,"w_resize_big"=>@$_POST['w_resize_big'],"h_resize_big"=>@$_POST['h_resize_big'],"filter"=>@$_POST['filter'],"search"=>@$_POST['search'],"translit"=>@$_POST['translit']);
					
					//$ob->update("i_option",$field,$_GET['id'],$page);
					//////////////////////////////////////////////////////////////////////////function option
					$i=0;
					$str='';
					$str2='';
					foreach($field as $k=>$v){
						if(($i!=count($field)) AND ($i!=0)){@$str.=",";}
						@$str.='`'.$k.'`=\''.$v.'\'';
						$i++;
					}
					$i=0;
					foreach($field2 as $k=>$v){
						if(($i!=count($field2)) AND ($i!=0)){@$str2.=",";}
						@$str2.='`'.$k.'`=\''.$v.'\'';
						$i++;
					}
					$update=$mysql->query("update `i_option` set ".$str." where `id`=".$_GET['id']."");
					$update=$mysql->query("update `i_option` set ".$str2." where `name_en`='".$res['name_en']."'");
					//echo "update ".$table." set ".$str." where id='".$this->pr($id)."'<br>";
					if(!$update){
						$ob->alert("Record has not update!\\nREASON:".$mysql->error);
					}else{
						if($page!=""){
							echo '
							<div id="save_title" style="position:absolute; background-color:#FFFFFF; BORDER: #c4c5a6 1px solid; width:200px; height:50px;">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td height="50" align="center" class="small_text"><img src="/admin/modules/theme/default/images/new.gif"  hspace="4" align="left" />подождите, идёт сохранение параметров</td>
									</tr>
								</table>
							</div>';
							$ob->go($page);
						}
					}
				//////////////////////////////////////////////////////////////////////////////
				}else{$ob->alert("Ошибка создания поля!\\nREASON:".$mysql->error);}
			}
			
			}else
			{
			$ob->alert("Необходимо выбрать тип файла!");
			}
		}else $ob->alert("Извените, но данное поле уже существует!");
	}
}
$field=array("id_sort"=>"ID сорт.","name_ru"=>"Наимен. поля","name_en"=>"Наимен. поля(eng)","type_field"=>"Тип файла");
?>
<link href="/admin/modules/theme/default/style.css" rel="stylesheet" type="text/css" />
<SCRIPT src="/admin/modules/general/script.js"></SCRIPT>

<script>
function select_type(id)
{
	var mas=new Array('i0','i1','i2','i3','i4','i5','i6','i7','i8','i9','i10','i11','i12');
	//var div = document.getElementById(id);
	
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
		
	if(id == 'i10' || id == 'i11' || id == 'i12'){
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
{document.location.href='<?=$ob->gets_go($_GET,"delete","true")?>&id_field='+i;}
}
</script>
<?
$select=$ob->select("i_option",array("id"=>@$_GET['id'],"category"=>$_GET['module']),"");
$res=$select->fetch_array();
?>
<form id="form" name="form" method="post" action="<?=$ob->gets_go($_GET,"","")?>" enctype="multipart/form-data" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="35" class="title_text">Настройка полей
        <input type="hidden" name="reffer" id="reffer" value="<? if(!@$_POST['reffer'])
		{
		echo @$_SERVER['HTTP_REFERER'];
		}else
		{
		echo @$_POST['reffer'];
		}?>" /></td>
    </tr>
    <tr>
    	<td>
    		<?=admin_print_dir('templates',true);?>
    	</td>
    </tr>
    <tr>
      <td height="15" align="left" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td bgcolor="#ecebcf" height="1"></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#f9f8e8" style="border: 1px solid #c4c5a6;"><table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="10"></td>
          </tr>
          <tr>
            <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                <tr>
                  <td align="right" class="small_text">Обязательное поле для заполнения:</td>
                  <td align="left"><input name="required_fields" type="checkbox" id="required_fields" value="1" <? if($res['required_fields']==1){echo "checked";}?>></td>
                </tr>
                <tr>
                  <td align="right" class="small_text">Выделить поле:</td>
                  <td align="left"><input name="select_fields" type="checkbox" id="select_fields" value="1" <? if($res['select_fields']==1){echo "checked";}?>></td>
                </tr>
                 <tr>
                  <td align="right" class="small_text">Учавствует в фильтре:</td>
                  <td align="left"><input name="filter" type="checkbox" id="select_fields" value="1" <? if($res['filter']==1){echo "checked";}?>></td>
                </tr>
                 <tr>
                  <td align="right" class="small_text">Учавствует в поиске:</td>
                  <td align="left"><input name="search" type="checkbox" id="select_fields" value="1" <? if($res['search']==1){echo "checked";}?>></td>
                </tr>
                <tr>
                  <td align="right" class="small_text">Транслировать для ссылки:</td>
                  <td align="left"><input name="translit" type="checkbox" id="select_fields" value="1" <? if($res['translit']==1){echo "checked";}?>></td>
                </tr>
                <tr>
                  <td width="41%" align="right" class="small_text">ID сортировки:</td>
                  <td width="59%" align="left"><input name="id_sort" type="text" id="id_sort" value="<?=$res['id_sort']?>" size="35" /></td>
                </tr>
                <tr>
                  <td align="right" class="small_text"><span class="small_red_text">*</span> Наименование поля:</td>
                  <td align="left"><input name="name_ru" type="text" id="name_ru" value="<?=$res['name_ru']?>" size="35" /></td>
                </tr>
                <tr>
                  <td align="right" class="small_text"><span class="small_red_text">*</span> Наименование поля (eng):</td>
                  <td align="left"><input name="name_en" type="text" id="name_en" value="<?=$res['name_en']?>" size="35" /></td>
                </tr>
                <tr>
                  <td colspan="2" align="center" class="small_text">(поле не должно содержать спец. символы и его длина не должна превышать 30 символов)</td>
                </tr>
                <tr>
                  <td align="right" class="small_text"><span class="small_red_text">*</span> Тип поля:</td>
                  <td align="left"><select name="type_field" id="type_field" onChange="select_type(this.value)">
                  <?
                  $ar=array("i0"=>"выберите из списка","i1"=>"текстовое (text)","i2"=>"текстовое (дата)","i3"=>"текстовое (логин)","i4"=>"текстовое (пароль)","i5"=>"текстовое цена (числовое)","i6"=>"много текста (textarea)","i7"=>"выбор (checkbox)","i8"=>"список (select)","i9"=>"список (radio button)","i10"=>"html редактор","i11"=>"рисунок (file)","i12"=>"файл (file)");
				  foreach($ar as $k=>$v)
				  {
				  if($res['type_field']==$k){$sel='selected';}else{$sel='';}
				  echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
				  }
				  ?>
                  </select>                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="center" class="small_text">
                  <div id="i0" style="display:none"></div>
                  <div id="i1" style="display:<? if($res['type_field']=="i1"){echo "block";}else{echo "none";}?>">
                  <table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>
                      <td width="41%" align="right" class="small_text">Длина поля:</td>
                      <td width="59%" align="left"><input name="i1_width" type="text" id="i1_width" value="<? if(@$_GET['id']){echo $res['width'] ? $res['width'] : "30";}else{echo "30";}?>" size="35" /></td>
                    </tr>
                  </table>
                  </div>
                  <div id="i2" style="display:<? if($res['type_field']=="i2"){echo "block";}else{echo "none";}?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="41%" align="right" class="small_text">Длина поля:</td>
                        <td width="59%" align="left"><input name="i2_width" type="text" id="i2_width" value="<? if(@$_GET['id']){echo $res['width'] ? $res['width'] : "30";}else{echo "30";}?>" size="35" /></td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Формат даты:</td>
                        <td align="left"><input name="i2_format_date" type="text" id="i2_format_date" value="Y-m-d H:i:s" size="35" disabled="disabled" /></td>
                      </tr>
                    </table>
                    </div>
                    <div id="i3" style="display:<? if($res['type_field']=="i3"){echo "block";}else{echo "none";}?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="41%" align="right" class="small_text">Длина поля:</td>
                        <td width="59%" align="left"><input name="i3_width" type="text" id="i3_width" value="<? if(@$_GET['id']){echo $res['width'] ? $res['width'] : "30";}else{echo "30";}?>" size="35" /></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" class="small_text">(данный тип поля шифруется)</td>
                      </tr>
                    </table>
                    </div>
                    <div id="i4" style="display:<? if($res['type_field']=="i4"){echo "block";}else{echo "none";}?>">
                      <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr>
                          <td width="41%" align="right" class="small_text">Длина поля:</td>
                          <td width="59%" align="left"><input name="i4_width" type="text" id="i4_width" value="<? if(@$_GET['id']){echo $res['width'] ? $res['width'] : "30";}else{echo "30";}?>" size="35" /></td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center" class="small_text">(данный тип поля шифруется)</td>
                        </tr>
                      </table>
                    </div>
                    <div id="i5" style="display:<? if($res['type_field']=="i5"){echo "block";}else{echo "none";}?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="41%" align="right" class="small_text">Длина поля:</td>
                        <td width="59%" align="left"><input name="i5_width" type="text" id="i5_width" value="<? if(@$_GET['id']){echo $res['width'] ? $res['width'] : "30";}else{echo "30";}?>" size="35" /></td>
                      </tr>
                    </table>
                    </div>
                    <div id="i6" style="display:<? if($res['type_field']=="i6"){echo "block";}else{echo "none";}?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="41%" align="right" class="small_text">Длина:</td>
                        <td width="59%" align="left"><input name="i6_width" type="text" id="i6_width" value="<? if(@$_GET['id']){echo $res['width'] ? $res['width'] : "30";}else{echo "30";}?>" size="45" /></td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Высота:</td>
                        <td align="left"><input name="i6_height" type="text" id="i6_height" value="<? if(@$_GET['id']){echo $res['height'] ? $res['height'] : "5";}else{echo "5";}?>" size="35" /></td>
                      </tr>
                    </table>
                    </div>
                    <div id="i7" style="display:<? if($res['type_field']=="i7"){echo "block";}else{echo "none";}?>">
                    </div>
                    <div id="i8" style="display:<? if($res['type_field']=="i8"){echo "block";}else{echo "none";}?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="41%" align="right" class="small_text">Длина поля:</td>
                        <td width="59%" align="left"><input name="i8_width" type="text" id="i8_width" value="<? if(@$_GET['id']){echo $res['width'] ? $res['width'] : "100";}else{echo "100";}?>" size="35" /></td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Элементы выпадающего списка:</td>
                        <td align="left"><textarea name="i8_select_elements" id="i8_select_elements" cols="45" rows="5"><? if(@$_GET['id']){echo $res['select_elements'];}else{echo "";}?></textarea></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" class="small_text">(каждый элемент добавляется с новой строки &quot;с помощью клавиши <img src="/admin/modules/theme/default/images/enter.jpg" width="49" height="20" align="absmiddle" />&quot;)</td>
                      </tr>
                    </table>
                    </div>
                    <div id="i9" style="display:<? if($res['type_field']=="i9"){echo "block";}else{echo "none";}?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      
                      <tr>
                        <td width="41%" align="right" class="small_text">Элементы списка:</td>
                        <td width="59%" align="left"><textarea name="i9_select_elements" id="i9_select_elements" cols="45" rows="5"><? if(@$_GET['id']){echo $res['select_elements'];}else{echo "";}?></textarea></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" class="small_text">(каждый элемент добавляется с новой строки &quot;с помощью клавиши <img src="/admin/modules/theme/default/images/enter.jpg" width="49" height="20" align="absmiddle" />&quot;)</td>
                      </tr>
                    </table>
                    </div>
                    <div id="i10" style="display:<? if($res['type_field']=="i10"){echo "block";}else{echo "none";}?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="41%" align="right" class="small_text">Длина:</td>
                        <td width="59%" align="left"><input name="i10_width" type="text" id="i10_width" value="<? if(@$_GET['id']){echo $res['width'] ? $res['width'] : "700";}else{echo "700";}?>" size="35" /></td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Высота:</td>
                        <td align="left"><input name="i10_height" type="text" id="i10_height" value="<? if(@$_GET['id']){echo $res['height'] ? $res['height'] : "400";}else{echo "400";}?>" size="35" /></td>
                      </tr>
                    </table>
                    </div>
                    <div id="i11" style="display:<? if($res['type_field']=="i11"){echo "block";}else{echo "none";}?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="41%" align="right" class="small_text">Максимальный размер (в байтах):</td>
                        <td width="59%" align="left"><input name="i11_size_file" type="text" id="i11_size_file" value="<? if(@$_GET['id']){echo $res['size_file'] ? $res['size_file'] : "3000000";}else{echo "3000000";}?>" size="35" /></td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Форматы изображений:</td>
                        <td align="left"><input name="i11_format_file" type="text" id="i11_format_file" value="<? if(@$_GET['id']){echo $res['format_file'] ? $res['format_file'] : "jpg|gif|png";}else{echo "jpg|gif|png";}?>" size="35" /></td>
                      </tr>
                        <tr>
                        <td colspan="2" align="center" class="small_text">(каждый формат добавляется с разделителем |)</td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Тип обработки изображения:</td>
                        <td align="left"><select name="type_resize" >
                        <option value="auto" <?=$res["type_resize"]=='auto'?'selected':''?>>Авто</option>
                        <option value="landscape" <?=$res["type_resize"]=='landscape'?'selected':''?>>По ширине</option>
                        <option value="portrait" <?=$res["type_resize"]=='portrait'?'selected':''?>>По высоте</option>
                        <option value="exact" <?=$res["type_resize"]=='exact'?'selected':''?>>Точная</option>
                        <option value="crop" <?=$res["type_resize"]=='crop'?'selected':''?>>Обрезка</option>
                        </select>
                        
                      </td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Размеры миниатюры:</td>
                        <td align="left">
                        <table>
                        <tr>
                        <td>Ширина: <input type="text" name="w_resize_small" size="10" value="<?=@$res["w_resize_small"]?>"> Высота:
                        <input type="text" name="h_resize_small" size="10" value="<?=@$res["h_resize_small"]?>">
                        </td>
                        </tr>
                        </table>
                      </td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Размеры основного изображения:</td>
                        <td align="left">
                        <table>
                        <tr>
                        <td>Ширина: <input type="text" name="w_resize_big" size="10" value="<?=@$res["w_resize_big"]?>"> Высота:
                        <input type="text" name="h_resize_big" size="10" value="<?=@$res["h_resize_big"]?>">
                        </td>
                        </tr>
                        </table>
                      </td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Водяной знак:</td>
                        <td align="left">
                        <?
                         if(@$res['watermark']!="")
                        {
                        echo'<table width="100%"  border="0" cellspacing="0" cellpadding="2" align="left">
        <tr>
          <td align="left" class="left_menu"><img src="/upload/images/watermark/'.$res["watermark"].'"></td>
        </tr>
        <tr>
          <td align="left"><table width="20%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="39%" align="center"><input type="checkbox" name="delete'.$res['id'].'" value="1" onClick="SectionClick(\'delete_forms'.$res['id'].'\')"></td>
              <td width="61%" align="left" class="small_text">удалить</td>
            </tr>
          </table>
            </td>
        </tr><tr><td class="small_text"><DIV id="delete_forms'.$res['id'].'" style="DISPLAY:none"><input name="watermark" type="file" /><br>формат(png)/размер(1000000 bytes)</div></td></tr>
      </table>';
                        }else
                        {
                        echo'<input name="watermark" type="file" /><br>формат(png)/размер(1000000 bytes)';
                        }
						?>
                      </td>
                      </tr>
                    
                    </table>
                    </div>
                    <div id="i12" style="display:<? if($res['type_field']=="i12"){echo "block";}else{echo "none";}?>"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="41%" align="right" class="small_text">Максимальный размер (в байтах):</td>
                        <td width="59%" align="left"><input name="i12_size_file" type="text" id="i12_size_file" value="<? if(@$_GET['id']){echo $res['size_file'] ? $res['size_file'] : "3000000";}else{echo "3000000";}?>" size="35" /></td>
                      </tr>
                      <tr>
                        <td align="right" class="small_text">Форматы файлов:</td>
                        <td align="left"><input name="i12_format_file" type="text" id="i12_format_file" value="<? if(@$_GET['id']){echo $res['format_file'] ? $res['format_file'] : "doc|xls";}else{echo "doc|xls";}?>" size="35" /></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" class="small_text">(каждый формат добавляется с новой строки &quot;с помощью клавиши <img src="/admin/modules/theme/default/images/enter.jpg" width="49" height="20" align="absmiddle" />&quot;)</td>
                      </tr>
                    </table>
                    </div>                    </td>
                </tr>
                
            </table></td>
          </tr>
          <tr>
            <td height="10"></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="10" align="center" ></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#f9f8e8" style="border: 1px solid #c4c5a6;"><table width="100%" border="0" cellspacing="4" cellpadding="0">
          <tr>
            <td width="10%" align="left"><input type="button" name="button" id="button" value="Сохранить" onClick="document.getElementById('i2_format_date').disabled = false; pr('save')" /></td>
            <td width="11%" align="left"><input type="button" name="button2" id="button2" value="Применить"  onclick="document.getElementById('i2_format_date').disabled = false; pr('apply')" /></td>
            <td width="79%" align="left"><input type="reset" name="button3" id="button3" value="Отменить"  />
            <input type="hidden" name="hidden" id="hidden" /></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="10"></td>
    </tr>
    <tr>
      <td style="border: 1px solid #c4c5a6;"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#ecebcf" style="border-collapse:collapse">
          <td width="45" height="35" align="center" class="check_table_title" >Действия</td>
            <?
		foreach($field as $k=>$v)
		{
		if(@$_GET['order']==$k OR @$_GET['order']==$k." DESC")
		{
		$style='top_table_title_back'; ;
			if($_GET['order']!=$k." DESC"){$k.=" DESC";}
		}else{$style='top_table_title';}
		echo '<td  class="'.$style.'" onmouseover="this.className=\'top_table_title_back\';" onmouseout="this.className=\''.$style.'\';" onclick="document.location.href=\'option.php'.$ob->gets_go($_GET,"order",$k).'\'" title="сортировка по полю '.$v.'">'.$v.'</td>
';
		}
		?>
          </tr>


          <?
	  
	  $select=$ob->select("i_option",array("category"=>$_GET['module'],"category_id"=>@$_GET['id_section']),"id_sort");
      while($res=$select->fetch_array())
	  {
	  $li_style='style="height:23px;background-image: url(/admin/modules/theme/default/images/menu_03.gif);
	background-repeat: repeat-y;
	background-position: left top; text-align:left;	padding-left: 23px;"';
	
	  echo '<tr onMouseOver="this.bgColor=\'#f3f2da\'" onMouseOut="this.bgColor=\'#fafaf0\'" >
        
		<td align="center"><ul id="actions_menu'.$res['id'].'" class="MenuBarHorizontal">
		<li ><img src="/admin/modules/theme/default/images/icons_edit.png" border="0"/>
      <ul>
		<li '.$li_style.'><a href="'.$ob->gets_go($_GET,"id",$res['id']).'">редактировать</a></li>
		<li '.$li_style.'><a href="javascript:del(\''.$res['id'].'\')">удалить</a></li>
	
	</ul>
  </li>
		
		 </ul><script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("actions_menu'.$res['id'].'", {imgDown:"", imgRight:""});
//-->
</script></td>';
		foreach($field as $k=>$v)
		{
			if($k!="type_field")
			{
			echo '<td class="table_value">'.$res[''.$k.''].'&nbsp;</td>';
			}else
			{
			echo '<td class="table_value">'.$ar[''.$res[''.$k.''].''].'&nbsp;</td>';
			}
		}
	  echo'</tr>';
	  }
	  ?>
      </table></td>
    </tr>
    <tr>
      <td >&nbsp;</td>
    </tr>
  </table>
</form>
<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/footer.php")?>