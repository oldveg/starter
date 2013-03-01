<?php

/**
 * VIEW Home
 * 
 * Home
 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
 * @subpackage view
 * @package frontend 
 */


echo form_open_multipart(current_url());
?>
 <div id="suporte" class="wrapper clearfix">
  <p><h4>Nome:</h4><?php echo form_input($nome); ?></p>

  <p><h4>E-mail:</h4><?php echo form_input($email); ?></p>

  <p>
	<h4>Tipo: <span class="obrigatorio"></span></h4>
    <input type="radio" name="tipo" value="1" checked="checked"> Dúvida<br>
    <input type="radio" name="tipo" value="2"> Crítica<br>
    <input type="radio" name="tipo" value="3"> Sugestão<br>
    <input type="radio" name="tipo" value="4"> Elogio
  </p>
  
  <p><h4>Arquivo:</h4><?php echo form_input($arquivo); ?></p>

  <p><h4>Assunto:</h4><?php echo form_input($assunto); ?></p>
  <p><h4>Mensagem: <span class="obrigatorio"></span></h4><?php echo form_textarea($mensagem); ?></p>
</div> <!-- #main -->
<br>
<?php
echo form_submit($submit);
echo form_close();
?>