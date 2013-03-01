<?php echo form_open(base_url()."auth/login");?>
  	
  <p>
    <label for="identity">Login:</label>
    <?php echo form_input($identity);?>
  </p>

  <p>
    <label for="password">Senha:</label>
    <?php echo form_input($password);?>
  </p>

  <p>
    <label for="remember" style=""><?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?> Lembrar-me</label>
  </p>
    
  <p>
    <?php 
      $form_submit = array('name' => 'submit', 'value' => 'Entrar', 'class' => 'btn btn-primary'); 
      echo form_submit($form_submit);
    ?>
  </p>
    
<?php echo form_close();?>

<p><?php echo anchor(base_url().'backend/auth/forgot_password', 'Esqueceu sua senha?'); ?></p>