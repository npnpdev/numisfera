$(document).ready(function(){
  if( $('.categories_slider').length > 0 ){
    subcategorySlider();
  }
});

function subcategorySlider(){

  $('.categories_slider').slick({
    dots: false,
    infinite: true,
    slidesToShow: 8,
    slidesToScroll: 1,
    prevArrow: '<a onclick=""  data-role="none" class="slick-prev slick-prev-products"  tabindex="0" role="button">Previous</a>',
    nextArrow: '<a onclick=""  data-role="none" class="slick-next slick-next-products"  tabindex="0" role="button">Next</a>',
    autoplay: true,
    autoplaySpeed: 3000,
    responsive: [
      {
        breakpoint: 1790,
        settings: {
          slidesToShow: 6,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 1300,
        settings: {
          slidesToShow: 5,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 960,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 750,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        }
      },
      {
        breakpoint: 540,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        }
      }
    ]
  });

}
