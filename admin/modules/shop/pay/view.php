<?
$nameRazdel = 'payments';
$nameElement = 'payments';
$nameModule = 'pay';

$_GET["id_section"] = intval(@$_GET["id_section"]);
$_GET["module"] = $nameElement;
$_GET["sub_module"] = $nameRazdel;
if (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && 
	($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	(isset($_POST['do'])) && ($_POST['do'] == 'saveNotes') && 
	(isset($_POST['id'])) && ($_POST['id'] != '') && 
	(isset($_POST['dd'])) && 
	(isset($_POST['x'])) && ($_POST['x']=='secure'))
{
	header('Content-Type: text/html; charset=utf-8');
	
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/api.php');
	
	$id = intval($_POST["id"]);
	$dd = $ob->pr($_POST["dd"]);
	
	$sql = "UPDATE i_payments SET driver_delivery='".$dd."' WHERE id='".$id."'";
	$update = $mysql->query($sql);
	
	if ($update) 	
	{ 
		echo '
		<script type="text/javascript">
		alert("Пометки были успешно сохранены!");
		setTimeout(function() { self.location = "'.$_SERVER['PHP_SELF'].'?id='.$id.'"; }, 500);
		</script>
		'; 
	}
	else 			{ echo '<p style="padding:5px 0 0; color:#f00">Пометки были сохранены!</p>'; }
	
	exit;	
}
else if (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && 
	($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	(isset($_POST['do'])) && ($_POST['do'] == 'saveComment') && 
	(isset($_POST['id'])) && ($_POST['id'] != '') && 
	(isset($_POST['com'])) && 
	(isset($_POST['x'])) && ($_POST['x']=='secure'))
{
	header('Content-Type: text/html; charset=utf-8');
	
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/api.php');
	
	$id = intval($_POST["id"]);
	$com = $ob->pr($_POST["com"]);
	
	$sql = "UPDATE i_payments SET comments='".$com."' WHERE id='".$id."'";
	$update = $mysql->query($sql);
	
	if ($update) 	
	{ 
		echo '
		<script type="text/javascript">
		alert("Коментарий успешно сохранен!");
		setTimeout(function() { self.location = "'.$_SERVER['PHP_SELF'].'?id='.$id.'"; }, 500);
		</script>
		'; 
	}
	else { echo '<p style="padding:5px 0 0; color:#f00">Ошибка, попробуйте еще раз!</p>'; }
	
	exit;	
}
else if (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && 
	($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	(isset($_POST['do'])) && ($_POST['do'] == 'editInfo') && 
	(isset($_POST['id'])) && ($_POST['id'] != '') &&  
	(isset($_POST['x'])) && ($_POST['x']=='secure'))
{
	header('Content-Type: text/html; charset=utf-8');
	
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/api.php');
	
	$id = intval($_POST["id"]);
	$timestamp_x = $ob->pr($_POST["timestamp_x"]);
	$info_delivery = $mysql->real_escape_string($_POST["info_delivery"]);
	$user_adrdos = $ob->pr(@$_POST["user_adrdos"]);
	
	$sql = "UPDATE i_payments SET info_delivery='".$info_delivery."' WHERE id='".$id."'";
	$update = $mysql->query($sql);
	
	$s=$mysql->query("SELECT * FROM i_payments WHERE id='".$id."' ");
	if ($s->num_rows>0)
	{
		$r=$s->fetch_array();
		if (intval($r["id_user"]) != 0)
		{
			$sqlU = "UPDATE i_shop_users SET adress='".$user_adrdos."' WHERE id='".$r["id_user"]."'";
			$updateU = $mysql->query($sqlU);
		}	
	}
	
	if ($update) 	
	{ 
		echo '
		<script type="text/javascript">
		alert("Информация успешно сохранена!");
		setTimeout(function() { self.location = "'.$_SERVER['PHP_SELF'].'?id='.$id.'"; }, 500);
		</script>
		'; 
	}
	else { echo '<p style="padding:5px 0 0; color:#f00">Ошибка на сервере, попробуйте чуть позже!</p>'; }
	
	exit;	
}
else if (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && 
	($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	(isset($_POST['do'])) && ($_POST['do'] == 'deleteElement') && 
	(isset($_POST['order'])) && ($_POST['order'] != '') &&  
	(isset($_POST['element'])) && ($_POST['element'] != '') &&  
	(isset($_POST['x'])) && ($_POST['x']=='secure'))
{
	header('Content-Type: text/html; charset=utf-8');
	
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/api.php');
	
	$order = intval($_POST["order"]);
	$element = intval($_POST["element"]);
	
	$s=$mysql->query("SELECT * FROM i_payments WHERE id='".$order."'");
	if ($s->num_rows>0)
	{
		$r=$s->fetch_array();
		
		$sql = "delete from i_pay_goods WHERE id_section='".$order."' and id_good='$element' ";
		$update = $mysql->query($sql);	
	}
	
	if ($update) 	
	{ 
		echo '
		<script type="text/javascript">
		alert("Успешно удалили товар!");
		setTimeout(function() { self.location = "'.$_SERVER['PHP_SELF'].'?id='.$order.'"; }, 500);
		</script>
		'; 
	}
	else { echo '<p style="padding:5px 0 0; color:#f00">Ошибка на сервере, попробуйте чуть позже!</p>'; }
	
	exit;	
}
else  if (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && 
	($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	(isset($_POST['do'])) && ($_POST['do'] == 'saveAction') && 
	(isset($_POST['id'])) && ($_POST['id'] != '') && 
	(isset($_POST['action']) && ($_POST['action'] != '')) && 
	(isset($_POST['x'])) && ($_POST['x']=='secure'))
{
	header('Content-Type: text/html; charset=utf-8');
	
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/api.php');
	
	$id = intval($_POST["id"]);
	$action = intval($_POST["action"]);
	
	$sql = "UPDATE i_payments SET paid='".$action."' WHERE id='".$id."'";
	$update = $mysql->query($sql);
	
	if ($update) 	
	{ 
		echo '
		<script type="text/javascript">
		setTimeout(function() { self.location = "/admin/modules/shop/pay/"; }, 500);
		</script>
		'; 
	}
	else 	{ echo '<p style="padding:5px 0 0; color:#f00">Ошибка сервера, попробуйте еще раз!</p>'; }
	
	exit;	
}
else if (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && 
	($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	(isset($_POST['do'])) && ($_POST['do'] == 'saveAction2') && 
	(isset($_POST['id'])) && ($_POST['id'] != '') && 
	(isset($_POST['action']) && ($_POST['action'] != '')) && 
	(isset($_POST['x'])) && ($_POST['x']=='secure'))
{
	header('Content-Type: text/html; charset=utf-8');
	
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/api.php');
	
	$id = intval($_POST["id"]);
	$action = intval($_POST["action"]);
	$reazon_chancel = $ob->pr(@$_POST["reazon_chancel"]);
	
	$sql = "UPDATE i_payments SET paid='".$action."', reazon_chancel='".$reazon_chancel."' WHERE id='".$id."'";
	$update = $mysql->query($sql);
	
	if ($update) 	
	{ 
		echo '
		<script type="text/javascript">
		setTimeout(function() { self.location = "/admin/modules/shop/pay/"; }, 500);
		</script>
		'; 
	}
	else 	{ echo '<p style="padding:5px 0 0; color:#f00">Ошибка сервера, попробуйте еще раз!</p>'; }
	
	exit;	
}
require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php")?>
<?

if (isset($_POST["action"]) && $_POST["action"]!='')
{
	$update_order = $mysql->query("update i_payments set paid='".$_POST["action"]."' where id='".$_POST["id"]."'");	
	if ($update_order) 
	{
		?>
		<script type="text/javascript">
			setTimeout(function() { self.location = "/admin/modules/shop/pay/"; }, 500);
		</script>
		<?
	}
}

if (isset($_POST["theme"]) && $_POST["theme"]!='' && isset($_POST["text"]) && $_POST["text"]!='')
{
	include_once($_SERVER["DOCUMENT_ROOT"].'/admin/modules/general/api.php');
	# Составляем письмо
	$headers  = "Content-type: text/html; charset=utf-8\n"; 
	$headers .= "From: ".$_SERVER['HTTP_HOST']." <info@".$_SERVER['HTTP_HOST'].">\n";
	$message = '<html>
	<body>
	'.nl2br(stripslashes($_POST["text"])).'
	</body>
	</html>';	
	$select=$ob->select("i_payments",array("id"=>$_POST['id']),"");
	$res=$select->fetch_array();
	
	$users=$ob->select("i_shop_users",array("id"=>$res['id_user']),"");
	$users_res=$users->fetch_array();
	
	$mail_user = $users_res["mail"];
	if ($users_res["mail"]=='')
	{
		$about = explode('<p>', str_replace('</p>', '', $res['info_delivery']));	
		$rUname = ''; $rPhone = ''; $rUadres = ''; $rUinfo = '';
		foreach($about as $kA=>$vA)
		{
			$t_a = explode(' - ', strip_tags($vA));	
			
			if (trim($t_a[0]) == 'E-mail') { $mail_user = stripslashes(trim($t_a[1])); }
		}
	}
	
	$message3 = ''.nl2br(stripslashes($_POST["text"]));	
	$mail_to  =  Array($mail_user);	
	$headers  = "Content-type: text/html; charset=utf-8\n"; 
	$headers .= "From: ".$_SERVER['HTTP_HOST']." <admin@".$_SERVER['HTTP_HOST'].">\n";
	for($i=0; $i<sizeof($mail_to); $i++){
		mail($mail_to[$i], A::$api->mime(stripslashes($_POST["theme"])), $message3, $headers);
	}
	
	
}

$select=$ob->select("i_payments",array("id"=>$_GET['id']),"");
$res=$select->fetch_array();

$users=$ob->select("i_shop_users",array("id"=>$res['id_user']),"");
$users_res=$users->fetch_array();

?>
<script>
	$(function(){
		$('title').html("<?=($users_res["id"]!='' ? $users_res["name"].' '.$users_res["fam"].' - '.$users_res["id"] : 'Быстрый заказ')?>");	
	})

	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		jQuery("#none_print").hide();
		window.print();
		jQuery("#none_print").show();
		document.body.innerHTML = originalContents;
	}

</script>
<style>
.info_e { display:none; }
</style>
<?
$sql = "select name from i_modules where folders='$nameModule'";
$res1 = $mysql->get($sql, 1);
?>
<form  name="form" id="form"  method="post" action="" >
	<!-- Start: Topbar-->
	<header id="topbar" class="alt" style="padding-bottom: 0px;">
		<div class="topbar-left pull-left">
			<ol class="breadcrumb">
				<li class="crumb-icon"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
				<li class="crumb-active"><a href="index.php"><?=$res1["name"]?></a></li>
				/ Информация о заказе
			</ol>
		</div>
	</header>
	<script type="text/javascript">

		function show_edit_inf() {
			jQuery(".info_t").hide();
			jQuery(".info_e").show();	
		}

		function edit_info(id) {
			var id;
			<? if ($res['id_user']==0) { ?>
				var oEditor = FCKeditorAPI.GetInstance('info_delivery') ;
				var res = oEditor.GetHTML();
			<? } ?>
			$(document).ready(function()
			{ 
				$.ajax(
				{
					url: "<?=$_SERVER['PHP_SELF']?>",
					<? if ($res['id_user']!=0) { ?>
						data: "do=editInfo&id="+id+"&timestamp_x="+jQuery("#timestamp_x").val()+"&info_delivery="+jQuery("#info_delivery").val()<?=($res['id_user']!=0 ? '+"&user_adrdos="+jQuery("#user_adrdos").val()' : '')?>+"&x=secure",
					<? } else { ?>
						data: "do=editInfo&id="+id+"&timestamp_x="+jQuery("#timestamp_x").val()+"&info_delivery="+encodeURIComponent(res)<?=($res['id_user']!=0 ? '+"&user_adrdos="+jQuery("#user_adrdos").val()' : '')?>+"&x=secure",
					<? } ?>
					type: "POST",
					dataType : "html",
					cache: false,

					success: function(responseText)
					{
						$("#protocolInf").empty();
						$("#protocolInf").html(responseText);
					}
				});
			});		
		}

		function delete_elements(order, element) {
			var order, element;
			$(document).ready(function()
			{ 
				if (confirm('Вы действительно хотите удалить этот элемент из заказа?') == true)
				{
					$.ajax(
					{
						url: "<?=$_SERVER['PHP_SELF']?>",
						data: "do=deleteElement&order="+order+"&element="+element+"&x=secure",
						type: "POST",
						dataType : "html",
						cache: false,

						success: function(responseText)
						{
							$("#protocolTable").empty();
							$("#protocolTable").html(responseText);
						}
					});
				}
			});		
		}

		function change_price(order, element) {
			var order, element;
			$(document).ready(function()
			{ 
				$.ajax(
				{
					url: "<?=$_SERVER['PHP_SELF']?>",
					data: "do=changePrice&order="+order+"&element="+element+"&price="+jQuery("#price-"+element).val()+"&x=secure",
					type: "POST",
					dataType : "html",
					cache: false,

					success: function(responseText)
					{
						$("#protocolTable").empty();
						$("#protocolTable").html(responseText);
					}
				});
			});		
		}

		function change_count(order, element) {
			var order, element;
			$(document).ready(function()
			{ 
				$.ajax(
				{
					url: "<?=$_SERVER['PHP_SELF']?>",
					data: "do=changeCount&order="+order+"&element="+element+"&count="+jQuery("#count-"+element).val()+"&x=secure",
					type: "POST",
					dataType : "html",
					cache: false,

					success: function(responseText)
					{
						$("#protocolTable").empty();
						$("#protocolTable").html(responseText);
					}
				});
			});		
		}
		function save_comment(id) {
			var id;
			$(document).ready(function()
			{ 
				$.ajax(
				{
					url: "<?=$_SERVER['PHP_SELF']?>",
					data: "do=saveComment&id="+id+"&com="+jQuery("#comment").val()+"&x=secure",
					type: "POST",
					dataType : "html",
					cache: false,

					success: function(responseText)
					{
						$("#protocol_comment").empty();
						$("#protocol_comment").html(responseText);
					}
				});
			});		
		}
		function save_notes(id) {
			var id;
			$(document).ready(function()
			{ 
				$.ajax(
				{
					url: "<?=$_SERVER['PHP_SELF']?>",
					data: "do=saveNotes&id="+id+"&dd="+jQuery("#driver_delivery").val()+"&x=secure",
					type: "POST",
					dataType : "html",
					cache: false,

					success: function(responseText)
					{
						$("#protocol").empty();
						$("#protocol").html(responseText);
					}
				});
			});		
		}
	</script>
	<section id="content" class="table-layout">
		<div class="tray tray-center" style="padding-top: 0px;">
			<div class="tray-inner">
				<div class="row flex-column-reverse-before-md">
					<div class="col-sm-12">

						<div class="panel-body">
							<form action="" method="post" enctype="multipart/form-data" name="form" id="form" >

								<div class="row">
									<div class="col-md-3" style="text-align: right">
										<label>№ заказа:</label>
									</div>
									<div class="col-md-6">
										<?=$res["id"]?>
									</div>
									<div class="col-md-3" style="text-align: right;">
										<div class="info_t"><a onclick="show_edit_inf()" class="btn btn-sm btn-success">Редактировать инфу</a></div>
										<div class="info_e"><a onclick="edit_info(<?=$res["id"]?>)" class="btn btn-sm btn-success">Сохранить изменения в базе</a></div>
										<div id="protocolInf"></div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" style="text-align: right">
										<label>Дата/Время:</label>
									</div>
									<div class="col-md-9">
										<?=A::$api->date('ru', $res['timestamp_x'], 'sql', 'datetimetext')?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" style="text-align: right">
										<label>Статус заказа:</label>
									</div>
									<div class="col-md-9">
										<? switch ($res['paid']) {
                      case '0':
                        $act = 'Заказ не прошел';
                        break;
					case '11':
						$act = 'Новый';
						break;
                      case '1':
                        $act = 'В сборке';
                        break;
                      case '2':
                        $act = 'Готов к отправке';
                        break;
                      case '3':
                        $act = 'Передан курьеру';
                        break;
                      case '4':
                        $act = 'Завершён';
                        break;
                      case '5':
                        $act = 'Ожидает в пункте самовывоза';
                        break;
                      case '6':
                        $act = 'Отменен';
                        break;
                      case '7':
                        $act = 'Ожидается возврат';
                        break;
                      case '8':
                        $act = 'Возврат в пути';
                        break;
                      case '9':
                        $act = 'Ожидает решения по возврату';
                        break;
                      case '10':
                        $act = 'Оплачен через платежную систему';
                        break;
                    }
										echo $act;
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" style="text-align: right">
										<label>Доставка:</label>
									</div>
									<div class="col-md-9">
										<?=$res['delivery']?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" style="text-align: right">
										<label>Способ оплаты:</label>
									</div>
									<div class="col-md-9">
										<?=$res['payment']?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" style="text-align: right">
										<label>Доп. информация о доставке:</label>
									</div>
									<div class="col-md-9">
										<div class="info_t"><?=$res['info_delivery']?></div>
										<div class="info_e">
											<textarea name="text" cols="5" rows="6"  id="info_delivery" class="form-control"><?=stripslashes(str_replace(chr(9),"",$res['info_delivery']))?></textarea>
											
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<hr>
									</div>
								</div>

								<div class="row">
									<div class="col-md-3" style="text-align: right">
										<label>Комментарий к заказу:</label>
									</div>
									<div class="col-md-6">
										<textarea name="text" cols="5" rows="6" id="comment" class="form-control"><?=stripslashes($res["comments"])?></textarea>
									</div>
									<div class="col-md-3" style="text-align: right">
										<a onclick="save_comment(<?=$res["id"]?>)" class="btn btn-sm btn-success">Сохранить комментарий</a>
										<div id="protocol_comment"></div>
									</div>
								</div>


								<div class="row">
									<div class="col-md-12">
										<hr>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" style="text-align: right">
										<label>Пометки для водителя доставки:</label>
									</div>
									<div class="col-md-6">
										<textarea name="text" cols="5" rows="6" id="driver_delivery" class="form-control"><?=stripslashes($res["driver_delivery"])?></textarea>
									</div>
									<div class="col-md-3" style="text-align: right">
										<a onclick="save_notes(<?=$res["id"]?>)" class="btn btn-sm btn-success">Сохранить пометки в базе</a>
										<div id="protocol"></div>

									</div>
								</div>


								<div class="row">
									<div class="col-md-12">
										<hr>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<h4 style="margin:0px; padding: 0px; margin-bottom: 20px;">Заказанные товары</h4>
										<table width="100%" class="table table-responsive table-hover table-bordered" style="color: #000; ">
											<thead>
												<tr class="dark">
													<td align="left" valign="top" style="padding:5px;">
														<strong>№</strong>
													</td>
													<td align="left" valign="top" style="padding:5px;">
														<strong>Артикул</strong>
													</td>
													<td align="left" valign="top" style="padding:5px;">
														<strong>Фото</strong>
													</td>
													<td align="left" valign="top" style="padding:5px;">
														<strong>Наименование</strong>
													</td>
													<td align="left" valign="top" style="padding:5px;">
														<strong>Цена</strong>
													</td>
													<td align="left" valign="top" style="padding:5px;">
														<strong>Количество</strong>
													</td>
													<td align="left" valign="top" style="padding:5px;">
														<strong>Сумма</strong>
													</td>
												</tr>
											</thead>
											<?
											$sql = "select * from i_pay_goods where id_section='".$res["id"]."'";
											$resGoods = A::$db->get($sql);
											$i=1;
											$cc=0; 
											$all_count = 0; 
											$count_str = 0 ;
											$excel_elements = '';
											foreach($resGoods as $k=>$v){
												if($v!=""){
													$count = $v["kol"];
													$price = $v["price"];

													$s=$mysql->query("select * from i_cat_elements where id=".$v["id_good"]."");
													if ($s->num_rows>0){
														$r = $s->fetch_array();	
													}

													$summa = $price * $count;

													echo '
													<tr>
													<td align="left" valign="middle" style="padding:5px;">
													'.$i.'
													<a style="margin-left:5px; cursor:pointer" onclick="delete_elements('.$res["id"].', '.$r["id"].')">
													<img src="/modules/shop/assets/images/del.jpg" />
													</a>
													</td>
													<td align="left" valign="middle" style="padding:5px;">
													'.$v["art"].'
													</td>
													<td align="left" valign="middle" style="padding:5px;">
													'.(@$r["image"]!=''?'<a href="http://'.$_SERVER['HTTP_HOST'].'/catalog/'.$r["url"].'/"><img src="/upload/images/small/'.$r["image"].'" width="100"></a>':'').'
													</td>
													<td align="left" valign="middle" style="padding:5px;">
													'.(@$r["name2"]!=''?$r["name2"]:$v["name"]).'<br>'.(str_replace('size:', 'Размер:', str_replace('color:', 'Цвет:', $v["param"]))).'
													</td>';
													echo '
													<td align="left" valign="middle" style="padding:5px;">
													'.$price.'
													</td>
													<td align="left" valign="middle" style="padding:5px;">
													'.$count.'
													</td>
													';	
													echo '
													<td align="left" valign="middle" style="padding:5px;">
													<span id="summa-'.$r["id"].'">'.$summa.'</span>
													</td>
													</tr>
													';

													$excel_elements .= '
													<tr>
													<td>&nbsp;</td>
													<td style="text-align:center; border:1px solid #000">'.$i.'</td>
													<td colspan="5" style="border:1px solid #000">'.$v["art"].'</td>
													<td style="text-align:center; border:1px solid #000">&nbsp;&nbsp;&nbsp;'.(@$r["name2"]!=''?$r["name2"]:$v["name"]).'&nbsp;&nbsp;&nbsp;</td>
													<td style="text-align:center; border:1px solid #000">&nbsp;&nbsp;&nbsp;'.number_format($count, 0, '', ' ').'&nbsp;&nbsp;&nbsp;</td>
													<td style="text-align:center; border:1px solid #000">&nbsp;&nbsp;&nbsp;'.number_format($price, 0, '', ' ').'&nbsp;&nbsp;&nbsp;</td>
													<td style="text-align:center; border:1px solid #000">&nbsp;&nbsp;&nbsp;'.number_format($summa, 0, '', ' ').'&nbsp;&nbsp;&nbsp;</td>
													</tr>
													';

													$i++;

													$all_count = $all_count + intval($count);

													$cc=$cc+$summa;

												}
												$count_str = $i - 1;
											}

											?>

										</table>
										<div id="protocolTable"></div>

										<div style="padding:20px 0; text-align:left; font-size:16px; line-height:18px">
											<div class="row">
												<div class="col-md-9">	
													<div id="none_print">
														
													</div>
													<div style="display:none;" id="chancel_cat">
														<br />
														<textarea name="text" cols="5" rows="6" id="reazon_chancel" class="form-control" placeholder="Причина отмены"><?=$res["reazon_chancel"]?></textarea><br />
														<a onclick="save_action2(3)" class="btn btn-success btn-sm">Сохранить</a>
													</div>
													<div style="margin:20px 0 0; font-size: 14px;" id="chancel_cat_text">
														<!--<? if ($res["reazon_chancel"]!='') { ?>
															<div class="title_text" style="margin:0 0 5px"><b>Причина отмены:</b>
																<?=$res["reazon_chancel"]?></div>
															<? } ?>-->
														</div>
													</div>
													<div class="col-md-3" style="text-align: right; line-height:26px;">
													<strong>Сумма:</strong> <span ><?=$cc?></span> тенге
														<? if ($res["sale"]>0){ ?>
															<br><strong>Промокод "<?=$res["promo"]?>":</strong> <span >-<?=$res["sale"]?></span> тенге	<br>
														<? } ?>
														<? if ($res["dost"]>0){ ?>
															<br><strong>Доставка:</strong> <span ><?=$res["dost"]?></span> тенге	<br>
														<? } ?>
														<strong>Итого к оплате:</strong> <span id="all_summa"><?=$cc-$res["sale"]+$res["dost"]?></span> тенге
													</div>
												</div>
											</div>

											<div id="none_print">
												<div style="float:left;">
													<script type="text/javascript">
														function save_action(action) {
															var action;
															$(document).ready(function()
															{ 
																$.ajax(
																{
																	url: "<?=$_SERVER['PHP_SELF']?>",
																	data: "do=saveAction&action="+action+"&id=<?=intval($_GET["id"])?>&x=secure",
																	type: "POST",
																	dataType : "html",
																	cache: false,

																	success: function(responseText)
																	{
																		$("#protocol_a").empty();
																		$("#protocol_a").html(responseText);
																	}
																});
															});		
														}

														function save_action2(action) {
															var action;
															$(document).ready(function()
															{ 
																if (jQuery("#reazon_chancel").val()!='')
																{
																	$.ajax(
																	{
																		url: "<?=$_SERVER['PHP_SELF']?>",
																		data: "do=saveAction2&action="+action+"&id=<?=intval($_GET["id"])?>&reazon_chancel="+jQuery("#reazon_chancel").val()+"&x=secure",
																		type: "POST",
																		dataType : "html",
																		cache: false,

																		success: function(responseText)
																		{
																			$("#protocol_a").empty();
																			$("#protocol_a").html(responseText);
																		}
																	});
																}
																else { alert("Заполните причину!"); }
															});		
														}

													</script>
													<div id="protocol_a"></div>

													<script type="text/javascript">
														function show_chancel_cat(){
															jQuery("#chancel_cat").slideDown();
															jQuery("#chancel_cat_text").slideUp();
														}
													</script>

												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<h4 style="margin:0px; padding: 0px; margin-bottom: 20px;">Информация о покупателе</h4>
													<?
													$users=$ob->select("i_shop_users",array("id"=>$res['id_user']),"");
													$users_res = $users->fetch_array();

													?>
													<?
													$option = $ob->select("i_option",array("category"=>"shop_users","category_id"=>$users_res['id_section']),"id_sort");
													while($option_res=$option->fetch_array())
													{
														if($users_res[''.$option_res['name_en'].'']!="")
														{
															if($users_res[''.$option_res['name_en'].'']!="" AND ($option_res['type_field']=="i10" OR $option_res['type_field']=="i11"))
															{
																if($option_res['type_field']=="i11"){$text='<img src="/admin/resize.php?url='.$_SERVER['DOCUMENT_ROOT'].'/upload/images/'.$users_res[''.$option_res['name_en'].''].'&w=100&h=90">';}
																if($option_res['type_field']=="i12"){}
															}else{$text='';}
														if($option_res['type_field']=="i7")
														{
															if($users_res[''.$option_res['name_en'].'']==1){$text='Да';}else{$text='Нет';}
														}


														echo '<div class="row" style="margin-bottom:18px;">
														<div class="col-md-4">'.$option_res['name_ru'].':</div>';
														if(!@$text){
															echo '<div class="col-md-6">'.substr(strip_tags($users_res[''.$option_res['name_en'].'']),0,200).'</div>';
														}else{
															echo '<div class="col-md-6">'.$text.'&nbsp;</div>';
														}
														echo '</div>';
													}
													
												}
												?>
												<div class="row">
													<div class="col-md-4">E-mail:</div>
													<div class="col-md-6"><?=$users_res["mail"]?>&nbsp;</div>
												</div>
											</div>
											<div class="col-md-6">
												<h4 style="margin:0px; padding: 0px; margin-bottom: 20px;">Отправить сообщение</h4>
												<div >
													<?
													$temp_theme = 'Заказ на сайте '.$_SERVER['HTTP_HOST'];
													$temp_letter = "Добрый день!\n\n";
													$temp_letter.= "Спасибо за заказ на нашем сайте.\n\n";
													$temp_letter.= "Ваш заказ принят,  при выезде со склада, служба доставки с Вами свяжется.\n\n";
													$temp_letter.= "С уважением, ".$_SERVER['HTTP_HOST']."";

													?>
													<div id="letter_protocol"></div>
													<form method="post" action="">
														<input name="id"  type="hidden" value="<?=intval($_GET["id"])?>">
														<p>Тема письма<br />
															<input class="form-control" name="theme" id="temp_theme" style="width:300px;"  type="text" value="<?=$temp_theme?>" />
														</p>
														<p>Текст письма<br />
															<textarea class="form-control"  name="text" id="temp_letter" cols="5" rows="6" ><?=$temp_letter?></textarea>
														</p>
														<input type="submit" class="btn btn-primary btn-mini" value="Отправить" >
													</form>
												</div>
											</div>
										</div>
										<input type="button" class="btn btn-primary " name="button" id="button" value="Вернуться к списку заказов" onclick="document.location.href='index.php'" />
									</div>
								</div>

							</form>
							<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/footer.php")?>
