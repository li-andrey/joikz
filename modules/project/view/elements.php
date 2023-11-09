<?
$fon = A::$app->wf(42, 'block_elements');
$img = @$fon["image"];
if ($block["image"]!=''){
    $img = $block["image"];
}
?>

<div class="dlab-bnr-inr overlay-black-middle text-center bg-pt" style="background-image:url(/upload/images/<?=$img?>);">
    <div class="container">
        <div class="dlab-bnr-inr-entry align-m text-center">
            <h1 class="text-white"><?=$block["name"]?></h1>
            <div class="breadcrumb-row">
                <ul class="list-inline">
                    <?=A::$app->breadcrumb($block["name"])?>
                </ul>
            </div>
        </div>
    </div>
</div>
<? if ($project = A::$app->wf(2, 'cat')) { ?>
    <section class="projects wow zoomIn" id="projects" style="padding: 5rem 0">
        <div class="dotted">
            <div class="dot-3"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="section-title">
                        <h3><?= $project["name"] ?></h3>
                        <p>
                            <?= $project["anounce"] ?>
                        </p>
                        <div class="nav flex-column project-tabs" id="v-pills-tab" role="tablist"
                             aria-orientation="vertical">
                            <?
                            $i = 1;
                            $res = A::$app->catalog->bb($project["slug"], 1, 4);
                            foreach ($res as $k => $r) {
                                ?>
                                <a class="nav-link <?= $k == 0 ? 'active' : '' ?>"
                                   id="v-pills-project<?= $r["id"] ?>-tab" data-toggle="pill"
                                   href="#v-pills-project<?= $r["id"] ?>"
                                   role="tab" aria-controls="v-pills-project<?= $r["id"] ?>" aria-selected="true">
                                    <?= $r["name2"] ?> <i class="fas fa-angle-right"></i>
                                </a>
                            <? } ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <?
                        $i = 1;
                        $res = A::$app->catalog->bb($project["slug"], 1, 4);
                        foreach ($res as $k => $r) {
                            ?>
                            <div class="tab-pane fade <?= $k == 0 ? ' show active' : '' ?>"
                                 id="v-pills-project<?= $r["id"] ?>" role="tabpanel"
                                 aria-labelledby="v-pills-project<?= $r["id"] ?>-tab">
                                <div class="row ">

                                    <div class="col-md-7">
                                        <div class="project-slider">
                                            <div class="d-block d-sm-none name">
                                                <?= $r["name"] ?>
                                            </div>
                                            <div class="slider project-slider-tabs<?= $k ?>">
                                                <? if ($r["image"] != '') { ?>
                                                    <div class="slider-item">
                                                        <img src="/upload/images/small/<?= $r["image"] ?>" alt="" />
                                                    </div>
                                                <? } ?>
                                                <? if ($r["image1"] != '') { ?>
                                                    <div class="slider-item">
                                                        <img src="/upload/images/small/<?= $r["image1"] ?>" alt="" />
                                                    </div>
                                                <? } ?>
                                                <? if ($r["image2"] != '') { ?>
                                                    <div class="slider-item">
                                                        <img src="/upload/images/small/<?= $r["image2"] ?>" alt="" />
                                                    </div>
                                                <? } ?>
                                                <? if ($r["image3"] != '') { ?>
                                                    <div class="slider-item">
                                                        <img src="/upload/images/small/<?= $r["image3"] ?>" alt="" />
                                                    </div>
                                                <? } ?>
                                                <? if ($r["image4"] != '') { ?>
                                                    <div class="slider-item">
                                                        <img src="/upload/images/small/<?= $r["image4"] ?>" alt="" />
                                                    </div>
                                                <? } ?>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="project-info">
                                            <div class="logo">
                                                <img src="/upload/images/<?= $r["logo"] ?>" alt="" />
                                            </div>
                                            <div class="number">
                                                0<?= $k + 1 ?>
                                            </div>
                                            <div class="d-none d-sm-block name">
                                                <?= $r["name"] ?>
                                            </div>
                                            <div class="anounce">
                                                <?= $r["anounce"] ?>
                                            </div>
                                            <div class="btn-group">
                                                <a href="<?= LINK ?>project/<?= $r["url"] ?>/"
                                                   class="btn btn-danger rounded-0">Подробнее</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? } ?>


                    </div>
                </div>
            </div>
        </div>
    </section>
<? } ?>