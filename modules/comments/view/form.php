<hr><form class="" method="post" action="" id="comment-form" name="comment_form"  onsubmit="return send_comment();">
    <h3 id="review-form">Написать отзыв</h3>
    <input name="form_key" type="hidden" value="T07uzBAUqtEJvMnO">
    <fieldset class="">
        
        <div class="review1">
            <ul class="form-list" style="clear:both;">
                <li>
                    <label for="nickname_field">Ваше имя<span class="required">*</span></label>
                    <div class="input-box">
                        <input type="text" name="name" id="nickname_field" required class="input-text required-entry" value="">
                    </div>
                </li>
                <li>
                    <label for="summary_field">E-mail<span class="required">*</span></label>
                    <div class="input-box">
                        <input type="email" name="email" id="summary_field" class="input-text required-entry" value="">
                    </div>
                </li>
            </ul>
        </div> <!--review1-->
        <div class="review2">
            <ul class="form-list">
                <li>
                    <label for="review_field">Отзыв<span class="required">*</span></label>
                    <div class="input-box">
                        <textarea name="review" id="review_field" required cols="5" rows="3" class="required-entry"></textarea>
                    </div>
                </li>
            </ul>
            <div class="buttons-set">
                <button type="submit" title="Submit Review" class="button submit"><span>Отправить</span></button>
            </div>
        </div> <!--review2-->
    </fieldset>
    <input type="hidden" name="module" value="<?=$module?>">
    <input type="hidden" name="idd" value="<?=$idd?>">
    <input type="hidden" name="ids" value="">
    <input type="hidden" name="do" value="addcomment">
</form>



<script type="text/javascript">
  function show_comment_form(){
    jQuery('#comment-form').slideToggle();
  }
  function send_comment(){
    var $form = jQuery('form[name=comment_form]');
    jQuery.ajax({
      url: "/",
      data: $form.serialize(),
      type: "POST",
      dataType: "JSON",
      cache: false,
      success: function (data) {
        if (data.success==1){
          alert(data.msg);
          show_comment_form();
          $form[0].reset();
        }else{
          alert(data.error);
        }
      }
    })
    return false;
  }
  function replay_to(id){
    jQuery('form[name=comment_form] input[name=ids]').val(id);
    jQuery('#comment-form').slideDown();
  }
</script>