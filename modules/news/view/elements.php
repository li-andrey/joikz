<h1 class="page-title"><?=$block["name"]?></h1>
    <? if (sizeof($elements) > 0){ ?>
    <div class="news">
                <? $i = 1;
                foreach ($elements as $k => $r) { ?>
                    <div class="news-item">
                        <a href="<?=$r["furl"]?>">
                            <img src="<?=$r["image"]["s"]?>"
                                 alt="<?=$r["name"]?>">
                        </a>
                        <a href="<?=$r["furl"]?>" class="news-title">
                            <?=$r["name"]?> </a>
                        <p><?=$r["anounce"]?>
                        </p>
                        <span><?=A::$api->date(LANG, $r["data"], 'sql', 'datetext')?></span>
                    </div>
                    <?
                    if ($i == 3) {
                        $i = 0;
                        echo '<div class="clearfix"></div>';
                    }
                    $i++;
                    ?>
                <? } ?>

        </div>
    <? } ?>
</div>