$('.tab_box .tab_btn').click(function () {
  var index = $('.tab_box .tab_btn').index(this);
  $('.tab_box .tab_btn, .tab_box .tab_panel').removeClass('active');
  $(this).addClass('active');
  $('.tab_box .tab_panel').eq(index).addClass('active');
});

$(function () {
  $(".js-ac-title").on("click", function () {
    $(this).next().slideToggle(300);
    $(this).toggleClass("open", 300);
  });
});


// tableのthの中のテキストをspanで囲む
$th = $('.secContentsInner th, .tbl-user-contact th, .tbl-claim-every th, .tbl-claim-7th th')
$th.each(function () {
  if ($(this).text() !== "") {
    $(this).wrapInner('<span class="thInnerWrap"></span>');
  }
});

// .item-nameの中のspanの中身が「*」だった場合にクラスを追加
$('.item-name span').each(function () {
  if ($(this).text() === "*") {
    $(this).addClass('required');
  }
});

// .item-inputの中のdiv[style="margin-left: 15px;"]の内側をlabelで囲む
$('.item-input div[style="margin-left: 15px;"]').each(function () {
  $(this).wrapInner('<label class="label-checkbox"></label>');
});

// input[type="date"]を囲っているlabelのstyle属性を削除
$('input[type="date"]').parent().removeAttr('style');

// labelの空のforは削除
$('label').each(function () {
  if ($(this).attr('for') === "") {
    $(this).removeAttr('for');
  }
});
