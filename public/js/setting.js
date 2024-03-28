$(function() {
 
  $('button.add').click(function(){
 
  var tr_form = '' +
  '<div class="input-wrap">' +
    '<input type="text">' +
  '</div>';
 
  $(tr_form).appendTo($('form .contact-list'));
 
  });

});