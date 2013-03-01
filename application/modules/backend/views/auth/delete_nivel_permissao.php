<!--<h1>Deactivate User</h1>-->
 <div class="container-fluid">
  <h2><?php echo $titulo; ?></h2>

<p>Tem certeza que gostaria de excluir o nível de permissão '<?php echo $nivel_permissao->name; ?>'</p>
	
<?php echo form_open("backend/auth/excluir_nivel_permissao/".$nivel_permissao->id);?>
  

    <label class="checkbox inline">
     <input type="radio" name="confirm" value="yes"  /> Sim
   </label>
    <label class="checkbox inline">
     <input type="radio" name="confirm" value="no" /> Não
   </label>
   <p></p>



  <?php echo form_hidden($csrf); ?>
  <?php echo form_hidden(array('id'=>$nivel_permissao->id)); ?>

 <div class="form-actions">
      <button type="submit" class="btn btn-primary">Excluir</button>
      <button type="button" class="btn" onClick="javascript:history.back(-1);">Voltar</button>
    </div>

<?php echo form_close();?>

</div>