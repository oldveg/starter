
 <div class="container-fluid">
 	<h2>Usuários</h2>

<table cellpadding="0" cellspacing="10" class="table table-bordered">
	<tr>
		<th>Primeiro Nome</th>
		<th>Último Nome</th>
		<th>Login</th>
		<th>E-mail</th>
		<th>Níveis de Permissão</th>
		<th colspan="2"><div align="center">Opções</div></th>
		<th>Status</th>
	</tr>
	<?php foreach ($users as $user):?>
		<tr>
			<td><?php echo $user->first_name;?></td>
			<td><?php echo $user->last_name;?></td>
			<td><?php echo $user->username;?></td>
			<td><?php echo $user->email;?></td>
			<td>
				<?php foreach ($user->groups as $group):?>
					<?php echo $group->name;?><br />
                <?php endforeach?>
			</td>
			<td>
				<div align="right"><a href="<?php echo base_url(); ?>backend/auth/edit_user/<?php echo $user->id; ?>" title="Editar Usuário"><i class="icon-pencil"></i></a></div>
			</td>
			<td>
				<div align="left"><a href="<?php echo base_url(); ?>backend/auth/delete_user/<?php echo $user->id; ?>" title="Excluir Usuário"><i class="icon-trash"></i></a></div>
			</td>
			<td><?php echo ($user->active) ? anchor("backend/auth/deactivate/".$user->id, 'Ativo') : anchor("backend/auth/activate/". $user->id, 'Inativo');?></td>
		</tr>
	<?php endforeach;?>
</table>

<p><a class="btn" href="<?php echo site_url('auth/create_user');?>"><i class="icon-plus"></i>&nbsp;Novo Usuário</a></p>
<?php
/**
 * Abre o formulário para a busca e edição via autocomplete
 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
 */
echo form_open("backend/auth/edit_user"); ?>
<p> <div class="input-append input-prepend"><span class="add-on"><i class="icon-search"></i></span><input type="text" id="autocomplete_usuario" placeholder="Buscar usuário"><button class="btn" type="submit">Go!</button></p></div>
<?php
echo '<input type="hidden" name="usuario_id" id="usuario_id">';
echo form_close();

?>

<hr>
	<h2>Níveis de Permissão</h2>
	<table cellpadding="0" cellspacing="10" class="table table-bordered">
		<tr>
			<th>Nível de Permissão</th>
			<th>Quantidade de Usuários</th>
			<th colspan="2"><div align="center">Opções</div></th>
		</tr>
		<?php
			if($dados_tabela_niveis_permissao) {
			
				foreach($dados_tabela_niveis_permissao as $tabela_niveis_permissao) {

					echo "<tr>";
				?>
					<td><?php echo $tabela_niveis_permissao[0] ;?></td>
					<td><?php echo $tabela_niveis_permissao[1] ;?></td>
					<td>
						<div align="right"><a href="<?php echo base_url(); ?>backend/auth/editar_nivel_permissao/<?php echo $tabela_niveis_permissao[2]; ?>" title="Editar Nível Permissão"><i class="icon-pencil"></i></a></div>
					</td>
					<td>
						<div align="left"><a href="<?php echo base_url(); ?>backend/auth/excluir_nivel_permissao/<?php echo $tabela_niveis_permissao[2]; ?>" title="Excluir Nível Permissão"><i class="icon-trash"></i></a></div>
					</td>
				<?php
					echo "</tr>";
			
				}
				echo "</table>";
			    echo "<p>$paginacao_links</p>";
			    echo "<br class='clear'>";
		
			}
			else {
				echo "</table>";
		    	echo "<p>Não há Nivéis de Permissão Cadastrados.</p>";
			}
		?>

<p><a class="btn" href="<?php echo site_url('auth/novo_nivel_permissao');?>"><i class="icon-plus"></i>&nbsp;Novo Nível Permissão</a></p>
<?php
/**
 * Abre o formulário para a busca e edição via autocomplete
 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
 */
echo form_open("backend/auth/editar_nivel_permissao"); ?>
<p> <div class="input-append input-prepend "><span class="add-on"><i class="icon-search"></i></span><input type="text" id="autocomplete_nivel_permissao" placeholder="Buscar Nível de Permissão"><button class="btn" type="submit">Go!</button></p></div>
<?php
echo '<input type="hidden" name="nivel_permissao_id" id="nivel_permissao_id">';
echo form_close();
?>
</div>