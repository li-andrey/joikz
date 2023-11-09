<ul class="sx-breadcrumb breadcrumb-style-2">
    <?
    $sep = '';
    ?>
    <li class=""><a class="bread-link bread-home" href="/" title=""><?= $this->t('main') ?></a></li>
    <? foreach ($e as $k => $r) { ?>
        <? if ($k == (sizeof($e) - 1)) { ?>
            <?= $sep ?>
            <li class=" active"><strong><?= $r["name"] ?></strong></li>
        <? } else { ?>
            <?= $sep ?>
            <li class=""><a class="bread-link " href="<?= $r["link"] ?>"><?= $r["name"] ?></a></li>
        <? } ?>
    <? } ?>
</ul>
