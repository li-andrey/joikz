<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/libs/Payment.php");

function executeREST($params, $action)
{

	$queryUrl = 'https://minestone.bitrix24.kz/rest/31/nrmwp2er1771w68u/' . $action;
	$queryData = http_build_query($params);

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $queryUrl,
		CURLOPT_POSTFIELDS => $queryData,
	));

	$result = curl_exec($curl);
	curl_close($curl);

	//vd(json_decode($result, true));

	return json_decode($result, true);
}

$error = "";
$success = 0;
if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	if (isset($_GET['do']) && $_GET["do"] == 'load-sizes') {

		$sql = "select * from i_cat_elements where id_section='" . $_GET["color"] . "' and active=1 group by size order by id asc";

		$res = A::$db->get($sql);

		$data = [
			'res' => $res
		];

		$html = A::$app->catalog->viewJSON('size', $data);
		A::$app->setData([
			'html' => $html
		]);
		A::$app->success('done');
	}


	if (isset($_GET['do']) && $_GET["do"] == 'load-images') {

		$sql = "select * from i_cat where id='" . $_GET["color"] . "' and active=1 limit 1";
		$res = A::$db->get($sql, 1);

		$data = [
			'color' => $res
		];

		$html = A::$app->catalog->viewJSON('images', $data);
		A::$app->setData([
			'html' => $html
		]);
		A::$app->success('done');
	}


	if (isset($_GET["do"]) && ($_GET["do"]) == 'promo') {
		$sql = "select * from i_promo where name='" . trim($_GET["value"]) . "' and active=1 limit 1";
		$res = A::$db->get($sql, 1);

		if (intval(@$res["id"]) == 0) {
			echo json_encode(['success' => 0, 'msg' => 'Промокод не найден']);
			exit;
		}

		if (strtotime($res["data"]) > time() || strtotime($res["data_end"]) < time()) {
			echo json_encode(['success' => 0, 'msg' => 'Промокод недействителен']);
			exit;
		}

		$amount = str_replace([' ', '₸'], '', $_GET["amount"]);

		if ($res["price1"] > 0) {
			$html = '<div class="row align-items-end" data-id="' . $res["id"] . '" style="margin-bottom: 1rem">';
			$html .= '<div class="col-6 col-md-6">Промокод "' . $res["name"] . '"</div>';
			$html .= '<div class="col-6 col-md-6" style="text-align: right;">';
			$html .= '<b class="promo-sale">-' . price((round($amount * $res["price1"] / 100)), 1) . ' ₸</b>';
			$html .= '<input type="hidden" name="promo_id[]" value="' . $res["id"] . '">';
			$html .= '<input type="hidden" name="promo_name[]" value="' . $res["name"] . '">';
			$html .= '<input type="hidden" name="promo_value[]" class="promo-input" value="' . round($amount * $res["price1"] / 100) . '">';
			$html .= '</div>';
			$html .= '</div>';
		}
		echo json_encode(['success' => 1, 'promo' => $html, 'id' => $res["id"]]);
		exit;
	}


	if (isset($_GET["do"]) && ($_GET["do"]) == 'city') {

		$sql = "select * from i_shop where id_section='5' and name='" . $_GET["id"] . "' and active=1 order by id_sort asc";
		$res = A::$db->get($sql, 1);

		$sql = "select * from i_shop_elements where id_section='" . intval($res["id"]) . "' and active=1 order by id_sort asc";
		$res = A::$db->get($sql);

		$opt = '';

		foreach ($res as $key => $r) {
			$opt .= '<option value="' . $r["name"] . '" data-dost="' . $r["price"] . '" >' . $r["name"] . '</option>';
		}

		echo json_encode(
			array(
				'success' => 1,
				'city' => $opt
			)
		);
	}

	if (isset($_GET["do"]) && ($_GET["do"]) == 'dost') {

		$sql = "select * from i_shop_elements where name='" . $_GET["id"] . "' and active=1 order by id_sort asc";
		$res = A::$db->get($sql, 1);

		$dost = explode('|', $res["dost"]);

		$dost_id = array();
		$dost_price = array();
		foreach ($dost as $key => $v) {
			$a = explode('-', $v);
			$dost_id[] = @$a[0];
			$dost_price[] = @$a[1];
		}

		$sum = A::$app->cart->sum();

		$dost = '';
		if ($payment = A::$app->shop->m(3)) {
			$res = A::$app->shop->elements($payment["id"]);
			$i = 0;
			foreach ($res as $key => $r) {
				if (in_array($r["id"], $dost_id)) {
					$price = array_search($r["id"], $dost_id);

					if ($dost_price[$price] !== '') {
						if ($sum >= 15000) {
							$dost_price[$price] = 0;
						}
						$dost .= '<div class="' . $dost_price[$price] . 'aa radio-input padding-off">
						<label for="delivery" style="font-weight: normal; display:flex"><input style="float: left;
						margin: 4px 10px 5px;     min-width: 15px; min-height: 15px;" id="delivery' . $r["id"] . '" name="delivery" type="radio" ' . ($i == 0 ? 'checked=""' : '') . ' value="' . $r["name"] . '" data-price="' . price($dost_price[$price], 1) . '"> ' . $r["name"] . ' (' . ($dost_price[$price] === 0 || $dost_price[$price] === '0' ? 'бесплатно' : price($dost_price[$price], 1) . ' ₸') . ')</label></div>';
						$i++;
					}
				}
			}
		}
		echo json_encode(
			array(
				'success' => 1,
				'dost' => $dost
			)
		);
	}


	if (isset($_GET['search'])) {
		$author = array();
		if (strlen($_GET['search']) > 3) {
			$sql = "select name, id  from i_cat_elements where id_section!=3 and active=1 and name like '%" . $_GET['search'] . "%' order by name asc";
			$res = A::$db->get($sql);
			$author = $res;
		}
		echo json_encode(
			$author
		);
	}

	// комментарий
	if (isset($_POST['do']) && ($_POST['do'] == 'addcomment')) {
		A::$app->comments->add();
		exit;
	}

	// Регистрация
	if (isset($_POST['do']) && ($_POST['do'] == 'register')) {
		A::$app->user->add();
		exit;
	}

	// проверка кода подтверждения телефона
	if (isset($_POST['do']) && ($_POST['do'] == 'forgot')) {
		$phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);
		A::$app->user->forgot();
		exit;
	}

	// отправка кода подтверждения телефона
	if (isset($_POST['do']) && ($_POST['do'] == 'send-sms')) {
		$phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', $_POST["phone"]);
		A::$app->user->sendSmsCode($phone);
		exit;
	}

	// отправка кода подтверждения телефона
	if (isset($_POST['do']) && ($_POST['do'] == 'send-sms-for-register')) {
		$phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', $_POST["phone"]);
		A::$app->user->sendSmsCodeForRegister($phone);
		exit;
	}


	// проверка кода подтверждения телефона
	if (isset($_POST['do']) && ($_POST['do'] == 'check-sms')) {
		$phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);
		A::$app->user->checkSmsCode(@$_POST["code"], $phone, @$_POST["password"]);
		exit;
	}

	// проверка кода подтверждения телефона
	if (isset($_POST['do']) && ($_POST['do'] == 'check-sms1')) {
		$phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);
		A::$app->user->checkSmsCode1(@$_POST["code"]);
		exit;
	}

	// смена пароля
	if (isset($_POST['do']) && ($_POST['do'] == 'change-password')) {
		A::$app->user->changePassword();
		exit;
	}

	// Авторизация
	if (isset($_POST['do']) && ($_POST['do'] == 'login')) {
		$phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["email"]);
		A::$app->user->login($phone, @$_POST["password"]);
		exit;
	}

	// Авторизация
	if (isset($_POST['do']) && ($_POST['do'] == 'profile')) {

		A::$app->user->edit();
		exit;
	}


	if (isset($_POST['do']) && ($_POST['do'] == 'addBasket') && isset($_POST['id']) && (intval($_POST['id']) != '')) {

		$params = '';
		if (isset($_POST['color']) && ($_POST['color'] != '')) {
			$params .= 'Цвет:' . $ob->pr($_POST['color']) . ",";
		}

		if (isset($_POST['size']) && ($_POST['size'] != '')) {
			$params .= 'Размер:' . $ob->pr($_POST['size']);
		}

		if (A::$app->cart->check($_POST['id'], $params) == false) {
			A::$app->cart->add($_POST['id'], $_POST['kol'], $params);
			$success = 1;
		} else {
			$error = 'Данный товар уже добавлен в корзину';
		}

		$sql = "select price from i_cat_elements where id='" . $_POST["id"] . "'";
		$res = A::$db->get($sql, 1);

		echo json_encode(
			array(
				'success' => $success,
				'msg' => $error,
				'count' => $success == 1 ? (A::$app->cart->count() + 1) : A::$app->cart->count(),
			)
		);
	}

	if (isset($_POST['do']) && ($_POST['do'] == 'addLike') && isset($_POST['id']) && (intval($_POST['id']) != '')) {

		if (A::$app->wishlist->check($_POST['id']) == false) {
			A::$app->wishlist->add($_POST['id'], $_POST['kol'], @$params);
			$success = 1;
		} else {
			A::$app->wishlist->del($_POST['id'], $_POST['kol'], @$params);
		}

		echo json_encode(
			array(
				'success' => $success,
				'msg' => $error,
				'count' => A::$app->wishlist->count() + 1,
			)
		);
	}

	if (isset($_POST['do']) && ($_POST['do'] == 'delLike') && isset($_POST['id']) && (intval($_POST['id']) != '')) {

		if (A::$app->wishlist->check($_POST['id']) == true) {
			A::$app->wishlist->del($_POST['id']);
			$success = 1;
		} else {
			A::$app->wishlist->add($_POST['id'], 1, array());
		}

		echo json_encode(
			array(
				'success' => $success,
				'msg' => $error,
				'count' => A::$app->wishlist->count() - 1,
			)
		);
	}


	if (isset($_POST['do']) && ($_POST['do'] == 'addCompare') && isset($_POST['id']) && (intval($_POST['id']) != '')) {

		if (A::$app->simile->check($_POST['id']) == false) {
			A::$app->simile->add($_POST['id'], $_POST['kol'], $params);
			$success = 1;
		} else {
			$error = 'Данный товар уже в списке желаний';
		}

		echo json_encode(
			array(
				'success' => $success,
				'msg' => $error,
				'count' => A::$app->simile->count() + 1,
			)
		);
	}

	if (isset($_POST['do']) && ($_POST['do'] == 'delCompare') && isset($_POST['id']) && (intval($_POST['id']) != '')) {

		if (A::$app->simile->check($_POST['id']) == true) {

			A::$app->simile->del($_POST['id']);
			$success = 1;
		}

		echo json_encode(
			array(
				'success' => $success,
				'msg' => $error,
				'count' => A::$app->simile->count() - 1,
			)
		);
	}



	if ((@$_POST['do'] == 'del') && isset($_POST['pos']) && ($_POST['pos'] != '')) {
		if (A::$app->cart->del($_POST['pos'], $_POST['params']) == false) {
		}
		$success = 1;
		echo json_encode(
			array(
				'success' => $success,
				'error' => $error,
				'count' => A::$app->cart->count() - 1,
			)
		);
	}


	if (isset($_POST['do']) && $_POST['do'] == 'load') {
		$data = array();
		$basket = A::$app->shop->viewJSON('basket', $data);
		$basket_order = A::$app->shop->viewJSON('order_basket', $data);
		$success = 1;
		echo json_encode(
			array(
				'success' => $success,
				'error' => $error,
				'basket' => $basket,
				'basket_order' => $basket_order,
				'count' => A::$app->cart->count(),
			)
		);
	}

	if (isset($_POST['do']) && ($_POST['do'] == 'clear_basket')) {
		A::$app->cart->clear();
		$success = 1;
		echo json_encode(
			array(
				'success' => $success,
				'error' => $error,
				'count' => 0,
			)
		);
	}

	if (@$_POST['do'] == 'recount') {
		A::$app->cart->update($_POST['element'], $_POST['value'], $_POST['params']);
		$success = 1;
		echo json_encode(
			array(
				'success' => $success,
				'error' => $error,
				'count' => A::$app->cart->count(),
			)
		);
	}

	if (isset($_POST['do']) && ($_POST['do'] == 'order')) {

		$headers = "Content-type: text/html; charset=utf-8\n";
		$configFrom = ML::param('E-mail отправителя');
		$headers .= "From: " .  $configFrom . " <" . $configFrom  . ">\n";

		$message = '<html>
		<body>
		Отправлено: ' . date('d.m.Y') . ' в ' . date('h:i') . ' с IP ' . $_SERVER['REMOTE_ADDR'] . '<br/>
		<br/>
		<h2>Форма заявки на сайте ' . $_SERVER['HTTP_HOST'] . '.</h2>
		<p><b>Имя:</b> ' . $_POST["name"] . '</p>
		<p><b>Телефон:</b> ' . $_POST["phone"] . '</p>
		</body>
		</html>';
		$mail_to   =  array();
		$mail_to[] = ML::param('E-mail администратора');


		for ($i = 0; $i < sizeof($mail_to); $i++) {
			$send = mail($mail_to[$i], ('Заявка с сайта ' . $_SERVER['HTTP_HOST']), $message, $headers);
		}

		if ($send) {
			$success = 1;
			echo json_encode(
				array(
					'success' => $success,
					'error' => $error,
					'msg' => A::$app->t('order_success'),
				)
			);
		} else {
			$error = A::$app->t('error');
			echo json_encode(
				array(
					'success' => $success,
					'error' => $error,
				)
			);
		}

		exit;
	}

	if (isset($_POST['do']) && ($_POST['do'] == 'feedback')) {

		$headers = "Content-type: text/html; charset=utf-8\n";
		$configFrom = ML::param('E-mail отправителя');
		$headers .= "From: " .  $configFrom . " <" . $configFrom  . ">\n";

		$message = '<html>
		<body>
		Отправлено: ' . date('d.m.Y') . ' в ' . date('h:i') . ' с IP ' . $_SERVER['REMOTE_ADDR'] . '<br/>
		<br/>
		<h2>Обратная связь на сайте ' . $_SERVER['HTTP_HOST'] . '.</h2>
		<p><b>Имя:</b> ' . $_POST["name"] . '</p>
		<p><b>E-mail:</b> ' . $_POST["email"] . '</p>
		<p><b>Телефон:</b> ' . $_POST["phone"] . '</p>
		<p><b>Сообщение:</b> ' . $_POST["message"] . '</p>
		</body>
		</html>';
		$mail_to   =  array();
		$mail_to[] = ML::param('E-mail администратора');


		for ($i = 0; $i < sizeof($mail_to); $i++) {
			$send = mail($mail_to[$i], ('Обратная связь с сайта ' . $_SERVER['HTTP_HOST']), $message, $headers);
		}

		if ($send) {
			$success = 1;
			echo json_encode(
				array(
					'success' => $success,
					'error' => $error,
					'msg' => A::$app->t('order_success'),
				)
			);
		} else {
			$error = A::$app->t('error');
			echo json_encode(
				array(
					'success' => $success,
					'error' => $error,
				)
			);
		}

		exit;
	}

	if (isset($_POST['do']) && ($_POST['do'] == 'subs')) {

		$headers = "Content-type: text/html; charset=utf-8\n";
		$configFrom = ML::param('E-mail отправителя');
		$headers .= "From: " .  $configFrom . " <" . $configFrom  . ">\n";

		$message = '<html>
		<body>
		Отправлено: ' . date('d.m.Y') . ' в ' . date('h:i') . ' с IP ' . $_SERVER['REMOTE_ADDR'] . '<br/>
		<br/>
		<h2>Получить скидку на сайте ' . $_SERVER['HTTP_HOST'] . '.</h2>
		<p><b>E-mail:</b> ' . $_POST["email"] . '</p>
		</body>
		</html>';
		$mail_to   =  array();
		$mail_to[] = ML::param('E-mail администратора');


		for ($i = 0; $i < sizeof($mail_to); $i++) {
			$send = mail($mail_to[$i], ('Получить скидку с сайта ' . $_SERVER['HTTP_HOST']), $message, $headers);
		}

		if ($send) {
			$success = 1;
			echo json_encode(
				array(
					'success' => $success,
					'error' => $error,
					'msg' => A::$app->t('order_success'),
				)
			);
		} else {
			$error = A::$app->t('error');
			echo json_encode(
				array(
					'success' => $success,
					'error' => $error,
				)
			);
		}

		exit;
	}

	if (isset($_POST['do']) && ($_POST['do'] == 'send_order')) {

		$send_sms = 0;

		$phone = str_replace(array(' ', '+', '(', ')', '_', '-'), '', @$_POST["phone"]);

		if (@$_POST["uid"] != '') {
			$sql = "update i_shop_users set fam='" . $_POST["fam"] . "', name='" . $_POST["name"] . "', ";
			$sql .= "phone='" . $phone . "', city='" . $_POST["city"] . "', country='" . $_POST["country"] . "', dom='" . $_POST["dom"] . "', kv='" . $_POST["kv"] . "', adress='" . $_POST["adress"] . "', mail='" . $_POST["email"] . "'";
			$sql .= " where id='" . intval($_POST["uid"]) . "' ";

			if (!isset($_COOKIE["uid"])){
				$send_sms = 1;
			}

		} else {


			$sql = "select * from i_shop_users where phone='" . $phone . "' order by id desc limit 1";
			$user = A::$db->get($sql, 1);

			if (isset($user["id"])) {

				if (@$user["active"] == 1) {
					$sql = "update i_shop_users set fam='" . $_POST["fam"] . "', name='" . $_POST["name"] . "', ";
					$sql .= "phone='" . $phone . "', city='" . $_POST["city"] . "', country='" . $_POST["country"] . "', dom='" . $_POST["dom"] . "', kv='" . $_POST["kv"] . "', adress='" . $_POST["adress"] . "', mail='" . $_POST["email"] . "'";
					$sql .= " where id='" . intval($user["id"]) . "' ";

					if (!isset($_COOKIE["uid"])){
						$send_sms = 1;
					}

				} else {
					$sql = "update i_shop_users set fam='" . $_POST["fam"] . "', name='" . $_POST["name"] . "', ";
					$sql .= "phone='" . $phone . "', city='" . $_POST["city"] . "', country='" . $_POST["country"] . "', dom='" . $_POST["dom"] . "', kv='" . $_POST["kv"] . "', adress='" . $_POST["adress"] . "', mail='" . $_POST["email"] . "'";
					$sql .= " where id='" . intval($user["id"]) . "' ";

					$send_sms = 1;
				}
			} else {
				$sql = "insert into i_shop_users set id_section=5, version='ru', active=0, fam='" . $_POST["fam"] . "', name='" . $_POST["name"] . "', ";
				$sql .= "phone='" . $phone . "', city='" . $_POST["city"] . "', country='" . $_POST["country"] . "', dom='" . $_POST["dom"] . "', kv='" . $_POST["kv"] . "', adress='" . $_POST["adress"] . "', mail='" . $_POST["email"] . "', login='" . $_POST["email"] . "'";
				$send_sms = 1;
			}
		}


		$i = A::$db->query($sql);


		if ($send_sms == 1) {
			A::$app->user->sendSmsCode($phone);
			exit;
		}



		$sql = "select * from i_shop_users where phone='" . $phone . "' order by id desc limit 1";
		$user = A::$db->get($sql, 1);


		if ($i) {
			if (@$_POST["uid"] != '') {
				$id_user = $user["id"];
			} else {
				$id_user =  $user["id"];
			}

			
			$_SESSION["uid"] = $id_user;

            setcookie(
                "uid",
                $id_user,
                time() + 7776000,
                '/',
                str_replace('www.', '', '.' . $_SERVER["HTTP_HOST"])
            );


			$sql = "insert into i_payments set id_user=$id_user, paid=0, delivery='" . $_POST["delivery"] . "', info_delivery='" . $_POST["info"] . "', ";
			$sql .= "payment='" . $_POST["payment"] . "', phone='" . $_POST["phone"] . "', email='" . $_POST["email"] . "'";


			$i = A::$db->query($sql);

			if ($i) {
				$id_order = A::$db->insert_id;

				$msg = '<table border="1" width="100%" cellspacing="0" cellpadding="0">';
				$msg .= '<tr>';
				$msg .= '<td align="center" width="20" style="padding:5px;"><strong>№</strong></td>';
				$msg .= '<td align="left" style="padding:5px;"><strong>Наименование</strong></td>';
				$msg .= '<td align="center" style="padding:5px;"><strong>Кол-во</strong></td>  ';
				$msg .= '<td align="center" style="padding:5px;" ><strong>Цена</strong></td>  ';
				$msg .= '</tr>';
				$cost = 0;
				$shopGoods = array();
				$elements_mass = A::$app->cart->get_all();
				for ($i = 0; $i < sizeof($elements_mass); $i++) {
					$id = $elements_mass[$i]['id'];
					$count = $elements_mass[$i]['count'];
					$param = str_replace('color:', 'Цвет:', $elements_mass[$i]['params']);
					$result = A::$db->query(" SELECT * FROM `i_cat` WHERE `id`='$id' ");
					$myrow = $result->fetch_array();

					/*if ($elements_mass[$i]['params']!=''){
						$par = explode(',', trim($elements_mass[$i]['params'], ','));
						$size = explode(':', @$par[0]);

						if ($myrow["form"]!=''){
							$p1 = explode("\n", $myrow["form"]);
							if (sizeof($p1)>0){
								foreach ($p1 as $p) {
									$all = explode(';', $p);
									if (@$all[0] == @$size[1]){
										$myrow["price"] = str_replace(' ', '', @$all[1]);
									}
								}
							}
						}
					}*/


					if ($myrow["active"] == 1) {
						$cena = price($myrow["price"]) * $elements_mass[$i]['count'];

						$cost = $cost + $cena;
						$msg .= '<tr>';
						$msg .= '<td class="name name3_padd" style=" width:20px; padding:5px;">' . ($i + 1) . '</td>';
						$msg .= '<td class="name name3_padd">' . str_replace("'", "&acute;", $myrow1['name']) . ' ' . str_replace("'", "&acute;", $myrow['name']) . '';
						if ($param != '') {
							$msg .= '<p style="margin-top: -20px;">' . str_replace("\n", '<br>', $param) . '</p>';
						}
						$msg .= '</td><td align="center" class="s_cart_td">' . $elements_mass[$i]['count'] . '</td>  ';
						$msg .= '<td align="center" class="s_cart_td"><span class="productSpecialPrice">' . $cena . ' ₸</span></td>';
						$msg .= '</tr>';

						$shopGoods[] = array(
							'id' => $id,
							'name' =>  $myrow['name'],
							'params' => $elements_mass[$i]['params'],
							'price' =>  $cena,
							'count' =>  $elements_mass[$i]['count'],
							'art' =>  $myrow['art'],
						);
					}
				}

				$msg .= '<tr>';
				$msg .= '<td colspan="3" align="right" class="cart_total_left" style="text-align: right">Стоимость заказа:</td>';
				$msg .= '<td  align="center" class="cart_total_right main">';
				$msg .= '<span class="productSpecialPrice">' . round($cost, 0) . ' ₸</span>';
				$msg .= '</td>';
				$msg .= '</tr>';

				$msg .= '<tr>';
				$msg .= '<td colspan="3" align="right" class="cart_total_left" style="text-align: right">Стоимость доставки:</td>';
				$msg .= '<td  align="center" class="cart_total_right main">';
				$msg .= '<span class="productSpecialPrice">' . round(@$_POST["delivery_price1"], 0) . ' ₸</span>';
				$msg .= '</td>';
				$msg .= '</tr>';

				$promo = 0;
				foreach (@$_POST["promo_name"] as $k => $v) {
					$promo = $promo + @$_POST["promo_value"][$k];
				}

				if ($promo > 0) {
					$msg .= '<tr>';
					$msg .= '<td colspan="3" align="right" class="cart_total_left">Скидка по промокоду:</td>';
					$msg .= '<td  align="center" class="cart_total_right main">';
					$msg .= '<span class="productSpecialPrice">' . round(@$promo, 0) . ' ₸</span>';
					$msg .= '</td>';
					$msg .= '</tr>';
				}

				$msg .= '<tr>';
				$msg .= '<td colspan="3" align="right" class="cart_total_left" style="text-align: right">Итого:</td>';
				$msg .= '<td  align="center" class="cart_total_right main">';
				$msg .= '<span class="productSpecialPrice">' . round($cost + @$_POST["delivery_price1"] - $promo, 0) . ' ₸</span>';
				$msg .= '</td>';
				$msg .= '</tr>';
				$msg .= '</table>';


				$_SESSION["BITRIX_AMOUNT"] = round($cost, 0) + round(@$_POST["delivery_price1"], 0)  - $sale;

				$contact_data = executeREST(
					array(
						'fields' => array(
							"NAME" => @$_POST["name"],
							"OPENED" => "Y",
							"ASSIGNED_BY_ID" => 1,
							"TYPE_ID" => "CLIENT",
							'SOURCE_ID' => 'WEB',
							"PHONE" => array(
								array(
									"VALUE" => @$_POST["phone"],
									"VALUE_TYPE" => "WORK"
								)
							),
							"EMAIL" => array(
								array(
									"VALUE" => @$_POST["email"],
									"VALUE_TYPE" => "WORK"
								)
							),
						)
					),
					'crm.contact.add'
				);

				$deal_data = executeREST(
					array(
						'fields' => array(
							"TITLE" => "Заказ из интернет магазина № " . $id_order . "",
							"TYPE_ID" => "GOODS",
							"STAGE_ID" => "NEW",
							"CONTACT_ID" => $contact_data["result"],
							"OPENED" => "Y",
							"ASSIGNED_BY_ID" => 1,
							"PROBABILITY" => 30,
							"CURRENCY_ID" => "KZT",
							"OPPORTUNITY" => round($cost, 0) + round(@$_POST["delivery_price1"], 0)  - $sale,
							"BEGINDATE" => date('Y-m-d H:i:s'),
						)
					),
					'crm.deal.add'
				);

				$row_array = array();

				foreach ($shopGoods as $key => $r) {
					$row_array[] = array(
						"PRODUCT_ID" => $r["id"],
						"PRICE" => $r["price"],
						"QUANTITY" => $r["count"],
						"PRODUCT_NAME" => $r["name"] . ' (' . $r["params"] . ')',
					);
				}

				if (round(@$_POST["delivery_price1"], 0) > 0) {

					$row_array[] = array(
						"PRODUCT_ID" => '1',
						"PRICE" => round(@$_POST["delivery_price1"], 0),
						"QUANTITY" => 1,
						"PRODUCT_NAME" => 'Доставка',
					);
				}

				if (round(@$sale, 0) > 0) {

					$row_array[] = array(
						"PRODUCT_ID" => '1',
						"PRICE" => round(@$sale, 0),
						"QUANTITY" => 1,
						"PRODUCT_NAME" => 'Промокод: ' . @join(', ', @$_POST["promo_name"]),
					);
				}

				$row_data = executeREST(
					array(
						'id' => $deal_data["result"],
						'rows' => $row_array
					),
					'crm.deal.productrows.set'
				);


				$sql = "update i_payments set amount='" . round($cost, 0) . "', dost='" . round(@$_POST["delivery_price1"], 0) . "', sale='" . $promo . "', promo='" . @join(', ', @$_POST["promo_name"]) . "' where id='" . $id_order . "'";
				A::$db->query($sql);

				foreach ($shopGoods as $key => $r) {
					$sql = "insert into i_pay_goods set id_section='$id_order', id_good='" . $r["id"] . "', name='" . $r["name"] . "', art='" . $r["art"] . "', ";
					$sql .= "kol='" . $r["count"] . "', price='" . $r["price"] . "',  param='" . $r["params"] . "'";
					A::$db->query($sql);
				}


				$orderClass = new stdClass();
				$orderClass->id = $id_order;
				$orderClass->amount = round($cost - $promo, 0);
				$orderClass->dost = round(@$_POST["delivery_price1"], 0);
				$orderClass->delivery = $_POST["delivery"];
				$orderClass->phone = str_replace(array('(', ')', ' ', '-'), '', $_POST["phone"]);
				$orderClass->email = $_POST["email"];


				$_SESSION['user_basket'] = '';

				A::$app->cart->clear();

				$textMsg = ML::msg(1);



				$textMsg["text"] = str_replace('{{goods}}', $msg, $textMsg["text"]);
				$textMsg["text"] = str_replace('{{site}}', $_SERVER["HTTP_HOST"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{name}}', $_POST["name"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{fam}}', $_POST["fam"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{country}}', $_POST["country"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{dom}}', $_POST["dom"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{kv}}', $_POST["kv"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{order}}', $id_order, $textMsg["text"]);
				$textMsg["text"] = str_replace('{{phone}}', $_POST["phone"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{email}}', $_POST["email"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{delivery}}', $_POST["delivery"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{payment}}', $_POST["payment"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{city}}', $_POST["city"], $textMsg["text"]);
				$textMsg["text"] = str_replace('{{adress}}', $_POST["adress"], $textMsg["text"]);

				$sql = "insert into i_pay_mail set order_id='".$id_order."', message='".$textMsg["text"]."', theme='".str_replace('{{site}}', $_SERVER["HTTP_HOST"], $textMsg["title"])."', email='".$_POST["email"]."'";
				A::$db->query($sql);
				$send = 1;
			} else {
				$error = 'Ошибка добавления заказа';
			}
		} else {
			$error = 'Ошибка добавления пользователя';
		}

		if ($send) {


			$data = [
				'amount' => round($cost - $promo, 0) + round(@$_POST["delivery_price1"], 0),
				'reference' => $id_order,
				'description' => 'Оплата заказа №' . $id_order . ' на сайте joi.kz',
				'success_url' => 'https://joi.kz/payment-success/?id=' . $id_order,
				'failure_url' => 'https://joi.kz/payment-failure/?id=' . $id_order,
				'params' => [
					'flag_get_url' => 1,
					'user_id' => $id_user,
					'user_phone' => @$_POST["phone"],
					'user_email' => @$_POST["email"]
				]
			];

			$result = Payment::init()->setData($data, Payment::PAY)->send();

			$data = Payment::parseResponseData($result["data"]);

			$success = 1;
			echo json_encode(
				array(
					'success' => $success,
					'payment' => isset($data->url) ? $data->url : '',
					'result' => ['phone' => 0],
					'error' => $error,

				)
			);
		} else {
			if (isset($error) && $error != '') {
				$error = $error;
			} else {
				$error = A::$app->shop->t('error_order');
			}

			echo json_encode(
				array(
					'success' => $success,
					'result' => ['phone' => 0],
					'error' => $error,
				)
			);
		}
		exit;
	}
}
exit;
