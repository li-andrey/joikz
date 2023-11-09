<? if ($guest = A::$app->guest->bs(1)){ ?>
    <section class="section section-lg bg-default text-center">
        <div class="container">
          <? if ($guestInfo = A::$app->guest->b(1)){ ?>
            <h2><?=$guestInfo["name"]?></h2>
            <div class="divider-lg"></div>
        <? } ?>
    </div>
    <div class="container">
      <!-- Owl Carousel-->
      <div class="owl-carousel" data-items="1" data-lg-items="2" data-dots="true" data-nav="false" data-stage-padding="15" data-loop="false" data-margin="30" data-mouse-drag="false">
        <? foreach($guest as $r){ ?>
            <div class="quote-corporate">
              <div class="quote-header">
                <h4><?=$r["name"]?></h4>
                <p class="big"><?=$r["dol"]?></p>
            </div>
            <div class="quote-body">
                <div class="quote-text">
                  <p><?=nl2br($r["guest"])?></p>
              </div>
              <svg class="quote-body-mark" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="66px" height="49px" viewbox="0 0 66 49" enable-background="new 0 0 66 49" xml:space="preserve">
                  <g></g>
                  <path d="M36.903,49v-3.098c9.203-5.315,14.885-12.055,17.042-20.222c-2.335,1.524-4.459,2.288-6.37,2.288                        c-3.186,0-5.875-1.29-8.071-3.876c-2.194-2.583-3.293-5.74-3.293-9.479c0-4.133,1.443-7.605,4.327-10.407                       C43.425,1.405,46.973,0,51.185,0c4.213,0,7.735,1.784,10.566,5.352C64.585,8.919,66,13.359,66,18.669                       c0,7.482-2.85,14.183-8.549,20.112C51.751,44.706,44.902,48.112,36.903,49z M0.69,49v-3.098                        c9.205-5.315,14.887-12.055,17.044-20.222c-2.335,1.524-4.478,2.288-6.423,2.288c-3.152,0-5.823-1.29-8.02-3.876                        C1.096,21.51,0,18.353,0,14.614c0-4.133,1.434-7.605,4.301-10.407C7.168,1.405,10.709,0,14.92,0c4.247,0,7.778,1.784,10.592,5.352                       c2.814,3.567,4.223,8.007,4.223,13.317c0,7.482-2.843,14.183-8.524,20.112C15.53,44.706,8.69,48.112,0.69,49z"></path>
              </svg>
          </div>
          <div class="quote-image"><img src="/upload/images/big/<?=$r["image"]?>" alt="" width="90" height="90"/>
          </div>
      </div>
  <? } ?>

</div>
</div>
</section>
<? } ?>