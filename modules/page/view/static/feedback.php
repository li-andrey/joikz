
<style type="text/css">
    <? if ($e["image"]!=''){ ?>
        .section-page-title{
            background-image: url(/upload/images/<?=$e["image"]?>) !important;
        }
    <? } ?>
</style>
<section class="section section-md">
    <div class="container">
      <div class="row row-50">
        <div class="col-lg-8">
          <h2>Форма обратной связи</h2>
          <div class="divider-lg"></div>
          <p>Вы всегда можете связаться с нами и задать любой вопрос.</p>
          <!-- RD Mailform-->
          <form class="rd-mailform text-left rd-form"  name="contactForm" onsubmit="return feedback()" method="post" action="">
            <input type="hidden" name="do" value="feedback">
            <div class="row row-15">
              <div class="col-sm-6">
                <div class="form-wrap">
                  <label class="form-label" for="contact-name">Имя</label>
                  <input class="form-input" id="contact-name" type="text" name="name" required>
              </div>
          </div>
          <div class="col-sm-6">
            <div class="form-wrap">
              <label class="form-label" for="contact-sec-name">Фамилия</label>
              <input class="form-input" id="contact-sec-name" type="text" name="fam" required>
          </div>
      </div>
      <div class="col-sm-6">
        <div class="form-wrap">
          <label class="form-label" for="contact-phone">Контактный телефон</label>
          <input class="form-input" id="contact-phone" type="text" name="phone" required>
      </div>
  </div>
  <div class="col-sm-6">
    <div class="form-wrap">
      <label class="form-label" for="contact-email">E-Mail</label>
      <input class="form-input" id="contact-email" type="email" name="email" >
  </div>
</div>
<div class="col-12">
    <div class="form-wrap">
      <label class="form-label" for="contact-message">Сообщение</label>
      <textarea class="form-input" id="contact-message" name="message" ></textarea>
  </div>
</div>
</div>
<div class="form-button group-sm text-left">
  <button class="button button-primary" type="submit">Отправить</button>
</div>
</form>
</div>
<div class="col-lg-4">
    <?
    $t = str_replace(array( 'btgrid'), ' ', $e["text"]);
    $t = str_replace(array('<ul>'), '<ul class="list-marked"> ', $t);
    echo $t;
    ?>

</div>
</div>
</div>
</section>
