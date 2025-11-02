$(document).ready(function(){

  $( ".getFormColumn .col-lg-offset-3" ).sortable({
    forcePlaceholderSize: true,
    axis: 'y',
    update: function() { updatePosition(1) },
  } );

  $(document).on('click', '.table_link_edit a', function(e){
    e.preventDefault();
    var id = $(this).attr('data-id-link');
    var id_item = $(this).parents('.block_item_group').attr('data-id');
    editLinkItem(id, id_item);
  });


  $(document).on('click', '.table_link_delete a', function(e){
    e.preventDefault();
    var id = $(this).attr('data-id-link');
    var id_item = $(this).parents('.block_item_group').attr('data-id');
    removeLinkItem(id, id_item);
  });

  $(document).on('click', '.table_list_delete a', function(e){
    e.preventDefault();
    var id_product = $(this).attr('data-id-product');
    var id_item = $(this).parents('.block_item_group').attr('data-id');
    removeProductItem(id_product, id_item);
  });


  $(document).on('click', '#add_products_item', function(){
    addProductItem();
  });


  $(document).on('click', '.uploadImagesForm a.btn.btn-default', function(e){
    e.preventDefault();
    var id_item = $(this).parents('.block_item_group').attr('data-id');
    var id_group = $(this).attr('href');
    removeImage(id_item, id_group);
  });

  $(document).on('click', '.topMenuLinkBlock .add_link', function(){
    var id = $(this).attr('data-id');
    saveLink($('.form_group_type_content.form_group_class_'+id), id);
  });

  $('.form-group.tabs_content .col-lg-offset-3 .tabs a').live('click', function (event) {
    if( !$(this).hasClass('active')){
      var tab = $(this).attr('data-class');
      $('.form-group.tabs_content .col-lg-offset-3 .tabs a').removeClass('active');
      $('.form-group.tab_content').hide();
      $(this).addClass('active');
      $('.form-group.tab_content.'+tab).show();
    }
  });

  $('.button_block .open_column').live('click', function (event) {
    if( !$(this).hasClass('active')){
      var id = $(this).attr('data-id');
      $(this).addClass('active');
      $('.form_group_class_'+id).removeClass('hide');


      $( ".getFormColumn .col-lg-offset-3" ).sortable({
        disabled : true,
      } );

    }
    else{
      var id = $(this).attr('data-id');
      $(this).removeClass('active');
      $('.form_group_class_'+id).addClass('hide');

      $( ".getFormColumn .col-lg-offset-3" ).sortable({
        forcePlaceholderSize: true,
        axis: 'y',
        disabled : false,
        update: function() { updatePosition(1) },
      } );

    }
  });

  $('.button_block_group .open_column').live('click', function (event) {
    if( !$(this).hasClass('active')){
      var id = $(this).attr('data-id');
      $(this).addClass('active');

      $('.form_group_class_'+id).removeClass('hide');

      $( ".getFormGroup .tab_column_content" ).sortable({
        disabled : true,
      } );
    }
    else{
      var id = $(this).attr('data-id');
      $(this).removeClass('active');
      $('.form_group_class_'+id).addClass('hide');

      $( ".getFormGroup .tab_column_content" ).sortable({
        forcePlaceholderSize: true,
        axis: 'y',
        disabled : false,
        update: function() { updatePositionGroup(0) },
      } );
    }
  });


  $('.button_block .add_column').live('click', function (event) {
    var count = $('.block_item_column').length;
    var id = $(this).attr('data-id');
    addNewColumn(count, id)
  });

  $('.button_block .remove_column').live('click', function (event) {
    var id = $(this).attr('data-id');
    removeColumn(id)
  });

  $('.button_block .save_column').live('click', function (event) {
    var id = $(this).attr('data-id');
    saveColumn(id)
  });



  $(document).on('change', '.block_item_group .type', function(){
    var id = $(this).attr('data-id');
    var type = $("select[name='type_"+id+"']").val();

    getContentType(id, type);

  });


  $('.tab_add_item_group').live('click', function (event) {
    getGroups(0);
  });


  $('.button_block_group .save_column').live('click', function (event) {
    var id = $(this).attr('data-id');
    saveGroup(id)
  });




  $('.tab_group_item').live('click', function (event) {
    var id = $(this).attr('data-id');
    $(this).addClass('active');
    $('.tab_group_item').removeClass('active');
    getGroups(id)
  });



  $('.button_block_group .add_column').live('click', function (event) {
    var count = $('.block_item_group').length;
    var id = $(this).attr('data-id');
    addNewItemGroup(count, id)
  });


  $('.button_block_group .remove_column').live('click', function (event) {
    var id = $(this).attr('data-id');
    removeGroup(id)
  });

});


