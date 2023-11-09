<? 
$this->meta(
    array(
        'h1' => 'Оформление заказа',
        'title' => 'Оформление заказа',
        'descr' => '',
        'keyw' => '',
    )
);
$this->header();
?>
<style type="text/css">
    .page-header{
        display: none;
    }
</style>
<style type="text/css">
   
    .main-content{
      padding-top: 0px;
    }
    .maps{
      margin-bottom: 40px;
    }
  </style>
<? if (@$_POST['step']==1 || @$_POST['step']==''){ ?>

    <form method="post" action="">
        <input type="hidden" name="step" value="2">
        
        <?
        $sql = "select * from i_shop_users where id='".@A::$app->user->info["id"]."'";
        $user = A::$db->get($sql, 1);
        ?>

        <div class="portfolio">
            <div class="container">
                <div class="row ">
                    <div class="col-md-8">
                        <div class="card card-block">
                            <div class=" row">
                                <div class="col-md-12 padding-off">
                                    <h4 style="text-transform: uppercase; margin-top:4px;">Оформление заказа</h4>
                                    <hr style="margin: 15px -20px;">
                                </div>
                            </div>
                            <div class="order-block">
                                <div class=" row">
                                    <div class="col-md-12">
                                        <h4 style="margin-bottom: 2rem" >Персональные данные</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="" for="name">Имя <span style="color: red">*</span></label>
                                                <input class="form-control" type="text" value="<?=@$user["name"]?>" id="name" name="name" required="">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="" for="name">Фамилия <span style="color: red">*</span></label>
                                                <input class="form-control" type="text" id="fam" value="<?=@$user["fam"]?>" name="fam" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="" for="name">Номер телефона <span style="color: red">*</span></label>
                                                <input class="form-control" type="text" id="phone" value="<?=@$user["phone"]?>" name="phone" required="">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="" for="name">E-mail <span style="color: red"></span></label>
                                                <input class="form-control" type="email" id="email" value="<?=@$user["mail"]?>" name="email" >
                                            </div>
                                        </div>

                                        <hr>

                                        <h4 style="margin-bottom: 2rem">Заполнить адрес доставки</h4>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="" for="name">Страна</label>
                                                <select class="form-control" type="text" id="country" name="country" required="">
                                                    <?
                                                    $ids = 0;
                                                    $sql = "select * from i_shop where id_section=5 and active=1 order by id_sort asc";
                                                    $res = A::$db->get($sql);
                                                    foreach ($res as $key => $r) {
                                                        if ($key==0) $ids = $r["id"];
                                                        if ($user["country"]==$r["name"]) $ids = $r["id"];
                                                        ?>
                                                        <option value="<?=$r["name"]?>" <?=@$user["country"]==$r["name"]?'selected':''?>><?=$r["name"]?></option>
                                                        <?
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="" for="name">Город</label>
                                                <select class="form-control" type="text" id="city" name="city" required="">
                                                    <?
                                                    $dost = array();
                                                    $sql = "select * from i_shop_elements where id_section=$ids and active=1 order by id_sort asc";
                                                    $res = A::$db->get($sql);
                                                    foreach ($res as $key => $r) {
                                                        if ($key==0) $dost = explode('|',$r["dost"]);
                                                        if (@$user["city"]==$r["name"]) $dost = explode('|',$r["dost"]);
                                                        ?>
                                                        <option value="<?=$r["name"]?>" data-dost="<?=$r["price"]?>" <?=@$user["city"]==$r["name"]?'selected':''?>><?=$r["name"]?></option>
                                                        <?
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
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
                                                <? if ($payment = A::$app->wf(3, 'shop')){ ?>
                                                    <div class="form-group" style="text-align: left;"> 
                                                        <label class=" padding-off bold name" for="delivery" style="display: block; text-align: left;"><?=$payment["name"]?></label><br>
                                                        <div class="dost-variant">
                                                            <?
                                                            $res = A::$app->shop->elements($payment["id"]);
                                                            foreach ($res as $key => $r) {
                                                                if (in_array($r["id"], $dost_id)){
                                                                    $price = array_search($r["id"], $dost_id);
                                                                    ?>
                                                                    <div class=" radio-input padding-off">
                                                                        <label for="delivery" style="font-weight: normal;"><input style="float: left;
                                                                        margin: 4px 10px 5px;" id="delivery<?=$r["id"]?>" name="delivery" type="radio" <?=$key==0?'checked=""':''?> value="<?=$r["name"]?>" data-price="<?=$dost_price[$price]?>"> <?=$r["name"]?> (<?=$dost_price[$price]===0?'бесплатно':$dost_price[$price].' тг'?>)</label>
                                                                    </div>
                                                                <? } ?>
                                                            <? } ?>
                                                        </div>
                                                    </div>
                                                <? } ?>
                                                
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="" for="name">Адрес доставки <span style="color: red">*</span></label>
                                                <input class="form-control" type="text" id="adress" name="adress" value="<?=@$user["adress"]?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="" for="name">Дом</label>
                                                <input class="form-control" type="text" id="dom" name="dom" value="<?=@$user["dom"]?>" >
                                            </div>
                                            <div class="col-md-4">
                                                <label class="" for="name">Квартира</label>
                                                <input class="form-control" type="text" id="kv" name="kv" value="<?=@$user["kv"]?>" >
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="" for="name">Примечание</label>
                                                <textarea class="form-control" id="info" name="info" rows="3"></textarea>
                                            </div>
                                            
                                        </div>
                                        <hr>
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-md-8" style="display: flex; flex-direction: row; align-items: center">
                                                <label style="white-space: nowrap; padding-right: 15px;">
                                                Дарим добро
                                                </label>
                                                <select name="bonus" id="bonus" class="form-control" style="display: inline-block; width: auto">
                                                    <option value="50">50 тг</option>
                                                    <option value="100">100 тг</option>
                                                    <option value="200">200 тг</option>
                                                    <option value="500">500 тг</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4" style="text-align: right">
                                        <button type="submit" class="cusmo-btn btn btn-primary btn-big">Продолжить</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-block">
                            <div class="order-info" style="margin-top: 5px;">
                              <div class="row" style="margin-bottom: 1rem">
                                <div class="col-md-8 col-md-8">Стоимость</div>
                                <div class="col-md-4 col-md-4" style="text-align: right;"><b id="itogo"></b></div>
                            </div>
                            <div class="row" style="margin-bottom: 1rem">
                                <div class="col-md-8 col-md-8">Доставка</div>
                                <div class="col-md-4 col-md-4" style="text-align: right;"><b id="dost">-</b></div>
                            </div>

                            <hr style="margin: 25px -20px;">
                            <div class="row" >
                                <div class="col-md-8 col-md-8">Итого</div>
                                <div class="col-md-4 col-md-4" style="text-align: right;"><b id="amount"></b></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="delivery_price" value="0">
</form>
<div id="basket_protocol_order" class="protocol" style="display: none;"></div>
<div id="basket_place_order" style=" width: 100%; display: none;"></div>
<? } ?>

<? if (@$_POST['step']==2 ){ ?>
    <form method="post" action="">
     <input type="hidden" name="step" value="3">
     <input type="hidden" name="name" value="<?=$ob->pr($_POST["name"])?>">
     <input type="hidden" name="fam" value="<?=$ob->pr($_POST["fam"])?>">
     <input type="hidden" name="phone" value="<?=$ob->pr($_POST["phone"])?>">
     <input type="hidden" name="email" value="<?=$ob->pr($_POST["email"])?>">
     <input type="hidden" name="adress" value="<?=$ob->pr($_POST["adress"])?>">
     <input type="hidden" name="dom" value="<?=$ob->pr($_POST["dom"])?>">
     <input type="hidden" name="kv" value="<?=$ob->pr($_POST["kv"])?>">
     <input type="hidden" name="country" value="<?=$ob->pr($_POST["country"])?>">
     <input type="hidden" name="city" value="<?=$ob->pr($_POST["city"])?>">
     <input type="hidden" name="delivery" value="<?=$ob->pr($_POST["delivery"])?>">
     <input type="hidden" name="delivery_price1" value="<?=$ob->pr($_POST["delivery_price"])?>">
     <input type="hidden" name="info" value="<?=$ob->pr($_POST["info"])?>">
        <input type="hidden" name="bonus" value="<?=$ob->pr($_POST["bonus"])?>">


     <div class="portfolio">
        <div class="container">
            <div class="row ">
                <div class="col-md-8">
                    <div class="card card-block">
                        <div class=" row">
                            <div class="col-md-12 padding-off">
                                <h4 style="text-transform: uppercase; margin-top:4px;">Оформление заказа</h4>
                                <hr style="margin: 25px -20px;">
                            </div>
                        </div>
                        <div class="order-block">
                            <div class=" row">
                                <div class="col-md-12">
                                    <b>Адрес доставки:</b> <?=$ob->pr($_POST["city"])?>, <?=$ob->pr($_POST["adress"])?> <?=$ob->pr($_POST["dom"])?> <?=$ob->pr($_POST["kv"])?>
                                </div>
                            </div>
                            <hr>
                            <div class=" row">
                                <div class="col-md-12">
                                    <b>Способ доставки:</b> <?=$ob->pr($_POST["delivery"])?>
                                </div>
                            </div>
                            <hr>
                            <div class=" row">
                                <div class="col-md-12">

                                    <? if ($payment = A::$app->wf(4, 'shop')){ ?>
                                        <div class="form-group"> 
                                            <label class=" padding-off bold name" for="payment" style="display: block; text-align: left;"><?=$payment["name"]?></label><br>
                                            <div class="dost-variant">
                                                <?
                                                $res = A::$app->shop->elements($payment["id"]);
                                                foreach ($res as $key => $r) {

                                                    ?>
                                                    <div class=" radio-input padding-off">
                                                        <label  style="font-weight: normal;"><input style="float: left;
                                                        margin: 4px 10px 5px;" id="payment" name="payment" type="radio" <?=$key==0?'checked=""':''?> value="<?=$r["name"]?>" > <?=$r["name"]?></label>
                                                    </div>

                                                <? } ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <br>
                                    <div class="col-md-12 col-md-12 col-sm-12 col-md-12 padding-off">
                                        <button type="submit" class="cusmo-btn btn btn-primary btn-big" >Подтвердить заказ</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-block">
                        <div class="order-info" style="margin-top: 5px;">
                          <div class="row" style="margin-bottom: 1rem">
                            <div class="col-md-8 col-md-8">Стоимость</div>
                            <div class="col-md-4 col-md-4" style="text-align: right;"><b id="itogo"></b></div>
                        </div>
                            <div class="row" style="margin-bottom: 1rem">
                                <div class="col-md-8 col-md-8">Дарим добро</div>
                                <div class="col-md-4 col-md-4" style="text-align: right;"><b id="dobro"><?=$ob->pr($_POST["bonus"])?> тг</b></div>
                            </div>
                        <div class="row" style="margin-bottom: 1rem">
                            <div class="col-md-8 col-md-8">Доставка</div>
                            <div class="col-md-4 col-md-4" style="text-align: right;"><b id="dost1"><?=($_POST["delivery_price"]>0?$_POST["delivery_price"].' тг':'-')?></b></div>
                        </div>

                        <hr style="margin: 25px -20px;">
                        <div class="row" >
                            <div class="col-md-8 col-md-8">Итого</div>
                            <div class="col-md-4 col-md-4" style="text-align: right;"><b id="amount1"></b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</form>
<div id="basket_protocol_order" class="protocol" style="display: none;"></div>
<div id="basket_place_order" style=" width: 100%; display: none;"></div>
<? } ?>
<? if (@$_POST['step']==3 ){ ?>
    <form method="post" data-action="/thanks/" id="order_form">
        <input type="hidden" name="do" value="send_order">
        <input type="hidden" name="step" value="3">
        <input type="hidden" name="uid" value="<?=@A::$app->user->info["id"]?>">
        <input type="hidden" name="name" value="<?=$ob->pr($_POST["name"])?>">
        <input type="hidden" name="fam" value="<?=$ob->pr($_POST["fam"])?>">
        <input type="hidden" name="phone" value="<?=$ob->pr($_POST["phone"])?>">
        <input type="hidden" name="email" value="<?=$ob->pr($_POST["email"])?>">
        <input type="hidden" name="adress" value="<?=$ob->pr($_POST["adress"])?>">
        <input type="hidden" name="dom" value="<?=$ob->pr($_POST["dom"])?>">
        <input type="hidden" name="kv" value="<?=$ob->pr($_POST["kv"])?>">
        <input type="hidden" name="country" value="<?=$ob->pr($_POST["country"])?>">
        <input type="hidden" name="city" value="<?=$ob->pr($_POST["city"])?>">
        <input type="hidden" name="delivery" value="<?=$ob->pr($_POST["delivery"])?>">
        <input type="hidden" name="delivery_price1" value="<?=$ob->pr($_POST["delivery_price1"])?>">
        <input type="hidden" name="payment" value="<?=$ob->pr($_POST["payment"])?>">
        <input type="hidden" name="info" value="<?=$ob->pr($_POST["info"])?>">
        <input type="hidden" name="bonus" value="<?=$ob->pr($_POST["bonus"])?>">
        
        <section style="padding-top: 30px;">
            <div class="container">
                <div class="portfolio card card-block">
                   <div class=" row">
                    <div class="col-md-12 padding-off">
                        <h4 style="text-transform: uppercase; margin-top:4px;">Оформление заказа</h4>
                        <hr style="margin: 15px -20px;">
                    </div> 
                </div>
                <div class="container">
                    <div class="row ">
                        <div class="col-md-6">
                            <div >

                                <div class="order-block" style="margin: 0px;">
                                    <div class=" row">
                                        <div class="col-md-12">
                                            <b>ФИО:</b> <?=$ob->pr($_POST["fam"])?>, <?=$ob->pr($_POST["name"])?>
                                        </div>
                                    </div>
                                    <hr style="margin: 15px -15px;">
                                    <div class=" row">
                                        <div class="col-md-12">
                                            <b>E-mail:</b> <?=$ob->pr($_POST["email"])?>
                                        </div>
                                    </div>
                                    <hr style="margin: 15px -15px;">
                                    <div class=" row">
                                        <div class="col-md-12">
                                            <b>Телефон:</b> <?=$ob->pr($_POST["phone"])?>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div >
                                <div class="order-block" style="margin: 0px;">
                                    <div class=" row">
                                        <div class="col-md-12">
                                            <b>Адрес доставки:</b> <?=$ob->pr($_POST["city"])?>, <?=$ob->pr($_POST["adress"])?> <?=$ob->pr($_POST["dom"])?> <?=$ob->pr($_POST["kv"])?>
                                        </div>
                                    </div>
                                    <hr style="margin: 15px -15px;">
                                    <div class=" row">
                                        <div class="col-md-12">
                                            <b>Способ доставки:</b> <?=$ob->pr($_POST["delivery"])?>
                                        </div>
                                    </div>
                                    <hr style="margin: 15px -15px;">
                                    <div class=" row">
                                        <div class="col-md-12">
                                            <b>Способ оплаты:</b> <?=$ob->pr($_POST["payment"])?>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="portfolio card card-block">
                <div class="row">
                    <div class="col-md-12">
                        <div id="basket_protocol_order" class="protocol" ></div>
                        <div id="basket_place_order" style=" width: 100%; "></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<? } ?>

<? $this->footer() ?>