<?
include_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/general/mysql.php");
// версия
$incom->guest->lang='ru';
if (isset($_POST["name"]) && $_POST["name"]!='' && isset($_POST["token"]) && $_SESSION["token"]==$_POST["token"])
{
	
	if ($incom->guest->add_guest($_POST["name"], $_POST["mail"], $_POST["text"]))
	{
		echo '<script>';
		echo 'jQuery(".guest_field").val("");';
		echo 'jQuery("#guest_info").html("'.$incom->guest->t("success").'").css("color","green").slideDown("slow");';
		echo '</script>';
		
		
		$link = $_SERVER['HTTP_REFERER'].'#guest';
		
		$incom->guest->send_mail($link, $_POST["name"], $_POST["text"]);
		
	}
	else
	{
		echo '<script>';
		echo 'jQuery("#guest_info").html("'.$incom->guest->t("error").'").css("color","red").slideDown("slow");';
		echo '</script>';
	}
	
	exit;
}

// модерация комментариев

if ($ob->check_admin())
{
	if (isset($_POST["active"]) && isset($_POST["id"]))	
	{
		$mysql->query("update i_guest set active=".intval($_POST["active"])." where id=".intval($_POST["id"])."");	
	}
}

// вывод
$incom->guest->index();