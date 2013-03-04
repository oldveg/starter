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
    <title>Backend :: <?php echo $titulo ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php
    /*
      CSS A SER MINIMIFICADO
      para adicionar outro arquivo, só jogar dentor da array
    */
    $this->minify->css_file = 'min.css';
    $this->minify->assets_dir = 'assets';
    $this->minify->css(array('backend.css','jquery-ui-1.10.0.custom.css', 'jquery.ui.1.10.0.ie.css','bootstrap.min.css','bootstrap-responsive.min.css'));
    
    echo $this->minify->deploy_css(true);
    ?>
    <!-- MODERNIR TEM QUE SER NO HEAD -->
    <script src="<?php echo base_url();?>js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

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

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><?php echo $nome_site; ?></a>
          <div class="btn-group pull-right">
              <a href="<?php echo base_url(); ?>backend/site/suporte" title="Enviar mensagem para o Suporte" class="btn"><i class="icon-bullhorn"></i></a>
              <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> <?php echo $this->session->userdata('username'); ?> <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                  <?php
                  $this->load->library('ion_auth');
                  if($this->ion_auth->is_admin()){
                  ?>
                    <li><a href="<?php echo base_url(); ?>backend/auth/edit_user/<?php echo $this->session->userdata('user_id'); ?>">Editar Usuário</a></li>
                    <li class="divider"></li>
                  <?php
                  }
                  ?>
                  <li><a href="<?php echo base_url(); ?>auth/logout">Sair</a></li>
              </ul>
          </div>
          <?php 
            //Chama o menu superior
            $this->load->view("partes/menu_superior");
          ?>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <!-- Classe responsável pela LOGO do sistema -->
          <div class="logo">
            <img src="<?php echo base_url(); ?>img/logo.gif">
          </div>
          <?php $this->load->view('menu.php'); ?>
        </div><!--/span-->
        <div class="span10">
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
          <div class="conteudo">
            <span class="titulo"><?php echo $titulo; ?></span>
            <br><br>
            <div class="pagina"><?php $this->load->view($pagina); ?></div>
          </div>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p align="center">&copy; VEG Tecnologia <?php echo date('Y'); ?></p>
      </footer>

    </div><!--/.fluid-container-->


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript"> CI_ROOT = '<?php echo base_url(); ?>'; </script>
    <script>window.jQuery || document.write('<script src="'+CI_ROOT+'js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
    <script src="<?php echo base_url(); ?>js/vendor/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/jquery-ui-1.10.0.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/jquery.autotab-1.1b.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/placeholder.min.js"></script>
    <script src="<?php echo base_url(); ?>js/funcoes.js"></script>
    <script src="<?php echo base_url(); ?>js/main.js"></script>

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