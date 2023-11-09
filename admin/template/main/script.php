<script src="<?= ASSETS ?>js/jquery-3.5.1.min.js"></script>
<script src="<?= ASSETS ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= ASSETS ?>js/swiper-bundle.min.js"></script>
<script src="<?= ASSETS ?>js/slick.min.js"></script>
<script src="<?= ASSETS ?>js/script.js"></script>
<script src="/upload/js/jquery.maskedinput-1.2.2.js" type="text/javascript"></script>
<script type="text/javascript">
    (function($) {

        jQuery('input[name=phone]').mask('+7 (999) 999-99-99');

        $('.colors .item').on('click', function(e) {
            $('.colors .item').removeClass('active');
            $(this).addClass('active');

            $.getJSON('/', {
                do: 'load-sizes',
                color: $(this).data("color"),
                id: $(this).data('id')
            }, function(data) {
                $('.sizes').html(data.result.html);
                jQuery(".sizes").removeClass("error")
            })

            $.getJSON('/', {
                do: 'load-images',
                color: $(this).data("color"),
                id: $(this).data('id')
            }, function(data) {
                $('.gallery').html(data.result.html);

                $('.slider-for').slick({
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    arrows: true,
                    infinite: true,
                    dots: true,
                    appendDots: $('.appendDots'),
                    responsive: [{
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }]
                });
            })
        })
        $(document).on('click', '.sizes .item', function(e) {

            if (!$(this).hasClass('disabled')) {
                $('.sizes .item').removeClass('active');
                $(this).addClass('active');
                jQuery(".sizes").removeClass("error")
            }
        })

        $(document).on('click', '.plus', function(e) {
            e.preventDefault()
            var $a = parseInt($(this).prev().val());
            $(this).prev().val($a + 1).trigger('change');
        })
        $(document).on('click', '.minus', function(e) {
            e.preventDefault()
            var $a = parseInt($(this).next().val());
            var $b = $a - 1;
            if ($b == 0) $b = 1;
            $(this).next().val($b).trigger('change');
        })
        jQuery('#country').change(function() {
            jQuery.getJSON('/', {
                do: 'city',
                id: jQuery(this).val()
            }, function(data) {
                if (data.success == '1') {
                    jQuery('#city').html(data.city).change();
                } else {
                    alert('Ошибка');
                }
            })
        })

        jQuery('#city').change(function() {
            jQuery.getJSON('/', {
                do: 'dost',
                id: jQuery(this).val()
            }, function(data) {
                if (data.success == '1') {
                    jQuery('.dost-variant').html(data.dost);
                    dostCount()
                } else {
                    alert('Ошибка');
                }
            })
        })
        jQuery('.dost-variant').on("click", "input[name=delivery]", function() {
            dostCount();
        })

        jQuery('.order-block select[name=city]').trigger("change");


        jQuery('#city').change();
        jQuery('.dost-variant').on("click", "input[name=delivery]", function() {
            dostCount();
            if (jQuery(this).attr("id") != 'delivery1') {
                jQuery('.delivery-input').removeClass('d-none');
                jQuery('input[name=adress]').attr("required", "required");
                jQuery('input[name=dom]').attr("required", "required");
                jQuery('input[name=kv]').attr("required", "required");
            } else {
                jQuery('.delivery-input').addClass('d-none');
                jQuery('input[name=adress]').removeAttr("required");
                jQuery('input[name=dom]').removeAttr("required");
                jQuery('input[name=kv]').removeAttr("required");
            }
        })

        jQuery('.order-block select[name=city]').trigger("change");

        jQuery(document).on("change", "#bonus", function() {

            if (jQuery('#itogo2').length > 0) {
                var i = parseInt(jQuery('#itogo2').html().replaceAll(' ', '').replace(' тг', ''));
                if (isNaN(i)) i = 0;
            } else {
                var i = 0;
            }

            var db = parseFloat(jQuery('#bonus-field').data("bonus"));
            var mb = (i * parseFloat(jQuery('#max-bonus').data("per")) / 100).toFixed(0);

            if (mb > db) {
                mb = db;
            }

            var v = parseInt(jQuery(this).val());

            if (v > mb) {
                jQuery(this).val(mb);
            }

            if (isNaN(v)) {
                jQuery(this).val(0);
            }

            itogo()
        })

        $(document).on('click', '.promocode-btn', function(e) {
            e.preventDefault();
            if ($('#promocode').val() != '') {
                $.getJSON('', {
                    do: 'promo',
                    value: $('#promocode').val(),
                    amount: $('#itogo').html()
                }, function(data) {
                    if (data.success == '1') {
                        if ($('#promocodes .row[data-id=' + data.id + ']').length == 0) {
                            $('#promocodes').html(data.promo);
                        }
                        $('#promocode-result').attr("class", "").html("");
                        alga('#order-info');
                        dostCount();
                        //$('#promocode').val("")
                    } else {
                        $('#promocodes').html("");
                        $('#promocode-result').attr("class", "alert alert-danger").html(data.msg);
                        dostCount();
                    }
                })
            } else {
                $('#promocode').focus()
            }
        })

        $(document).on('click', '.next-slide', function(e) {
            e.preventDefault();
            $('.slider-for').slick('slickNext');
        })
        $(document).on('click', '.prev-slide', function(e) {
            e.preventDefault();
            $('.slider-for').slick('slickPrev');
        })


        $('.filter-subject').on('click', function(e) {
            $(this).parent().toggleClass('active');
        })

    })(jQuery)

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ')
    }
    /*
  function dostCount() {
    if (jQuery('.dost-variant input[name=delivery]:checked').length > 0) {
        var price = parseInt(jQuery('.dost-variant input[name=delivery]:checked').data("price"));
        jQuery('#dost').html(formatNumber(price) + " тг.");
    } else {
        jQuery('#dost').html("-");
    }
    if (jQuery('#itogo').length > 0) {
        var i = parseInt(jQuery('#itogo').html().replace(' тг.', '').replace(' ', '').replace(' ', '').replace(' ', ''));

        if (isNaN(i)) i = 0;
    } else {
        var i = 0;
    }
    if (jQuery('#dost').length > 0) {
        var d = parseInt(jQuery('#dost').html().replace(' тг.', '').replace('-', '').replace(' ', '').replace(' ', '').replace(' ', ''));
        if (isNaN(d)) d = 0;
    } else {
        var d = 0;
    }
    if (jQuery('#dost1').length > 0) {
        var d1 = parseInt(jQuery('#dost1').html().replace(' тг.', '').replace('-', '').replace(' ', '').replace(' ', '').replace(' ', ''));
        if (isNaN(d1)) d1 = 0;
    } else {
        var d1 = 0;
    }
    if (jQuery('#skidak').length > 0) {
        var skidak = parseInt(jQuery('#skidak').html().replace(' тг.', '').replace('-', '').replace(' ', '').replace(' ', '').replace(' ', ''));
        if (isNaN(skidak)) skidak = 0;
    } else {
        var skidak = 0;
    }


    var amount = i + d - skidak;
    var amount1 = i + d1 - skidak;

    console.log(skidak);

    jQuery('input[name=delivery_price]').val(d);
    jQuery('#amount').html(new Intl.NumberFormat('ru-RU').format(amount) + " тг.");
    jQuery('#amount1').html(new Intl.NumberFormat('ru-RU').format(amount1) + " тг.");
}

function itogo() {
    if (jQuery('#itogo2').length > 0) {
        var i = parseInt(jQuery('#itogo2').html().replace(' тг', '').replace(' ', '').replace(' ', '').replace(' ', ''));
        if (isNaN(i)) i = 0;
    } else {
        var i = 0;
    }
    if (jQuery('input[name=delivery_price1]').length > 0) {
        var d1 = parseInt(jQuery('input[name=delivery_price1]').val().replace(' ', '').replace(' ', '').replace(' ', ''));

        if (isNaN(d1)) d1 = 0;
    } else {
        var d1 = 0;
    }

    if (jQuery('#skidak').length > 0) {
        var skidak = parseInt(jQuery('#skidak').html().replace(' тг', '').replace('-', '').replace(' ', '').replace(' ', '').replace(' ', ''));
        if (isNaN(skidak)) skidak = 0;
    } else {
        var skidak = 0;
    }




    var b = parseFloat(jQuery('#bonus').val());

    if (isNaN(b)) b = 0;

    console.log(i);
    console.log(d1);
    console.log(b);


    var amount2 = i + d1 - b-skidak;
    jQuery('#dost2').html(new Intl.NumberFormat('ru-RU').format(d1) + " тг.");
    jQuery('#amount2').html(new Intl.NumberFormat('ru-RU').format(amount2) + " тг.");
}
*/
    function is_mobile_or_tablet() {
        var check = false;
        (function(a) {
            if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4)))
                check = true;
        })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    }
