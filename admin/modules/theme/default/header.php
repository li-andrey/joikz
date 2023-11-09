<?
$startTimer = microtime(1);
require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/general/mysql.php");

if (@$_POST["do"]=='sidebar' && @$_POST["value"]=='add'){
    $_SESSION["sidebar"] = 'sb-l-m';
    exit;
}else if (@$_POST["do"]=='sidebar' && @$_POST["value"]=='remove'){
    unset($_SESSION["sidebar"]);
    exit;
}

if(@$_GET['exit']=="true"){
    @setcookie("web_auth", "", time()-722600, '/', str_replace('www.', '', '.'.$_SERVER["HTTP_HOST"]));
    @session_destroy();
    $ob->go("/sm/index.php");
}

if(@$_GET['version']){
	$_SESSION['version'] = $_GET['version'];
}

$ob->admin();

$users = $mysql->query("select * from i_user where id='".$_SESSION['user_id']."'"); 
$users_res = $users->fetch_array();

$group = $mysql->query("select * from i_user_group where id='".$_SESSION['id_group']."'"); 
$group_res = $group->fetch_array();


$self = explode("/",$_SERVER['PHP_SELF']);
$mas = explode("|",$group_res['privileges']);

$head = array();
$head_name = array();
$sub_head = array();
$sub_head_name = array();


foreach($mas as $k=>$v){
	$namef = $mysql->query("select * from i_modules where id='$v' and install=1");
	$namef_res = $namef->fetch_array();
	$res_v = explode("=", $v);

	array_push($head, @$res_v['0']);
	array_push($head_name, @$namef_res['folders']);
	
	if(@$res_v['1']!=""){
		$sub_v = explode(",",$res_v['1']);
		foreach($sub_v as $f=>$m){
			$namef = $mysql->query("select * from i_modules where id='$m' and install=1");
			$namef_res = $namef->fetch_array();
			
			array_push($sub_head, @$m);
			array_push($sub_head_name, @$namef_res['folders']);
		}
	}
}

if($_SERVER['PHP_SELF']!="/admin/modules/desktop.php" AND $_SERVER['PHP_SELF']!="/admin/modules/search/index.php"){
	if(in_array($self['3'],$head_name)){
		if(!strstr($self['4'],".")){
			if(!@in_array($self['4'],$sub_head_name)){
				header("LOCATION:/admin/index.php");
			}
		}
	}else{
		header("LOCATION:/admin/index.php");
	}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Site Title-->
    <title>Admin Panel</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <!-- Stylesheets-->
    <link rel="stylesheet" type="text/css" href="<?=ADMIN?>/theme/default/css/admin-forms.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open Sans:300,400,600,700">
    <link rel="stylesheet" type="text/css" href="<?=ADMIN?>/theme/default/css/theme.css">
    <link rel="StyleSheet" href="/upload/js/calendar_data/calendar.css" type="text/css">
    <link href="<?=ADMIN?>/theme/default/popup_menu.css" rel="stylesheet" type="text/css" />
    <style>
    .panel, .panel-heading {
        color: #333;
        font-size: 13px;
    }
    body.sb-l-m #sidebar_left {
       overflow: visible; 
   }
</style>
<!-- core scripts-->
<script src="<?=ADMIN?>/theme/default/js/core.min.js?v=1"></script>
<!-- Theme Javascript-->
<script src="<?=ADMIN?>/theme/default/js/utility.js"></script>
<script src="<?=ADMIN?>/theme/default/js/demo.js"></script>
<script src="<?=ADMIN?>/theme/default/js/main.js"></script>
<script type="text/javascript" src="<?=ADMIN?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" src="/upload/js/calendar_data/calendar.js"></script>
<script type="text/javascript" language="javascript" src="/upload/js/calendar_data/calendar-setup.js"></script>
<script type="text/javascript" language="javascript" src="/upload/js/calendar_data/lang/calendar-ru.js"></script>
<script type="text/javascript" src="<?=ADMIN?>/general/script.js"></script>
<script src="<?=ADMIN?>/theme/default/left_menu.js" type="text/javascript"></script>
<script src="<?=ADMIN?>/theme/default/popup_menu.js" type="text/javascript"></script>
</head>
<body class="<?=$_SERVER["PHP_SELF"]=='/admin/modules/desktop.php'?'dashboard-page':''?> <?=@$_SESSION["sidebar"]?>">

    <div id="main">

      <!-- Start: Header-->
      <header class="navbar dark bg-system navbar-fixed-top">
        <div class="navbar-branding dark bg-system"><a href="/admin/modules/desktop.php" class="navbar-brand text-uppercase">Admin Panel</a><span id="toggle_sidemenu_l" class="fa fa-bars"></span></div>
        <ul class="nav navbar-nav navbar-left">
            <li class="version">Языковая версия
                <select name="select3" class="form-control" id="select3" onchange="MM_jumpMenu('parent',this,0)">
                    <?
                    $select = $mysql->query("select * from i_lang where active=1 order by id asc");
                    while($res = $select->fetch_array()){
                     if($_SESSION['version'] == $res['name_reduction']){
                         $sel = 'selected';
                     }else{
                         $sel = '';
                     }
                     echo '<option value="?version='.$res['name_reduction'].'" '.$sel.'>'.$res['name'].'</option>';
                 }
                 ?>
             </select>
         </li>
     </ul>

     <ul class="nav navbar-nav navbar-right">
      <li class="user">Добрый день, <?=$users_res['last_name']."&nbsp;".$users_res['name']?></li>
      <li class="dropdown menu-merge">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle fw600 p15"><span class="fa fa-angle-down"></span> </a>
        <ul role="menu" class="dropdown-menu list-group dropdown-persist w250">
            <li class="dropdown-footer"><a href="javascript:exit_to()"><span class="fa fa-power-off pr5"></span> Выйти</a></li>
        </ul>
    </li>
