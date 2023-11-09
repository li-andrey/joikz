<h1 class="page-title"><?=$block["name"]?></h1>

<? if (sizeof($subBlock)>0){ ?>
    <div class="category-meta with-grid visible">
        <ul class="link-list">
            <li class="<?=2==$block["id"]?'active':''?>">
                <a href="/actions/">
                    Все акции
                </a>
            </li>
            <? foreach ($subBlock as $k => $r ){ ?>
            <li class="<?=$r["id"]==$block["id"]?'active':''?>">
                <a href="/actions/<?=$r["url"].'/'?>">
                   <?=$r["name"]?>
                </a>
            </li>
            <? } ?>
            

        </ul>
    </div>


<? } ?>

    <? if (sizeof($elements) > 0){ ?>
 
                <? $i = 1;
                foreach ($elements as $k => $r) { ?>
                    <div class="action-row">
                        <div class="action-banner <?=$i==1?'gray':'light'?>" id="bx_3218110189_54451" onclick="location = '<?=$r["furl"]?>'">
                            <div class="action-text">
                                <h4> <?=$r["name"]?></h4>
                                <a href="<?=$r["furl"]?>" class="btn bordered">
                                    Подробнее            </a>
                            </div>
                            <div class="action-img">
                                <img class=" lazyloaded" data-src="<?=$r["image"]["b"]?>" alt="" src="<?=$r["image"]["b"]?>">
                            </div>
                            <span class="action-label">СКИДКА</span>
                        </div>            </div>
                   
                    <?
                    if ($i == 2) {
                        $i = 0;
                        echo '<div class="clearfix"></div>';
                    }
                    $i++;
                    ?>
                <? } ?>

   
    <? } ?>
