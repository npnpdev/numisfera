$(document).ready(function(){

  if(is_mobile){
    $('.block_description_front .cont_column_form p').each(function() {
      $(this).html($(this).html().replace(/&nbsp; /g, ''));
    });
  }

  $(document).on('click', '.send_contact_form_message', function(){
    sendContactFormMessage();
  });


  if( $('.one_field_form_captcha').length>0 ){
    resizeCaptcha();
  }

  if( $('.one_field_form_captcha').length>0 ){
    $(window).resize(function () {
      resizeCaptcha();
    });
  }


});

function resizeCaptcha() {

  var width_block = $('.block_contact_form_front .cont_column_form').width();
  $('.block_result_captcha').css('width', width_block-172);

}


function sendContactFormMessage() {
  var name = $('.one_field_form .user_name').val();
  var phone = $('.one_field_form .user_phone').val();
  var email = $('.one_field_form .user_email').val();
  var subject = $('.one_field_form .subject_message').val();
  var comment = $('.one_field_form .message').val();
  var result_captcha = $('.one_field_form .result_captcha').val();
  $('.one_field_form .user_name, .one_field_form .user_phone, .one_field_form .user_email, .one_field_form .subject_message, .one_field_form .message, .one_field_form .result_captcha').css('border-color', '#dadada');



  var xlsxData = new FormData();

  if( $('.one_field_form_attach').length>0 ){
    xlsxData.append('file', $('input[name=fileUpload]')[0].files[0]);
  }
  else{
    xlsxData.append('file', false);
  }

  xlsxData.append('ajax', true);
  xlsxData.append('token', "");
  xlsxData.append('controller', 'AjaxForm');
  xlsxData.append('fc', 'module');
  xlsxData.append('action', 'send');
  xlsxData.append('module', 'mpm_contactform');

  xlsxData.append('name', name);
  xlsxData.append('phone', phone);
  xlsxData.append('email', email);
  xlsxData.append('subject', subject);
  xlsxData.append('comment', comment);
  xlsxData.append('result_captcha', result_captcha);
  xlsxData.append('id_shop', $('input[name="idShop"]').val());
  xlsxData.append('id_lang', $('input[name="idLang"]').val());


  $.ajax({
    url: $('input[name="base_url"]').val()+'index.php?rand=' + new Date().getTime(),
    type: 'post',
    data: xlsxData,
    dataType: 'json',
    processData: false,
    contentType: false,
    success: function (json) {
      if(json['error']){

        if(json['error'] == 'name'){
          fieldError('user_name');
        }
        if(json['error'] == 'email'){
          fieldError('user_email');
        }
        if(json['error'] == 'phone'){
          fieldError('user_phone');
        }
        if(json['error'] == 'subject'){
          fieldError('subject_message');
        }
        if(json['error'] == 'comment'){
          fieldError('message');
        }
        if(json['error'] == 'captcha'){
          fieldError('result_captcha');
        }

        if(json['error'] == 'mess'){
          showNotice('error');
          setTimeout(function(){
            hideNotice();
          }, 4000);
        }
      }
      if(json['success'] == 'mess'){
        showNotice('success');
        setTimeout(function(){
          hideNotice();
        }, 3000);
      }
    }
  });
}


function fieldError(field){
  $('.one_field_form  .'+field).css('border-color', '#FF3F3F');
  $('.one_field_form  .'+field).focus();
}

function showNotice(type){

  $('.form_notice_contact_form_ov').show();

  if(type == 'error'){
    $('.notice_error').show();
  }
  else{
    $('.notice_success').show();
  }

  var h = 100;


  var top = $(window).scrollTop()+h;
  if( top < $('.contactFormContent').offset().top ){
    top = $('.contactFormContent').offset().top + 100;
  }
  $('body').append('<div class="form_notice_contact_form">'+$('.form_notice_contact_form_hidden').html()+ '</div>');
  $('.form_notice_contact_form').css("top", top + "px");
}

function hideNotice(){
  $('.form_notice_contact_form_ov').hide();
  $('.form_notice_contact_form').remove();
  $('.notice_success').hide();
  $('.notice_error').hide();
  $('.one_field_form .user_name, .one_field_form .user_phone, .one_field_form .user_email, .one_field_form .subject_message, .one_field_form .message, .one_field_form .result_captcha').val('');

}

