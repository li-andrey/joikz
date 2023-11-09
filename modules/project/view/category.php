 <form action="" name="filter-form" id="filter-form">
    <?
    $sql_search = array();

    if (isset(A::$url->get["brand"])){
        $brandArray = array();
        if (is_array(A::$url->get["brand"])){
            foreach (A::$url->get["brand"] as $key => $v) {
                $brandArray[] = "'".str_replace('*', '&', $v)."'";
            }
        }else{
            $brandArray[] = "'".str_replace('*', '&',A::$url->get["brand"])."'";
        }
        if (sizeof($brandArray)>0){
            $sql_search['brands'] = " brand in (".join(',', $brandArray).") ";    
        }

    }
    if (isset(A::$url->get["min_price"])){
        $price[0] = @A::$url->get["min_price"];
        $price[1] = @A::$url->get["max_price"];
        if (sizeof($price)>0){
            $sql_search['price'] = " (price>=".trim(@$price[0])." and price<=".trim(@$price[1]).") ";    
        }
    }
    ?>


    <style type="text/css">
        .main-content{
            padding-top: 0px;
        }
    </style>

    <div class="left-sidebar">
        <div id="content" class="site-content ">
            <div class="row">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <header class="woocommerce-products-header">
                            <h1 class="woocommerce-products-header__title page-title"><?=$block["name"]?></h1>
                        </header>
                        <div class="control-bar">
                            <select name="order" class="orderby">
                                <option value="" >Сортировать по</option>
                                <option value="raiting desc" <?=A::$app->get('order')=='raiting desc'?'selected':''?>>Популярности</option>
                                <option value="price asc" <?=A::$app->get('order')=='price asc'?'selected':''?>>Возрастанию цены</option>
                                <option value="price desc" <?=A::$app->get('order')=='price desc'?'selected':''?>>Убыванию цены</option>
                                <option value="name asc" <?=A::$app->get('order')=='name asc'?'selected':''?>>По наименованию А-Я</option>
                                <option value="name desc" <?=A::$app->get('order')=='name desc'?'selected':''?>>По наименованию Я-А</option>
                            </select>
                            <ul class="shop-view-switcher">
                                <li class="active"> <a href="#grid-view" data-toggle="tab"> <i class="fa fa-th-large"></i> Таблица </a></li>
                                <li> <a href="#list-view" data-toggle="tab"> <i class="fa fa-th-list"></i> Список </a></li>
                            </ul>
                        </div>
                        <? if (sizeof($elements)>0){ ?> 



                            <div id="grid-view" class="woocommerce active">
                                <ul class="products columns-3 enable-hover">
                                    <? foreach($elements as $r){ ?>
                                        <li class="post-119 product type-product status-publish has-post-thumbnail product_cat-accessories-de pa_brands-sony first instock shipping-taxable purchasable product-type-simple">
                                            <div class="product-inner"> <a href="<?=LINK?>catalog/<?=$r["url"]?>/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                <? if ($r["sale"]==1){ ?>
                                                    <div class="ribbon red"><span class="onsale">Распродажа</span></div>
                                                <? } ?>
                                                <? if ($r["top"]==1){ ?>
                                                    <div class="ribbon label-52"><span>Топ продаж</span></div>
                                                <? } ?>
                                                <div class="product-thumbnail-wrapper"> 
                                                    <img width="246" height="186" src="<?=ASSETS?>images/blank.gif" class="attachment-shop_catalog size-shop_catalog echo-lazy-loading wp-post-image" alt="Iconia W700" data-echo="/upload/images/small/<?=$r["image"]?>" />
                                                </div>
                                                <div class="title-area">
                                                    <h2 class="woocommerce-loop-product__title"><?=$r["name"]?></h2>
                                                    <div class="product-brand"><?=$r["brand"]?></div>
                                                </div> 
                                                <span class="price">
                                                    <span class="mc-price-wrapper">
                                                        <? if ($r["price_old"]>0){ ?>
                                                            <del><span class="woocommerce-Price-amount amount"><?=price($r["price_old"])?> ₸</span></del>
                                                        <? } ?>
                                                        <span class="woocommerce-Price-amount amount"><?=price($r["price"])?> ₸</span>
                                                    </span>
                                                </span>
                                            </a>
                                            <div class="hover-area">
                                                <div class="hover-area-inner"> <a rel="nofollow" href="javascript:;" data-quantity="1" data-id="<?=$r["id"]?>" data-product_sku="" class="button product_type_simple add_to_cart_button basket">В корзину</a>
                                                    <div class="action-buttons">
                                                        <div class="yith-wcwl-add-to-wishlist add-to-wishlist-39">
                                                            <div class="yith-wcwl-add-button show" style="display:block"> <a href="javascript:;" rel="nofollow" data-product-id="39" data-product-type="simple" class="add_to_wishlist like-icon <?=A::$app->Wishlist->check($r["id"])?'active':''?>" data-id="<?=$r["id"]?>"> В избранное</a></div>

                                                        </div>
                                                        <div class="clear"></div><a href="javascript:;" class="add-to-compare-link compare-icon <?=A::$app->Simile->check($r["id"])?'active':''?>" data-id="<?=$r["id"]?>" data-product_id="39">Сравнить</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <? } ?>

                            </ul>
                        </div>
                        <div id="list-view">
                            <? foreach($elements as $r){ ?>
                                <div class="post-119 product type-product status-publish has-post-thumbnail product_cat-accessories-de pa_brands-sony first instock shipping-taxable purchasable product-type-simple">
                                    <div class="product-list-view-inner">
                                        <div class="product-list-view-header">
                                         <div class="product-thumbnail-wrapper"> 
                                            <img width="246" height="186" src="<?=ASSETS?>images/blank.gif" class="attachment-shop_catalog size-shop_catalog echo-lazy-loading wp-post-image" alt="Iconia W700" data-echo="/upload/images/small/<?=$r["image"]?>" />
                                        </div>
                                    </div> <a href="<?=LINK?>catalog/<?=$r["url"]?>/" class="product-list-view-body">

                                        <div class="title-area">
                                         <h2 class="woocommerce-loop-product__title"><?=$r["name"]?></h2>
                                         <div class="product-brand"><?=$r["brand"]?></div>
                                     </div>
                                     <div class="excerpt"> <?=$r["anounce"]?></div>
                                 </a>
                                 <div class="list-view-footer"> <span class="price">
                                    <span class="mc-price-wrapper">
                                        <? if ($r["price_old"]>0){ ?>
                                            <del><span class="woocommerce-Price-amount amount"><?=price($r["price_old"])?> ₸</span></del>
                                        <? } ?>
                                        <span class="woocommerce-Price-amount amount"><?=price($r["price"])?> ₸</span>
                                    </span>
                                </span>
                                <div class="availability"><label>В наличии:</label><span class="available"><?=$r["kol"]>0?'Да':'Под заказ'?></span></div>
                                <a rel="nofollow" href="javascript:;" data-quantity="1" data-id="<?=$r["id"]?>" data-product_sku="" class="button product_type_simple add_to_cart_button basket">В корзину</a>
                                <div class="action-buttons">
                                    <div class="yith-wcwl-add-to-wishlist add-to-wishlist-39">
                                        <div class="yith-wcwl-add-button show" style="display:block"> <a href="javascript:;" rel="nofollow" data-product-id="39" data-product-type="simple" class="add_to_wishlist like-icon <?=A::$app->Wishlist->check($r["id"])?'active':''?>" data-id="<?=$r["id"]?>"> В избранное</a></div>

                                    </div>
                                    <div class="clear"></div><a href="javascript:;" class="add-to-compare-link compare-icon <?=A::$app->Simile->check($r["id"])?'active':''?>" data-id="<?=$r["id"]?>" data-product_id="39">Сравнить</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>




            </div>
            <div style="text-align: center;"><?=$pagination->show()?></div>
        <? } ?>
    </main>
