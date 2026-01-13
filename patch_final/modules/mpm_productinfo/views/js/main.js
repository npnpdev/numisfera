$(document).ready(function(){
  if( $('.product-block-info .product-block-item').length > 0 ){
    sizeBlockInfo();
  }
});


function sizeBlockInfo(){

  var height_item = 50;

  $('.product-block-info .product-block-item').each(function() {
    var height_current = $(this).height();
    if(height_current > height_item){
      height_item = height_current;
    }
  });

  $('.product-block-info .product-block-item').css('height', height_item+'px');
  $('.product-block-info .product-block-item .content-item').css('height', height_item+'px');


}