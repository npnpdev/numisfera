$(document).ready(function() {
  $('.submitCustomBlock').live('click', function(e){
    e.preventDefault()
    submitCustomBlock();
  });
});


function submitCustomBlock(){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax: true,
      token: $('input[name="token_slider"]').val(),
      controller: 'AdminCustomBlock',
      fc: 'module',
      module: 'mpm_customblock',
      action: 'saveCustom',
      id_shop: $('input[name="idShop"]').val(),
      id_lang: $('input[name="idLang"]').val(),
      hook: $('select[name="hook"]').val(),
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){
          showSuccessMessage(json['success']);
        }
      }
    }
  });
}

function showSuccessMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function showErrorMessage(msg) {
  $.growl.error({ title: "", message:msg});
}