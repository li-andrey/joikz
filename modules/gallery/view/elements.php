<?
$gallery = A::$app->gallery->m(1);
?>
<? if (sizeof($gallery["b"]) > 0) { ?>
    <!-- ALL PROJECTS START -->
    <div class="section-full  mobile-page-padding bg-white  p-t80 p-b80">

        <div class="container">

            <!-- TITLE END -->
            <!-- Filter Nav START -->
            <div class="filter-wrap p-b30 text-center">
                <ul class="filter-navigation masonry-filter clearfix">
                    <li class="active"><a class="btn from-top" data-filter="*" href="#" data-hover="All">Все</a></li>
                    <? foreach ($gallery["b"] as $k => $r) { ?>
                        <li><a class=" btn from-top" data-filter=".cat-<?= $r["id"] ?>" href="#"><?= $r["name"] ?></a>
                        </li>
                    <? } ?>
                </ul>
            </div>
            <!-- Filter Nav END -->

            <!-- GALLERY CONTENT START -->
            <ul class="masonry-outer mfp-gallery work-grid row clearfix list-unstyled m-b0">
                <? foreach ($gallery["b"] as $k => $r) { ?>
                    <?
                    $res = A::$app->gallery->elements($r["id"]);
                    foreach ($res as $re) {
                        ?>
                        <!-- COLUMNS 1 -->
                        <li class="masonry-item cat-<?= $r["id"] ?> col-lg-4 col-md-6 col-sm-12 m-b30">
                            <div class="sx-box   image-hover-block">
                                <div class="sx-thum-bx">
                                    <img src="<?= $re["image"]["b"] ?>" alt="">
                                </div>
                                <div class="sx-info  p-t20 text-white">
                                    <h4 class="sx-tilte"><a><?= $re["name"] ?></a></h4>
                                    <p class="m-b0"><?= $re["anounce"] ?></p>
                                </div>
                                <a class="mfp-link" href="<?= $re["image"]["o"] ?>">
                                    <i class="fa fa-arrows-alt"></i>
                                </a>
                            </div>
                        </li>
                    <? } ?>
                <? } ?>

            </ul>
            <!-- GALLERY CONTENT END -->

            
        </div>
    </div>
    <!-- WHAT WE DO END -->
<? } ?>
