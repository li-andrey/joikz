<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/libs/Payment.php");
$data = [
    'reference' => A::$app->get('id'),
];

$result = Payment::init()->setData($data, Payment::PAY_STATUS)->send();

$data = Payment::parseResponseData($result["data"]);

if(isset($data->items[0]->status) && $data->items[0]->status==3){
	$sql = "update i_payments set pay=1 where id='".A::$app->get('id')."'";
	A::$db->query($sql);
}

$this->meta(
	array(
		'h1' => 'Оформление заказа',
		'title' => 'Оформление заказа',
	)
);
$this->header();
?>
<style type="text/css">
	.filter>li>a:after {
		display: none;
	}

	.cart hr {
		margin: 1.5rem 0;
		border-color: #2e2e2e;
	}
</style>
<div class="content order">
	<ul class="filter">
		<li>
			<a href="">
				<span class="text-uppercase">Оформление заказа</span>
				<b style="float: right;"><?= A::$app->cart->count() ?></b>
			</a>

		</li>
	</ul>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="cart text-center py-5">

					<div id="thankyou" style="border:1px solid #ccc; padding:20px 35px 20px 35px; font-size:14px;">
						<p><span style="color: red; font-weight: bold; font-size: 14px;">Ваш заказ не оплачен!</span></p>
						<p>Во время проведения платежа, произошла ошибка, попробуйте еще раз</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<? $this->footer() ?>