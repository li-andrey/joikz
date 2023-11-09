<?
$this->meta(
    array(
        'h1' => '',
        'title' => '',
        'descr' => '',
        'keyw' => '',
    )
);
$this->header();
?>
<? $slider = A::$app->slider->m(1); ?>
<? if ($slider && sizeof($slider["e"])>0){ ?>
    <div class="swiper">
        <div class="swiper-wrapper">
            <? foreach ($slider["e"] as $key => $r) { ?>

                <div class="swiper-slide">
                    <a href="<?=$r["link"]?>">
                        <img src="<?=$r["image"]["o"]?>" class="d-none d-sm-block" alt="" />
                        <img src="<?=$r["image1"]["o"]?>" class="d-block d-sm-none" alt="" />
                    </a>
                </div>
            <? } ?>
        </div>
        <div class="swiper-pagination swiper-pagination-clickable"></div>
    </div>
<? } ?>
<? $this->footer(); ?>