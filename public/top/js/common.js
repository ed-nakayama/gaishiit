// ヘッダーの高さ分だけコンテンツを下げる
// $(window).on('load', function() {
// 	var height=$("header").height();
// 	$("body").css("margin-top", height -2);
// });


(function ($) {
  var $nav = $('.nav-sp');
  var $btn = $('#menu .toggle-btn');
  var $mask = $('#mask');
  var open = 'open'; // class
  // menu open close
  $btn.on('click', function () {
    if (!$nav.hasClass(open)) {
      $nav.addClass(open);
    } else {
      $nav.removeClass(open);
    }
  });
  // mask close
  $mask.on('click', function () {
    $nav.removeClass(open);
  });
})(jQuery);


$(function () {
  $('#openModal').click(function(){
      $('#modalArea').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
    $('#modalArea').fadeOut();
  });
});


$(function () {
  $('#openModalSort').on('click', function () {
    $('.pane-leftbar').addClass('open-leftbar');
  });
  $('.pane-leftbar , .toggle-btn').on('click', function () {
    $('.pane-leftbar').removeClass('open-leftbar');
  });
});


$(function () {
  $('.modal-btn').on('click', function () {
    $('.message-wrap').addClass('open-message-wrap');
  });
  $('.chat-inner .toggle-btn').on('click', function () {
    $('.message-wrap').removeClass('open-message-wrap');
  });
});




$(document).ready(function () {
  // $(".modal-btn").hide();
  $(window).on("scroll", function () {
    // if ($(this).scrollTop() > 100) {
    //   $(".modal-btn").fadeIn(0);
    // } else {
    //   $(".modal-btn").fadeOut(0);
    // }
    scrollHeight = $(document).height(); //ドキュメントの高さ 
    scrollPosition = $(window).height() + $(window).scrollTop(); //現在地 
    footHeight = $("footer").innerHeight(); //footerの高さ（＝止めたい位置）
    if (scrollHeight - scrollPosition <= footHeight) { //ドキュメントの高さと現在地の差がfooterの高さ以下になったら
      $(".modal-btn").css({
        "position": "fixed", //pisitionをabsolute（親：wrapperからの絶対値）に変更
        "bottom": footHeight + 20 //下からfooterの高さ + 20px上げた位置に配置
      });
    } else { //それ以外の場合は
      $(".modal-btn").css({
        "position": "fixed", //固定表示
        "bottom": "20px" //下から20px上げた位置に
      });
    }
  });
  $('.modal-btn').click(function () {
    $('body,html').animate({
      scrollTop: 0
    }, 400);
    return false;
  });
});



$(function () {
  $(".ac-item").on("click", function () {
    $(this).children().eq(1).slideToggle(300);
    $(this).children().eq(0).toggleClass("ac-no-bar");
    $(this).siblings().find(".ac-header").removeClass("ac-gold");
    $(this).siblings().find(".ac-header").removeClass("rotate-arrow");
    $(this).find(".ac-header").toggleClass("ac-gold");
    $(this).find(".ac-header").toggleClass("rotate-arrow");

    $(".ac-item .ac-txt").not($(this).children().eq(1)).slideUp(300);
  });
});

// $(function () {
//   $('#openModalSort').click(function(){
//       $('#modalSort').fadeIn();
//   });
//   $('#closeModalSort , #ModalSortBg').click(function(){
//     $('#modalSort').fadeOut();
//   });
// });