</ul>
</header>

<!-- Start: Sidebar-->
<aside id="sidebar_left" class="nano nano-light affix">
    <!-- Start: Sidebar Left Content-->
    <div class="sidebar-left-content nano-content">
        <ul class="nav sidebar-menu">
            <?
            $select = $mysql->query("select * from i_modules where id_head=0 and install=1 and section=0 order by section asc, id_sort asc");
            while($res = $select->fetch_array()){
                $popup = $mysql->query("select * from i_modules where id_head='".$res['id']."' and install=1 order by id_sort asc");
                if($popup->num_rows>0){
                    $link = '/admin/modules/'.$res['folders'].'/';
                }else{
                    $link = '/admin/modules/'.$res['folders'].'/';
                }

                if(in_array($res['id'], $head)){
                    ?>
                    <li>
                        <a href="<?=$link?>" <?=($popup->num_rows>0)?'class="accordion-toggle"':''?>>
                            <span class=" <?=$res["icon"]?> "></span>
                            <span class="sidebar-title"><?=$res["name"]?></span>
                            <?=($popup->num_rows>0)?'<span class="caret"></span>':''?>
                        </a>
                        <?
                        if($popup->num_rows>0){ 
                            ?>
                            <ul class="nav sub-nav">
                                <?
                                while($popup_res = $popup->fetch_array()){
                                    if(in_array($popup_res['id'],$sub_head)){
                                        ?>
                                        <li>
                                            <a href="/admin/modules/<?=$res['folders']?>/<?=$popup_res['folders']?>/">
                                                <span class=" <?=$popup_res["icon"]?>"></span><?=$popup_res["name"]?>
                                            </a>
                                        </li>
                                        <?
                                    }
                                }
                                ?>
                            </ul>
                            <?
                        }
                        ?>
                    </li>
                    <?
                }  
            }
            ?>
            <li class="sidebar-label pt35"></li>
            <?
            $select = $mysql->query("select * from i_modules where id_head=0 and install=1 and section=1 order by section asc, id_sort asc");
            while($res = $select->fetch_array()){
                $popup = $mysql->query("select * from i_modules where id_head='".$res['id']."' and install=1 order by id_sort asc");
                if($popup->num_rows>0){
                    $link = '#';
                }else{
                    $link = '/admin/modules/'.$res['folders'].'/';
                }

                if(in_array($res['id'], $head)){
                    ?>
                    <li>
                        <a href="<?=$link?>" <?=($popup->num_rows>0)?'class="accordion-toggle"':''?>>
                            <span class=" <?=$res["icon"]?> "></span>
                            <span class="sidebar-title"><?=$res["name"]?></span>
                            <?=($popup->num_rows>0)?'<span class="caret"></span>':''?>
                        </a>
                        <?
                        if($popup->num_rows>0){ 
                            ?>
                            <ul class="nav sub-nav">
                                <?
                                while($popup_res = $popup->fetch_array()){
                                    if(in_array($popup_res['id'],$sub_head)){
                                        ?>
                                        <li>
                                            <a href="/admin/modules/<?=$res['folders']?>/<?=$popup_res['folders']?>/">
                                                <span class=" <?=$popup_res["icon"]?>"></span><?=$popup_res["name"]?>
                                            </a>
                                        </li>
                                        <?
                                    }
                                }
                                ?>
                            </ul>
                            <?
                        }
                        ?>
                    </li>
                    <?
                }  
            }
            ?>





        </ul>
        <!-- Start: Sidebar Collapse Button-->
        <div class="sidebar-toggle-mini" style="display: none;"><a href="#"><span class="fa fa-sign-out"></span></a></div>
    </div>
</aside>
<!-- Start: Content-Wrapper-->
<section id="content_wrapper">



