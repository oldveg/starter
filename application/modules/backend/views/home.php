<?php

/**
 * VIEW Home
 * 
 * Home
 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
 * @subpackage view
 * @package frontend 
 */


echo form_open(current_url());
?>
 <div id="main" class="wrapper clearfix">
  <p>
    <textarea name="conteudo_gerenciavel" class="conteudo_gerenciavel" id="conteudo_gerenciavel"></textarea>  
  </p>
</div> <!-- #main -->
<br>
<input type="submit" class="pull-right btn btn-primary" value="Salvar">
<?php
echo form_close();
?>