</script>
<div class="modal fade" id="basket-box" tabindex="-1" role="dialog">
    <div class="modal-dialog login1">
        <div class="modal-content">
            <div class="user-box">
                <h3 style="text-align: center;">Товар успешно добавлен в корзину!</h3>
                <div class="dialog-footer" style="text-align: center;">
                    <button class="btn btn-outline-primary" onclick="jQuery('#basket-box').modal('hide')">Продолжить покупки</button>
                    <button class="btn btn-primary" onclick="location.href='/shop/cart/'">Перейти в корзину</button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.js"></script>

<script>
    var timwer = 60;
    var inter;

    function timerbi() {
        if (timwer > 0) {
            $('.send-replay span').html('(' + timwer + ')');
            $('.send-replay-register span').html('(' + timwer + ')');
            timwer--;
            if (timwer == 1) {
                clearInterval(inter);
                $('.send-replay-register').removeAttr("disabled").removeClass("disabled");
                $('.send-replay-register span').html('');
                $('.send-replay').removeAttr("disabled").removeClass("disabled");
                $('.send-replay span').html('');
                timwer = 60;
            }
        }
    }

    $(function() {
        $(document).on("click", '.slider-for .fancybox-wrapper', function(e) {
            e.preventDefault();
            $('a.fancybox-link[data-id=' + $(this).data('id') + ']').trigger('click');
        })


        $('input[name=phone]').on('keyup', function() {
            var $ap = $(this).val().replaceAll('_', '').replaceAll(' ', '').replaceAll('(', '').replaceAll(')', '').replaceAll('-', '').length;
            if ($ap == 12) {
                $('.btn-send-code').removeAttr("disabled");
                $('.btn-send-code-register').removeAttr("disabled");
            }
        })

        $('input[name=phone]').on('change', function() {
            var $ap = $(this).val().replaceAll('_', '').replaceAll(' ', '').replaceAll('(', '').replaceAll(')', '').replaceAll('-', '').length;
            if ($ap == 12) {
                $('.btn-send-code').removeAttr("disabled");
                $('.btn-send-code-register').removeAttr("disabled");
            }
        })

        $('input[name=code]').on('keyup', function() {
            if ($(this).val().length == 4) {
                $('.btn-check-code').removeAttr("disabled");
            }
        })
        $('input[name=code]').on('change', function() {
            if ($(this).val().length == 4) {
                $('.btn-check-code').removeAttr("disabled");
            }
        })


        $('.btn-send-code').on('click', function() {

            var $data = new FormData();
            $data.append('do', 'send-sms');
            $data.append('phone', $('.phone-code').val());

            var $btn = $(this);
            $btn.attr("disabled", "disabled");

            jQuery.ajax({
                url: "/",
                data: $data,
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                success: function(data) {
                    if (data.success == 1) {
                        $('.modal .close').hide();
                        $('input[name=code]').show().focus();
                        $('.btn-send-code').hide();
                        $('.send-replay').show();
                        $('.btn-check-code').show();
                        $('#register-result-code').attr("class", "alert alert-success").html(data.result.msg);
                        inter = setInterval("timerbi();", 1000)
                    } else {
                        $('#register-result-code').attr("class", "alert alert-danger").html(data.result.msg);
                    }
                    $btn.removeAttr("disabled");
                }
            })
        })

        $('.btn-send-code-register').on('click', function() {

            var $data = new FormData();
            $data.append('do', 'send-sms-for-register');
            $data.append('phone', $('.phone-code').val());

            var $btn = $(this);
            $btn.attr("disabled", "disabled");

            jQuery.ajax({
                url: "/",
                data: $data,
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                success: function(data) {
                    if (data.success == 1) {
                        $('input[name=code]').show().focus();
                        $('.btn-send-code-register').hide();
                        $('.send-replay-register').show();
                        $('.btn-check-code').show();
                        $('#register-result-code').attr("class", "alert alert-success").html(data.result.msg);
                        inter = setInterval("timerbi();", 1000)
                    } else {
                        $('#register-result-code').attr("class", "alert alert-danger").html(data.result.msg);
                    }
                    $btn.removeAttr("disabled");
                }
            })
        })

        $('.send-replay').on('click', function(e) {
            e.preventDefault();

            var $data = new FormData();
            $data.append('do', 'send-sms');
            $data.append('phone', $('.phone-code').val());

            var $btn = $(this);
            $btn.attr("disabled", "disabled");

            jQuery.ajax({
                url: "/",
                data: $data,
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                success: function(data) {
                    if (data.success == 1) {
                        $('input[name=code]').val("").show().focus();
                        $('.btn-check-code').show().attr("disabled", "disabled");
                        $('#register-result-code').attr("class", "alert alert-success").html(data.result.msg);
                        inter = setInterval("timerbi();", 1000)
                    } else {
                        $('#register-result-code').attr("class", "alert alert-danger").html(data.result.msg);
                    }
                }
            })

        })

        $('.send-replay-register').on('click', function(e) {
            e.preventDefault();

            var $data = new FormData();
            $data.append('do', 'send-sms-for-register');
            $data.append('phone', $('.phone-code').val());

            var $btn = $(this);
            $btn.attr("disabled", "disabled");

            jQuery.ajax({
                url: "/",
                data: $data,
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                success: function(data) {
                    if (data.success == 1) {
                        $('input[name=code]').val("").show().focus();
                        $('.btn-check-code').show().attr("disabled", "disabled");
                        $('#register-result-code').attr("class", "alert alert-success").html(data.result.msg);
                        inter = setInterval("timerbi();", 1000)
                    } else {
                        $('#register-result-code').attr("class", "alert alert-danger").html(data.result.msg);
                    }
                }
            })

        })

        $('.btn-check-code').on('click', function() {

            var $data = new FormData();
            $data.append('do', 'check-sms');
            $data.append('code', $('.code-form input[name=code]').val());
            $data.append('phone', $('.phone-code').val());

            var $btn = $(this);
            $btn.attr("disabled", "disabled");

            jQuery.ajax({
                url: "/",
                data: $data,
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                success: function(data) {
                    if (data.success == 1) {
                        $('.modal .close').hide();
                        <? if (A::$app->path() == LINK.'user/login/') { ?>
                            $('#register-result-code').attr("class", "alert alert-success").html('Вы успешно авторизовались');
                            $('input[name=code]').hide();
                            $('.btn-send-code').hide();
                            $('.send-replay').hide();
                            $('.btn-send-code-register').hide();
                            $('.send-replay-register').hide();
                            $('.btn-check-code').hide();
                            setTimeout(
                                function() {
                                    location.href = '/';
                                },
                                2000
                            );
                        <? } else if (A::$app->path() == LINK.'shop/order/') { ?>
                            $('#register-result-code').attr("class", "alert alert-success").html('Телефон подтвержден');
                            $('input[name=code]').hide();
                            $('.btn-send-code').hide();
                            $('.send-replay').hide();
                            $('.btn-send-code-register').hide();
                            $('.send-replay-register').hide();
                            $('.btn-check-code').hide();
                            jQuery('#order_form').submit()
                        <? } else { ?>
                            $('input[name=code]').hide();
                            $('.btn-send-code').hide();
                            $('.send-replay').hide();
                            $('.btn-send-code-register').hide();
                            $('.send-replay-register').hide();
                            $('.btn-check-code').hide();
                            $('#register-result-code').attr("class", "").html("");
                            $('#register-form').show();
                            $('form input[name=code]').val(data.result.code);
                            $('#register-form input[name=phone]').val($('.code-form input[name=phone]').val());
                            $('#register-result-code').attr("class", "alert alert-success").html('Phone verified');
                            $('.code-form').hide();
                            jQuery('html,body').animate({
                                scrollTop: 0
                            }, 'slow');
                        <? } ?>
                    } else {
                        $('#register-result-code').attr("class", "alert alert-danger").html(data.result.msg);
                    }
                    $btn.removeAttr("disabled");
                }
            })
        })
    })
</script>

<?= A::$app->shop->script() ?>