const swiper = new Swiper('.swiper', {
  loop: true,
  autoplay: {
    delay: 5000,
  },
  pagination: {
   el: ".swiper-pagination",
   clickable:true
 },

});


(function($){
  $('footer .menu li>a').on('click', function(e){
    e.preventDefault();
    if ($(this).hasClass('active')){
      $('footer .menu li>a').removeClass('active');
      $('footer .menu li>div').slideUp();
    }else{
      $('footer .menu li>a').removeClass('active');
      $('footer .menu li>div').slideUp();
      $(this).toggleClass('active')
      $(this).next().slideToggle()
    }

  })

  $('.filter>li>a').on('click', function(e){
    e.preventDefault();
    if ($(this).hasClass('active')){
      $('.filter>li>a').removeClass('active');
      $('.filter>li>div').slideUp();
    }else{
      $('.filter>li>a').removeClass('active');
      $('.filter>li>div').slideUp();
      $(this).toggleClass('active')
      $(this).next().slideToggle()
    }

  })

  $('.about-ul>li>a').on('click', function(e){
    e.preventDefault();
    if ($(this).hasClass('active')){
      $('.about-ul>li>a').removeClass('active');
      $('.about-ul>li>div').slideUp();
    }else{
      $('.about-ul>li>a').removeClass('active');
      $('.about-ul>li>div').slideUp();
      $(this).toggleClass('active')
      $(this).next().slideToggle()
    }

  })

  $('.n-search__input').on('focus', function(e){
    $('body').addClass('search-on');
  })

  $('.n-search-popup-close').on('click', function(e){
    $('body').removeClass('search-on');
  })

  $('.slider-for').slick({
    slidesToShow: 2,
    slidesToScroll: 1,
    arrows: true,
    infinite:true,
    dots:true,
    appendDots: $('.appendDots'),
    responsive: [
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
  });


})(jQuery);