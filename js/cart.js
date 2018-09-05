$(document).ready(function () {
  

  function updateCartPanel(){
    // update counter badge
    var basket=$('#nanogallery2').nanogallery2('shoppingCartGet');
    var cartCounter = 0;
    for(var i = 0;i < basket.length; i++){
        if(basket[i].cnt%2!=0){
          cartCounter++;
        }
    }
    //$("#cart_counter" ).toggle( "bounce", { times: 1 }, "slow" );
    
    //$("#cart_counter" ).effect("bounce", { times:1 }, 300, function(){$("#cart_counter").html(cartCounter);} );
    //$("#cart_counter").effect( "pulsate");
    $("#cart_counter").html(cartCounter + "  foto scelte...");
    
    var list_element = "";
    if(cartCounter == 0){
      list_element= "<li><p class='text-center' >Seleziona le foto, poi inserisci mail e nome allievo...</p></li>";
      $('#requestphoto').prop("disabled",true);
    } else {
      $('#requestphoto').prop("disabled", false);
      var items = $('#nanogallery2').nanogallery2('data').items;
      for(var j = 0;j < basket.length; j++){
        if(basket[j].cnt%2 == 0){
          continue;
        }
        
        var item = items[basket[j].idx];
        list_element +='<li>';
        list_element +='  <span class="item">';
        list_element +='    <span class="item-left">';
        list_element +='      <a href="#" target="_blank" data-featherlight="'+item.src+'"><img src="'+item.src+'"  height="60" width="60" alt="'+item.title+'" data-album="'+item.customData.albumname+'"data-imagetitle="'+item.title+'"  /></a>';
        list_element +='      <span class="item-info"><i class="album_tag" >ALBUM ' + item.customData.albumname + ':</i><br/>' + item.title + '</span>';
        list_element +='    </span>';
        list_element +='    <span class="item-right"><button type="button" class="btn btn-default btn-circle" data-idx="'+basket[j].idx+'" data-id="'+item.GetID()+'"  ><i class="fa fa-trash-o" aria-hidden="true"></i></button></span>';
        list_element +='  </span>';
        list_element +='</li>';
      }
    }
    
    $("#photocartlist").html(list_element);
    
    
  }
  
  $('#photocartlist').on("click", "button", function(e) {
    //alert(this.dataset.idx);
    var basket=$('#nanogallery2').nanogallery2('shoppingCartGet');
    //$('#nanogallery2').nanogallery2('shoppingCartUpdate',  this.dataset.id, 0);;
    basket = removeFromCart(basket, this.dataset.id, this.dataset.idx);
    photofnShoppingCartUpdated(basket)
    e.stopPropagation();
  });
  
  
  function photoTnInit( $e, item, GOMidx ) {
    var c = getColourCart(item);
    tnColourInCartItem(item, c);
  }
  
  function getColourCart(item){
    var c='#FFFFFF';
    if( item.customData.incart ) {
        c='#ffff00';
    }
    return c;
  }
    
  // Set the favorite status
  function tnColourInCartItem(item, c) {
    if(item == null){
      console.log("item nullo");
      return;
    }
    if(item.$elt == null){
      console.log("item.$elt nullo");
      return;
    }
    item.$elt.find('[data-ngy2action="CART"]').css('color',c);
    if(item.customData.incart){
      item.$elt.addClass("selected-photo");
      item.$elt.find('.nGY2GThumbnailIconsFullThumbnail').addClass('selected-photo-innercolour');
    } else {
      item.$elt.removeClass("selected-photo");
      item.$elt.find('.nGY2GThumbnailIconsFullThumbnail').removeClass('selected-photo-innercolour');
    }
  }

  function tnColourInCartLightBox(c) {
    $('[data-ngy2action="custom1"]').css('color',c);
  }

 
  function addToCart(basket, itemID, itemIdx){
    var basketContains = false;
    for(var i = 0;i < basket.length; i++){
      var el = basket[i];
      if(el.ID == itemID){
        el.cnt++;
        basketContains = true;
        break;
      }
    }
    if(!basketContains) {
      basket.push({'idx':itemIdx, 'ID': itemID, 'cnt':1});
    }
    return basket;
  }
  
  function removeFromCart(basket, itemID, itemIdx){
    
    for(var i = 0;i < basket.length; i++){
      var el = basket[i];
      if(el.ID == itemID){
        el.cnt = 0;
        break;
      }
    }
    return basket;
    
  }
  
  function photofnImgToolbarCustClick(customElementName, $customIcon, item){
      addItemToCart(item);
  }
  
  
  function addItemToCart(item){
    
    var basket=$('#nanogallery2').nanogallery2('shoppingCartGet');
    var itemID = item.GetID();
    var itemIdx = item.customData.idx;
    basket = addToCart(basket, itemID, itemIdx);
    photofnShoppingCartUpdated(basket);
    var itemsReloaded = $('#nanogallery2').nanogallery2('data').items;
    var itemReloaded = itemsReloaded[item.customData.idx];
    var c = getColourCart(itemReloaded);
    tnColourInCartLightBox(c);
  }
  
  function photofnShoppingCartUpdated(basket){
      var items = $('#nanogallery2').nanogallery2('data').items;
      for(var i = 0;i < basket.length; i++){
        var el = basket[i];       
        var item = items[el.idx];
        if(el.cnt%2==0){
          item.customData.incart=false;  
          item.selected = false;
          // if click twice reset cart counter
          el.cnt = 0;       
        } else {
          item.customData.incart=true;
          item.selected = true;
        }
        
        var c = getColourCart(item);
        tnColourInCartItem(item, c);      
        updateCartPanel();
      }
      
  }
  
  function photofnImgToolbarCustDisplay(customToolbarElements, item){
    var c = getColourCart(item);
    tnColourInCartLightBox(c);
  }
  
  function photofnThumbnailToolCustAction(thumbnailPar, item){
    console.log(item);
    //var imgQuery = $("img[data-idx='" + 3 +"']").first();
    var imgQuery = item.src;
    $.featherlight(imgQuery, $.featherlight.defaults);
    
    return false;
  }
  
  function photofnThumbnailOpen(items){
    //alert(items.lenght);
    addItemToCart(items[0]);
    //return false;
  }
  
  
  $('#nanogallery2').nanogallery2('option', 'fnImgToolbarCustClick', photofnImgToolbarCustClick); 
  $('#nanogallery2').nanogallery2('option', 'fnShoppingCartUpdated', photofnShoppingCartUpdated); 
  $('#nanogallery2').nanogallery2('option', 'fnThumbnailInit',       photoTnInit);
  $('#nanogallery2').nanogallery2('option', 'fnImgToolbarCustDisplay', photofnImgToolbarCustDisplay); 
  $('#nanogallery2').nanogallery2('option', 'fnThumbnailToolCustAction', photofnThumbnailToolCustAction); 
  $('#nanogallery2').nanogallery2('option', 'fnThumbnailOpen', photofnThumbnailOpen); 


  
  // Attach a submit handler to the form
  $( "#sendform" ).submit(function( event ) {
   
    // Stop form from submitting normally
    event.preventDefault();
   
    // Get some values from elements on the page:
    var $form = $( this );
    var email = $form.find( "input[name='email']" ).val().trim();
    var name = $form.find( "input[name='name']" ).val().trim();
    var imagePathList = $('ul#photocartlist li img').map(function() { 
      
      var imagePathListElem = {};
      imagePathListElem.album = this.dataset.album;
      imagePathListElem.imagetitle = this.dataset.imagetitle;
      imagePathListElem.path= this.src.replace(window.location.origin+window.location.pathname,"");
      return imagePathListElem; 
      
    }).get();
    /*imagePathList = imagePathList.map(function(e){
      
      return e.replace(window.location.origin+window.location.pathname,"");
    });*/
    
    var url = $form.attr( "action" );
    $('#requestphoto').button('loading');
   
    // Send the data using post
    var posting = $.post( url, { 'email': email, 'name':name, 'imagePathList': imagePathList }, 
      function(data, textStatus, jqXHR) {
        //alert( "success" + data);
        console.log("SUCCESS:", data, textStatus, jqXHR);
        //$("#myModalOK").modal('show');
        
        var list_element = "";
        list_element +='<div class="">';
        list_element +='    <div class="">';
        list_element +='        <div class="modal-header">';
        list_element +='          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        list_element +='<h4 class="modal-title">Confermato</h4>';
        list_element +='</div>';
        list_element +='<div class="modal-body">';
        list_element +='<p>Richiesta di <B>'+imagePathList.length+'</B> foto completata</p>';
        list_element +='<p class="text-info">Controlla nei prossimi 10 minuti al tuo indirizzo email <b>('+email+')</b> per verifica se se è arrivata la ricevuta della richiesta...</p>';
        list_element +='<p class="text-info"><b>Attenzione:</b> alcune volte la mail va nello <b>SPAM</b>!</p>';
        list_element +='</div>';
        list_element +='<div class="modal-footer">';
        list_element +='    <a href="." class="btn btn-info" role="button">Inizia da capo!</a>';
        list_element +='</div>';
        list_element +='</div>';
        list_element +='</div>';
        $("#form_container").html(list_element);
        //$("#dropdown_cart").toggleClass('open');
       }, 'json');
    /*posting.done(function(data, textStatus, jqXHR ) {
        alert( "second success" );
      });*/
    posting.fail(function(jqXHR, textStatus, errorThrown ) {
        //alert( "error" );
        console.log("ERROR:", jqXHR, textStatus, errorThrown);
        $("#myModalError").modal('show');
      });
    posting.always(function(data_jqXHR, textStatus, jqXHR_errorThrown ) {
        console.log("ALWAYS:",data_jqXHR, textStatus, jqXHR_errorThrown);
        $('#requestphoto').button('reset');
        //$(".dropdown-menu").dropdown('toggle');
      });
    // Put the results in a div
    /*
    posting.done(function( data ) {
      var content = $( data ).find( "#content" );
      $( "#result" ).empty().append( content );
    });*/
  });
  
  /*
  $('#sendform').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    emailAddress: {
                        message: 'The value is not a valid email address'
                    }
                }
            }
        }
    });
  */
  

  $('#dropdown_cart').on({
    "shown.bs.dropdown": function() { this.closable = false; },
    "click":             function() { this.closable = true; },
    "hide.bs.dropdown":  function() { return this.closable; }
  });
  
  /* disable back button */
  history.pushState(null, null, location.href);
    window.onpopstate = function () {
        alert("Non premere il pulsante indietro...");
        return false;
    };

  $('body').on('contextmenu', function (evt) {
    evt.preventDefault();
  });

});
  