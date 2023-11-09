<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/libs/Payment.php");
$data = [
    'reference' => A::$app->get('id'),
];

$result = Payment::init()->setData($data, Payment::PAY_STATUS)->send();

$data = Payment::parseResponseData($result["data"]);

if (isset($data->items[0]->status) && $data->items[0]->status == 3) {
    $sql = "update i_payments set pay=1, paid=11 where id='" . A::$app->get('id') . "'";
    A::$db->query($sql);

    $sql = "select * from i_pay_mail where order_id='" . A::$app->get('id') . "'";
    $order = A::$db->get($sql, 1);

    if ($order["send"] == 0) {

        $textMsg = ML::msg(1);

        ML::s(
            $order["email"],
            $order["theme"],
            $order["message"],
            $textMsg["email"],
            $textMsg["doobel"]
        );

        $sql = "update i_pay_mail set send=1 where order_id='" . A::$app->get('id') . "'";
        A::$db->query($sql);
    }
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
                <div class="cart py-5">

                    <div id="thankyou" style="border:1px solid #ccc; padding:20px 35px 20px 35px; font-size:14px;">
                        <p><span style="color: green; font-weight: bold; font-size: 14px;">Ваш заказ успешно оплачен!</span></p>
                        <p>Спасибо Вам за совершенную покупку в нашем интернет-магазине. Мы сделаем все возможное, чтобы оправдать оказанное доверие.</p>
                        <p><strong>На Ваш email адрес</strong> отправлено письмо с полной информацией о Вашем заказе. Мы быстро обработаем Вашу заявку.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<? $this->footer() ?>