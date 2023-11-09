<? if ($guest = A::$app->wf(1, 'guest')) { ?>
    <div class="vc_row wpb_row vc_row-fluid page-margin-top-section">
        <div class="wpb_column vc_column_container vc_col-sm-12">
            <div class="wpb_wrapper">
                <div class="wpb_text_column wpb_content_element  large align-center margin-top-20">
                    <div class="wpb_wrapper">
                        <p><?= $guest["guest"] ?>
                        </p>

                    </div>
                </div>
                <div class="clearfix scrolling-controls">
                    <div class="header-left"></div>
                    <div class="header-right"><a href="#" id="carousel_prev"
                                                 class="scrolling-list-control-left template-arrow-horizontal-3"></a><a
                                href="#" id="carousel_next"
                                class="scrolling-list-control-right template-arrow-horizontal-3"></a></div>
                </div>
                <ul class="mc-gallery gallery-3-columns dm_carousel horizontal-carousel carousel id-carousel autoplay-0 pause_on_hover-1 scroll-1 effect-scroll easing-swing duration-500">
                    <li class="gallery-box gallery-box-1 hover-icons-off" id="gallery-item-ann-blyumin-prof">
                        <? $i = 1;
                        if ($info = A::$app->guest->bs($guest["slug"], true)) { ?>
                            <? foreach ($info as $k => $r) { ?>
                                
                                <?= $r["guest"] ?>
                                
                                
                                <? if ($i == 2) {
                                    $i = 0;
                                    echo '</li><li class="gallery-box gallery-box-1 hover-icons-off" id="gallery-item-ann-blyumin-prof">';
                                }
                                $i++;
                            } ?>
                        <? } ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<? } ?>