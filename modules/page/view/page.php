<style>
    <? if ($e["image"]['o']!=''){ ?>
    .sx-bnr-inr {
        background-image: url(<?=$e["image"]['o']?>) !important;
    }

    <? } ?>
</style>

<? if (strstr($e["text"], 'container')) { ?>
    <?
    $t = str_replace(array('btgrid'), ' ', str_replace(array('<p'), '<p ', str_replace(array('<li>'), '<li> ', $e["text"])));
    
    $t = str_replace(array('<ul>'), '<ul> ', $t);
    echo $t;
    ?>
<? } else { ?>
    <div class="service-info contacts">
        
        <?
        $t = str_replace(array('btgrid'), ' ', str_replace(array('<p'), '<p ', str_replace(array('<li>'), '<li> ', $e["text"])));
        
        $t = str_replace(array('<ul>'), '<ul> ', $t);
        echo $t;
        ?>
    </div>

<? } ?>


<? if ($e["id"] == 1) { ?>
    <? $news = A::$app->news->m(1, ["limit" => 3]); ?>
    <? if (sizeof($news["e"]) > 0) { ?>
        <div id="buyer-slider">
            <div class="block-title">
                <h3><?= $news["name"] ?></h3>
            </div>
            <div class="news">
                <? foreach ($news["e"] as $l => $r) { ?>
                    <div class="news-item">
                        <a href="<?= $r["furl"] ?>">
                            <img src="<?= $r["image"]["s"] ?>"
                                 alt="<?= $r["name"] ?>">
                        </a>
                        <a href="<?= $r["furl"] ?>" class="news-title">
                            <?= $r["name"] ?> </a>
                        <p><?= $r["anounce"] ?>
                        </p>
                        <span><?= A::$api->date(LANG, $r["data"], 'sql', 'datetext') ?></span>
                    </div>
                <? } ?>
                <div class="clearfix"></div>
            </div>
        </div>
    <? } ?>
<? } ?>
