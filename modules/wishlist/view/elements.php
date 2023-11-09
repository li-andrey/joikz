<style>
    .col-main{
        width: 100%;
    }
</style>
                    <? if (sizeof($elements)>0){ ?>
                        <div class="category-products">





                            <ul class="products-grid first last odd">
                                <? foreach($elements as $r){ ?>
                                    <li class="item col-lg-3 col-md-3 col-sm-6 col-xs-12 wisha<?=$r["id"]?>">
                                        <div class="item-inner">
                                            <div class="item-img">
                                                <div class="item-img-info">
                                                    <a class="product-image" href="/catalog/<?= $r["url"] ?>/"
                                                       title="<?= $r["name"] ?>">
                                                        <img class="img-responsive" alt="<?= $r["name"] ?>"
                                                             src="/upload/images/small/<?= $r["image"] ?>">
                                                    </a>
                                                    <div class="box-hover">
                                                        <ul class="add-to-links">


                                                            <li>
                                                                <a href="" data-id="<?= $r["id"] ?>"
                                                                   class="link-wishlist like-icon <?= A::$app->wishlist->check($r["id"]) ? 'active' : '' ?>"
                                                                   title="В избранное">В&nbsp;избранное</a>
                                                            </li>

                                                        </ul>
                                                        <!--add-to-links-->
                                                    </div>
                                                    <!--box-hover-->
                                                    <div class="box-timer">
                                                        <div class="countbox_1 timer-grid">

                                                        </div>
                                                        <!--countbox_1 timer-grid-->
                                                    </div>
                                                    <!--box-timer-->
                                                </div>
                                                <!--item-img-info-->
                                            </div>
                                            <!--item-img-->
                                            <div class="item-info">
                                                <div class="info-inner">
                                                    <div class="item-title">
                                                        <a href="/catalog/<?= $r["url"] ?>/"
                                                           title="<?= $r["name"] ?>">
                                                            <?= $r["name"] ?> </a>
                                                    </div>
                                                    <!--item-title-->

                                                    <div class="ratings">
                                                        <div class="rating-box">
                                                            <div style="width:<?= round(intval($r["raiting"]) / 5 * 100) ?>%"
                                                                 class="rating"></div>
                                                        </div>
                                                    </div>
                                                    <div class="item-content">
                                                        <div class="item-price">


                                                            <div class="price-box">
                                                                                <span class="regular-price"
                                                                                      id="product-price-389">
                                                                                    <? if ($r["price_old"] > 0) { ?>
                                                                                        <del>
                                                                                            <span class="woocommerce-Price-amount amount"><?= price($r["price_old"]) ?> ₸</span>
                                                                                        </del>
                                                                                    <? } ?>
                                                                                    <ins>
                                                                                        <span class="woocommerce-Price-amount amount"><?= price($r["price"]) ?> ₸
                                                                                    </ins> </span>

                                                            </div>

                                                        </div>
                                                        <div class="action">
                                                            <button type="button" title="В корзину"
                                                                    class="button btn-cart basket"
                                                                    data-id="<?= $r["id"] ?>"
                                                            >
                                                                <span>В корзину</span></button>
                                                        </div>
                                                        <!--action-->
                                                    </div>
                                                    <!--item-content-->
                                                </div>
                                                <!--info-inner-->
                                            </div>
                                            <!--item-info-->
                                        </div>
                                    </li>
                            <? } ?>

                        </ul>
                    </div>

                <? } ?>
            