function editLinkItem(id, id_item) {
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_top_group]').val(),
      controller: 'AdminTopMenuGroup',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'editLinkItem',
      id_lang: $("input[name='idLang']").val(),
      id_shop: $("input[name='idShop']").val(),
      id: id,
      ident: id_item,
      value:  $('.block_item_group_'+id_item+' input[name=ids_link]').val(),

    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar_ex').remove();
      if(json['form']){
        $('.form_group_type_content.form_group_class_'+id_item).html(json['form']);
      }
    }
  });
}

function removeLinkItem(id, id_item) {
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_top_link]').val(),
      controller: 'AdminTopMenuLink',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'removeLink',
      id_lang: $("input[name='idLang']").val(),
      id_shop: $("input[name='idShop']").val(),
      id: id,

    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar_ex').remove();
      if(json['success']){
        var links = $('.block_item_group_'+id_item+' #ids_link').val();
        if(links){
          var new_links = links.split(',');
          var index = $.inArray(id, new_links);
          new_links.splice(index, 1);
          $('.block_item_group_'+id_item+' #ids_link').val(new_links);
        }
        $('.block_item_group_'+id_item+' .table_link_list .item_link_'+id).remove();
        showSuccessMessage(json['success']);
      }
    }
  });
}


function removeProductItem(id, id_item) {
  var products = $('.block_item_group_'+id_item+' #productIds').val();
  if(products){
    var new_products = products.split(',');
    var index = $.inArray(id, new_products);
    new_products.splice(index, 1);
    $('.block_item_group_'+id_item+' #productIds').val(new_products);
  }
  $('.block_item_group_'+id_item+' .table_product_list .item_product_'+id).remove();
}

function removeImage(id_item, id_group){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_top_group]').val(),
      controller: 'AdminTopMenuGroup',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'removeImage',
      id_lang: $("input[name='idLang']").val(),
      id_shop: $("input[name='idShop']").val(),
      id_group: id_group,

    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar_ex').remove();
      if(json['success']){
        showSuccessMessage(json['success']);

        $('.block_item_group_'+id_item+' .uploadImagesForm #image-images-thumbnails').remove();
      }
    }
  });
}


function updatePositionGroup(el) {
  var position = {};
  $('.getFormGroup .block_item_group').each(function(k) {
    var id = $(this).attr('data-id');

    var p = k+1;
    var id_group = $('.getFormGroup .block_item_group_'+id+' input[name="id_group"]').val();

    if(id_group){
      position[id_group] = p;
    }
  });

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_top_group"]').val(),
      controller: 'AdminTopMenuGroup',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'updatePosition',
      id_shop: $('#idShop').val(),
      id_lang: $('#idLang').val(),
      position: position,
      id_topmenu: $('input[name="id_topmenu"]').val(),
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      if (json['error']) {
        $('.progres_bar_ex').remove();
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){
          $('.progres_bar_ex').remove();
            getGroups(el);
        }
      }
    }
  });
}



