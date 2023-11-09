<? require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/header.php") ?>
<?
$nameRazdel = 'payments';
$nameElement = 'payments';
$nameModule = 'pay';

$_GET["id_section"] = intval(@$_GET["id_section"]);
$_GET["module"] = $nameElement;
$_GET["sub_module"] = $nameRazdel;


if (isset($_POST['actions'])) {
  if (count(@$_POST['select_ch']) > 0) {
    foreach ($_POST['select_ch'] as $k => $v) {

      if ($_POST['actions'] == 0) {
        $query = "update i_payments set paid='0' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "1") {
        $query = "update i_payments set paid='1' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "2") {
        $query = "update i_payments set paid='2' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "3") {
        $query = "update i_payments set paid='3' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "4") {
        $query = "update i_payments set paid='4' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "5") {
        $query = "update i_payments set paid='5' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "6") {
        $query = "update i_payments set paid='6' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "7") {
        $query = "update i_payments set paid='7' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "8") {
        $query = "update i_payments set paid='8' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "9") {
        $query = "update i_payments set paid='9' where id='" . $ob->pr($v) . "'";
      }
      if ($_POST['actions'] == "10") {
        $query = "update i_payments set paid='10' where id='" . $ob->pr($v) . "'";
      }

      if ($_POST['actions'] == "delete") {
        $ob->del_r("i_payments", array("id" => $v));
      }

      $mysql->query(@$query);
    }
  } else {
    $ob->alert("Необходимо выбрать элементы таблицы!");
  }
}

if (@$_GET['delete'] == "true" and $_GET['id']) {
  $ob->del_r("i_payments", array("id" => $_GET['id']));
}

$field = array(
  "id" => "Номер заказа",
  "timestamp_x" => "Дата/Время",
  "id_user" => "Имя",
  "paid" => "Статус",
  "amount" => "Сумма заказа",
  "dost" => "Сумма доставки",
  "promo" => "Промокод",
  "comments" => "Комментарий к заказу"
);

$where = '';

if (!@$_GET['order']) {
  $ord = "id DESC";
} else {
  $ord = $ob->pr($_GET['order']);
}
if (!@$_GET['start']) {
  $start = 0;
} else {
  $start = $ob->pr($_GET['start']);
}
if (!@$_GET['number']) {
  $number = 100;
} else {
  $number = $ob->pr($_GET['number']);
}

$sql_search = "";
if (isset($_GET["search"]) && $ob->pr($_GET["search"]) != '') {

  $sql_search .= " AND (";
  $mass_catalog = array();
  $sql = "SELECT id FROM i_cat_elements WHERE INSTR(`name`, '" . $ob->pr($_GET["search"]) . "') OR INSTR(`art`, '" . $ob->pr($_GET["search"]) . "')";
  $sCatalog = A::$db->query($sql);
  if (@$sCatalog->num_rows > 0) {
    while ($rC = $sCatalog->fetch_array()) {
      $sql = "select id_section from i_pay_goods where id_good='" . $rC["id"] . "' group by id_section";
      $resGoods = A::$db->get($sql);
      foreach ($resGoods as $r) {
        $mass_catalog[] = " id = '" . $r["id_section"] . "' ";
      }
    }
  }

  if (sizeof($mass_catalog) > 0)
    $sql_search .= "(" . join("OR ", $mass_catalog) . ") OR";


  $mass_users = array();
  $sUsers = $mysql->query("SELECT id FROM i_shop_users WHERE INSTR(`name`, '" . $ob->pr($_GET["search"]) . "') OR INSTR(`phone`, '" . $ob->pr($_GET["search"]) . "') OR INSTR(`mail`, '" . $ob->pr($_GET["search"]) . "') OR INSTR(`city`, '" . $ob->pr($_GET["search"]) . "') OR INSTR(`adress`, '" . $ob->pr($_GET["search"]) . "')");
  if ($sUsers->num_rows > 0) {
    while ($rUs = $sUsers->fetch_array()) {
      $mass_users[] = "INSTR(`id_user`, '" . $rUs["id"] . "') ";
    }
  }

  if (sizeof($mass_users) > 0)
    $sql_search .= "(" . join("OR ", $mass_users) . ") OR";

  $sql_search .= "
INSTR(`info_delivery`, '" . $ob->pr($_GET["search"]) . "')
OR INSTR(`delivery`, '" . $ob->pr($_GET["search"]) . "')
OR INSTR(`payment`, '" . $ob->pr($_GET["search"]) . "')
OR INSTR(`driver_delivery`, '" . $ob->pr($_GET["search"]) . "')
OR `id`='" . $ob->pr($_GET["search"]) . "'
OR INSTR(`timestamp_x`, '" . $ob->pr($_GET["search"]) . "')
)";
}


