<!--<h1>Create User</h1>
<p>Please enter the users information below.</p>

<div id="infoMessage"><?php echo $message;?></div>-->

<?php echo form_open("auth/create_user");?>
 <div class="container-fluid">
      <h2>Novo Usuário</h2>
      <p>
            Primeiro Nome: <br />
            <?php echo form_input($first_name);?>
      </p>

      <p>
            Último Nome: <br />
            <?php echo form_input($last_name);?>
      </p>

    
      <p>
            E-mail: <br />
            <?php echo form_input($email);?>
      </p>

      <p>
            Telefone: <br />
            <?php echo form_input($phone1);?> - <?php echo form_input($phone2);?> - <?php echo form_input($phone3);?>
      </p>

      <p>Grupos</p>
      <?php 
      foreach($groups as $group){
            $checkbox = array("name" => "groups[]", "value" => $group->id);;
            echo form_checkbox($checkbox);
            echo "&nbsp;".ucwords($group->name);
            echo br();
      }
      ?>
      <br>

      <p>
            Login: <br />
            <?php echo form_input($username);?>
      </p>

      <p>
            Senha: <br />
            <?php echo form_input($password);?>
      </p>

      <p>
            Confirmação de Senha: <br />
            <?php echo form_input($password_confirm);?>
      </p>

      <p>            
         <div class="form-actions">
            <button type="submit" class="btn btn-primary">Incluir</button>
            <button type="button" class="btn" onClick="javascript:history.back(-1);">Voltar</button>
         </div>
      </p>

<?php echo form_close();?>
</div>