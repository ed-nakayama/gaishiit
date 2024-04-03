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
  $('.openModalIndustory').click(function(){
      $('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
      $('.modalAreaIndustory').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
    $('.modalAreaIndustory').fadeOut();
  });
});

$(function () {
  $('.openModalBussiness').click(function(){
      $('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
      $('.modalAreaBussiness').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
    $('.modalAreaBussiness').fadeOut();
  });
});

$(function () {
  $('.openModalCommit').click(function(){
      $('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
      $('.modalAreaCommit').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
    $('.modalAreaCommit').fadeOut();
  });
});


$(function () {
  $('.openModal').click(function(){
      $('body').css('overflow-y', 'hidden');  // 本文の縦スクロールを無効
      $('.modalLogin').fadeIn();
  });
  $('#closeModal , #modalBg').click(function(){
     $('body').css('overflow-y','auto');     // 本文の縦スクロールを有効
    $('.modalLogin').fadeOut();
  });
});

