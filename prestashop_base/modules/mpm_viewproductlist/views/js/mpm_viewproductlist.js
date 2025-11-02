$(document).ready(function(){


  $(document).on('click', '#search_filters .facet', function(e){
    if($(this).hasClass('active')){
      $(this).removeClass('active')
      $(this).find('.facet-title').removeClass('active')
      $(this).find('.collapse ').removeClass('in')
    }
    else{
      $(this).addClass('active')
      $(this).find('.facet-title').addClass('active')
      $(this).find('.collapse ').addClass('in')
    }
  });



  if($('#products').length>0){

    if (($(window).width()+scrollCompensate()) >= 1000)
    {
      if($.cookie){
        if($.cookie("category_view")){
          displayListGrid($.cookie("category_view"));
        }
        else{
          displayListGrid('grid');
        }
      }
    }
  }

  $(document).on('click', '.display_list_grid li', function(e){
    e.preventDefault();
    if(!$(this).hasClass('selected')){
      if($(this).hasClass('list')){
        displayListGrid('list');
        setInCookie('list');
      }
      else{
        displayListGrid('grid');
        setInCookie('grid');
      }
    }
  });

});

function setInCookie(type){
  $.cookie("category_view",type,{ expires : 100, path:'/' });
}

function displayListGrid(type) {

  if(type == 'list'){
    $('.display_list_grid li.list').addClass('selected');
    $('.display_list_grid li.grid').removeClass('selected');


    $('#new-products #products').addClass('list');
    $('#new-products #products').removeClass('grid');


    $('#prices-drop #products').addClass('list');
    $('#prices-drop #products').removeClass('grid');


    $('#supplier #products').addClass('list');
    $('#supplier #products').removeClass('grid')


    $('#manufacturer #products').addClass('list');
    $('#manufacturer #products').removeClass('grid')


    $('#category #products').addClass('list');
    $('#category #products').removeClass('grid')


    $('#best-sales #products').addClass('list');
    $('#best-sales #products').removeClass('grid');

    $('#search #products').addClass('list');
    $('#search #products').removeClass('grid');
  }
  else{
    $('.display_list_grid li.grid').addClass('selected');
    $('.display_list_grid li.list').removeClass('selected');


    $('#new-products #products').addClass('grid');
    $('#new-products #products').removeClass('list');


    $('#prices-drop #products').addClass('grid');
    $('#prices-drop #products').removeClass('list');


    $('#manufacturer #products').addClass('grid');
    $('#manufacturer #products').removeClass('list')


    $('#supplier #products').addClass('grid');
    $('#supplier #products').removeClass('list')


    $('#category #products').addClass('grid');
    $('#category #products').removeClass('list')

    $('#search #products').addClass('grid');
    $('#search #products').removeClass('list')

    $('#best-sales #products').addClass('grid');
    $('#best-sales #products').removeClass('list');
  }
}

