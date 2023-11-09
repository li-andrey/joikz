<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php")?>
<?
if(@$_POST['hidden'])
{
if($_POST['hidden']=='save'){$page="index.php".$ob->gets_go($_GET,"","");}else{$page="";}
$field=array();
$translit='';
	
	$option=$ob->search_option($_GET['module'],"",@$_GET['id_section'],array(""));
	while($option_res=$option->fetch_array())
	{
		if($option_res['type_field']=="i11"){$folder="images";}
		if($option_res['type_field']=="i12"){$folder="files";}
		
	switch($option_res['type_field'])
	{
	case $option_res['type_field']=="i11" OR $option_res['type_field']=="i12":
	//если выбрано удаление
				if(@$_POST['delete'.$option_res['id'].'']==1){
					$delete=$ob->select("i_".$_GET['module'],array("id"=>$_GET['id']),"");
					$delete_res=$delete->fetch_array();
					unlink($_SERVER['DOCUMENT_ROOT']."/upload/".$folder."/".$delete_res[''.$option_res['name_en'].'']);
					if ($folder=='images')
				{
				unlink($_SERVER['DOCUMENT_ROOT']."/upload/".$folder."/small/".$delete_res[''.$option_res['name_en'].'']);
				unlink($_SERVER['DOCUMENT_ROOT']."/upload/".$folder."/big/".$delete_res[''.$option_res['name_en'].'']);
				}
					$field[$option_res['name_en']]="";
				}
				if(!empty($_FILES[''.$option_res['name_en'].'']['tmp_name']))
				{
					if($_FILES[''.$option_res['name_en'].'']['size']<=$option_res['size_file'])
					{
					$format=explode("|",$option_res['format_file']);
						if(in_array(substr($_FILES[''.$option_res['name_en'].'']['name'],(strlen($_FILES[''.$option_res['name_en'].'']['name'])-3),strlen($_FILES[''.$option_res['name_en'].'']['name'])),$format))
						{
						$text=rand(0,100000)."_".rand(500,1000000)."_".date("H").".".substr($_FILES[''.$option_res['name_en'].'']['name'],(strlen($_FILES[''.$option_res['name_en'].'']['name'])-3));
						$upload=move_uploaded_file($_FILES[''.$option_res['name_en'].'']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/upload/".$folder."/".$text);
						
						$field[''.$option_res['name_en'].'']=$text;
							if(!$upload){$erorr='Не возможно загрузить файл!';}
							else{
							
								$resizeObj_small = new resize('/upload/images/'.$text);
						$resizeObj_small -> resizeImage($option_res['w_resize_small'], $option_res['h_resize_small'], $option_res['type_resize']);
						$resizeObj_small -> saveImage('/upload/images/small/'.$text, 100);	
						
						$resizeObj_big = new resize('/upload/images/'.$text);
						$resizeObj_big -> resizeImage($option_res['w_resize_big'], $option_res['h_resize_big'], $option_res['type_resize']);
						$resizeObj_big -> saveImage('/upload/images/big/'.$text, 100);	
						if ($option_res['watermark']!='')
						{
							
							$resizeObj_wat = new resize('/upload/images/big/'.$text);
							$resizeObj_wat -> watermark($_SERVER['DOCUMENT_ROOT'].'/upload/images/big/'.$text,$_SERVER['DOCUMENT_ROOT'].'/upload/images/watermark/'.$option_res['watermark']);
						}
							
							
							}
						}else{$erorr='Не верный формат файла';}
					}else{$erorr="Файл превышает размер ".$option_res['size_file']." байт";}
				}
				
				
		break;
	case $option_res['type_field']=="i3":
	$login_search=$mysql->query("select * from i_".$_GET['module']." where `".$option_res['name_en']."`='".sha1($_POST[''.$option_res['name_en'].''])."' AND id<>'".$ob->pr($_GET['id'])."'");
	if($login_search->num_rows>0){$erorr="Извените, но данный логин уже существует!";}else{$field[''.$option_res['name_en'].'']=sha1($_POST[''.$option_res['name_en'].'']);}
	break;
	
	case $option_res['type_field']=="i4":
	$field[''.$option_res['name_en'].'']=sha1($_POST[''.$option_res['name_en'].'']);
	break;
	
	default:
	$field[''.$option_res['name_en'].'']=addslashes($_POST[''.$option_res['name_en'].'']);
	break;
	}
	
	
	
	}
		
	if(!@$erorr)
	{	
	if(@$_POST['version']){$ver=$_POST['version'];}else{$ver=$_SESSION['version'];}
	$field['version']=$ver;
	$ob->update("i_".$_GET['module'],$field,$_GET['id'],$page);
	}else
	{
	$ob->alert($erorr);
	}
}
//проверка на валидность
$ob->script_view($_GET['module'],"",@$_GET['id_section'],array("required_fields"=>1));
?>
<link href="/admin/modules/theme/default/style.css" rel="stylesheet" type="text/css" />


<form action="" method="post" enctype="multipart/form-data" name="form" id="form" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="35" class="title_text">Шаблоны модулей</td>
    </tr>
	<tr>
    	<td>
    		<?=admin_print_dir(true);?>
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
                  <td align="right" class="small_text">акт. для всех языковых версий:</td>
                  <td align="left" class="small_text"><input name="version" type="checkbox" id="version" value="all" <? 
				  $main=$ob->select("i_block",array("id"=>$_GET['id'],"version"=>"all"),"");
				  if($main->num_rows>0){echo "checked";}
				  ?> /></td>
                </tr>
                <?
				$option=$ob->search_option($_GET['module'],"",@$_GET['id_section'],array(""));
				while($option_res=$option->fetch_array())
				{
				if($option_res['required_fields']==1){$star='<span class="small_red_text">*</span>';}else{$star='';}
					if($option_res['type_field']=="i10")
					{
					echo '<tr>
                  <td align="center" class="small_text" colspan="2">'.$star.'&nbsp;'.$option_res['name_ru'].':</td>
                  
                </tr><tr>
                  
                  <td align="left" class="small_text"  colspan="2">'.$ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"").'</td>
                </tr>';
					}else
					{
					echo '<tr>
                  <td align="right" class="small_text">'.$star.'&nbsp;'.$option_res['name_ru'].':</td>
                  <td align="left" class="small_text">'.$ob->input_view($option_res['id'],"i_".$_GET['module'],"id",$_GET['id'],"").'</td>
                </tr>';
					}
                 
				}
				?>
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
            <td width="10%" align="left"><input type="button" name="button" id="button" value="Сохранить" onclick="pr('save')" /></td>
            <td width="11%" align="left"><input type="button" name="button2" id="button2" value="Применить"  onclick="pr('apply')" /></td>
            <td width="79%" align="left"><input type="reset" name="button3" id="button3" value="Отменить" />
            <input type="hidden" name="hidden" id="hidden" /></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="10"></td>
    </tr>
  </table>
</form>
<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/footer.php")?>
