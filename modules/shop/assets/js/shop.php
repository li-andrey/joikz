<script type="text/javascript">
    function alga(id) {
        id = id.replace("#", "").replace(" ", "");
        jQuery('html,body').animate({
            scrollTop: jQuery("#" + id).offset().top
        }, 'slow');
    }
    jQuery(function() {
        jQuery('#order_form').on('submit', function(e) {
            e.preventDefault();
            var $form = jQuery(this)
            var $btn = $form.find('button');
            $btn.attr("disabled", "disabled");
            jQuery.ajax({
                url: "/",
                data: $form.serialize(),
                type: "POST",
                dataType: "JSON",
                cache: false,
                success: function(data) {
                    if (data.success == 1) {

                        if (data.result.phone == '1') {
                            $('#modal-sms-code').data('bs.modal', null);
                            $('#modal-sms-code').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            inter = setInterval("timerbi();", 1000)
                        } else {

                            if (data.payment != '') {
                                location.href = data.payment
                            } else {
                                location.href = $form.data('action');
                            }
                        }


                    } else {
                        if (data.error == undefined) {
                            alert(data.result.msg);
                        } else {
                            alert(data.error);
                        }
                    }
                    $btn.removeAttr("disabled");
                }
            })
        })
    })
    jQuery(document).ready(function() {


        // регистрация
        jQuery('#register-form').on('submit', function(e) {
            e.preventDefault();
            var $form = jQuery(this);
            jQuery.ajax({
                url: "/",
                data: new FormData(this),
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                beforeSend: function() {
                    /*if (jQuery('#register-form input[name=password]').val()!=jQuery('#register-form input[name=password1]').val()){
                        jQuery('#register-result').attr('class','alert alert-danger').html('Пароли не совпадают').show();
                        alga('#register-result');
                        return false;
                    }else{
                        jQuery('#register-result').attr('class','').html('').hide();
                        return true;
                    }*/
                },
                success: function(data) {
                    if (data.success == '1') {
                        jQuery('#register-result').attr('class', 'alert alert-success').html(data.result.msg).show();
                        $form[0].reset();
                        //alga('#register-result');
                        setTimeout(
                            function() {
                                location.href = '/';
                            },
                            2000
                        );
                    } else {
                        jQuery('#register-result').attr('class', 'alert alert-danger').html(data.result.msg).show();
                        //alga('#register-result');
                    }
                }
            })
        })

        jQuery('#login-form').on('submit', function(e) {
            e.preventDefault();
            var $form = jQuery(this);
            jQuery.ajax({
                url: "/",
                data: new FormData(this),
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                beforeSend: function() {},
                success: function(data) {
                    if (data.success == '1') {
                        jQuery('#login-result').attr('class', 'alert alert-success').html(data.result.msg).show();
                        $form[0].reset();
                        //alga('#login-result');
                        setTimeout(
                            function() {
                                location.href = '/';
                            },
                            2000
                        );
                    } else {
                        jQuery('#login-result').attr('class', 'alert alert-danger').html(data.result.msg).show();
                        //alga('#login-result');
                    }
                }
            })
        })

        jQuery('#forgot-form').on('submit', function(e) {
            e.preventDefault();
            var $form = jQuery(this);
            jQuery.ajax({
                url: "/",
                data: new FormData(this),
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                beforeSend: function() {},
                success: function(data) {
                    if (data.success == '1') {
                        jQuery('#forgot-result').attr('class', 'alert alert-success').html(data.result.msg).show();
                        $form[0].reset();
                        //alga('#forgot-result');
                        setTimeout(
                            function() {
                                location.href = '/';
                            },
                            2000
                        );
                    } else {
                        jQuery('#forgot-result').attr('class', 'alert alert-danger').html(data.result.msg).show();
                        //alga('#forgot-result');
                    }
                }
            })
        })

        jQuery('#profile-form').on('submit', function(e) {
            e.preventDefault();
            var $form = jQuery(this);
            jQuery.ajax({
                url: "/",
                data: new FormData(this),
                processData: false,
                type: "POST",
                dataType: "JSON",
                contentType: false,
                beforeSend: function() {

                },
                success: function(data) {
                    if (data.success == '1') {
                        jQuery('#profile-result').attr('class', 'alert alert-success').html(data.result.msg).show();
                        //alga('#profile-result');
                    } else {
                        jQuery('#profile-result').attr('class', 'alert alert-danger').html(data.result.msg).show();
                        // alga('#profile-result');
                    }
                }
            })
        })

        jQuery('#par1').change(function() {
            jQuery('.current-price').html(jQuery('option:selected', this).data("price") + ' ₸');
        });


        jQuery(document).on('click', '.basket', function(e) {
            e.preventDefault();
            var id = jQuery(this).data('id');
            var kol = 1;

            if ((jQuery("#kol" + id).length > 0)) {
                kol = parseInt(jQuery("#kol" + id).val());
            }

            var color = '';
            if ((jQuery(".colors .item.active").length > 0)) {
                color = jQuery(".colors .item.active").data("color1");
            }

            var size = '';
            if ((jQuery(".sizes .item").length > 0)) {
                if ((jQuery(".sizes .item.active").length > 0)) {
                    size = jQuery(".sizes .item.active").data("size");
                    jQuery(".sizes").removeClass("error")
                } else {
                    jQuery(".sizes").addClass("error")
                }
            }


            if (jQuery(".sizes .item").length > 0) {
                if (size != '') {
                    jQuery.ajax({
                        url: "/",
                        data: 'do=addBasket&id=' + id + '&kol=' + kol + '&color=' + color + '&size=' + size,
                        type: "POST",
                        dataType: "JSON",
                        beforeSend: function() {

                        },
                        success: function(data) {
                            if (data.success == '1') {
                                jQuery('#basket-box').modal('show');
                                jQuery('.cart .count').html(data.count);
                            } else {
                                alert(data.msg);
                            }
                        }
                    })
                } else {
                    alert('Выберите размер')
                }
            } else {
                jQuery.ajax({
                    url: "/",
                    data: 'do=addBasket&id=' + id + '&kol=' + kol + '&color=' + color + '&size=' + size,
                    type: "POST",
                    dataType: "JSON",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        if (data.success == '1') {
                            jQuery('#basket-box').modal('show');
                            jQuery('.cart .count').html(data.count);
                        } else {
                            alert(data.msg);
                        }
                    }
                })
            }
        })

        jQuery(document).on('click', '.like-icon', function(e) {
            e.preventDefault();
            var id = jQuery(this).data('id');
            var kol = 1;
            if (jQuery(this).hasClass("active")) {
                jQuery(this).removeClass("active");

                jQuery('.wisha' + id).remove()

                jQuery.ajax({
                    url: "/",
                    data: 'do=delLike&id=' + id + '&kol=' + kol,
                    type: "POST",
                    dataType: "JSON",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        if (data.success == '1') {
                            jQuery('.wishlist .count').html(data.count);
                            jQuery('.wishlist-item-' + id).remove()
                        } else {
                            alert(data.msg);
                        }
                    }
                })
            } else {
                jQuery(this).addClass("active");
                jQuery.ajax({
                    url: "/",
                    data: 'do=addLike&id=' + id + '&kol=' + kol,
                    type: "POST",
                    dataType: "JSON",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        if (data.success == '1') {
                            jQuery('.wishlist .count').html(data.count);
                        } else {
                            alert(data.msg);
                        }
                    }
                })
            }
        })

        jQuery(document).on('click', '.compare-icon', function(e) {
            e.preventDefault();
            var id = jQuery(this).data('id');
            var kol = 1;
            if (jQuery(this).hasClass("active")) {
                jQuery(this).removeClass("active");
                jQuery.ajax({
                    url: "/",
                    data: 'do=delCompare&id=' + id + '&kol=' + kol,
                    type: "POST",
                    dataType: "JSON",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        if (data.success == '1') {

                            jQuery('#count-compare .count').html(data.count);
                            if (parseInt(data.count) > 0) {
                                jQuery('.wish').addClass("active");
                            } else {
                                jQuery('.wish').removeClass("active");
                            }
                        } else {
                            alert(data.msg);
                        }
                    }
                })
            } else {
                jQuery(this).addClass("active");
                jQuery.ajax({
                    url: "/",
                    data: 'do=addCompare&id=' + id + '&kol=' + kol,
                    type: "POST",
                    dataType: "JSON",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        if (data.success == '1') {

                            jQuery('#count-compare .count').html(data.count);
                            if (parseInt(data.count) > 0) {
                                jQuery('.wish').addClass("active");
                            } else {
                                jQuery('.wish').removeClass("active");
                            }
                        } else {
                            alert(data.msg);
                        }
                    }
                })
            }
        })

    })
    jQuery(document).ready(function() {
        LoadBasket();
        jQuery(document).on('click', '.clear_basket', function() {
            jQuery.ajax({
                url: "",
                data: "do=clear_basket&x=secure",
                type: "POST",
                dataType: "JSON",
                cache: false,
                beforeSend: function() {},
                success: function(data) {
                    if (data.success == 1) {
                        jQuery('.cart .count').html(data.count);
                        jQuery("#basket_place").html(data.basket);
                    } else {
                        alert(data.error);
                    }

                }
            });
        });

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
            if (jQuery(this).attr("id") != 'delivery1') {
                jQuery('input[name=adress]').attr("required", "required");
                jQuery('input[name=dom]').attr("required", "required");
                jQuery('input[name=kv]').attr("required", "required");
            } else {
                jQuery('input[name=adress]').removeAttr("required");
                jQuery('input[name=dom]').removeAttr("required");
                jQuery('input[name=kv]').removeAttr("required");
            }
        })

        jQuery('.order-block select[name=city]').trigger("change");
    });

    function LoadBasket() {
        jQuery.ajax({
            url: "/",
            data: "do=load&x=secure",
            type: "POST",
            dataType: "JSON",
            cache: false,
            beforeSend: function() {},
            success: function(data) {
                if (data.success == 1) {
                    jQuery('.bsk_count').html(data.count);
                    jQuery("#basket_place").html(data.basket);
                    jQuery("#basket_place_order").html(data.basket_order);
                } else {
                    alert(data.error);
                }

            }
        });
    }

    function DeleteFromBasket(pos, p) {
        if (confirm('Вы действительно хотите удалить этот элемент из корзины?') == true) {
            jQuery.ajax({
                url: "/",
                data: "do=del&pos=" + pos + "&params=" + p + "&x=secure",
                type: "POST",
                dataType: "JSON",
                cache: false,
                success: function(data) {
                    if (data.success == 1) {
                        jQuery('.cart .count').html(data.count);
                        LoadBasket();
                    } else {
                        alert(data.error);
                    }
                }
            });
        }
    }

    function ReCount(m, p, k) {
        value = document.getElementById('count_' + m + "_" + k).value;
        jQuery(document).ready(function() {
            jQuery.ajax({
                url: "/",
                data: "do=recount&element=" + m + "&params=" + p + "&value=" + value + "&x=secure",
                type: "POST",
                dataType: "JSON",
                cache: false,
                beforeSend: function() {
                    jQuery("#basket_protocol").html("").hide();
                },
                success: function(data) {
                    console.log(data.success);
                    if (data.success == '1') {
                        jQuery('.bsk_count').html(data.count);
                        LoadBasket();
                    } else {
                        alert(data.error);
                    }
                }
            });
        });
    }

    function dostCount() {
        if (jQuery('.dost-variant input[name=delivery]:checked').length > 0) {
            var price = parseInt(jQuery('.dost-variant input[name=delivery]:checked').attr("data-price").replaceAll(' ', ''));
            if (price == 0) {
                jQuery('#dost').html('<b style="color:green; word-wrap: normal">бесплатно</b>');
            } else {
                jQuery('#dost').html(Intl.NumberFormat('ru-RU').format(price) + " ₸");
            }

        } else {
            jQuery('#dost').html("-");
        }
        if (jQuery('#itogo').length > 0) {
            var i = parseInt(jQuery('#itogo').html().replaceAll(' ', '').replace(' ₸', ''));
            if (isNaN(i)) i = 0;
        } else {
            var i = 0;
        }
        var d = price;

        if (jQuery('#dost1').length > 0) {
            var d1 = parseInt(jQuery('#dost1').html().replaceAll(' ', '').replace(' ₸', '').replace('-', ''));
            if (isNaN(d1)) d1 = 0;
        } else {
            var d1 = 0;
        }

        var promo = 0;
        jQuery('.promocodes .promo-input').each(function(a, b) {
            promo = promo + parseInt(jQuery(b).val());
        })

        var amount = i + d - promo;
        var amount1 = i + d1 - promo;

        jQuery('input[name=delivery_price]').val(d);
        jQuery('#amount').html(Intl.NumberFormat('ru-RU').format(amount) + " ₸");
        jQuery('#amount1').html(Intl.NumberFormat('ru-RU').format(amount1) + " ₸");
    }

    function itogo() {
        if (jQuery('#itogo').length > 0) {
            var i = parseInt(jQuery('#itogo').html().replaceAll(' ', '').replace(' ₸', ''));
            if (isNaN(i)) i = 0;
        } else {
            var i = 0;
        }

        console.log(jQuery('input[name=delivery_price1]').length);

        if (jQuery('input[name=delivery_price1]').length > 0) {
            var d1 = parseInt(jQuery('input[name=delivery_price1]').val());
            if (isNaN(d1)) d1 = 0;
        } else {
            var d1 = 0;
        }

        console.log(i);
        console.log(d1);

        var amount2 = i + d1;

        var promo = 0;
        jQuery(' .promo-input').each(function(a, b) {
            promo = promo + parseInt(jQuery(b).val());
        })

        amount2 = amount2 - promo;

        if (promo > 0) {
            jQuery('#sale2').html('-' + Intl.NumberFormat('ru-RU').format(promo) + "₸");
            jQuery('.sale2').show();
        }
        if (d1 == 0) {
            jQuery('#dost2').html('<b style="color:green; word-wrap: normal">бесплатно</b>');
        } else {
            jQuery('#dost2').html(Intl.NumberFormat('ru-RU').format(d1) + " ₸");
        }

        jQuery('#amount2').html(Intl.NumberFormat('ru-RU').format(amount2) + " ₸");
    }
</script>