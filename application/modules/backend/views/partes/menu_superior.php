<?php

/**
 * VIEW Menu Superior
 * 
 * [Description]
 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
 * @subpackage view
 * @package modulo 
 */




?>

<div class="nav-collapse collapse">
            <!--
            <p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link"><?php echo $this->session->userdata('username'); ?><b class="caret"></b></a>
            </p>
            -->
    <ul class="nav">
	      <li <?php if($pagina == 'site') echo "class='active'"; ?>><a href="<?php echo base_url(); ?>backend/site"><i class="icon-home icon-white"></i>Home</a></li>
	      <?php
	      	if($this->ion_auth->is_admin()) {
	      	?>
	        <li <?php if($pagina == 'auth/index') echo "class='active'"; ?>><a href="<?php echo base_url(); ?>backend/auth/index"><i class="icon-user icon-white"></i>Usuários</a></li>
	      <?php
	      	}
	      ?>

    </ul>
</div><!--/.nav-collapse -->