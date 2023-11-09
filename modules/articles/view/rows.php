<?
if (sizeof($el)>0){
    ?>
    <div class="row align-items-start mb-30 justify-content-between">
        <? foreach($el as $k=>$r){ ?>
            <div class="col-md-3">
                <div class="item">
                  <div class="image">
                    <a href="<?=LINK?>articles/<?=$r["url"]?>/"><img src="/upload/images/small/<?=$r["image"]?>" alt=""> </a>
                </div>
                <div class="name">
                    <h4><a href="<?=LINK?>articles/<?=$r["url"]?>/"><?=$r["name"]?></a></h4>
                </div>
                <div class="anounce">
                    <?=$r["anounce"]?>
                </div>

            </div>
        </div> 
        <? if ($k==2){ ?>
        </div><div class="row align-items-start mb-30 justify-content-between">
        <? } ?>
    <? } ?>
</div>
<? } ?>