<html>
<body>
	<h1>Resetar a senha de <?php echo $identity;?>,</h1>
	<p>Por favor, visite esse link para <?php echo anchor('backend/auth/reset_password/'. $forgotten_password_code, 'Resetar sua Senha');?>.</p>
</body>
</html>