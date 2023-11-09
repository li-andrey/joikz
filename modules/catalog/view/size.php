<? foreach($res as $k=>$r){ ?>
    <div class="item <?=$r["kol"]==0?'disabled':''?>" data-id='<?=$r["id_section"]?>' data-size="<?=$r["size"]?>">
        <?=$r["size"]?>
    </div>
    <? } ?>