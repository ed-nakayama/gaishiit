$(function () {
  $('.openModalSeek').click(function(){
      $('.modalAreaSeek').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
    $('.modalAreaSeek').fadeOut();
  });
});

$(function () {
  $('.openModalSeek').on('click', function () {
      $('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
    $('.modalAreaSeek').addClass('open-leftbar');
  });
  $('.modalAreaSeek , .toggle-btn').on('click', function () {
     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
    $('.modalAreaSeek').removeClass('open-leftbar');
  });
});

