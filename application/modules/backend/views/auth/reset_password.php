<!--<h1>Change Password</h1>

<div id="infoMessage"><?php echo $message;?></div>-->

<?php echo form_open('backend/auth/reset_password/' . $code);?>
      
	<p>
		Nova Senha:<br><font size='1'>(com ao menos <?php echo $min_password_length;?> caracteres)</font><br />
		<?php echo form_input($new_password);?>
	</p>

	<p>
		Confirmação de Nova Senha: <br />
		<?php echo form_input($new_password_confirm);?>
	</p>

	<?php echo form_input($user_id);?>
	<?php echo form_hidden($csrf); ?>

	<p><?php echo form_submit('submit', 'Alterar', 'class="btn btn-primary"');?></p>
      
<?php echo form_close();?>