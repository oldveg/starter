<p>Por favor, insira seu e-mail para que possamos reseta-la.</p>

<?php echo form_open(base_url()."auth/forgot_password");?>

      <p>
      	E-mail: <br />
      	<?php echo form_input($email);?>
      </p>
            
      <p>
      	<?php 
	      $form_submit = array('name' => 'submit', 'value' => 'Enviar', 'class' => 'btn btn-primary');
	      echo form_submit($form_submit).br();
	      echo "<div align='right'>".anchor(base_url().'auth/login', 'Voltar')."</div>";
	    ?>
	  </p>
      
<?php echo form_close();?>