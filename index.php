<?php 
# loading configuration

$config_ini_array = parse_ini_file("config.ini");

?>

<html>
  <head>
    <meta charset="utf-8">

    <title><?php echo $config_ini_array['title']; ?></title>

    <meta name="viewport" content="initial-scale=1">
    <!--<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">-->
    <meta name="description" content="<?php echo $config_ini_array['descr']; ?>">
    <meta name="author" content="mattia">

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <!-- Custom styles for this template -->
    <link href="css/style.css?v=1" rel="stylesheet">
    

   <!-- nanogallery https://nanogallery2.nanostudio.org -->
    <link href="https://unpkg.com/nanogallery2/dist/css/nanogallery2.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="https://unpkg.com/nanogallery2@2.2.0/dist/jquery.nanogallery2.min.js"></script>
    
    <!-- lightbox https://github.com/noelboss/featherlight/ -->
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css" type="text/css" rel="stylesheet" />
    <script src="//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>

    
  </head>
  <body class="transparent-header front-page fixed-header-on wide">

  
    <header class="main-header sticky-header transparent-header light menu-icon-inside">
        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand" href="#"><?php echo $config_ini_array['info']; ?></a>
            </div>
            <div id="navbar" class="nav navbar-nav navbar-right">
            <ul class="nav">
                <li class="dropdown" id="dropdown_cart">
                  <a id="photo-dropdown-link" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded=false> CARRELLO : <span class="fa fa-shopping-bag"></span> <span id="cart_counter" class="badge badge-light">0 foto scelte...</span><span class="caret"></span></a>
                  <div class="dropdown-menu">
                    <div id="form_container">
                    <form id="sendform" class="dropdown-cart-form" data-toggle="validator" role="form" action="./src/send.php" >
                        <p style="padding:5px">Inserisci i tuoi dati dopo aver selezionato le foto:</p>
                        
                        <div class="form-group">
                        <div class="container-fluid">
                        <div class="row">
                          <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                          <input type="email" name="email" class="form-control" id="exampleDropdownFormEmail" placeholder="email@example.com" required="required" data-error="Email non valida">
                          <div class="help-block with-errors"></div>
                        
                          <input type="text" name="name" class="form-control" id="exampleDropdownFormNome" placeholder="Nome Alievo/a" required="required" data-error="Il nome va inserito">
                          <div class="help-block with-errors"></div>
                          </div>
                          <div class="col-xs-2  col-sm-3 col-md-3 col-lg-3">
                          <button id="requestphoto" type="submit" disabled class="btn btn-primary text-center" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i><br/>Invio<br/>in corso">Richiedi<br/>le<br/>foto</button>
                          </div>
                        </div>
                        </div>
                        </div>
                        <span style="padding-top:5px; font-style: italic;">Foto scelte: (scorri in basso per rivederle)</span>
                        <div class="divider"></div>
                        <div class="form-group photocartlistcontainer row">
                            <div class="col-md-12">
                            <ul id="photocartlist" class="dropdown-cart" role="menu">
                                <li><p class='text-center' >Seleziona le foto, poi inserisci mail e nome allievo...</p></li>
                            </ul>
                            </div>
                        </div>
                        
                    </form>
                    </div>
                  </div>
                </li>
            </ul>
            </div>
          </div>
        </nav>
    </header>
    
    <div class="container">
      <!--
      <div id="nanogallery2" class="gallery_container" data-nanogallery2='{
        "itemsBaseURL": "<?php echo $config_ini_array['itemsBaseURL']; ?>",
        "thumbnailWidth": "120",
        "thumbnailHeight": "120",
        "thumbnailBorderVertical": 1,
        "thumbnailBorderHorizontal": 1,
        "galleryDisplayMode": "moreButton",
        "galleryDisplayMoreStep": 20,
        "thumbnailOpenImage": true,
        "thumbnailLabel": {
          "align": "left",
          "displayDescription": false,
          "titleMultiLine": true,
          "position":"overImageOnBottom"
        },
        "thumbnailSelectable" : "true",
        "locationHash": false,
        "allowHTMLinData": true,
        "thumbnailAlignment": "center",
        "thumbnailToolbarImage": {  "topRight" : "cart", "topLeft": "featured" },
        "icons": {      
            "thumbnailCart": "<i class=\"fa fa-shopping-bag\"></i>",
            "viewerCustomTool1": "<i class=\"fa fa-shopping-bag\"></i>",
            "thumbnailInfo": "<i class=\"fa fa-search-plus\"></i>"
        },
        "viewerToolbar": {
             "standard":  "label, custom1",
             "minimized": "" },
        "viewerTools": {"topLeft":"label","topRight":"closeButton" }
      }'>
      -->
       <div id="nanogallery2" class="gallery_container" data-nanogallery2='{
        "itemsBaseURL": "<?php echo $config_ini_array['itemsBaseURL']; ?>",
        "thumbnailWidth": "auto",
        "thumbnailHeight":  "300",
        "thumbnailBorderVertical": 1,
        "thumbnailBorderHorizontal": 1,
        "galleryDisplayMode": "moreButton",
        "galleryDisplayMoreStep": 20,
        "thumbnailOpenImage": true,
        "thumbnailLabel": {
          "align": "left",
          "displayDescription": false,
          "titleMultiLine": true,
          "position":"overImageOnBottom"
        },
        "thumbnailSelectable" : false,
        "touchAnimation": false,
        "locationHash": false,
        "allowHTMLinData": true,
        "thumbnailAlignment": "fillWidth",
        "thumbnailToolbarImage": {  "topRight" : "cart", "topLeft": "custom2" },
        "thumbnailToolbarAlbum" : { "topRight" : "counter" },
        "icons": {      
            "thumbnailCart": "<i class=\"fa fa-shopping-bag\"></i>",
            "viewerCustomTool1": "<i class=\"fa fa-shopping-bag\"></i>",
            "thumbnailCustomTool2": "<i class=\"fa fa-search-plus\"></i>"
        },
        "viewerToolbar": {
             "standard":  "label, custom1",
             "minimized": "" },
        "viewerTools": {"topLeft":"label","topRight":"closeButton" }
      }'>
      
      <?php include 'image_list.php';?>

      </div>

    <div class="bs-docs-section" >
    <div class="bs-callout bs-callout-warning" id="callout-inputgroup-container-body"> 
    <h4>Come ordinare le foto</h4> 
    <p>Per farlo da PC o MAc vedi il video <a href="#">[VIDEO DESKTOP]</a></p> 
    <p>Per farlo da Smatphone il video <a href="#">[VIDEO SMARTPHONE]</a></p> 
    </div>
    </div>
       
    </div>
    
    <script type="text/javascript" src="js/cart.js?v=1"></script>
    
    <div id="myModalOK" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confermato</h4>
                </div>
                <div class="modal-body">
                    <p>Richiesta di <span>X</span> foto completata</p>
                    <p class="text-info">Controlla nella posta per verifica se se è arrivata la ricevuta della richiesta</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    <a href="." class="btn btn-info" role="button">Rifai la selezione</a>
                </div>
            </div>
        </div>
    </div>
    
    <div id="myModalErrore" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Errore</h4>
                </div>
                <div class="modal-body">
                    <p>L'invio delle foto non lo riusciamo a complatare</p>
                    <p class="text-warning">Segnala l'anomalia a info@artedanzabologna.it</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    
  </body>
</html>