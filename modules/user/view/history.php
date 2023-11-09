<style type="text/css">
	.input-group {
		position: relative;
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		-ms-flex-align: stretch;
		align-items: stretch;
		width: 100%
	}

	.input-group-addon {
		width: 40px;
		height: calc(1.5em + .75rem + 2px);
		margin-bottom: 0;
		font-size: 1rem;
		font-weight: 400;
		line-height: calc(1.5em + .75rem + 2px);
		color: #b7bec5;
		text-align: center;
		border: 1px solid #000;
		border-right: 0;
		border-radius: 0 !important;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	hr {
		border-color: #000 !important;
	}

	.input-group-addon svg {
		width: 20px;
		height: 20px;
		fill: #000;
	}

	.btn-link {
		color: #000 !important;
	}
</style>
<ul class="filter">
	<li>
		<a href="">
			<span class="text-uppercase"><?= $this->h1 ?></span>
		</a>

	</li>
</ul>
<div class="std">
	<div class="cart">

		<div class="container py-4">
			<?
			if (A::$app->user->auth()) {
				if (!isset(A::$url->get["id"])) {

					$sql = "select * from i_shop_users where id='" . A::$app->user->id . "'";
					$res = A::$db->get($sql, 1);

					$sql = "select * from i_payments where email='" . A::$app->user->mail . "' order by id desc";
					$res = A::$db->get($sql);
					if (sizeof($res) == 0) {
			?>
						<div class="row">
							<div class="col-md-12 text-center">
								<br><br><br><br>
								К сожалению, ваш список заказов пуст.
								<br><br><br><br><br>
							</div>
						</div>
					<?
					} else {
					?>
						<div class="row d-none d-md-flex">
							<div class="col-md-1" style="text-align: center;padding: 10px 15px;"><b>ID</b></div>
							<div class="col-md-2" style="text-align: center;padding: 10px 15px;"><b>Дата</b></div>
							<div class="col-md-5" style="text-align: left;padding: 10px 15px;"><b>Товары</b></div>
							<div class="col-md-2" style="text-align: center;padding: 10px 15px;"><b>Сумма</b></div>
							<div class="col-md-2" style="text-align: center;padding: 10px 15px;"><b>Статус</b></div>
						</div>
						<style>
							.tbl>div>span {
								display: block;
								padding: 20px 0px;
								text-align: center;
								font-size: 14px;
							}
						</style>
						<?
						foreach ($res as $key => $r) {
							switch ($r['paid']) {
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
							$sql = "select * from i_pay_goods where id_section='" . $r["id"] . "'";
							$ar = A::$db->get($sql);
							if (sizeof($ar) > 0) {
						?>
								<div class="row <?=$key==0?'border-top-0':''?> border-top-lg-1" style="background: #fff; border-top: 1px solid #000;">
									<div class="col-md-1 d-none d-md-block">
										<div style="padding:20px 10px; text-align: center;"><?= $r["id"] ?></div>
									</div>
									<div class="col-md-2 d-none d-md-block">
										<div style="padding:20px 10px; text-align: center;"><?= A::$api->date($this->lang, $r["timestamp_x"], 'sql', 'date') ?></div>
									</div>
									<div class="col-md-5">
										<div class="row d-flex d-md-none" style="padding-top: 10px; ">
											<div class="col-6" style=" text-align: left;">
												<b>Заказ №<?= $r["id"] ?></b>
											</div>
											<div class="col-6" style=" text-align: right;">
												<b><?= A::$api->date($this->lang, $r["timestamp_x"], 'sql', 'date') ?></b>
											</div>
										</div>
										<?

										foreach ($ar as $k => $v) {
											if ($v["name"] != "") {
												$price = ($v["price"]);
												$count = intval($v["kol"]);
												$summa = $price * $count;
												$sql = "select * from i_cat where id='" . $v["id_good"] . "'";
												$r1 = A::$db->get($sql, 1);
										?>
												<div class="row">
													<div class="col-md-2 col-4" style="line-height: 22px; padding-top: 10px; padding-bottom: 10px;">
														<a href="<?= LINK ?>catalog/<?= @$r1["url"] ?>/"><img src="/upload/images/small/<?= $r1["image"] ?>" style="max-width: 100%;"></a>
													</div>
													<div class="col-md-10 col-8">
														<div style="line-height: 22px; font-size: 14px; padding: 10px 0px;">
															<b><?= $v["name"] ?></b><br>
															<?= $v["param"]!=''?$v["param"].'<br>':'' ?>
															Кол-во: <?= $count ?><br>
															Сумма: <?= A::$api->price($summa, 1) ?> ₸<br>
														</div>
													</div>
												</div>

										<?

											}
										}
										?>
										<div class="row d-flex d-md-none" style="padding-bottom: 10px;">
											<div class="col-6" style=" text-align: left;">
												<b>Итого: <?= A::$api->price($r["amount"], 1) ?>₸</b>
											</div>
											<div class="col-6" style=" text-align: right;">
												<b>Статус: <?= $act ?></b>
											</div>
										</div>
									</div>
									<div class="col-md-2 d-none d-md-block">
										<div style="padding:20px 10px; text-align: center;"><?= A::$api->price($r["amount"], 1) ?>₸</div>
									</div>
									<div class="col-md-2 d-none d-md-block">
										<div style="padding: 20px 10px; text-align: center;"><?= $act ?></div>
									</div>

								</div>
						<?
							}
						}
						?>
					<?
					}
					?>


		</div>
		<div class="payment-result" id="order_form">
			<input type="hidden" name="order_id" id="order-id">
			<input type="hidden" name="order_amount" id="order-amount">
			<input type="hidden" name="email" id="order-amount">
		</div>

	<?
				} else {
					$sql = "select * from i_payments where id='" . A::$url->get["id"] . "' and id_user='" . A::$app->user->info->id . "'";
					$res = A::$db->get($sql, 1);

					$sql1 = "select * from i_shop_users where id='" . $res["id_user"] . "'";
					$res1 = A::$db->get($sql1, 1);
	?>
		<div class="row">
			<div class="col-md-12">
				<h2>Заказ №<?= $res["id"] ?>. Дата заказа: <?= date('d.m.Y H:i', strtotime($res["timestamp_x"])) ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<h4 style="background: #f0f0f0; padding: 8px 20px 7px; margin-bottom: 10px;">Информация о заказе</h4>
				<p class="  name padding-off" style="font-weight: 400;"><b>Имя, фамилия:</b> <?= $res1["name"] ?> <?= $res1["fam"] ?></p>
				<p class="  name padding-off" style="font-weight: 400;"><b>Номер телефона:</b> <?= $res1["phone"] ?></p>
				<p class="  name padding-off" style="font-weight: 400;"><b>E-mail: </b><?= $res1["mail"] ?></p>
				<p class="  name padding-off" style="font-weight: 400;"><b>Город:</b> <?= $res1["city"] ?></p>
				<p class="  name padding-off" style="font-weight: 400;"><b>Адрес доставки:</b> <?= $res1["adress"] ?></p>
				<p class="  name padding-off" style="font-weight: 400;"><b>Способ доставки:</b> <?= $res["delivery"] ?></p>
				<p class="  name padding-off" style="font-weight: 400;"><b>Способ оплаты:</b> <?= $res["payment"] ?></p>
			</div>
			<div class="col-md-8">
				<h4 style="background: #f0f0f0; padding: 8px 20px 7px; margin-bottom: 10px;">Заказанные товары</h4>
				<div id="basket_protocol_history" class="protocol"></div>
				<div id="basket_place_history" style=" width: 100%"></div>
			</div>
		</div>
		<div class="payment-result" id="order_form">
			<input type="hidden" name="order_id" id="order-id">
			<input type="hidden" name="order_amount" id="order-amount">
			<input type="hidden" name="email" id="order-amount">
		</div>
<?
				}
			} else {

				echo '
<br/><br/><br/>
<p align="center">
<b>Ошибка авторизации! Пожалуйста авторизуйтесь.</b>
</p>
<p align="center">Данная страница доступна только зарегистрированным пользователям</p>
<br/><br/><br/>
';
			}
?>
	</div>
</div>