function saveGroup(id){

  var description_after = {};
  var description_before = {};
  var description = {};
  var title = {};
  var product = '';
  var link = '';
  var category = '';
  var brand = '';
  var supplier = '';
  var page = '';
  var cms = '';
  var type_img = '';
  var product_title = 1;
  var product_img = 1;
  var product_price = 1;
  var product_add = 1;
  var subcategories = 1;
  var imgData = new FormData();
  var type = $('select[name="type_'+id+'"]').val();

  $('.block_item_group_'+id+' .form-group-after textarea').each(function(i,elem) {
    var value = $(this).attr('id');
    var description = tinyMCE.get(value).getContent();
    var value_lang = value.split('_');
    var key = value_lang[4];
    description_after[key] = encodeURIComponent(description);
  });

  $('.block_item_group_'+id+' .form-group-before textarea').each(function(i,elem) {
    var value = $(this).attr('id');
    var description = tinyMCE.get(value).getContent();
    var value_lang = value.split('_');
    var key = value_lang[4];
    description_before[key] = encodeURIComponent(description) ;
  });

  $('.block_item_group_'+id+' .form-group-title input').each(function(i,elem) {
    var value = $(this).attr('id');
    var value_lang = value.split('_');
    var key = value_lang[1];
    title[key] = $(this).val();
  });

  if(type == 'product'){
    product = $('.block_item_group_'+id+' input[name="productIds"]').val();
    type_img = $('.block_item_group_'+id+' select[name="type_image_'+id+'"]').val();
    product_title = $('.block_item_group_'+id+' input[name="product_title_'+id+'"]:checked').val();
    product_img = $('.block_item_group_'+id+' input[name="product_img_'+id+'"]:checked').val();
    product_price = $('.block_item_group_'+id+' input[name="product_price_'+id+'"]:checked').val();
    product_add = $('.block_item_group_'+id+' input[name="product_add_'+id+'"]:checked').val();

  }
  if(type == 'link'){
    link = $('.block_item_group_'+id+' input[name="ids_link"]').val();
  }

  if(type == 'category'){
    var field = $('.block_item_group_'+id+' .bestCategoryBox input[type=checkbox]:checked');
    category = getCheckboxValue(field);
    subcategories = $('.block_item_group_'+id+' input[name=subcategories_'+id+']:checked').val();
  }
  if(type == 'cms'){
    var field = $('.block_item_group_'+id+' .getCmsBlock input[type=checkbox]:checked');
    cms = getCheckboxValue(field);
  }
  if(type == 'brand'){
    var field = $('.block_item_group_'+id+' .getBrandBlock input[type=checkbox]:checked');
    brand = getCheckboxValue(field);
  }
  if(type == 'supplier'){
    var field = $('.block_item_group_'+id+' .getSuppliersBlock input[type=checkbox]:checked');
    supplier = getCheckboxValue(field);
  }
  if(type == 'page'){
    var field = $('.block_item_group_'+id+' .getPagesBlock input[type=checkbox]:checked');
    page = getCheckboxValue(field);
  }

  if(type == 'image'){
    imgData.append('file', $('.block_item_group_'+id+' input[name=image]')[0].files[0]);
  }

  if(type == 'description'){
    $('.block_item_group_'+id+' .descriptionBox textarea').each(function(i,elem) {
      var value = $(this).attr('id');
      var text = tinyMCE.get(value).getContent();
      var value_lang = value.split('_');
      var key = value_lang[1];
      description[key] = encodeURIComponent(text);
    });
  }

  description = JSON.stringify(description);
  description_before = JSON.stringify(description_before);
  description_after = JSON.stringify(description_after);
  title = JSON.stringify(title);


  imgData.append('ajax', true);
  imgData.append('token', $('input[name="token_top_group"]').val());
  imgData.append('controller', 'AdminTopMenuGroup');
  imgData.append('fc', 'module');
  imgData.append('module', 'mpm_topmenu');
  imgData.append('action', 'saveGroup');
  imgData.append('id_shop', $('#idShop').val());
  imgData.append('id_lang', $('#idLang').val());
  imgData.append('id_topmenu', $('input[name="id_topmenu"]').val());
  imgData.append('id_topmenu_column', $('.block_item_group_'+id+' .current_column').val());
  imgData.append('id_group', $('.block_item_group_'+id+' .id_group').val());
  imgData.append('title_admin', $('input[name="title_group_'+id+'"]').val());
  imgData.append('type', type);
  imgData.append('active', $('input[name="active_group_'+id+'"]:checked').val());
  imgData.append('text_color', $('input[name="text_color_group_'+id+'"]').val());
  imgData.append('text_color_hover', $('input[name="text_color_group_hover_'+id+'"]').val());
  imgData.append('background_color', $('input[name="background_color_group_'+id+'"]').val());
  imgData.append('description_after', description_after);
  imgData.append('description_before', description_before);
  imgData.append('title', title);
  imgData.append('ident', id);
  imgData.append('position', 0);
  imgData.append('product', product);
  imgData.append('link', link);
  imgData.append('category', category);
  imgData.append('cms', cms);
  imgData.append('brand', brand);
  imgData.append('supplier', supplier);
  imgData.append('page', page);
  imgData.append('description', description);
  imgData.append('type_img', type_img);
  imgData.append('product_title', product_title);
  imgData.append('product_img', product_img);
  imgData.append('product_price', product_price);
  imgData.append('product_add', product_add);
  imgData.append('subcategories', subcategories);

  $.ajax({
    url: 'index.php?rand=' + new Date().getTime(),
    type: 'post',
    data: imgData,
    dataType: 'json',
    processData: false,
    contentType: false,
      beforeSend: function(){
        // $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
      },
    success: function(json) {
      $('.progres_bar_ex').remove();
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else {
        if (json['success']) {
          showSuccessMessage(json['success']);
          $('.block_item_group_'+id+' .id_group').val(json['id_group']);
          $('.progres_bar_ex').remove();
          updatePositionGroup($('.block_item_group_'+id+' .current_column').val());


        }
      }
    }
  });
}

