$(document).ready(function(){
  if($('.productsBlockFeatured').length>0){
    select2IncludeAdmin();
  }

  $('#add_products_item_featured').live('click', function(){
    addItem();
  });

  $('.type_products_show').change(function(){
    var type = $(this).val();
    if(type == 'category'){
      $('.form-group.content_category').addClass('active');
      $('.form-group.content_product').removeClass('active');
    }
    else if(type == 'products'){
      $('.form-group.content_product').addClass('active');
      $('.form-group.content_category').removeClass('active');
    }
    else{
      $('.form-group.content_product').removeClass('active');
      $('.form-group.content_category').removeClass('active');
    }
  });

  $('.table_delete a').live('click', function(){
    var ids = $('input[name=idsProducts]').val();
    var id_product = $(this).attr('data-id-product');
    ids = ids.split(',');
    var k = ids.indexOf(id_product);
    ids.splice(k, 1);
    ids = ids.join(',');
    $('input[name=idsProducts]').val(ids);
    $(this).parents('.item_product').remove();
  });

});

function addItem() {

  var ids = $('input[name=idsProducts]').val();
  var id_product = $('#attendee_home').val();
  var ids_new = ids.split(',');
  var k = ids_new.indexOf(id_product);

  if(k >= 0){
    return false;
  }

  if(!ids){
    ids = id_product;
  }
  else{
    ids = ids+','+id_product;
  }

  $('input[name=idsProducts]').val(ids);

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax: true,
      token: $('input[name=token_featured]').val(),
      controller: 'AdminMpmHomeFeatured',
      fc: 'module',
      module: 'mpm_homefeatured',
      action: 'addProduct',
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      ids: ids,
    },
    success: function (json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else {
        if (json['list']) {
          $('.added_products').html(json['list']);
        }
      }
    }
  });
}

function select2IncludeAdmin(){
  $('#attendee_home').select2({
    placeholder: "Search for a repository",
    minimumInputLength: 1,
    width: '345px',
    dropdownCssClass: "bootstrap",
    ajax: {
      url: 'index.php',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          q: params,
          ajax	: true,
          token: $('input[name=token_featured]').val(),
          controller: 'AdminMpmHomeFeatured',
          action: 'searchProduct'
        };
      },
      results: function (data) {
        if( data ){
          return { results: data };
        }
        else{
          return {
            results: []
          }
        }
      }
    },
    formatResult: productFormatResult,
    formatSelection: productFormatSelection,
  })
}

function productFormatResult(item) {
  itemTemplate = "<div class='media'>";
  itemTemplate += "<div class='pull-left'>";
  itemTemplate += "<img class='media-object' width='40' src='" + item.image + "' alt='" + item.name + "'>";
  itemTemplate += "</div>";
  itemTemplate += "<div class='media-body'>";
  itemTemplate += "<h4 class='media-heading'>" + item.name + "</h4>";
  itemTemplate += "<span>REF: " + item.ref + "</span>";
  itemTemplate += "</div>";
  itemTemplate += "</div>";
  return itemTemplate;
}

function productFormatSelection(item) {
  return item.name;
}

function showSuccessMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function showErrorMessage(msg) {
  $.growl.error({ title: "", message:msg});
}