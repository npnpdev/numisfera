$(document).ready(function(){




  if( $('.rrssb-social-buttons').length > 0 ){
    $('.rrssb-buttons').rrssb({
      // required:
      title: $('.rrssb-social-buttons').attr('data-title'),
      url: $('.rrssb-social-buttons').attr('data-url'),

      // optional:
      description: $('.rrssb-social-buttons').attr('data-description'),
      emailBody: $('.rrssb-social-buttons').attr('data-emailBody'),
    });
  }


  if( $('.block_featured .featured').length > 0 ){
    var slid = 2;

    var all = parseInt($('.block_content.featured_blog').attr('data-count-slides'));
    var display = parseInt($('.block_content.featured_blog').attr('data-slides'));

    if(display){
      slid = display;
    }
    if( (display > all) || (display == all)){
      $('.block_featured_arrows').hide();
    }

    $('.block_featured .featured').bxSlider({
      useCSS: false,
      minSlides: slid,
      mode: 'vertical',
      maxSlides: 1000,
      slideMargin: 15,
      moveSlides: 1,
      infiniteLoop: true,
      hideControlOnEnd: true,
      autoHover: true,
      pause: true,
      controls: true,
      pager: false,
      prevText: ' ',
      nextText: ' '
    });
  }

  if( $('.related_products_content').length > 0 ){
    var slid = 3;
    var slideWidth = $('.related_products_content').width();

    var all = $('#slider-arrows').attr('data-counts-slides');
    var display = $('#slider-arrows').attr('data-slides');

    if(display){
      slid = display;
    }

    if(display > all){
      slid = all;
    }

    if(slideWidth<500){
      slid = 1;
    }

    $('.related_products_content').bxSlider({
      useCSS: false,
      minSlides: slid,
      maxSlides: 1000,
      slideMargin: 15,
      slideWidth: slideWidth/slid,
      moveSlides: 1,
      infiniteLoop: true,
      hideControlOnEnd: true,
      autoHover: true,
      pause: true,
      controls: true,
      pager: false,
      prevText: '<i class="material-icons">keyboard_arrow_left</i>',
      nextText: '<i class="material-icons">keyboard_arrow_right</i>',
      nextSelector: '#slider-next',
      prevSelector: '#slider-prev',
    });
  }

  if( $('#blog_post_form').length > 0 || $('#blog_category_form').length > 0 ){
    ps_force_friendly_product = true;
    PS_ALLOW_ACCENTED_CHARS_URL = false;
  }



  if($('.rate_user').length > 0){
    $('.rate_user').raty();
  }
  if($.cookie){
    if($.cookie("category_view")){
      contentReplace($.cookie("category_view"));
    }
  }
  $('.bx-prev-blog').click(function(event){
    $('.featured_blog .bx-prev').click()
  });
  $('.bx-next-blog').click(function(event){
    $('.featured_blog .bx-next').click()
  });

  $('.sortPagiBarBlog #grid').click(function(){
    if( $('.content_post.list').length ){
      contentReplace('grid');
    }
  });
  $('.sortPagiBarBlog #list').click(function(){
    if( $('.content_post.grid').length ){
      contentReplace('list');
    }
  });



  if( $('.content_post.grid').length ){
    setTimeout(function () {
      sizeBlockPost();
    }, 50);
  }

  $( window ).resize(function() {
    if( $('.content_post.grid').length ){
      sizeBlockPost();
    }
  });

  if( $( window).width() < 767 ){
    $('.content_post').removeClass('grid');
    $('.content_post').addClass('list');
    contentReplace('list');
  }
});


function contentReplace(type){

  setInCookieBlog(type);
  if(type == 'list'){
    $('#content_post').removeClass('grid');
    $('#content_post').addClass('list');
    $('#content_post .one_post').css('width','100%');
    $('.sortPagiBarBlog #list').addClass('selected');
    $('.sortPagiBarBlog #grid').removeClass('selected');
    $('#content_post .one_post').css('height','auto');

  }

  if(type == 'grid'){
    $('#content_post').removeClass('list');
    $('#content_post').addClass('grid');
    $('.sortPagiBarBlog #list').removeClass('selected');
    $('.sortPagiBarBlog #grid').addClass('selected');
    sizeBlockPost();
  }
}

function setInCookieBlog(type){
  $.cookie("category_view",type,{ expires : 100, path:'/' });
}

function searchBlog(val,blogUrl){
  if(val.length>0){
    location = blogUrl+'search/'+val;
  }
}

function sizeBlockPost(){

  var width = $('.center_column_blog .content_post.grid').width();
  var width_block = (width-25)/2;
  $('.center_column_blog .content_post.grid .one_post').width(width_block);
  $('.center_column_blog .content_post.grid .one_post.even').each(function() {
    height_even = $(this).height();
    height_odd = $(this).next().height();
    if(height_even > height_odd){
      $(this).next().height(height_even);
    }
    else if(height_odd > height_even){
      $(this).height(height_odd);
    }
  });
}


function addNewComment(shopId,langId,id_blog_post){

  var name = $('.name_user_comments').val();
  var email = $('.email_user_comments').val();
  var comment = $('.comments_text').val();
  var captcha = $('.captcha_comments').val();
  var raty = $('input[name="rating_post"]').val();

  $('.alert-danger-blog').hide();


  $.ajax({
    url: url_base+'/modules/mpm_blog/send.php',
    type: 'post',
    data: 'addComment=true&name='+name+'&raty='+raty+'&id_blog_post='+id_blog_post+'&email='+email+'&comment='+comment+'&captcha='+captcha+'&shopId='+shopId+'&langId='+langId,
    dataType: 'json',
    beforeSend: function(){
      $(".progres_bar_ex").show();
    },
    complete: function(){
      $(".progres_bar_ex").hide();
    },
    success: function(json) {
      if (json['error']) {
        var error = json['error'];
        if(error == 1){
          $('.alert-danger-blog.error-name').show();
        }
        else if(error == 2){
          $('.alert-danger-blog.error-email').show();
        }
        else if(error == 3){
          $('.alert-danger-blog.error-comment').show();
        }
        else if(error == 4){
          $('.alert-danger-blog.error-captcha').show();
        }
        else if(error == 5){
          $('.alert-danger-blog.message').show();
        }
        else if(error == 6){
          $('.alert-danger-blog.reg').show();
        }


        setTimeout(function(){
          $('.alert-danger-blog').hide();
        }, 4000);

      }
      else{
        if(json['success']){

          $('.alert-success-blog').show();

          setTimeout(function(){
            $('.alert-success-blog').hide();
          }, 4000);

        }
      }

    }
  });
}




