<? 
$this->meta(
	array(
		'h1' => 'История заказов',
		'title' => 'История заказов',
		'descr' => '',
		'keyw' => '',
	)
);
$this->header();
?>
    <style>

        .col-main{
            width: 100%;
        }
        .main-container #main-menu {
            top: -24px;
        }
    </style>
    <div class="std">
        <div class="cart">
           
            <div class="std">
				<?
				if (A::$app->user->check_auth()){
					if (!isset(A::$url->get["id"])){

						$sql = "select * from i_shop_users where id='".A::$app->user->info["id"]."'";
						$res = A::$db->get($sql, 1);

						$sql = "select * from i_payments where id_user='".A::$app->user->info["id"]."' order by id desc";
						$res = A::$db->get($sql);
						if (sizeof($res)==0){
							?>
							<div class="row">
								<div class="col-md-12">
									К сожалению, ваш список заказов пуст.       <br><br><br><br><br>     
								</div>
							</div>
							<?
						}else{
							?>
							<div class="row">
								<div class="col-md-1" style="text-align: center;padding: 10px 0px;"><b>ID</b></div>
								<div class="col-md-2" style="text-align: center;padding: 10px 0px;"><b>Дата</b></div>
								<div class="col-md-5" style="text-align: center;padding: 10px 0px;"><b>Товары</b></div>
								<div class="col-md-2" style="text-align: center;padding: 10px 0px;"><b>Сумма</b></div>
								<div class="col-md-2" style="text-align: center;padding: 10px 0px;"><b>Статус</b></div>
							</div>
							<style>
								.tbl>div>span{ display: block; padding: 20px 0px; text-align: center; font-size: 14px; }
							</style>
							<?
							foreach ($res as $key => $r) {
								switch($r['paid']){
									case '0':
									$act = '<strong>Новый</strong>';
									break;
									case '1':
									$act = '<strong>Принятый</strong>';
									break;
									case '2':
									$act = 'Закрытый';
									break;
									case '3':
									$act = 'Отмененный';
									break;
									case '4':
									$act = 'Без оплаты';
									break;
									case '5':
									$act = 'Оплачен';
									break;
									case '11':
									$act = 'Измененный';
									break;
									case '10':
									$act = 'Оплачен картой';
									break;
								}
								?>
								<div class="row" style="background: #fff; border-top: 1px solid #f1f1f1;">
									<div class="col-md-1"><div style="padding:20px 10px; text-align: center;"><?=$r["id"]?></div></div>
									<div class="col-md-2"><div style="padding:20px 10px; text-align: center;"><?=A::$api->date($this->lang, $r["timestamp_x"], 'sql', 'date')?></div></div>
									<div class="col-md-5">
										<?
										$sql = "select * from i_pay_goods where id_section='".$r["id"]."'";
										$ar = A::$db->get($sql);
										foreach($ar as $k=>$v)
										{
											if($v["name"]!="")
											{
												$price = ($v["price"]);
												$count = intval($v["kol"]);
												$summa = $price * $count;
												$sql = "select * from i_cat_elements where id='".$v["id_good"]."'";
												$r1 = A::$db->get($sql, 1);
												?>
												<div class="row">
													<div class="col-md-2" style="line-height: 22px; padding: 10px 0px;">
														<a href="<?=LINK?>catalog/<?=@$r1["url"]?>/"><img src="/upload/images/small/<?=$r1["image"]?>" style="max-width: 100%;"></a>
													</div>
													<div class="col-md-10">
														<div style="line-height: 22px; font-size: 14px; padding: 10px 0px;">
															<b><?=$r1["name"]?></b><br>
															Кол-во: <?=$count?><br>
															Сумма: <?=$summa?><br>
														</div>
													</div>
												</div>

												<?

											}

										}
										?>

									</div>
									<div class="col-md-2"><div style="padding:20px 10px; text-align: center;"><?=($r["amount"])?></div></div>
									<div class="col-md-2"><div style="padding: 20px 10px; text-align: center;"><?=$act?></div></div>
									
								</div>
								<?
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
				}else{
					$sql = "select * from i_payments where id='".A::$url->get["id"]."' and id_user='".A::$app->user->info->id."'";
					$res = A::$db->get($sql, 1);

					$sql1 = "select * from i_shop_users where id='".$res["id_user"]."'";
					$res1 = A::$db->get($sql1, 1);
					?>
					<div class="row">
						<div class="col-md-12">
							<h2>Заказ №<?=$res["id"]?>. Дата заказа: <?=date('d.m.Y H:i', strtotime($res["timestamp_x"]))?></h2>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<h4 style="background: #f0f0f0; padding: 8px 20px 7px; margin-bottom: 10px;">Информация о заказе</h4>
							<p class="  name padding-off" style="font-weight: 400;"><b>Имя, фамилия:</b> <?=$res1["name"]?> <?=$res1["fam"]?></p>
							<p class="  name padding-off" style="font-weight: 400;"><b>Номер телефона:</b> <?=$res1["phone"]?></p>
							<p class="  name padding-off" style="font-weight: 400;"><b>E-mail: </b><?=$res1["mail"]?></p>
							<p class="  name padding-off" style="font-weight: 400;"><b>Город:</b> <?=$res1["city"]?></p>
							<p class="  name padding-off" style="font-weight: 400;"><b>Адрес доставки:</b> <?=$res1["adress"]?></p>
							<p class="  name padding-off" style="font-weight: 400;"><b>Способ доставки:</b> <?=$res["delivery"]?></p>
							<p class="  name padding-off" style="font-weight: 400;"><b>Способ оплаты:</b> <?=$res["payment"]?></p>
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
			}else{

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

	<? $this->footer() ?>