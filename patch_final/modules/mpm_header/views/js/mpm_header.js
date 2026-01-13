$(document).ready(function(){

  if($('.product-accessories').length>0){
    productsCategorySlider($('.product-accessories .products'));
  }

  if($('.product-category').length>0){
    productsCategorySlider($('.product-category .products'));
  }

  if($('.crossseling-products').length>0){
    productsCategorySlider($('.crossseling-products .products'));
  }



  if (($(window).width()+scrollCompensate()) < 1270)
  {
    $('.topMenuBlock').addClass('mobile')
    $('.topMenuBlock').removeClass('desktop')
  }
  else{
    $('.topMenuBlock').addClass('desktop')
    $('.topMenuBlock').removeClass('mobile')
  }

  $(document).on('click', '.column-arrows-add', function(){
    $(this).removeClass('active');
    $(this).next().addClass('active');
    $(this).parent().next().show();
  });

  $(document).on('click', '.column-arrows-remove', function(){
    $(this).removeClass('active');
    $(this).prev().addClass('active');
    $(this).parent().next().hide();
  });

  $(document).on('click', '.thumb-container-img', function(e){
    $('.thumb-container-img').removeClass('selected');
    $(this).addClass('selected');
    var src = $(this).find('.thumb_item').attr('data-image-medium-src') ;
    var large = $(this).find('.thumb_item').attr('data-image-large-src') ;
    $('.product-cover-img .js-qv-product-cover').attr('src', src);
    $('.product-cover-img .js-qv-product-cover').attr('data-zoom-image', large);

    if (($(window).width()+scrollCompensate()) >= 1000 &&  parseInt(product_zoom)) {
      var ez = $('.left_block_product #zoom_mw').data('elevateZoom');
      ez.swaptheimage(src, large);
    }
  });

  $(document).on('click', '._desktop_search_icon', function(){
    if($(this).hasClass('active')){
      $(this).removeClass('active');
      $('#search_widget').removeClass('active');
    }
    else{
      $(this).addClass('active');
      $('#search_widget').addClass('active');
    }
  });

  $(document).on('click', '.search_close', function(e){
    e.preventDefault();
    $('._desktop_search_icon').removeClass('active');
    $('#search_widget').removeClass('active');
  });



  window.onscroll = function() {

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
      else{
        displayListGrid('grid');
      }
    }

    if (($(window).width()+scrollCompensate()) < 1270)
    {
      $('.topMenuBlock').addClass('mobile')
      $('.topMenuBlock').removeClass('desktop')
    }
    else{
      $('.topMenuBlock').addClass('desktop')
      $('.topMenuBlock').removeClass('mobile')
    }

  }

});


function productsCategorySlider(el ){
  el.slick({
    dots: false,
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    prevArrow: '<a onclick=""  data-role="none" class="slick-prev slick-prev-products"  tabindex="0" role="button">Previous</a>',
    nextArrow: '<a onclick=""  data-role="none" class="slick-next slick-next-products"  tabindex="0" role="button">Next</a>',
    autoplay: true,
    autoplaySpeed: 3000,
    responsive: [
      {
        breakpoint: 1790,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 1300,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 1000,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 700,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        }
      }
    ]
  });
}


function productsImageSlider(el){
  el.slick({
    dots: false,
    infinite: true,
    slidesToShow: 4,
    vertical: true,
    slidesToScroll: 1,
    prevArrow: '<a onclick=""  data-role="none" class="slick-prev slick-prev-img"  tabindex="0" role="button">Previous</a>',
    nextArrow: '<a onclick=""  data-role="none" class="slick-next slick-next-img"  tabindex="0" role="button">Next</a>',
    autoplay: false,

  });
}


function scrollCompensate()
{
  var inner = document.createElement('p');
  inner.style.width = "100%";
  inner.style.height = "200px";

  var outer = document.createElement('div');
  outer.style.position = "absolute";
  outer.style.top = "0px";
  outer.style.left = "0px";
  outer.style.visibility = "hidden";
  outer.style.width = "200px";
  outer.style.height = "150px";
  outer.style.overflow = "hidden";
  outer.appendChild(inner);

  document.body.appendChild(outer);
  var w1 = inner.offsetWidth;
  outer.style.overflow = 'scroll';
  var w2 = inner.offsetWidth;
  if (w1 == w2) w2 = outer.clientWidth;

  document.body.removeChild(outer);

  return (w1 - w2);
}