$(document).ready(function(){



  $(document).on('click', '.categories-block-arrows .column-arrows-add', function(e){
    $('.categories-block-arrows .column-arrows-remove').removeClass('active');
    $('.categories-block-arrows .column-arrows-add').addClass('active');
    $('.subcat_menu').hide();

    $(this).removeClass('active');
    $(this).next().addClass('active');
    $(this).parents('.item_menu').removeClass('active');
    $(this).parent().next().show();
    $(this).parent().prev().show();
  });

  $(document).on('click', '.categories-block-arrows .column-arrows-remove', function(e){
    $(this).removeClass('active');
    $(this).prev().addClass('active');
    $(this).parent().prev().hide();
    $(this).parents('.item_menu').addClass('active');
  });


  $(document).on('click', '.topMenuBlock.mobile .topmenu_mobile', function(e){
    e.preventDefault();
    if($(this).hasClass('active')){
      $('.topMenuBlock.mobile .topmenu').hide();
      $(this).removeClass('active')
    }
    else{
      $('.topMenuBlock.mobile .topmenu').show();
      $(this).addClass('active')
    }
  });


  $(".topMenuBlock.desktop .item_menu.narrow_item").each(function( index ) {
    var narrow = $(this).find('.subcat_menu');
    var el = $(this);
    positionItemMenu(el, narrow);
  });


});



function positionItemMenu(el, narrow) {

  var padding = 70;

  if (($(window).width()+scrollCompensate()) <= 1500)
  {
    padding = 20;
  }

  var width_page = $('#header').width();

  var left = el.offset().left - padding;
  var width = el.outerWidth();
  var width_narrow = narrow.outerWidth();
  var right = width_page - left;


  if(right > width_narrow){
    narrow.css('left',left+'px' )
  }
  else{
    if((width_page - 1*padding)<width_narrow){
      narrow.css('left','0px')
      narrow.css('right','initial' )
      narrow.css('width','100%')
    }
    else{
      narrow.css('right','0px' )
      narrow.css('left','initial' )
    }


  }


}