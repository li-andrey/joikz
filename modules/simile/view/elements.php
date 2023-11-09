<style type="text/css">
    .main-content{
        padding-top: 0px;
    }
</style>

<div class="">
    <div id="content" class="site-content ">
        <div class="row">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">

                    <? 
                    if (A::$app->simile->count()>0){ ?> 

                        <? 
                        $ids = @array_diff(A::$app->simile->get_all(), array(''));
                        ?>
                        <style type="text/css">
                            .pad_tabl{ 
                                padding:5px;
                            }
                        </style>
                        <table>
                            <tr>
                                <?
                                $_SESSION["compare"] = $ids;

                                if (sizeof($_SESSION["compare"])>0)
                                {
                                    $i=1;
                                    $j=1;
                                    foreach($_SESSION["compare"] as $k=>$v)
                                    {

                                        $s=A::$db->query("select * from i_cat_elements where id=".$v."");
                                        $r=$s->fetch_array();


                                        ?>
                                        <td class="pad_tabl compare-item<?=$v?>" valign="middle" align="center" style="padding:10px 20px; width:200px" >
                                            <a href="<?=LINK?>catalog/<?=$r["url"]?>"><strong><?=$r["name"]?></strong></a>
                                        </td>

                                        <?
                                    }
                                }
                                ?>
                            </tr>
                            <tr>
                                <?
                                $i=1;
                                $j=1;
                                foreach($_SESSION["compare"] as $k=>$v)
                                {

                                    $s=A::$db->query("select * from i_cat_elements where id=".$v."");
                                    $r=$s->fetch_array();


                                    ?>
                                    <td align="center" valign="top" class="pad_tabl compare-item<?=$v?>" >
                                        <div class="item-inner">
                                            <div class="image" style="text-align: center;">
                                                <a href="<?=LINK?>catalog/<?=$r["url"]?>/">
                                                    <? if ($r["image"]!=''){ ?>
                                                        <img class="image1" src="/upload/images/small/<?=$r["image"]?>" style="height: 100px;" alt="" />

                                                    <? }else{ ?> 
                                                        <img class="image1" src="/upload/images/noimagein.gif" alt="" style="height: 100px;" />

                                                    <? } ?>
                                                </a>
                                            </div>
                                            <div class="add-to-links">
                                                <div class="hover-area" style="padding: 25px 0px">
                                                <div class="hover-area-inner"> <a rel="nofollow" href="javascript:;" data-quantity="1" data-id="<?=$r["id"]?>" data-product_sku="" class="button product_type_simple add_to_cart_button basket">В корзину</a>
                                                    <div class="action-buttons" style="display: flex; margin-top: 20px; flex-direction: row; align-items: center; justify-content: center;">
                                                        <div class="yith-wcwl-add-to-wishlist add-to-wishlist-39">
                                                            <div class="yith-wcwl-add-button show" style="display:block; margin-right: 10px;"> <a href="javascript:;" rel="nofollow" data-product-id="39" data-product-type="simple" class="add_to_wishlist like-icon <?=A::$app->Wishlist->check($r["id"])?'active':''?>" data-id="<?=$r["id"]?>"> В избранное</a></div>

                                                        </div>
                                                        <div class="clear"></div><a href="javascript:;" class="add-to-compare-link compare-icon <?=A::$app->Simile->check($r["id"])?'active':''?>" data-id="<?=$r["id"]?>" data-product_id="39">Сравнить</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <?
                            }
                            ?>
                        </tr>
                        <tr>
                            <?
                            $i=1;
                            $j=1;
                            foreach($_SESSION["compare"] as $k=>$v)
                            {

                                $s=A::$db->query("select * from i_cat_elements where id=".$v."");
                                $r=$s->fetch_array();


                                ?>
                                <td align="center" valign="top" class="pad_tabl compare-item<?=$v?>" >
                                    <b>Цена: <?=@$r["price"]?> тг.</b>
                                </td>
                                <?
                            }
                            ?>
                        </tr>
                        <tr>
                            <?
                            $i=1;
                            $j=1;
                            foreach($_SESSION["compare"] as $k=>$v)
                            {

                                $s=A::$db->query("select * from i_cat_elements where id=".$v."");
                                $r=$s->fetch_array();

                                ?>
                                <td align="center" valign="top" class="pad_tabl compare-item<?=$v?>" >
                                    Призводитель: <?=$r["brand"]?>
                                </td>
                                <?
                            }
                            ?>
                        </tr>
                        <tr>
                            <?
                            $i=1;
                            $j=1;
                            foreach($_SESSION["compare"] as $k=>$v)
                            {

                                $s=A::$db->query("select * from i_cat_elements where id=".$v."");
                                $r=$s->fetch_array();




                                ?>
                                <td align="center" valign="top" class="pad_tabl compare-item<?=$v?>" style="font-size: 12px;">
                                    <?=$r["anounce"]?>
                                </td>
                                <?
                            }
                            ?>
                        </tr>



                    </table>
                    <style>
                        .pad_tabl table{ width:250px; border-collapse:collapse }
                        .pad_tabl table td{ padding:3px; border:1px solid #aaa; font-size:11px; color:#333; line-height:14px;}
                    </style>

                <? } ?>

            </main>
        </div>
    </div>
</div>
</div>
