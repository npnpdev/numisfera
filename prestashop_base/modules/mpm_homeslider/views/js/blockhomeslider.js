$(document).ready(function() {

  if($('.carousel-homeslider #carousel').length > 0){
     carouselSlider();
  }

});

function carouselSlider(){

   var auto = parseInt(auto_play);
  if(auto){
    auto = parseInt(speed_slider);
  }

  $('.carousel-homeslider #carousel').slick({
    dots: false,
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: '<a onclick=""  data-role="none" class="slick-prev slick-prev-products"  tabindex="0" role="button">Previous</a>',
    nextArrow: '<a onclick=""  data-role="none" class="slick-next slick-next-products"  tabindex="0" role="button">Next</a>',
    autoplay: auto,
    autoplaySpeed: parseInt(speed_slider),
    speed: 1000,
  });
}


