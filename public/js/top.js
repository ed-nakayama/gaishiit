$(function () {
  //動画パララックス
  $(window).on('load', function() {
    var rellax = new Rellax('.rellax', {
      speed: -7,
      center: true,
      wrapper: null,
      vertical: true,
      horizontal: false,
      breakpoints: [576, 768, 1201]
    });
  });

  //ロゴスライダー
  $('.slider').slick( {
    infinite: true,
    dots: false,
    slidesToShow: 11,
    autoplay: true,
    autoplaySpeed: 0,
    initialSlide: 0,
    arrows: true,
    adaptiveHeight: false,
    variableWidth: true,
    speed: 5000,
    cssEase: "linear",
  });

  //ロゴ位置固定
  var navPos = jQuery( '#logo-slider' ).offset().top;
  var navHeight = jQuery( '#logo-slider' ).outerHeight();
  jQuery( window ).on( 'scroll', function() {
    if ( jQuery( this ).scrollTop() > navPos ) {
      jQuery( 'body' ).css( 'padding-top', navHeight );
      jQuery( '#logo-slider' ).addClass( 'fixed' );
    } else {
      jQuery( 'body' ).css( 'padding-top', 0 );
      jQuery( '#logo-slider' ).removeClass( 'fixed' );
    }
  });

  //フェードイン
  $(window).scroll(function () {
    const windowHeight = $(window).height();
    const scroll = $(window).scrollTop();

    $('.fadein').each(function () {
      const targetPosition = $(this).offset().top;
      if (scroll > targetPosition - windowHeight + 100) {
        $(this).addClass("is-fadein");
      }
    });
  });
});