function getCheckboxValue(field) {
  var rez = '';
  $(field).each(function(i,elem) {
    var value = $(this).val();
    if(i == 0){
      rez += value;
    }
    else{
      rez += ','+value;
    }
  });

  return rez;
}

function saveLink(el, id){

  var link = {};
  $(el.find('input[type=text]')).each(function(k) {
      link[$(this).attr('name')] = $(this).val();
  });

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_top_link]').val(),
      controller: 'AdminTopMenuLink',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'saveLink',
      id_lang: $("input[name='idLang']").val(),
      id_shop: $("input[name='idShop']").val(),
      id_link: el.find('#hidden_id_link').val(),
      ids_link: el.find('#ids_link').val(),
      link: link,
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar_ex').remove();
      if(json['success']){

        el.find('#ids_link').val(json['ids_link'])
        $(".form_group_type_content.form_group_class_"+id+" .topMenuLinkList").html('<div class="col-lg-9 col-lg-offset-3">'+json['list']+'</div>');
        
        clearFormLink(id)

        showSuccessMessage(json['success']);
      }
    }
  });
}


function clearFormLink(id) {
  $('.block_item_group_'+id+' .topMenuLinkTitle input').each(function(i,elem) {
    $(this).val('');
  });

  $('.block_item_group_'+id+' .topMenuLinkUrl input').each(function(i,elem) {
    $(this).val('');
  });

  $('.block_item_group_'+id+' #hidden_id_link').val(0);
}


function getContentType(id, type){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_top_group]').val(),
      controller: 'AdminTopMenuGroup',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'getContentType',
      id_lang: $("input[name='idLang']").val(),
      id_shop: $("input[name='idShop']").val(),
      type : type,
      id : id,
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar_ex').remove();
      if(json['form']){
        $('.form_group_type_content.form_group_class_'+id).html(json['form']);
        if(type == 'product'){
          select2Include();
        }
      }
    }
  });
}

