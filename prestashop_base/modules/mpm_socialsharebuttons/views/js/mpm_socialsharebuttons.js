$(document).ready(function(){
  if( $('.rrssb-buttons').length > 0 ){

    $('.rrssb-buttons').rrssb({
      title: $('.rrssb-buttons').attr('data-title'),
      url: $('.rrssb-buttons').attr('data-url'),
      description: $('.rrssb-buttons').attr('data-description'),
      emailBody: $('.rrssb-buttons').attr('data-emailBody'),
      image: $('.rrssb-buttons').attr('data-image'),
    });
  }

});

