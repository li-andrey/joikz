<div class="section-full p-tb80 inner-page-padding">
    <!-- LOCATION BLOCK-->
    <div class="container">

        <!-- GOOGLE MAP & CONTACT FORM -->
        <div class="section-content">
            <!-- CONTACT FORM-->
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <form class="contact-form cons-contact-form bg-gray p-a30" name="contactForm" onsubmit="return feedback()" method="post" action="">
                        <input type="hidden" name="do" value="feedback">
                        <div class="contact-one">

                            <!-- TITLE START -->
                            <div class="section-head">
                                <div class="sx-separator-outer separator-left">
                                    <div class="sx-separator bg-white bg-moving bg-repeat-x" style="background-image: url(<?=ASSETS?>images/background/cross-line2.png); background-position: -2108px 0px;">
                                        <h3 class="sep-line-one">Обратная связь</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- TITLE END -->

                            <div class="form-group">
                                <input name="name" type="text" required="" class="form-control" placeholder="Имя ">
                            </div>

                            <div class="form-group">
                                <input name="email" type="email" class="form-control" required="" placeholder="Email">
                            </div>

                            <div class="form-group">
                                <textarea name="message" rows="4" class="form-control " required="" placeholder="Сообщение"></textarea>
                            </div>

                            <div class="text-right">
                                <button name="submit" type="submit" value="Submit" class="site-button btn-half"><span> Отправить</span>

                                </button>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="contact-info block-shadow bg-white bg-center p-a40" style="background-image:url(<?=ASSETS?>images/background/bg-map.png)">
                        <?=$e["text"]?>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="gmap-outline">
    <?=A::$app->wf(10, ['f'=>'text'])?>
</div>