function addProductItem(){
  var id = $('#attendee').val();
  var products = $('#productIds').val();
  if(!products){
    var new_products = [id];
  }
  else{
    var new_products = products.split(',');
    var index = $.inArray(id, new_products);
    if(index<0){
      new_products.push(id);
    }
  }

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_top_group]').val(),
      controller: 'AdminTopMenuGroup',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'addProduct',
      id_lang: $("input[name='idLang']").val(),
      id_shop: $("input[name='idShop']").val(),
      products : new_products,
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar_ex').remove();
      if(json['list']){
        $('#productIds').val(json['products']);
        $('.table_product_list tbody').html(json['list']);
      }
    }
  });
}

function getGroups(id_topmenu_column){

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_top_group"]').val(),
      controller: 'AdminTopMenuGroup',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'addNewGroup',
      id_shop: $('#idShop').val(),
      id_lang: $('#idLang').val(),
      id_topmenu: $('input[name="id_topmenu"]').val(),
      id_topmenu_column: id_topmenu_column,
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      if (json['error']) {
        $('.progres_bar_ex').remove();
        showErrorMessage(json['error']);
      }
      else{
        if(json['columns']){
          $('.tab_columns').html(json['columns']);
          $('.tab_column_content').html(json['groups']);
        }
        $('.progres_bar_ex').remove();

        if($('#attendee').length>0){
          select2Include();
        }
      }
    }
  });
}


function select2Include(){

  $('.attendee').select2({
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
          token: $('input[name=token_top_group]').val(),
          controller: 'AdminTopMenuGroup',
          action: 'searchProduct',
          id_shop: $('#idShop').val(),
          id_lang: $('#idLang').val(),
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


function removeColumn(id){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_top_column"]').val(),
      controller: 'AdminTopMenuColumn',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'removeColumn',
      id_shop: $('#idShop').val(),
      id_lang: $('#idLang').val(),
      id_topmenu: $('input[name="id_topmenu"]').val(),
      id_topmenu_column: $('input[name="id_topmenu_column_'+id+'"]').val(),
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      if (json['error']) {
        $('.progres_bar_ex').remove();
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){
          $('.block_item_column.block_item_column_'+id).remove();
          updatePosition(1);
          $('.progres_bar_ex').remove();
        }
      }
    }
  });
}


function removeGroup(id){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_top_group"]').val(),
      controller: 'AdminTopMenuGroup',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'removeGroup',
      id_shop: $('#idShop').val(),
      id_lang: $('#idLang').val(),
      id_topmenu: $('input[name="id_topmenu"]').val(),
      id_group: $('.block_item_group.block_item_group_'+id+' .id_group').val(),
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      if (json['error']) {
        $('.progres_bar_ex').remove();
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){
          $('.block_item_group.block_item_group_'+id).remove();
          updatePositionGroup($('.block_item_group_'+id+' .current_column').val());
          $('.progres_bar_ex').remove();
        }
      }
    }
  });
}


