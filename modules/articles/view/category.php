  <div class="news-section">
    <? if (sizeof($elements)>0){ ?>
        <div class="container">
          <div class="row align-items-end mb-30">


            <?
            $i = 1;
            foreach ($elements as $r){ ?>
             <div class="col-md-3">
                                <div class="item">
                                    <div class="image">
                                        <a href="<?=LINK?>articles/<?=$r["url"]?>/"><img src="/upload/images/small/<?=$r["image"]?>" alt=""> </a>
                                    </div>
                                    <div class="name">
                                        <h4><a href="<?=LINK?>articles/<?=$r["url"]?>/"><?=$r["name"]?></a></h4>
                                    </div>
                                    <div class="anounce">
                                        <?=$r["anounce"]?>
                                    </div>
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto">
                                            <a href="<?=LINK?>articles/<?=$r["url"]?>/" class="news-btn"><?=t('more')?></a>
                                        </div>
                                        <div class="col-auto">
                                            <span><?=date('d.m.Y', strtotime($r["data"]))?> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
            <?
            if($i==2) $i=0;
            $i++;
          }
          ?>
        </div>
        <div class=" row ">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div style="text-align: center;"><?=$pagination->show()?></div>
          </div>
        </div>
      </div>
    <style>
      .content.clients{
        display: none;
      }
      .content.news{
        display: none;
      }
      .news-section{
        background: none;
        padding: 3rem 0px;
      }
      .mb-30{
        margin-bottom: 3rem;
      }
    </style>

  <? } ?>
</div>