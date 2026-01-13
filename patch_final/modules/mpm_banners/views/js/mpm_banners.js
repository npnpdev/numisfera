$(document).ready(function(){
  if( $('#index').length > 0 ){
    $('.block_banners').each(function() {
      var id = $(this).attr('data-id');
      sizeBlockBanners($('.block_banner_'+id+' .content_banners .banners_left_column'), $('.block_banner_'+id+' .content_banners .banners_right_column'));
    });
  }
});


function sizeBlockBanners(left, right){

  var height = left.height();
  var height_2 = right.height();

  if(height_2 > height){
    height = height_2;
  }

  left.css('height', height+'px');
  right.css('height', height+'px');

}