$(function(){
    $('#login-form').ajaxForm({
        dataType: 'json',
        beforeSubmit : function(arr, $form, options){
            if ($('#login-form .has-error').length>0){
                return false;
            }
        },
        success: function(data){
            if (data.success=='1'){
                $('.login-result').html(data.msg).hide();
                goItem('login-top');

            }else{
                $('.login-result').attr('class','login-result alert alert-danger').html(data.msg).show();
                goItem('login-top');
            }
        }
    });
});

function goItem(id){
    id = id.replace("#", "").replace(" ", "");
    $('html,body').animate({ scrollTop: $("#"+id).offset().top-165}, 'slow');
}