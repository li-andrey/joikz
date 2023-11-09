<div id="add_message">
  <?=$this->t('add_success')?>
  <p>
    <input type="button" class="btn" onClick="add_hide();"  value="<?=$this->t('add_close')?>">
    <input type="button" class="btn" onClick="location.href='<?=A::$app->link()?>basket/';" value="<?=$this->t('add_basket')?>">
  </p>
</div>
