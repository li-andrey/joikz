<?
if (sizeof($el)>0){
    ?>
    <div class="row">
        <? foreach($el as $k=>$r){ ?>
            <div class="col-6 col-md-3">
                <div class="item-good">
                    <div class="image">
                        <div class="sale"> <?=t('sale')?> <?=round($r["price"]/$r["price_old"]*100)?>%</div>
                        <a href="/upload/images/<?=$r["image"]?>" data-fancybox="gallery<?=$r["id"]?>">
                            <? if ($r["image"]!=''){ ?>
                                <img src="/upload/images/small/<?=$r["image"]?>" alt="" />
                            <? } ?>
                        </a>
                    </div>
                    <div class="info">
                        <div class="price">
                            <?=price($r["price"], 1)?> тг. 
                            <? if ($r["price_old"]>0) { ?>
                                / <u><?=price($r["price_old"], 1)?> тг.</u>
                            <? } ?>
                        </div>
                        <div class="name">
                            <?=$r["name"]?>
                        </div>
                    </div>
                </div>
            </div>
            <? if ($k==3){ ?>
            </div><div class="row">
            <? } ?>
        <? } ?>
    </div>
<? } ?>