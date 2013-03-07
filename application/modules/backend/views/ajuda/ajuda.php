<?php
/**
 * VIEW Ajuda
 * 
 * Exibe os principais tópicos, com o link para conteúdo de ajuda.  		
 * @author ANDRADE, Luis Felipe de <luis_andrade11@hotmail.com>
 * @subpackage view
 * @package backend
 * @copyright VEG Tecnologia
 * 
 */
?>
<div class="accordion" id="accordion">
 	<div class="accordion-group">
 		<div class="accordion-heading">
 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#alterando_conteudo">
 				Como altero o conteúdo de uma página?
 			</a>
 		</div>
 		<div id="alterando_conteudo" class="accordion-body collapse ">
 			<div class="accordion-inner">
 				<ol>
 					<li>Selecione o conteúdo que deseja alterar no "menu" </br> 
 						<?php echo  img("img/ajuda/passo_1_selecionar_item.png"); ?>
 						<p>
  							<small>Basta clicar no menu lateral</small>
						</p>
 					</li>
 					<li>O conteúdo vai carregar dentro de uma interface semelhante o "Word". Nessa interface, você pode editar o conteúdo do seu site com 
 						ferramentas gráficas (negrito, itálico, sublinhado), aplicar efeitos de estilo, fonte, tamanho,  links, tabelas, listas e adicionar imagem
						</br>
						<?php echo  img("img/ajuda/passo_2_alterar conteúdo.png"); ?>
 						<p>
  							<small>Depois de editado é só clicar em "Salvar"</small>
						</p>
 					</li>
 					<li>Pronto! Agora seu conteúdo foi alterado, você pode ir até o site verificar agora mesmo :)</li>
 				</ol>
 			</div>
 		</div>
 	</div>
	<div class="accordion-group">
 		<div class="accordion-heading">
 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#adicionado_imagem">
 				Como adiciona uma imagem ao conteúdo?
 			</a>
 		</div>
 		<div id="adicionado_imagem" class="accordion-body collapse">
 			<div class="accordion-inner">
 				Para adicionar imagem é muito simples, é possível fazer pela própria interface.
 			</div>
 			<ol>
 				<li>
 					Bem no topo, clique no botão "Imagem".
					</br>
					<?php echo img("img/ajuda/passo_1_adicionando_imagem.png"); ?>
					<p>
  						<small>O botão fica ao lado do item "Tabela"</small>
					</p>
 				</li>
 				<li>Após clicar, vai abrir uma janela com as opções da imagem. Se quiser usar uma imagem a web, só colar o link. Se quiser
 					adicionar uma imagem da sua máquina clique em "Localizar no Servidor"</li>
 				 	</br>
 				 	<?php echo img("img/ajuda/passo_2_adicionando_imagem.png");  ?>

 				<li>
 					Nessa janela tem todos os arquivos que você fez upload, você pode excluir, editar e demais ações.Para adicionar um arquivo clique em "Upload"
					</br>
					<?php echo img("img/ajuda/passo_4_adicionando_imagem.png"); ?>
					<p>
						<small>Só selecionar a imagem na pasta desejada</small>
					</p>
				</li>
 				<li>Depois clique em cima dele e dê "Enter" o endeço automaticamente vai pra interface.
					</br>
					<?php echo img("img/ajuda/passo_5_adicionando_imagem.png"); ?>
					<p>
						<small>Você pode configurar as opções e dar "OK". A imagem é posta no editor de texto assim que finalizar</small>
					</p>

 				</li>
 			</ol>
 		</div>
 	</div>
 </div>
 