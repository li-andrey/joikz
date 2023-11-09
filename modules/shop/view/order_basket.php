<?
$cost = 0;
$sale = 0;
$elements_mass = A::$app->cart->get_all();
for ($i = 0; $i < sizeof($elements_mass); $i++) {

    $image = [];
    $id = $elements_mass[$i]['id'];
    $count = $elements_mass[$i]['count'];
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

?>

        <div class="row flex-nowrap align-items-center">

            <div class="col-md-2 col-3">
                <a href="<?= LINK ?>catalog/<?= $myrow["url"] ?>/" target="_blank">
                    <? if (isset($image["id"])) { ?>
                        <img src="/upload/images/small/<?= $image["image"] ?>" alt="" />
                    <? } else { ?>
                        <img src="/upload/images/small/<?= $myrow["image"] ?>" alt="" />
                    <? } ?>
                </a>
            </div>
            <div class="col-md-8 col-7">
                <div class="price">
                    <? if ($myrow["price_old"] > 0) { ?>
                        <s><?= price($myrow["price_old"], 1) ?> ₸</s>
                        <? $sale = $sale + $myrow["price_old"] - $myrow["price"]; ?>
                    <? } ?>
                    <?= price($myrow["price"], 1) ?> ₸
                </div>
                <div class="name">
                    <?= $myrow["name"] ?>
                    <? if ($param != '') { ?>
                        <p style=""><?= str_replace("\n", '<br>', $param) ?></p>
                    <? } ?>
                </div>
                <div class="art">
                    Код <?= $myrow["art"] ?>
                </div>
                <div class="art mt-2">
                    Количество: <?= $count ?>
                </div>

            </div>
            <div class="col-md-2 col-2">
                <div class="count">

                    <h3 class="m-0"></h3>

                </div>
            </div>
        </div>
        <? if ($i != sizeof($elements_mass) - 1) { ?>
            <hr>
        <? } ?>

    <? } ?>

<? } ?>



<script>
    jQuery(function() {
        jQuery('#itogo').html("<?= price($cost, 1) ?> ₸");
        dostCount();
        itogo();
    })
</script>