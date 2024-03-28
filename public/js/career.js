$(function () {
  $('.openModalSort').click(function(){
      $('.modalAreaSort').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
    $('.modalAreaSort').fadeOut();
  });
});

$(function () {
  $('.openModalSort').on('click', function () {
    $('.modalAreaSort').addClass('open-leftbar');
  });
  $('.modalAreaSort , .toggle-btn').on('click', function () {
    $('.modalAreaSort').removeClass('open-leftbar');
  });
});


$(function () {
  $('.openModalName').click(function(){
      $('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
      $('.modalAreaName').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
    $('.modalAreaName').fadeOut();
  });
});

$(function () {
  $('.openModalJob').click(function(){
       $('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
       $('.modalAreaJob').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
       $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
       $('.modalAreaJob').fadeOut();
  });
});

$(function () {
  $('.openModalIndustry').click(function(){
      $('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
      $('.modalAreaIndustry').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
    $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
    $('.modalAreaIndustry').fadeOut();
  });
});