<?php

/**
 * VIEW Editar Nivel de Permissao
 * 
 * Contem os campos do formulario para a edição do nivel de permissao
 * @author ANDRADE, Luis Felipe de <luis_andrade11@hotmail.com>
 * @subpackage view
 * @package modulo 
 * @copyright VEG Tecnologia
 */

?>


 <div class="container-fluid">
 	<h2>Editar Nível de Permissão</h2>
    <?php
    echo form_open();
    
    echo form_fieldset('Dados do Nível de Permissão');
    
    //Nome
    echo form_label('Nome', 'nome');
    echo form_input($nome);
    echo '<span class="help-inline">*</span>';
    echo "<p></p>";

    //Descrição
    echo form_label("Descrição", 'descricao');
    echo form_textarea($descricao);
    echo "<p></p>";
	  echo form_fieldset_close(); 
   	

   	echo form_fieldset("Permissões");
 	
	$contador = 0;

	foreach($array_metodos_apelidos as $key => $row) {

       $contador++;
?>  

        <label class="checkbox inline" for="<?php echo $key ?>">
        <input type="checkbox" name="<?php echo $key ?>" id="<?php echo $key ?>"  value="1" 
           <?php if($dados[$key] == "1" ) {  
                echo set_checkbox($key, '1', TRUE); 
           } else {
               echo set_checkbox($key, '1'); 
           }
           ?>  />
        <?php echo $row ?>
      </label>
<?php    
   if($contador % 3 == 0) {
       echo "<br clas='clear'>";
   }

}
   	echo form_fieldset_close();
    echo "</br></br>";
  ?>

    <div class="form-actions">
    	<button type="submit" class="btn btn-primary">Atualizar</button>
    	<button type="button" class="btn" onClick="javascript:history.back(-1);">Voltar</button>
    </div>

<?php 

    echo form_close();
    ?>
</div>