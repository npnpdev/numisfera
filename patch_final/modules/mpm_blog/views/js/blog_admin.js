$(document).ready(function(){

  if( $('#blog_post_form').length > 0 || $('#blog_category_form').length > 0 ){
    ps_force_friendly_product = true;
    PS_ALLOW_ACCENTED_CHARS_URL = false;
  }

  $(document).on('change', '.form-horizontal.blog input[name=show_archive]', function(event){
    if( $(this).val() == 1 ){
      $('.form-horizontal.blog .show_archive_home').show();
    }
    else{
      $('.form-horizontal.blog .show_archive_home').hide();
    }
  });

  if( $('.form-horizontal.blog input[name=show_archive]:checked').val() == 1 ){
    $('.form-horizontal.blog .show_archive_home').show();
  }

  $(document).on('change', '.form-horizontal.blog input[name=show_tags]', function(event){
    if( $(this).val() == 1 ){
      $('.form-horizontal.blog .show_tags_home').show();
    }
    else{
      $('.form-horizontal.blog .show_tags_home').hide();
    }
  });

  if( $('.form-horizontal.blog input[name=show_tags]:checked').val() == 1 ){
    $('.form-horizontal.blog .show_tags_home').show();
  }


  $(document).on('change', '.form-horizontal.blog input[name=featured_posts]', function(event){
    if( $(this).val() == 1 ){
      $('.form-horizontal.blog .featured_home').show();
    }
    else{
      $('.form-horizontal.blog .featured_home').hide();
    }
  });

  if( $('.form-horizontal.blog input[name=featured_posts]:checked').val() == 1 ){
    $('.form-horizontal.blog .featured_home').show();
  }


  $(document).on('change', '.form-horizontal.blog input[name=new_comments]', function(event){
    if( $(this).val() == 1 ){
      $('.form-horizontal.blog .send_email').show();
    }
    else{
      $('.form-horizontal.blog .send_email').hide();
    }
  });

  if( $('.form-horizontal.blog input[name=new_comments]:checked').val() == 1 ){
    $('.form-horizontal.blog .send_email').show();
  }


  $(document).on('change', '.form-horizontal.blog input[name=show_search]', function(event){
    if( $(this).val() == 1 ){
      $('.form-horizontal.blog .show_search_home').show();
    }
    else{
      $('.form-horizontal.blog .show_search_home').hide();
    }
  });

  if( $('.form-horizontal.blog input[name=show_search]:checked').val() == 1 ){
    $('.form-horizontal.blog .show_search_home').show();
  }


  $(document).on('change', '.form-horizontal.blog input[name=show_categories]', function(event){
    if( $(this).val() == 1 ){
      $('.form-horizontal.blog .show_categories_home').show();
    }
    else{
      $('.form-horizontal.blog .show_categories_home').hide();
    }
  });

  if( $('.form-horizontal.blog input[name=show_categories]:checked').val() == 1 ){
    $('.form-horizontal.blog .show_categories_home').show();
  }



  $(document).on('change', '.select_products', function(){
    var id_shop = $("input[name=id_shop]").val();
    var id_blog_post = $("input[name=id_blog_post]").val();
    $.ajax({
      url: '../modules/mpm_blog/send.php',
      type: 'post',
      data: 'add_product=true&id_product='+$(this).val() + '&id_shop=' + id_shop +'&id_blog_post='+id_blog_post,
      dataType: 'json'
    });
  });
  $(document).on('keyup', '.product_list .search_checkbox_table', function(e){
    var id_lang = $("input[name=id_lang]").val();
    var id_shop = $("input[name=id_shop]").val();
    var id_blog_post = $("input[name=id_blog_post]").val();
    var self = $(this);
    $.ajax({
      url: '../modules/mpm_blog/send.php',
      type: 'post',
      data: 'search_product=' + $(this).val() +'&id_shop='+id_shop+'&id_lang='+id_lang +'&id_blog_post='+id_blog_post,
      dataType: 'json',
      success: function(json) {
        $('.alert, .alert-danger, .alert-success').remove();
        if (json['products']) {
          self.parents('table').find('tbody').replaceWith(json['products']);
        }
      }
    });
  });
  $(document).on('click', '.product_list #show_checked', function(e){
    e.preventDefault();
    $(".product_list .col-lg-6 .search_checkbox_table").val("");
    var id_lang = $("input[name=id_lang]").val();
    var id_shop = $("input[name=id_shop]").val();
    var id_blog_post = $("input[name=id_blog_post]").val();
    $.ajax({
      url: '../modules/mpm_blog/send.php',
      type: 'post',
      data: 'show_checked_products=true' +'&id_shop='+id_shop+'&id_lang='+id_lang +'&id_blog_post='+id_blog_post,
      dataType: 'json',
      success: function(json) {
        $('.alert, .alert-danger, .alert-success').remove();
        $(".product_list .col-lg-6 tbody").replaceWith(json['products']);
      }
    });
  });
  $(document).on('click', '.product_list #show_all', function(e){
    e.preventDefault();
    $(".product_list .col-lg-6 .search_checkbox_table").val("");
    var id_lang = $("input[name=id_lang]").val();
    var id_shop = $("input[name=id_shop]").val();
    var id_blog_post = $("input[name=id_blog_post]").val();
    $.ajax({
      url: '../modules/mpm_blog/send.php',
      type: 'post',
      data: 'show_all_products=true' + '&id_shop='+id_shop+'&id_lang='+id_lang +'&id_blog_post='+id_blog_post,
      dataType: 'json',
      success: function(json) {
        $('.alert, .alert-danger, .alert-success').remove();
        $(".product_list .col-lg-6 tbody").replaceWith(json['products']);
      }
    });
  });
  $(document).on('change', '.select_posts', function(){
    var id_shop = $("input[name=id_shop]").val();
    var id_blog_post = $("input[name=id_blog_post]").val();
    $.ajax({
      url: '../modules/mpm_blog/send.php',
      type: 'post',
      data: 'add_post=true&id_post='+$(this).val() + '&id_shop=' + id_shop +'&id_blog_post='+id_blog_post,
      dataType: 'json'
    });
  });
  $(document).on('keyup', '.post_list .search_checkbox_table', function(e){
    var id_lang = $("input[name=id_lang]").val();
    var id_shop = $("input[name=id_shop]").val();
    var id_blog_post = $("input[name=id_blog_post]").val();
    var self = $(this);

    $.ajax({
      url: '../modules/mpm_blog/send.php',
      type: 'post',
      data: 'search_post=' + $(this).val() +'&id_shop='+id_shop+'&id_lang='+id_lang +'&id_blog_post='+id_blog_post,
      dataType: 'json',
      success: function(json) {
        $('.alert, .alert-danger, .alert-success').remove();
        if (json['post']) {
          self.parents('table').find('tbody').replaceWith(json['post']);
        }
      }
    });
  });
  $(document).on('click', '.post_list #show_checked', function(e){
    var id_lang = $("input[name=id_lang]").val();
    var id_shop = $("input[name=id_shop]").val();
    var id_blog_post = $("input[name=id_blog_post]").val();
    e.preventDefault();
    $(".post_list .col-lg-6 .search_checkbox_table").val("");
    $.ajax({
      url: '../modules/mpm_blog/send.php',
      type: 'post',
      data: 'show_checked_post=true&id_shop='+id_shop+'&id_lang='+id_lang +'&id_blog_post='+id_blog_post,
      dataType: 'json',
      success: function(json) {
        $('.alert, .alert-danger, .alert-success').remove();
        $(".post_list .col-lg-6 tbody").replaceWith(json['post']);
      }
    });
  });
  $(document).on('click', '.post_list #show_all', function(e){
    var id_lang = $("input[name=id_lang]").val();
    var id_shop = $("input[name=id_shop]").val();
    var id_blog_post = $("input[name=id_blog_post]").val();
    e.preventDefault();
    $(".post_list .col-lg-6 .search_checkbox_table").val("");
    $.ajax({
      url: '../modules/mpm_blog/send.php',
      type: 'post',
      data: 'show_all_post=true&id_shop='+id_shop+'&id_lang='+id_lang +'&id_blog_post='+id_blog_post,
      dataType: 'json',
      success: function(json) {
        $('.alert, .alert-danger, .alert-success:checked').remove();
        $(".post_list .col-lg-6 tbody").replaceWith(json['post']);
      }
    });
  });
  $('.filter-by-category-blog').change(function(){
    if($('.filter-by-category-blog:checked').val()){
      $('.categories-tree-blog').show();
    }
    else{
      $('.categories-tree-blog').hide();
      location.href = $('.base_url_filter').val()
    }
  });

  $('.id-category-blog').change(function(){
    location.href = $('.base_url_'+$(this).val()).val()
  });
  $('.id-category-blog-home').change(function(){
    location.href = $('.base_url_filter').val()
  });

});