$select = $mysql->query("select * from i_payments where id<>0 " . $where . "" . $sql_search . " ORDER BY " . $ord . ",'puttime' DESC LIMIT " . $start . "," . $number . "");


$select_all = $mysql->query("select * from  i_payments where id<>0 " . $where . "" . $sql_search . "");





?>

<script type="text/javascript">
  function del(i) {
    if (confirm("Вы действительно хотите удалить выбранный элемент?")) {
      document.location.href = '?start=<?= @$_GET['start'] ?>&delete=true&id=' + i;
    }
  }

  function del_pr() {
    if (document.form_index.actions.options.value != 'delete') {
      document.form_index.submit();
    } else {
      if (confirm("Вы действительно хотите удалить выбранные элементы?")) {
        document.form_index.submit();
      }
    }

  }

  function do_search() {
    if (jQuery("#search").val() != '') {
      setTimeout(function() {
        self.location = "<?= $_SERVER['PHP_SELF'] ?>?search=" + jQuery("#search").val();
      }, 50);
    }
  }

  function func_search(event) {
    if (event.keyCode == 13) {
      do_search();
    }
  }
</script>

<style>
  a.button {
    cursor: pointer;
    font-size: 13px;
    display: inline-block;
    padding: 5px 20px 5px;
    border: 0px solid #007f87;
    background: #64ad33;
    color: #fff;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    box-shadow: 0 2px 2px 0 #606060;
    width: auto
  }

  a.button:hover {
    color: #fff;
    background: #333
  }
</style>