</div>
<div id="sidebar" class="sidebar">
    <div id="secondary" class="secondary">
        <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
            <div class="product-filters">
                <h3>Фильтр товаров</h3>
                <div class="widgets">
                    <?
                    $sql_search_brands = $sql_search;
                    unset($sql_search_brands["brands"]);

                    $sql = "select brand from i_cat_elements where  active=1 and id_section IN (".join(',', $tree).") ".(sizeof($sql_search_brands)>0?" and  ".join(' and ', $sql_search_brands)."":'')." and brand!='' group by brand order by brand asc ";

                    $res = A::$db->get($sql);
                    if (sizeof($res)>0){
                        ?>
                        <aside id="mc_brands_filter-2" class="widget clearfix woocommerce widget_brands_filter">
                            <h4 class="widget-title">По бренду</h4>
                            <ul>
                               <? foreach ($res as $key => $r) { ?>
                                <?
                                $sql = "select id from i_cat_elements where active=1 and id_section IN (".join(',', $tree).") ".(sizeof($sql_search_brands)>0?" and  ".join(' and ', $sql_search_brands)."":'')." and brand!='' and brand = '".$r["brand"]."' group by id";


                                $res1 = A::$db->get($sql);
                                ?>
                                
                                <li class="wc-layered-nav-term ">
                                    <a href="javascript:;" class="<?=@in_array(str_replace('&', '*',$r["brand"]),@A::$url->get["brand"])?'active':''?> ">  
                                     <label class="facet-label" for="facet_input_<?=$key?>" style="cursor: pointer;">
                                        <input style="display: none;" id="facet_input_<?=$key?>" name="brand[]" value="<?=str_replace('&', '*',$r["brand"])?>" <?=@in_array(str_replace('&', '*',$r["brand"]),@A::$url->get["brand"])?'checked':''?>   type="checkbox">


                                        <?=$r["brand"]?>
                                        <span class="magnitude">(<?=sizeof($res1)?>)</span>
                                    </label>
                                </a>
                            </li>

                        <? } ?>
                    </ul>
                </aside>
            <? } ?>
            <aside id="woocommerce_price_filter-2" class="widget clearfix woocommerce widget_price_filter">
                <h4 class="widget-title">По цене</h4>
                <? 
                if (A::$app->get('min_price')!=''){ 
                    $minprice1 =  A::$app->get('min_price');
                    $maxprice1 =  A::$app->get('max_price');
                } else{
                    $minprice1 = @$minprice;
                    $maxprice1 = @$maxprice;
                }
                ?>
                <div class="price_slider_wrapper" style="text-align: center;">
                    <div class="price_slider" style="display:none;"></div>
                    <div class="price_slider_amount"> <input type="text" id="min_price" name="min_price" value="<?=$minprice1?>" data-min="<?=$minprice?>" placeholder="Min price" /> <input type="text" id="max_price" name="max_price" value="<?=$maxprice1?>" data-max="<?=$maxprice?>" placeholder="Max price" /> 
                        <div class="price_label" style="display:none; text-align: center;"> Цена: <span class="from"></span> &mdash; <span class="to"></span></div>
                        <div class="clear"></div>
                        <button type="submit" style="float: none;" class="button">Показать</button>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</div>
