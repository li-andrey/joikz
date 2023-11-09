<fieldset id="contactUsForm">
  <legend><?=$this->t("title")?></legend>
  <div id="guest_form">
  <a href="#guest" id="click_to_guest" style=" display:none;">
  перейти
  </a>
  
  <div id="guest_info" style="display:none">
  </div>
  
  <div id="guest_msg" style="display:none">
  </div>
  <br />
  
  
  
  
  <table width="100%">
    <tr>
      <td width="150" align="right" style="padding:5px;">
        <?=$this->t("name")?> *
      </td>
      <td align="left" style="padding:5px;">
        <input type="text" id="guest_name" style="width:300px;" class="guest_field">
      </td>
    </tr>
    <tr>
      <td width="150" align="right" style="padding:5px;">
        <?=$this->t("email")?>
      </td>
      <td align="left" style="padding:5px;">
        <input type="text" id="guest_mail" style="width:300px;" class="guest_field">
      </td>
    </tr>
    <tr>
      <td width="150" align="right" style="padding:5px;">
        <?=$this->t("text")?>
      </td>
      <td align="left" style="padding:5px;">
        <textarea id="guest_text" style="width:300px; margin:0px; min-height:70px; height:70px;" class="guest_field">
</textarea>
      </td>
    </tr>
    <tr>
      <td width="150" align="left" style="padding:5px;">&nbsp;
      </td>
      <td align="left" style="padding:5px;">
        <input type="button" id="guest_send" class="btn " value="<?=$this->t("send")?>" style="width:300px;">
        <input type="hidden" id="token" value="<?=$token?>">
      </td>
    </tr>
  </table>
</div>
</fieldset>
<script>


jQuery(function(){
	<?
	if ($this->check_admin()){			
	?>
		jQuery(".admin_mod").click(function(){
			if (jQuery(this).attr("checked")){
				var act = 1;	
			}else{
				var act = 0;	
			}
			jQuery.post("/<?=$this->lang?>/guest.php",{ 
				id:jQuery(this).attr("rel"),
				guest_act:act
			}, function(data){
			})
	  });

	<?				
	}
	?>
	jQuery("#guest_send").click(function(){
		var msg ="";
		if (jQuery("#guest_name").val()=="") {jQuery("#guest_name").css("border","1px solid red");
			msg=1;
		}
		if (jQuery("#guest_text").val()=="") {jQuery("#guest_text").css("border","1px solid red");
			msg=1;
		}
		if (msg!=""){
			alert("<?=$this->t("error")?>");	
		}else{
			jQuery.post("/<?=$this->lang?>/guest.php",{
				name:jQuery("#guest_name").val(),
				mail:jQuery("#guest_mail").val(),
				text:jQuery("#guest_text").val(),
				token:jQuery("#token").val()
			}, function(data){
				jQuery("#guest_msg").html(data);
				setTimeout(function() { jQuery("#guest_info").slideUp("slow").html("");  }, 2500);
			})	
		}	
	})	
})
</script>