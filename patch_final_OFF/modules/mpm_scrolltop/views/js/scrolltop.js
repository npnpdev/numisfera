$(document).ready(function(){

  $(document).on('click', '.scroll_top', function(){
    $("html, body").animate({ scrollTop: 0 }, "slow");
  })

  $(window).on('scroll', function(){
    scrollTopBlock();
  });

  window.onresize = function resize(){
    scrollTopBlock();
  };

});


function scrollTopBlock() {
  var window_width = window.innerWidth;
  var scroll = $(window).scrollTop();
  var height = ($(document).height() - $(window).height()) - scroll;
  var footer = $('#footer').outerHeight();
  var w = $('#wrapper .container').width();
  var scr = (window_width - w)/2;

  if(scr < 70){
    $('.scroll_top_block').width((w-55)+'px');
  }
  else{
    $('.scroll_top_block').width(w+'px');
  }

  if($(window).scrollTop() > 300 ) {

    if( height < footer){
      $('.scroll_top').css('bottom', footer - height + 5);
    }
    else{
      $('.scroll_top').css('bottom', 5);
    }

    $('.scroll_top').fadeIn(1000);
  }
  else{
    $('.scroll_top').fadeOut(1000);
  }
}