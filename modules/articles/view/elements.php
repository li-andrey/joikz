<div class="news-section">
    <? if (sizeof($elements) > 0){ ?>
    <div class="container">
        <div class="blog-inner">
            <? $i = 1;
            foreach ($elements as $k => $r) { ?>
                <div class="col-lg-3 col-md-3 col-sm-3 blog-preview_item">
                    <div class="blog-col">

                        <div class="entry-thumb image-hover2">
                            <a href="/articles/<?= $r["url"] ?>/">
                                <img src="/upload/images/small/<?= $r["image"] ?>"
                                     alt="<?= $r["name"] ?>" />
                            </a>
                        </div>
                        <!--entry-thumb image-hover2-->
                        <h4 class="blog-preview_title"><a
                                    href="/articles/<?= $r["url"] ?>/"><?= $r["name"] ?></a>
                        </h4>
                        <div class="blog-preview_info">
                            <ul class="post-meta">

                                <li><i class="fa fa-comments"></i>0 комментариев</li>
                                <li><i class="fa fa-clock-o"></i>
                                    <time class="entry-date"
                                          datetime="2017-08-21T13:21:14+00:00"><?= A::$api->date(LANG, $r["data"], 'sql', 'datetext') ?>
                                    </time>
                                </li>
                            </ul>
                            <!--post-meta-->

                            <div class="blog-preview_desc"><?= $r["anounce"] ?>
                            </div>
                            <a class="blog-preview_btn"
                               href="/articles/<?= $r["url"] ?>/">Подробнее</a>
                        </div>
                        <!--blog-preview_info-->
                    </div>
                    <!--blog-col-->
                </div>
                <?
                if ($i == 3) {
                    $i = 0;
                    echo '</div><div class="blog-inner">';
                }
                $i++;
                ?>
            <? } ?>
        </div>
    </div>
    <? } ?>
</div>
