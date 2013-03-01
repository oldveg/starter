<?php
/**
* VIEW Layout
*
* View do Layout Padrao do Frontend Site
* @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
* @copyright VEG Tecnologia
* @version 1.0
* @package principal
*/

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="<?php echo $description ;?>">
        <meta name="keywords" content="<?php echo $keywords ;?>">
        <meta name="viewport" content="width=device-width">

    <title><?php echo $titulo ?></title>

    <link rel="shortcut icon" href="<?php echo base_url();?>img/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo base_url();?>apple-touch-icon.png">


    <?php
    /*
      CSS A SER MINIMIFICADO
      para adicionar outro arquivo, só jogar dentor da array
    */
    $this->minify->css_file = 'min.css';
    $this->minify->assets_dir = 'assets';
    $this->minify->css(array('frontend.css','bootstrap.min.css','bootstrap-responsive.min.css'));
    
    echo $this->minify->deploy_css(false);
    ?>
    
    <!-- MODERNIR TEM QUE SER NO HEAD -->
    <script src="<?php echo base_url();?>js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>        
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->
    
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="#">Project name</a>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li class="nav-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="navbar-form pull-right">
                        <input class="span2" type="text" placeholder="Email">
                        <input class="span2" type="password" placeholder="Password">
                        <button type="submit" class="btn">Sign in</button>
                    </form>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>

    <div class="container">

        <?php echo $this->load->view( $pagina );?>
           

        <hr>

        <footer>
            <p>&copy; Company 2012</p>
        </footer>

    </div> <!-- /container -->

    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script type="text/javascript">
     CI_ROOT = '<?php echo base_url() ?>';
    </script>
    <script>window.jQuery || document.write('<script src="'+CI_ROOT+'js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
    <script type="text/javascript" src="<?php echo base_url()?>js/vendor/bootstrap.min.js"></script>
    
    <?php
    /*
      JS A SER MINIMIFICADO
      para adicionar outro arquivo, só jogar dentor da array
    */
    $this->minify->js_file = 'min.js';
    $this->minify->assets_dir = 'assets';
    $this->minify->js(array('funcoes.js'));
    
    echo $this->minify->deploy_js(false);
    ?>

    <script>
     /* GOOGLE ANALYTICS EXEMPLO 
        var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
     */
    </script>

</body>
</html>