<?
$res = A::$app->catalog->categories($block["id"]);
if (sizeof($res)==0){
    $res = A::$app->catalog->categories($block["id_section"]);
}

?>
<? if (sizeof($res)>0){ ?>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-12">
        <aside id="woocommerce_product_categories-2" class="widget clearfix woocommerce widget_product_categories">
            <h4 class="widget-title">Категории</h4>
            <ul class="product-categories">
                <? foreach ($res as $key => $r) { ?>
                    <?
                    $sql = "select * from i_cat_elements where (id_section=".$r["id"]." or id_section in (select id from i_cat where id_section='".$r["id"]."' and active=1) ) and active=1";
                    $res1 = A::$db->get($sql);
                    ?>
                    <li class="cat-item cat-item-49 <?=$block["id"]==$r["id"]?'active':''?>">
                        <a href="<?=LINK?>catalog/<?=$r["url"]?>/"><?=$r["name"]?> (<?=sizeof($res1)?>)</a>
                    </li>
                <? } ?>

            </ul>
        </aside>
    </div>
<? } ?>
<?
$sql = "select * from i_cat_elements where active=1 and sale=1 order by id_sort asc limit 3";
$res = A::$db->get($sql);
if (sizeof($res)>0){ 

    ?>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-12">
        <aside id="woocommerce_products-2" class="widget clearfix woocommerce widget_products">
            <h4 class="widget-title">Распродажа</h4>
            <ul class="product_list_widget">
                <? foreach ($res as $key => $r) { ?>
                    <li> <a href="<?=LINK?>catalog/<?=$r["url"]?>/"> <img width="73" height="73" src="<?=ASSETS?>images/blank.gif" class="attachment-shop_catalog size-shop_catalog echo-lazy-loading wp-post-image" alt="Iconia W700" data-echo="/upload/images/small/<?=$r["image"]?>" /> <span class="product-title"><?=$r["name"]?></span> </a> <span class="mc-price-wrapper">
                        <? if ($r["price_old"]>0){ ?>
                            <del><span class="woocommerce-Price-amount amount"><?=price($r["price_old"])?> ₸</span></del>
                        <? } ?>
                        <span class="woocommerce-Price-amount amount"><?=price($r["price"])?> ₸</span>
                    </span></li>
                <? } ?>

            </ul>
        </aside>
    </div>
<? } ?>
<?
$sql = "select * from i_slider_elements where active=1 and id_section=3 order by id_sort asc limit 1";
$res = A::$db->get($sql);
if (sizeof($res)>0){ 
    ?>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-12">
        <aside id="text-2" class="widget clearfix widget_text">
            <div class="textwidget">
                <? foreach ($res as $key => $r) { ?>
                    <p><a href="<?=$r["link"]?>"><img src="/upload/images/<?=$r["image"]?>" /></a></p>
                <? } ?>
            </div>
        </aside>
    </div>
<? } ?>
<?
$sql = "select * from i_cat_elements where active=1 and top=1 order by id_sort asc limit 3";
$res = A::$db->get($sql);
if (sizeof($res)>0){ 

    ?>
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-12">
        <aside id="woocommerce_products-2" class="widget clearfix woocommerce widget_products">
            <h4 class="widget-title">ТОП продаж</h4>
            <ul class="product_list_widget">
                <? foreach ($res as $key => $r) { ?>
                    <li> <a href="<?=LINK?>catalog/<?=$r["url"]?>/"> <img width="73" height="73" src="<?=ASSETS?>images/blank.gif" class="attachment-shop_catalog size-shop_catalog echo-lazy-loading wp-post-image" alt="Iconia W700" data-echo="/upload/images/small/<?=$r["image"]?>" /> <span class="product-title"><?=$r["name"]?></span> </a> <span class="mc-price-wrapper">
                        <? if ($r["price_old"]>0){ ?>
                            <del><span class="woocommerce-Price-amount amount"><?=price($r["price_old"])?> ₸</span></del>
                        <? } ?>
                        <span class="woocommerce-Price-amount amount"><?=price($r["price"])?> ₸</span>
                    </span></li>
                <? } ?>
                
            </ul>
        </aside>
    </div>
<? } ?>
</div>
</div>
</div>
</div>
</div>