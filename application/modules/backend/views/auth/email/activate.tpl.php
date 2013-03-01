<html>
<body>
	<h1>Ativação da conta de <?php echo $identity;?></h1>
	<p>Por favor clique nesse link para <?php echo anchor('backend/auth/activate/'. $id .'/'. $activation, 'Ativar sua Conta');?>.</p>
</body>
</html>