<style type="text/css">
.filter>li>a:after{
    content:'' !important
}
header .navbar.fixed-top {
    border-bottom: 1px solid #000;
}
</style>
<div class="content">
    <ul class="filter">
        <li>
            <a href="">
                <span class="text-uppercase">Корзина</span>
                <b style="float: right;"><?=A::$app->cart->count()?></b>
            </a>

        </li>
    </ul>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="cart py-5">
                    <div id="basket_protocol" class="protocol"></div>
                    <div id="basket_place"  ></div>
                </div>
            </div>
        </div>
    </div>
    <ul class="filter">
        <li>
            <a href="">
                <span class="text-uppercase">Вам также может понравиться</span>
            </a>
        </li>
    </ul>
    <div class="container py-3 pb-5">
        <div class="row">
            <?
            $sql = "select * from i_cat where active=1 and id_section=2 order by rand() limit 5";
            $res = A::$db->get($sql);
            foreach($res as $k => $r){
                ?>
                <div class="col-xl col-md-3 col-6 <?=$k==4?'d-none d-lg-none d-xl-block':''?>">

                    <div class="product-item">
                        <a href="<?=LINK?>catalog/<?=$r["url"]?>/">
                            <div class="image">
                                <img src="/upload/images/small/<?=$r["image"]?>" alt="<?=$r["name"]?>" />
                                <div class="badges">
                                    <? if ($r["spec"]==1){ ?>
                                        <div class="badge-dark">
                                            sale
                                        </div>

                                    <? } ?>
                                    <? if ($r["price_old"]>0){ ?>
                                        <div class="badge-light">
                                            <?=round(($r["price_old"]-$r["price"])/$r["price_old"], 2)*100?>%
                                        </div>
                                    <? } ?>
                                    <? if ($r["new"]==1){ ?>
                                        <div class="badge-dark new">
                                            Новинка
                                        </div>
                                    <? } ?>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
            <? } ?>
        </div>
    </div>
</div>