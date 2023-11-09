<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/subscribe/mail_function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/subscribe/class.phpmailer.php";
include_once($_SERVER["DOCUMENT_ROOT"].'/libs/PayBox/PaymentClass.php');
function mime($str, $data_charset = 'utf-8', $send_charset = 'utf-8')
{
  if ($data_charset != $send_charset) {
    $str = iconv($data_charset, $send_charset, $str);
  }

  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}
$sql = "select * from i_payments where id='".strip_tags(@$_REQUEST['pg_order_id'])."'";
$order = A::$db->get($sql, 1);


if (@$order["id"]>0){

  $sql = "select * from i_shop_users where id='".$order["id_user"]."'";
  $user = A::$db->get($sql, 1);

  $payment = new PaymentClass(array(
    'order_id' => $order["id"],
    'amount' => $order["amount"] + $order["dost"],
    'currency'=> 'KZT',
    'email' => '',
    'phone' => ''
  ));
  $result = $payment->result();


  if (@$_REQUEST["pg_result"]==1){
    $sql = "update i_payments set paid=10 where id='".$order["id"]."' ";
    $i = A::$db->query($sql);

    $msg= '<table border="1" width="100%" cellspacing="0" cellpadding="0">';
    $msg.= '<tr>';
    $msg.= '<td align="center" width="20" style="padding:5px;"><strong>№</strong></td>';
    $msg.= '<td align="left" style="padding:5px;"><strong>Артикул</strong></td>';
    $msg.= '<td align="left" style="padding:5px;"><strong>Наименование</strong></td>';
    $msg.= '<td align="center" style="padding:5px;"><strong>Кол-во</strong></td>  ';
    $msg.= '<td align="center" style="padding:5px;" ><strong>Цена</strong></td>  ';
    $msg.= '</tr>';
    $cost = 0;
    $sql = "select * from i_pay_goods where id_section='".$order["id"]."'";
    $res = A::$db->get($sql);


    foreach ($res as $key => $r) {
      $id = $r["id_good"];
      $count = $r["kol"];
      $res = A::$db->query(" SELECT * FROM `i_cat_elements` WHERE `id`='$id' ");
      $myrow = $res->fetch_array();

      $result1 = A::$db->query(" SELECT * FROM `i_cat` WHERE `id`='".$myrow["id_section"]."' ");
      $myrow1 = $result1->fetch_array();

      $cena=price($r["price"]);
      $nomer = $i;
      $msg.= '<tr>';
      $msg.= '<td class="name name3_padd" style=" width:20px; padding:5px;">' . ($i+1) . '</td>';
      $msg.= '<td class="name name3_padd">' . $r["art"] . '</td>';
      $msg.= '<td class="name name3_padd">' . $r["name"] . '</td>';
      $msg.= '<td align="center" class="s_cart_td">' . $count . '</td>  ';
      $msg.= '<td align="center" class="s_cart_td"><span class="productSpecialPrice">' . round($cena*$count, 0) . ' тг.</span></td>';
      $msg.= '</tr>';
      $cost = $cost + ($cena*$count);
    }


    $dost = $order["dost"];

     $msg.='<tr>';
                $msg.='<td colspan="3"></td>';
                $msg.='<td align="right" class="cart_total_left" style="text-align: right">Стоимость заказа:</td>';
                $msg.='<td  align="center" class="cart_total_right main">';
                $msg.='<span class="productSpecialPrice">' . round($cost, 0) . ' тг.</span>';
                $msg.='</td>';
                $msg.='</tr>';
         
                $msg.='<tr>';
                $msg.='<td colspan="3"></td>';
                $msg.='<td align="right" class="cart_total_left" style="text-align: right">Стоимость доставки:</td>';
                $msg.='<td  align="center" class="cart_total_right main">';
                $msg.='<span class="productSpecialPrice">' . round(@$order["dost"], 0) . ' тг.</span>';
                $msg.='</td>';
                $msg.='</tr>';
                
               
                
                $msg.='<tr>';
                $msg.='<td colspan="3"></td>';
                $msg.='<td align="right" class="cart_total_left" style="text-align: right">Итого:</td>';
                $msg.='<td  align="center" class="cart_total_right main">';
                $msg.='<span class="productSpecialPrice">' . round($cost+@$order["dost"]+@$order["bonus"]-@$order["sale"], 0) . ' тг.</span>';
                $msg.='</td>';
                $msg.='</tr>';
                $msg.='</table>';

    

    $_SESSION['user_basket'] = '';
    $textMsg = ML::msg(1);

    $textMsg["text"] = str_replace('{{goods}}', $msg, $textMsg["text"]);
    $textMsg["text"] = str_replace('{{site}}', $_SERVER["HTTP_HOST"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{name}}', $user["name"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{fam}}', $user["fam"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{country}}', $user["country"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{dom}}', $user["dom"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{kv}}', $user["kv"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{order}}', $order["id"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{phone}}', $user["phone"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{email}}', $user["mail"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{delivery}}', $order["delivery"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{payment}}', $order["payment"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{city}}', $user["city"], $textMsg["text"]);
    $textMsg["text"] = str_replace('{{adress}}', $user["adress"], $textMsg["text"]);


    $mail     = new PHPMailer(true);

    $mail->SetFrom( $textMsg["email"],  $textMsg["email"]);
    $mail->Subject = mime(str_replace('{{site}}', $_SERVER["HTTP_HOST"], $textMsg["title"]));
    $mail->MsgHTML($textMsg["text"]);
    $mail->AddAddress($user["mail"], $user["mail"]);
    

    $send = $mail->Send();


    $send = ML::s(
      ML::param('E-mail администратора'), 
      str_replace('{{site}}', $_SERVER["HTTP_HOST"], $textMsg["title"]), 
      $textMsg["text"], 
      $textMsg["email"], 
      0
    );


  }

  $data = '(';
  foreach ($_REQUEST as $k => $v) {
    $data .= "\t".'['.$k.'] => ' . $v . "\n";
  }
  $data .= ')';


  header('Content-type: text/xml; charset=utf-8');
  echo $result;
}
?>
