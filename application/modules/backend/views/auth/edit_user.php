<!--
<h2>Edit User</h2>
<p>Please enter the users information below.</p>
<div id="infoMessage"><?php echo $message;?></div>
-->

<?php echo form_open(current_url());?>

      <p>
            Primeiro Nome: <br />
            <?php echo form_input($first_name);?>
      </p>

      <p>
            Último Nome: <br />
            <?php echo form_input($last_name);?>
      </p>

      <p>
            Telefone: <br />
            <?php 
            echo form_input($phone1);?> - <?php echo form_input($phone2);?> - <?php echo form_input($phone3);
            ?>
      </p>

      <p>Grupos</p>
      <?php
      foreach($groups as $group){
            $checked = ""; 
            foreach($groups_user as $group_user){
                  if($group->id == $group_user->niveis_permissao_cod_nivel_permissao)
                        $checked = "checked"; 
            }
            $checkbox = array("name" => "groups[]", "value" => $group->id, "checked" => $checked);
            echo form_radio($checkbox);
            echo "&nbsp;".ucwords($group->name);
            echo br();
      }
      ?>
      <br>
      
      <p>
            Senha: (caso desejar alterar a senha)<br />
            <?php echo form_input($password);?>
      </p>

      <p>
            Confirmação de Senha: (caso desejar alterar a senha)<br />
            <?php echo form_input($password_confirm);?>
      </p>


      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>

      <p>
            <?php 
            $form_submit = array('name' => 'submit', 'value' => 'Salvar', 'class' => 'btn btn-primary');
            echo form_submit($form_submit);
            echo nbs();
            $form_button = array('type' => 'button', 'name' => 'voltar', 'value' => 'Voltar', 'onClick' => 'javascript:history.back(-1);', 'class' => 'btn');
            echo form_input($form_button);
            ?>
      </p>

<?php echo form_close();?>