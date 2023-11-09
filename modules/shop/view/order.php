<div class="modal fade" id="modal-sms-code">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-size:1rem">Подтверждение номера телефона</h4>
                <button onclick="location.href='/'" type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" class="sms-form code-form" id="sms-form">
                    <div id="register-result-code"></div>
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control text-center" maxlength="4" name="code" required="" placeholder="Введите код из SMS">
                            </div>
                            
                            <button type="btn" class="btn btn-primary mt-3 btn-block btn-check-code" disabled="">Отправить</button>
                            <div class=" text-center d-block mt-3 " style="font-size:13px;">
                                При подтверждении номера телефона, вы соглашаетесь с договором <a href="/publichnaya-oferta-6/" target="_blank">публичной оферты</a>
                            </div>
                            <button type="button" class="btn btn-link btn-block send-replay disabled" disabled="">
                                Отправить код повторно <span>(60)</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style type="text/css">
    .filter>li>a:after {
        content: '' !important
    }

    .cart hr {
        margin: 1.5rem 0;
        border-color: #2e2e2e;
    }

    header .navbar.fixed-top {
        border-bottom: 1px solid #000;
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
            <div class="col-md-12">
                <div class="cart py-3 pb-5">
                    <? if (@$_POST['step'] == 1 || @$_POST['step'] == '') { ?>

                        <form method="post" action="">
                            <input type="hidden" name="step" value="2">

                            <?
                            $sql = "select * from i_shop_users where id='" . @A::$app->user->id . "'";
                            $user = A::$db->get($sql, 1);
                            ?>

                            <div class="portfolio">
                                <div class="">
                                    <div class="row align-items-stretch mamma">
                                        <div class="col-lg-8">
                                            <div class="card card-block">
                                                <div class=" row">
                                                    <div class="col-md-12 padding-off">
                                                        <h2>Персональные данные</h2>
                                                    </div>
                                                </div>
                                                <div class="order-block mt-3">
                                                    <div class=" row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="" for="name">Имя <span style="color: red">*</span></label>
                                                                    <input class="form-control" type="text" value="<?= @$user["name"] ?>" id="name" name="name" required="">
                                                                </div>
                                                                <div class="col-md-12 mt-md-3 ">
                                                                    <label class="" for="name">Фамилия <span style="color: red">*</span></label>
                                                                    <input class="form-control" type="text" id="fam" value="<?= @$user["fam"] ?>" name="fam" required="">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-md-3  ">
                                                                <div class="col-md-12">
                                                                    <label class="" for="name">Номер телефона <span style="color: red">*</span></label>
                                                                    <input class="form-control" type="text" id="phone" value="<?= @$user["phone"] ?>" name="phone" required="">
                                                                </div>
                                                                <div class="col-md-12 mt-md-3 ">
                                                                    <label class="" for="name">E-mail <span style="color: red">*</span></label>
                                                                    <input class="form-control" type="email" id="email" value="<?= @$user["mail"] ?>" name="email">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-md-12">
                                                                    <label class="" for="name">Страна</label>
                                                                    <select class="form-control" type="text" autocomplete="off" id="country" name="country" required="">
                                                                        <?
                                                                        $ids = 0;
                                                                        $sql = "select * from i_shop where id_section=5 and active=1 order by id_sort asc";
                                                                        $res = A::$db->get($sql);
                                                                        foreach ($res as $key => $r) {
                                                                            if ($key == 0) $ids = $r["id"];
                                                                            if ($user["country"] == $r["name"]) $ids = $r["id"];
                                                                        ?>
                                                                            <option value="<?= $r["name"] ?>" <?= @$user["country"] == $r["name"] ? 'selected' : '' ?>><?= $r["name"] ?></option>
                                                                        <?
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12 mt-md-3 ">
                                                                    <label class="" for="name">Город</label>
                                                                    <select class="form-control" type="text" id="city" autocomplete="off" name="city" required="">
                                                                        <?
                                                                        $dost = array();
                                                                        $sql = "select * from i_shop_elements where id_section=$ids and active=1 order by id_sort asc";
                                                                        $res = A::$db->get($sql);
                                                                        foreach ($res as $key => $r) {
                                                                            if ($key == 0) $dost = explode('|', $r["dost"]);
                                                                            if (@$user["city"] == $r["name"]) $dost = explode('|', $r["dost"]);
                                                                        ?>
                                                                            <option value="<?= $r["name"] ?>" data-dost="<?= $r["price"] ?>" <?= @$user["city"] == $r["name"] ? 'selected' : '' ?>><?= $r["name"] ?></option>
                                                                        <?
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class=" row mt-md-3">
                                                                <div class="col-md-12 padding-off">
                                                                    <h2>Способ доставки</h2>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-3">
                                                                <div class="col-md-12">

                                                                    <?

                                                                    $dost_id = array();
                                                                    $dost_price = array();
                                                                    foreach ($dost as $key => $v) {
                                                                        $a = explode('-', $v);
                                                                        $dost_id[] = @$a[0];
                                                                        $dost_price[] = @$a[1];
                                                                    }

                                                                    ?>
                                                                    <? if ($payment = A::$app->shop->m(3)) { ?>
                                                                        <div class="">
                                                                            <div class="dost-variant">
                                                                                <?
                                                                                $res = A::$app->shop->elements($payment["id"]);
                                                                                foreach ($res as $key => $r) {
                                                                                    if (in_array($r["id"], $dost_id)) {
                                                                                        $price = array_search($r["id"], $dost_id);
                                                                                ?>
                                                                                        <div class=" radio-input padding-off">
                                                                                            <label for="delivery" style="font-weight: normal; display: flex;"><input style="float: left;
                                                                                            margin: 4px 10px 5px;   min-height: 15px;  min-width: 15px;" id="delivery<?= $r["id"] ?>" name="delivery" type="radio" <?= $key == 0 ? 'checked=""' : '' ?> value="<?= $r["name"] ?>" data-price="<?= $dost_price[$price] ?>"> <?= $r["name"] ?> (<?= $dost_price[$price] === 0 ? 'бесплатно' : $dost_price[$price] . ' ₸' ?>)</label>
                                                                                        </div>
                                                                                    <? } ?>
                                                                                <? } ?>
                                                                            </div>
                                                                        </div>
                                                                    <? } ?>

                                                                </div>
                                                            </div>
                                                            <div class="delivery-input d-none">
                                                                <div class=" row mt-md-3">
                                                                    <div class="col-md-12 padding-off">
                                                                        <h2>Адрес доставки</h2>
                                                                    </div>
                                                                </div>



                                                                <div class="row mt-md-3">
                                                                    <div class="col-md-12">
                                                                        <label class="" for="name">Улица <span style="color: red"></span></label>
                                                                        <input class="form-control" type="text" id="adress" name="adress" value="<?= @$user["adress"] ?>">
                                                                    </div>
                                                                    <div class="col-md-12 mt-md-3 ">
                                                                        <label class="" for="name">Дом</label>
                                                                        <input class="form-control" type="text" id="dom" name="dom" value="<?= @$user["dom"] ?>">
                                                                    </div>
                                                                    <div class="col-md-12 mt-md-3 ">
                                                                        <label class="" for="name">Квартира</label>
                                                                        <input class="form-control" type="text" id="kv" name="kv" value="<?= @$user["kv"] ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class=" row mt-md-3 d-none">
                                                                <div class="col-md-12 padding-off">
                                                                    <h2>Способ оплаты</h2>
                                                                </div>
                                                            </div>
                                                            <div class=" row mt-3 d-none">
                                                                <div class="col-md-12">

                                                                    <? if ($payment = A::$app->shop->m(4)) { ?>
                                                                        <div class="">
                                                                            <div>
                                                                                <?
                                                                                $res = A::$app->shop->elements($payment["id"]);
                                                                                foreach ($res as $key => $r) {

                                                                                ?>
                                                                                    <div class=" radio-input padding-off">
                                                                                        <label style="font-weight: normal;"><input style="float: left;
                                                                                    margin: 4px 10px 5px;     min-width: 15px; min-height: 15px;" id="payment" name="payment" type="radio" <?= $key == 0 ? 'checked=""' : '' ?> value="<?= $r["name"] ?>"> <?= $r["name"] ?></label>
                                                                                    </div>

                                                                                <? } ?>
                                                                            </div>
                                                                        </div>
                                                                    <? } ?>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-3">
                                                                <div class="col-md-12">
                                                                    <label class="" for="name">Примечание</label>
                                                                    <textarea class="form-control" id="info" name="info" rows="3"></textarea>
                                                                </div>

                                                            </div>
                                                            <div class="row mt-3 mb-5">
                                                                <div class="col-md-8">
                                                                    <label class="" for="name">Промокод</label>
                                                                    <input class="form-control" type="text" id="promocode" name="promocode" value="<?= @$_POST["value"] ?>">
                                                                </div>
                                                                <div class="col-md-4  ">
                                                                    <label class="d-none d-sm-block" for="name">&nbsp;</label>
                                                                    <button type="button" class="btn btn-outline-primary  btn-block promocode-btn">
                                                                        Применить
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-12 ">
                                                                    <div id="promocode-result">
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-12 mt-4  d-none d-sm-block">

                                                                    <button type="submit" class="btn btn-primary  btn-block ">
                                                                        Продолжить
                                                                    </button>
                                                                </div>


                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4" id="sidebar">
                                            <div class="card card-block mt-md-3" style="background-color: rgba(0,0,0,0.05); border-radius: 0px; padding: 1.5rem 15px;">
                                                <div class="order-info" id="order-info">
                                                    <h3>Информация о заказе</h3>
                                                    <hr class="my-4">
                                                    <div class="row ">
                                                        <div class="col-md-6 col-6">Стоимость</div>
                                                        <div class="col-md-6 col-6" style="text-align: right;"><b id="itogo"></b></div>
                                                    </div>
                                                    <div class="row  ">
                                                        <div class="col-md-6 col-6">Доставка</div>
                                                        <div class="col-md-6 col-6" style="text-align: right;"><b id="dost">-</b></div>
                                                    </div>
                                                    <div class="promocodes" id="promocodes">

                                                    </div>
                                                    <hr class="my-4">
                                                    <h3>
                                                        <div class="row">
                                                            <div class="col-md-6 col-6">Итого</div>
                                                            <div class="col-md-6 col-6" style="text-align: right;"><b id="amount"></b></div>
                                                        </div>
                                                    </h3>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 d-block d-sm-none">
                                            <label class="" for="name">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary  btn-block ">
                                                Продолжить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="delivery_price" value="0">
                        </form>
                        <div id="basket_protocol_order" class="protocol" style="display: none;"></div>
                        <div id="basket_place_order" style=" width: 100%; display: none;"></div>
                    <? } ?>

                    <? if (@$_POST['step'] == 2) { ?>
                        <form method="post" data-action="/thanks/" id="order_form">
                            <input type="hidden" name="do" value="send_order">
                            <input type="hidden" name="step" value="3">
                            <input type="hidden" name="uid" value="<?= @A::$app->user->id ?>">
                            <input type="hidden" name="name" value="<?= $ob->pr($_POST["name"]) ?>">
                            <input type="hidden" name="fam" value="<?= $ob->pr($_POST["fam"]) ?>">
                            <input type="hidden" name="phone" value="<?= $ob->pr($_POST["phone"]) ?>" class="phone-code">
                            <input type="hidden" name="email" value="<?= $ob->pr($_POST["email"]) ?>">
                            <input type="hidden" name="adress" value="<?= $ob->pr($_POST["adress"]) ?>">
                            <input type="hidden" name="dom" value="<?= $ob->pr($_POST["dom"]) ?>">
                            <input type="hidden" name="kv" value="<?= $ob->pr($_POST["kv"]) ?>">
                            <input type="hidden" name="country" value="<?= $ob->pr($_POST["country"]) ?>">
                            <input type="hidden" name="city" value="<?= $ob->pr($_POST["city"]) ?>">
                            <input type="hidden" name="delivery" value="<?= $ob->pr($_POST["delivery"]) ?>">
                            <input type="hidden" name="delivery_price1" value="<?= $ob->pr($_POST["delivery_price"]) ?>">
                            <input type="hidden" name="payment" value="<?= $ob->pr($_POST["payment"]) ?>">
                            <input type="hidden" name="info" value="<?= $ob->pr($_POST["info"]) ?>">
                            <input type="hidden" name="bonus" value="<?= $ob->pr($_POST["bonus"]) ?>">
                            <div class="promocodes" id="promocodes">
                                <? if (isset($_POST["promo_name"]) && is_array($_POST["promo_name"])) { ?>
                                    <? foreach (@$_POST["promo_name"] as $k => $v) {
                                        $html = '<input type="hidden" name="promo_id[]" value="' . @$_POST["promo_id"][$k] . '">';
                                        $html .= '<input type="hidden" name="promo_name[]" value="' . $v . '">';
                                        $html .= '<input type="hidden" name="promo_value[]" class="promo-input" value="' . @$_POST["promo_value"][$k] . '">';
                                        echo $html;
                                    } ?>
                                <? } ?>
                            </div>


                            <div class="">
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div>
                                            <h2>Персональные данные</h2>
                                            <div class="order-block py-2" style="font-size:14px;">
                                                <div class=" row ">
                                                    <div class="col-md-12">
                                                        <b style="font-weight: 400; color: #717171;">ФИО:</b>
                                                        <?= $ob->pr($_POST["fam"]) ?> <?= $ob->pr($_POST["name"]) ?>
                                                    </div>
                                                </div>
                                                <div class=" row mt-1">
                                                    <div class="col-md-12">
                                                        <b style="font-weight: 400; color: #717171;">E-mail:</b>
                                                        <?= $ob->pr($_POST["email"]) ?>
                                                    </div>
                                                </div>
                                                <div class=" row mt-1">
                                                    <div class="col-md-12">
                                                        <b style="font-weight: 400; color: #717171;">Телефон:</b>
                                                        <?= $ob->pr($_POST["phone"]) ?>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <h2>Доставка и оплата</h2>
                                            <div class="order-block  py-2" style="font-size:14px;">
                                                <div class=" row ">
                                                    <div class="col-md-12">
                                                        <b style="font-weight: 400; color: #717171;">Адрес доставки:</b> <?= $ob->pr($_POST["city"]) ?><?= $_POST["adress"] != '' ? ', ' . $ob->pr($_POST["adress"]) : '' ?><?= @$_POST["dom"] != '' ? ' ' . $ob->pr($_POST["dom"]) : '' ?><?= $_POST["kv"] != '' ? ', кв ' . $ob->pr($_POST["kv"]) : '' ?>
                                                    </div>
                                                </div>

                                                <div class=" row mt-1">
                                                    <div class="col-md-12">
                                                        <b style="font-weight: 400; color: #717171;">Способ доставки:</b> <?= $ob->pr($_POST["delivery"]) ?>
                                                    </div>
                                                </div>

                                                <div class=" row mt-1">
                                                    <div class="col-md-12">
                                                        <b style="font-weight: 400; color: #717171;">Способ оплаты:</b> <?= $ob->pr($_POST["payment"]) ?>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" mt-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2>Заказанные товары</h2>
                                        <div class="order-block  p-3">
                                            <div id="basket_protocol_order" class="protocol"></div>
                                            <div id="basket_place_order" style=" width: 100%; "></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card card-block">
                                            <div class="order-info ">
                                                <h2>Итого</h2>
                                                <div class="order-block  ">
                                                    <div class="row mt-md-3">
                                                        <div class="col-md-6 col-6">Стоимость</div>
                                                        <div class="col-md-6 col-6" style="text-align: right;"><b id="itogo"></b></div>
                                                    </div>
                                                    <div class="row  mt-md-3 mb-md-3">
                                                        <div class="col-md-6 col-6">Доставка</div>
                                                        <div class="col-md-6 col-6" style="text-align: right;"><b id="dost2">-</b></div>
                                                    </div>
                                                    <div class="row mt-md-3 mb-md-3 sale2" style="display: none;">
                                                        <div class="col-md-6 col-6">
                                                            Скидка по промокоду
                                                        </div>
                                                        <div class="col-md-6 col-6" style="text-align: right;">
                                                            <b id="sale2"></b>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 col-6">Сумма</div>
                                                        <div class="col-md-6 col-6" style="text-align: right;"><b id="amount2"></b></div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="cusmo-btn btn btn-primary btn-block py-0  mt-4" style="height: 60px; line-height: 60px;">
                                                    Оплатить
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <? } ?>

                </div>
            </div>
        </div>
    </div>
</div>