function saveColumn(id){

  var description_after = {};
  var description_before = {};

  $('.block_item_column_'+id+' .form-group-after textarea').each(function(i,elem) {
    var value = $(this).attr('id');
    var description = tinyMCE.get(value).getContent();
    var value_lang = value.split('_');
    var key = value_lang[4];
    description_after[key] = description;
  });

  $('.block_item_column_'+id+' .form-group-before textarea').each(function(i,elem) {
    var value = $(this).attr('id');
    var description = tinyMCE.get(value).getContent();
    var value_lang = value.split('_');
    var key = value_lang[4];
    description_before[key] = description;
  });


  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_top_column"]').val(),
      controller: 'AdminTopMenuColumn',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'saveColumn',
      id_shop: $('#idShop').val(),
      id_lang: $('#idLang').val(),
      id_topmenu: $('input[name="id_topmenu"]').val(),
      id_topmenu_column: $('input[name="id_topmenu_column_'+id+'"]').val(),

      title: $('input[name="title_column_'+id+'"]').val(),
      active: $('input[name="active_column_'+id+'"]:checked').val(),
      text_color: $('input[name="text_color_column_'+id+'"]').val(),
      text_color_hover: $('input[name="text_color_column_hover_'+id+'"]').val(),
      background_color: $('input[name="background_color_column_'+id+'"]').val(),
      width: $('input[name="width_'+id+'"]').val(),

      description_after: description_after,
      description_before: description_before,
      position: 0,
      id: id,

    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      if (json['error']) {
        $('.progres_bar_ex').remove();
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){

          $('input[name="id_topmenu_column_'+id+'"]').val(json['id_topmenu_column']);
          $('.progres_bar_ex').remove();
          updatePosition(1);
        }
      }
    }
  });


}


function updatePosition(replace){
  var position = {};
  $('.getFormColumn .block_item_column').each(function(k) {
    var id = $(this).attr('data-id');

    var p = k+1;
    var id_topmenu_column = $('input[name="id_topmenu_column_'+id+'"]').val();

    if(id_topmenu_column){
      position[id_topmenu_column] = p;
    }
  });

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_top_column"]').val(),
      controller: 'AdminTopMenuColumn',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'updatePosition',
      id_shop: $('#idShop').val(),
      id_lang: $('#idLang').val(),
      position: position,
      replace: replace,
      id_topmenu: $('input[name="id_topmenu"]').val(),
    },
    beforeSend: function(){
      if(parseInt(replace)){
        $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
      }
    },
    success: function(json) {
      if (json['error']) {
        $('.progres_bar_ex').remove();
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){

          if(parseInt(replace)){
            var tpl = json['tpl'];
            $('.getFormColumn').html('<div class="col-lg-9 col-lg-offset-3">'+tpl+'</div>');
            $('.progres_bar_ex').remove();
            $('.block_item_column .mColorPickerInput').mColorPicker();
          }



          showSuccessMessage(json['success']);

        }
      }
    }
  });
}

function addNewColumn(count, id){
  var new_count = parseInt(count) + 1;

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_top"]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'addNewColumn',
      id_shop: $('#idShop').val(),
      id_lang: $('#idLang').val(),
      id_top_menu: $('input[name="id_topmenu"]').val(),
      id: new_count,
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      if (json['error']) {
        $('.progres_bar_ex').remove();
        showErrorMessage(json['error']);
      }
      else{
        if(json['form']){
          $('.block_item_column.block_item_column_'+id).after(json['form']);
          $(".block_item_column.block_item_column_"+new_count).show();
          $('.progres_bar_ex').remove();
          $('.block_item_column.block_item_column_'+new_count+' .mColorPickerInput').mColorPicker();

        }
      }
    }
  });
}

function addNewItemGroup(count, id){
  var new_count = parseInt(count) + 1;

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_top_group"]').val(),
      controller: 'AdminTopMenuGroup',
      fc: 'module',
      module : 'mpm_topmenu',
      action: 'addNewItemGroup',
      id_shop: $('#idShop').val(),
      id_lang: $('#idLang').val(),
      id_top_menu: $('input[name="id_topmenu"]').val(),
      id_topmenu_column: $('.block_item_group.block_item_group_'+id+' input[name="current_column"]').val(),
      id: new_count,
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      if (json['error']) {
        $('.progres_bar_ex').remove();
        showErrorMessage(json['error']);
      }
      else{
        if(json['form']){
          $('.block_item_group.block_item_group_'+id).after(json['form']);
          $(".block_item_group.block_item_group_"+new_count).show();
          $('.progres_bar_ex').remove();
            select2Include();
        }
      }
    }
  });
}



function showSuccessMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function showErrorMessage(msg) {
  $.growl.error({ title: "", message:msg});
}