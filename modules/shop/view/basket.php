<div class="row ">
    <div class="col-lg-8">
        <?
        $cost = 0;
        $sale = 0;
        $elements_mass = A::$app->cart->get_all();
        for ($i = 0; $i < sizeof($elements_mass); $i++) {
            $id = $elements_mass[$i]['id'];
            $count = $elements_mass[$i]['count'];

            $image = [];

            $param = str_replace('color:', 'Цвет:', $elements_mass[$i]['params']);
            $result = A::$db->query(" SELECT * FROM `i_cat` WHERE `id`='$id' ");
            $myrow = $result->fetch_array();

            $parr = explode(',', $param);
            if (strstr($parr[0], 'Цвет')) {
                $color = str_replace('Цвет:', '', $parr[0]);

                $sql = "select * from i_cat where color='" . $color . "' and id_section='" . $myrow["id"] . "' limit 1";
                $image = A::$db->get($sql, 1);
            }

            /*if ($elements_mass[$i]['params']!=''){
                $par = explode(',', trim($elements_mass[$i]['params'], ','));
                $size = explode(':', @$par[0]);

                if (@$myrow["form"]!=''){
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
        ?>

                <div class="row align-items-center mb-4">
                    <div class="col-md-1 col-1">
                        <a href="javascript:;" class="delete" onclick="DeleteFromBasket(<?= $myrow["id"] ?>, '<?= $elements_mass[$i]['params'] ?>')">
                            +
                        </a>
                    </div>
                    <div class="col-md-2 col-3">
                        <a href="<?= LINK ?>catalog/<?= $myrow["url"] ?>/">
                            <? if (isset($image["id"])) { ?>
                                <img src="/upload/images/small/<?= $image["image"] ?>" alt="" />
                            <? } else { ?>
                                <img src="/upload/images/small/<?= $myrow["image"] ?>" alt="" />
                            <? } ?>
                        </a>
                    </div>
                    <div class="col-md-7 col-8">
                        <div class="price">
                            <? if ($myrow["price_old"] > 0) { ?>
                                <s><?= price($myrow["price_old"], 1) ?> ₸</s>
                                <? $sale = $sale + ($myrow["price_old"] - $myrow["price"]) * $count; ?>
                            <? } ?>
                            <?= price($myrow["price"], 1) ?> ₸
                        </div>
                        <div class="name">
                            <?= $myrow["name"] ?>
                            <? if ($param != '') { ?>
                                <p style=""><?= str_replace(",", '<br>', $param) ?></p>
                            <? } ?>
                        </div>
                        <div class="art">
                            Код <?= $myrow["art"] ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="count">
                            <a href="" class="minus">
                                -
                            </a>
                            <input type="number" onChange="ReCount(<?= $myrow["id"] ?>, '<?= $elements_mass[$i]['params'] ?>', '<?= $i ?>')" id="count_<?= $myrow["id"] ?>_<?= $i ?>" name="kol" value="<?= $count ?>" min="1">
                            <a href="" class="plus">
                                +
                            </a>
                        </div>
                    </div>
                </div>

            <? } ?>

        <? } ?>
    </div>
    <div class="col-lg-4 basket-order-info">
        <? if (A::$app->cart->count() > 0) { ?>
            <div><b>Информация о заказе</b></div>
            <hr class="my-4">
            <div class="row  align-items-center">
                <div class="col-md-8 col-6">
                    Сумма:
                </div>
                <div class="col-md-4 col-6  text-right">
                    <?= price($sale + $cost, 1) ?> ₸
                </div>
            </div>
            <? if ($sale > 0) { ?>
                <div class="row  align-items-center">
                    <div class="col-md-8 col-6">
                        Скидка:
                    </div>
                    <div class="col-md-4 col-6  text-right">
                        <?= price($sale, 1) ?> ₸
                    </div>
                </div>
            <? } ?>

            <hr class="mt-4 mb-4">
            <div class="row mb-5 align-items-center">
                <div class="col-md-8 col-6">
                    Итого:
                </div>
                <div class="col-md-4 col-6 text-right">
                    <?= price($cost, 1) ?> ₸
                </div>
            </div>
            <a href="<?= LINK ?>shop/order/" class="btn btn-primary btn-block text-bold">
                Оформить заказ
            </a>
        <? } ?>
    </div>
</div>

<? if (A::$app->cart->count() == 0) { ?>
    <p class="text-center py-5">
        Нет товаров в корзине
    </p>
<? } ?>