<!--<h1>Deactivate User</h1>-->
<p>Tem certeza que gostaria de desativar o usuário de '<?php echo $user->first_name.' '.$user->last_name;?>'</p>
	
<?php echo form_open("backend/auth/deactivate/".$user->id);?>

  <p>
  	<label for="confirm">Sim:</label>
    <input type="radio" name="confirm" value="yes" checked="checked" />
  	<label for="confirm">Não:</label>
    <input type="radio" name="confirm" value="no" />
  </p>

  <?php echo form_hidden($csrf); ?>
  <?php echo form_hidden(array('id'=>$user->id)); ?>

  <p><?php echo form_submit('submit', 'Enviar', 'class="btn btn-primary"');?></p>

<?php echo form_close();?>