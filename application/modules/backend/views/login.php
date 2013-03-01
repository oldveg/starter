<?php

/**
* VIEW Layout
*
* View do Layout Padrao do backend Site (Bootsrap fluído)
* @author LAZARINI, Leonardo Filipe<leo.lazarini@gmail.com>
* @copyright VEG Tecnologia
* @version 1.0
* @package principal
*/

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Backend :: <?php echo $titulo; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      body { padding-top: 60px; padding-bottom: 40px; }
      .sidebar-nav { padding: 9px 0; }
    </style>
    <link href="<?php echo base_url(); ?>css/bootstrap-responsive.min.css" rel="stylesheet">
    <?php
    /*
      CSS A SER MINIMIFICADO
      para adicionar outro arquivo, só jogar dentor da array
    */
    $this->minify->css_file = 'min.css';
    $this->minify->assets_dir = 'assets';
    $this->minify->css(array('backend.css'));
    echo $this->minify->deploy_css(true);
    ?>
    <!-- MODERNIR TEM QUE SER NO HEAD -->
    <script src="<?php echo base_url();?>js/modernizr_min.js"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>
  <body>
    <div class="container-fluid">
      <div class="alert-login">
      <?php
      //Verifica se existe a sessão que contém as informações do Alerta
        if($this->session->flashdata('alert')) 
          $alert = $this->session->flashdata('alert');

        //Verifica a existencia da variável de alerta, seja ela advinda de uma sessão ou passada por parâmetros no carregamento da view
        if(isset($alert) && !empty($alert)){
        ?>
          <!-- Alerta exibido no topo da tela, contendo o alerta e seu respectivo retorno -->
          <div class='alert <?php echo $alert['return']; ?>'>
            <button type='button' class='close' data-dismiss='alert'>×</button>
            <?php echo $alert['message']; ?>
          </div>
        <?php
        }?>
      </div>
      <!-- Conteúdo da página -->
      <div class="form-login">
        <div class="logo">
          <img src="<?php echo base_url(); ?>img/logo.gif">
        </div>
        <span class="titulo"><?php echo $titulo; ?></span>
        <br><br>
        <div class="conteudo"><?php $this->load->view($pagina); ?></div>
      </div>
      <hr>
      <footer>
        <p align="center">&copy; VEG Tecnologia 2012</p>
      </footer>
    </div><!--/.fluid-container-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script type="text/javascript"> CI_ROOT = '<?php echo base_url() ?>;';</script>
    <script>window.jQuery || document.write('<script src="'+CI_ROOT+'js/jquery_min.js"><\/script>')</script>
    <?php
    /*
      JS A SER MINIMIFICADO
      para adicionar outro arquivo, só jogar dentor da array
    
    $this->minify->js_file = 'min.js';
    $this->minify->assets_dir = 'assets';
    $this->minify->js(array('funcoes.js'));
    echo $this->minify->deploy_js(false);*/
    ?>
    <script src="<?php echo base_url(); ?>js/funcoes.js"></script>
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