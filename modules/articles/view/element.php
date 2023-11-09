<style>
    <? if ($block["image"]!=''){ ?>
    #banner-section-1532936603488 .animation-banner {
        background-image: url(/upload/images/<?=$block["image"]?>) !important;
    }

    <? } ?>
</style>

<?
$res = A::$app->news->bs($e["id_section"], true, 5);
?>
<section id='content-section-1528971561366' class='astroid-section astroid-component-section py-3 py-md-5' style=''
         data-animation='' data-animation-delay=''>
    <div class='container'>
        <div id="astroid-row-1528971561366" class="row astroid-row">
            <div id="astroid-column-1528971561366" class="astroid-column col-lg-<?= sizeof($res) > 0 ? '9' : '12' ?>"
                 style="" data-animation=""
                 data-animation-delay="">

                <div class="astroid-message" id="messages-1528971711775" style="" data-animation=""
                     data-animation-delay="">
                    <div id="system-message-container">
                    </div>
                </div>
                <div class="astroid-component" id="component-area-1529739248826" style="" data-animation=""
                     data-animation-delay="">
                    <div class="astroid-component-area">
                        <div class="blog" itemscope itemtype="https://schema.org/Blog">
                            <?
                            $t = str_replace(array('btgrid'), ' ', str_replace(array('<p'), '<p class="moto-text_system_10" ', str_replace(array('<li>'), '<li class="moto-text_system_10"> ', $e["text"])));
                            
                            $t = str_replace(array('<ul>'), '<ul class="list-marked"> ', $t);
                            echo $t;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <? if (sizeof($res) > 0) { ?>
                <div id="astroid-column-1528971561366" class="astroid-column col-lg-3" style="" data-animation=""
                     data-animation-delay="">
                    <p><b style="font-size: 20px;">Другие статьи:</b></p>
                    <ul class="list list-unstyled">
                        <? foreach ($res as $k => $r) { ?>
                            <? if ($r["id"] != $e['id']) { ?>
                                <li>
                                    <a <?= strstr(A::$app->path(), $r["url"]) ? 'class="active"' : '' ?>href="<?= LINK ?>articles/<?= $r["url"] ?>/"><?= $r["name"] ?></a>
                                </li>
                            <? } ?>
                        <? } ?>
                    </ul>
                </div>
            <? } ?>
        </div>
    </div>
</section>
