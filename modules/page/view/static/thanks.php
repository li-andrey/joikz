<? 
$cat = A::$app->catalog->category(6);
$this->meta(
    array(
        'h1' => 'Оформление заказа',
        'title' => 'Оформление заказа',
    )
);
$this->header();
?>
<style type="text/css">
.filter>li>a:after{
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
                <b style="float: right;"><?=A::$app->cart->count()?></b>
            </a>

        </li>
    </ul>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="cart py-5">

                    <div id="thankyou" style="border:1px solid #ccc; padding:20px 35px 20px 35px; font-size:14px;">
                        <p><span style="color: green; font-weight: bold; font-size: 14px;">Ваш заказ успешно отправлен!</span></p>
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