<?
$sql = "select name from i_modules where folders='$nameModule'";
$res = $mysql->get($sql, 1);
?>
<form id="form_index" name="form_index" method="post" action="index.php">
  <!-- Start: Topbar-->
  <header id="topbar" class="alt" style="padding-bottom: 0px;">
    <div class="topbar-left pull-left">
      <ol class="breadcrumb">
        <li class="crumb-icon"><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li class="crumb-active"><a href="index.php"><?= $res["name"] ?></a></li>
        <?
        admin_print_dir(@$_GET["module"], @$_GET["sub_module"]);
        ?>
      </ol>
    </div>
    <div class="topbar-right">
      <input type="text" class="form-control" value="<?= (isset($_GET["search"]) ? $ob->pr($_GET["search"]) : '') ?>" placeholder="Поиск по заказам..." name="search" id="search" style="display: inline-block; width: 300px; height: 32px; margin-top: -6px; vertical-align: middle;" onkeydown="func_search(event);" />
      <a class="btn btn-success" onclick="do_search()" style="height: 32px;margin-top: -5px;line-height: 2px;vertical-align: middle;">Поиск</a>


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
                  <a href="<?= $ob->gets_go($_GET, "excel", "true") ?>" target="_self" class="btn btn-system btn-sm" style="margin-right: 0px;">
                    <span class="glyphicon glyphicon-open"></span> Export
                  </a>


                </div>
                <div class="widget-menu pull-right">


                  Показывать по
                  <select name="jumpMenu" id="jumpMenu" class="form-control" style="display: inline-block; width: auto;" onchange="MM_jumpMenu('parent',this,0)">
                    <?
                    $elements = array(10, 20, 40, 80, 100, 1000);
                    foreach ($elements as $k => $v) {
                      if ($v == $number) {
                        $sel = 'selected';
                      } else {
                        $sel = '';
                      }
                      echo '<option value="' . $ob->gets_go($_GET, "number", $v) . '" ' . $sel . '>' . $v . '</option>';
                    }
                    ?>
                  </select>
                  | <select name="actions" id="actions" style="width:130px; display: inline-block;" class="form-control">
                    <?
                    $actions = array(
                      "" => "Выбор действия",
                      "0" => "Заказ не прошел",
                      "11" => "Новый",
                      "1" => "В сборке",
                      "2" => "Готов к отправке",
                      "3" => "Передан курьеру",
                      "4" => "Завершён",
                      "5" => "Ожидает в пункте самовывоза",
                      "6" => "Отменен",
                      "7" => "Ожидается возврат",
                      "8" => "Возврат в пути",
                      "9" => "Ожидает решения по возврату",
                      "delete" => "Удалить"
                    );

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
                      <td height="35" align="center" class="check_table_title">
                        <input type="checkbox" name="allbox" id="allbox" onclick="CheckAll()" />
                      </td>
                      <td width="65" align="center" class="check_table_title">
                        Действия
                      </td>
                      <?
                      foreach ($field as $k => $v) {
                        if (@$_GET['order'] == $k or @$_GET['order'] == $k . " DESC") {
                          $style = 'top_table_title_back';;
                          if ($_GET['order'] != $k . " DESC") {
                            $k .= " DESC";
                          }
                        } else {
                          $style = 'top_table_title';
                        }
                        echo '<td  class="' . $style . '" onmouseover="this.className=\'top_table_title_back\';" onmouseout="this.className=\'' . $style . '\';" onclick="document.location.href=\'index.php' . $ob->gets_go($_GET, "order", $k) . '\'" title="сортировка по полю ' . $v . '">' . $v . '</td>
                      ';
                      }
                      ?>
                    </tr>
                  </thead>
                  <?
                  while ($res = $select->fetch_array()) {
                    $li_style = 'style="height:23px;background-image: url(/admin/modules/theme/default/images/menu_03.gif);background-repeat: repeat-y; background-position: left top; text-align:left;	padding-left: 23px;"';

                    switch ($res['paid']) {
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
                    $user = $ob->select("i_shop_users", array("id" => $res['id_user']), "");
                    $user_res = $user->fetch_array();
                    echo '<tr onMouseOver="this.bgColor=\'#f3f2da\'" onMouseOut="this.bgColor=\'#fafaf0\'" onmouseup="if (!stopMoving()) if (!otherClicks(event)) return CheckTR(this);" onmousedown="startMoving()" id="str' . $res['id'] . '" ondblclick="javascript:document.location.href=\'view.php' . $ob->gets_go($_GET, "id", $res['id']) . '\'" title="двойной щелчок открывает запись для просмотра">
                  <td width="40" align="center" height="25"><INPUT id="chb' . $res['id'] . '" type="checkbox" onclick="CheckTR(this);" value="' . $res['id'] . '" name="select_ch[]"></td>

                  <td align="center"><ul id="actions_menu' . $res['id'] . '" class="MenuBarHorizontal">
                  <li ><span class="glyphicon glyphicon-align-justify" style="font-size:20px; color:#333;"></span>
                  <ul>
                  <li ' . $li_style . '><a href="view.php' . $ob->gets_go($_GET, "id", $res['id']) . '">Просмотреть</a></li>
                  </ul>
                  </li>

                  </ul><script type="text/javascript">
                  <!--
                  var MenuBar1 = new Spry.Widget.MenuBar("actions_menu' . $res['id'] . '", {imgDown:"", imgRight:""});
//-->
                  </script></td>
                  <td class="table_value">' . $res['id'] . '&nbsp;</td>
                  <td class="table_value" nowrap="nowrap">' . A::$api->date('ru', $res['timestamp_x'], 'sql', 'datetime') . '&nbsp;</td>
                  <td class="table_value" nowrap="nowrap">' . ($user_res["name"] != '' ? $user_res['name'] : 'Быстрый заказ') . '&nbsp;</td>
                  <td class="table_value">' . $act . '&nbsp;</td>
                  <td class="table_value">' . $res["amount"] . '&nbsp;</td> 
                  <td class="table_value">' . $res["dost"] . '&nbsp;</td>
                  <td class="table_value">' . ($res["promo"] != '' ? '' . $res["promo"] . ' - ' . $res["sale"] . '' : '') . '&nbsp;</td>
                  <td class="table_value">' . stripslashes($res["comments"]) . '&nbsp;</td> 
                  </tr>';
                  }
                  ?>
                  <tfoot>
                    <tr class="dark">
                      <td height="30" align="right" colspan="<?= (sizeof($field) + 2) ?>">
                        Всего: <?= $select_all->num_rows ?>
                      </td>
                    </tr>
                  </tfoot>
                </table>

                <div style="margin-top: 10px; text-align: center;">
                  <div class="pagination" style="display: inline-block; padding: 0px 0px; margin: 0px 0px 10px 0;">
                    <?
                    $count = $select_all->num_rows;

                    $por   = ceil($count / $number);
                    $link  = 0;
                    $right = 0;

                    for ($i = 1; $i <= $por; $i++) {
                      if ($start <= $link) {
                        $right++;
                        if (($right == 1) and $start > 0) {
                          if (($i - 5) > 0) {
                            $minus = $i - 5;
                          } else {
                            $minus = 0;
                          }
                          $link_main = $link - ($number * 5);
                          if ($link_main < 0) {
                            $link_main = 0;
                          }
                          echo '<a href="' . $ob->gets_go($_GET, "start", $link_main) . '" class="small_text">
                        [' . $minus . ".." . ($i - 1) . ']
                        </a> &nbsp;';
                        }

                        if ($right <= 5) {
                          if ($start == $link) {
                            $style = 'style="font-weight:bold"';
                          } else {
                            $style = '';
                          }
                          echo '<a href="' . $ob->gets_go($_GET, "start", $link) . '" class="small_text" ' . $style . '>' . $i . '</a>&nbsp;';
                        }
                        if (($right == 5) and (($link + $number) < $count)) {
                          echo '<a href="' . $ob->gets_go($_GET, "start", ($link + $number)) . '" class="small_text" >[' . ($i + 1) . ".." . ($i + 5) . ']</a>';
                        }
                      }
                      $link = $number + $link;
                    }
                    ?>
                  </div>
                </div>

</form>
<?
if (@$_GET['excel'] == "true") {
  $select = $mysql->query("select * from i_payments where id<>0 " . $where . " ORDER BY " . $ord . ",'puttime' DESC LIMIT " . $start . "," . $number . "");
  $str = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
              <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#ecebcf">
              <tr>';
  foreach ($field as $k => $v) {
    if (@$_GET['order'] == $k or @$_GET['order'] == $k . " DESC") {
      $style = 'top_table_title_back';;
      if ($_GET['order'] != $k . " DESC") {
        $k .= " DESC";
      }
    } else {
      $style = 'top_table_title';
    }

    $str .= '<td  class="' . $style . '">' . $v . '</td>';
  }
  $str .= '</tr>';

  while ($res = $select->fetch_array()) {

    switch ($res['paid']) {
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
    $user = $ob->select("i_shop_users", array("id" => $res['id_user']), "");
    $user_res = $user->fetch_array();

    $str .= '<tr>		
                <td class="table_value">' . $res['id'] . '&nbsp;</td>
                <td class="table_value">' . $api->Strings->date('ru', $res['timestamp_x'], 'sql', 'datetime') . '</td>
                <td class="table_value">' . ($user_res['name'] != '' ? $user_res['name'] : 'Быстрый заказ') . '</td>
                <td class="table_value">' . $act . '</td>
                <td class="table_value">' . $res["amount"] . '</td> 
                <td class="table_value">' . $res["dost"] . '</td>
                <td class="table_value">' . $res["bonus"] . '</td>
                <td class="table_value">' . stripslashes($res["comments"]) . '</td> 
                </tr>';
  }
  $str .= '</table>';
  $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/admin/tmp/export.xls", "w+");
  $wr = fputs($file, $str);
  if ($wr) {
    $ob->go("/admin/tmp/export.xls");
  } else {
    $ob->alert("Ошибка экспорта!");
  }
}
?>

<? require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/modules/theme/default/footer.php") ?>