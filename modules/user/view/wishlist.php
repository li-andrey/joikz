<style type="text/css">
.filter>li>a:after{
    display: none;
}
</style>
<div class="content">
    <ul class="filter">
        <li>
            <a href="" class="wishlist">
                <span class="text-uppercase">Список желаний</span>
                <b style="float: right;" class="count"><?=A::$app->wishlist->count()?></b>
            </a>

        </li>
    </ul>

    <div class="container py-5 pb-5">
        <div class="row">
            <?
            $idArray = A::$app->wishlist->get_all();
            $sql = "select * from i_cat where active=1 and id in(".join(',', $idArray).")";
            $res = A::$db->get($sql);
            foreach($res as $r){
                ?>
                <div class="col-md-3 col-6 wishlist-item-<?=$r["id"]?>">

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
                        <div class="info mt-3">
                            <div class="row no-gutters flex-nowrap align-items-center justify-content-between">
                                <div class="col-auto">
                                    <div class="price">
                                        <? if ($r["price_old"]>0){ ?>
                                            <s><?=A::$api->price($r["price_old"], 1)?> ₸</s>
                                        <? } ?>
                                        <?=A::$api->price($r["price"], 1)?> ₸
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="" class="favorites like-icon <?= A::$app->wishlist->check($r["id"]) ? 'active' : '' ?>" data-id="<?=$r["id"]?>">
                                        <img src="/upload/favorites.png">
                                    </a>
                                </div>
                            </div>

                            <div class="name">
                                <?=$r["name"]?>
                            </div>
                        </div>
                    </div>
                </div>
            <? } ?>

        </div>
        <? if (A::$app->wishlist->count()==0){ ?>
            <p class="text-center">
                Нет товаров в cписке желаний
            </p>
        <? } ?>
    </div>
</div>