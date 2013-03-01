# --------------------------------------------------------
# Host:                         localhost
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2012-08-31 10:14:05
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table laercio.login_attempts
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Armazena a quantidade de tentativas de acesso';

# Dumping data for table laercio.login_attempts: ~0 rows (approximately)
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;


# Dumping structure for table laercio.niveis_permissao
CREATE TABLE IF NOT EXISTS `niveis_permissao` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código Identificador do Nível de Permissão',
  `name` varchar(20) NOT NULL COMMENT 'Nome do Perfil',
  `description` varchar(100) DEFAULT NULL COMMENT 'Descrição do Nível de Permissão',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dumping data for table laercio.niveis_permissao: ~0 rows (approximately)
/*!40000 ALTER TABLE `niveis_permissao` DISABLE KEYS */;
INSERT INTO `niveis_permissao` (`id`, `name`, `description`) VALUES
	(1, 'admin', 'Administrador do Sistema');
/*!40000 ALTER TABLE `niveis_permissao` ENABLE KEYS */;


# Dumping structure for table laercio.sys_metodos
CREATE TABLE IF NOT EXISTS `sys_metodos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(45) NOT NULL,
  `classe` varchar(45) NOT NULL,
  `metodo` varchar(45) NOT NULL,
  `apelido` varchar(140) NOT NULL,
  `privado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Armazena os metódos e as classes.\n';

# Dumping data for table laercio.sys_metodos: ~0 rows (approximately)
/*!40000 ALTER TABLE `sys_metodos` DISABLE KEYS */;
INSERT INTO `sys_metodos` (`id`, `modulo`, `classe`, `metodo`, `apelido`, `privado`) VALUES
	(1, 'backend', 'auth', 'novo_nivel_permissao', 'Novo Nível Permissão', 1),
	(2, 'backend', 'auth', 'editar_nivel_permissao', 'Editar Nível Permissão', 1),
	(3, 'backend', 'auth', 'excluir_nivel_permissao', 'Excluir Nível Permissão', 1);
/*!40000 ALTER TABLE `sys_metodos` ENABLE KEYS */;


# Dumping structure for table laercio.sys_permissoes
CREATE TABLE IF NOT EXISTS `sys_permissoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_metodo` int(11) NOT NULL,
  `id_nivel_permissao` int(11) NOT NULL,
  `valor_permissao` tinyint(4) DEFAULT '0' COMMENT '0 - BLOQUEADO; 1 - PERMITIDO',
  PRIMARY KEY (`id`),
  KEY `fk_sys_permissoes_sys_metodos` (`id_metodo`),
  KEY `fk_sys_permissoes_niveis_permissao1` (`id_nivel_permissao`),
  CONSTRAINT `fk_sys_permissoes_sys_metodos` FOREIGN KEY (`id_metodo`) REFERENCES `sys_metodos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sys_permissoes_niveis_permissao1` FOREIGN KEY (`id_nivel_permissao`) REFERENCES `niveis_permissao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Armazena as permissões\n';

# Dumping data for table laercio.sys_permissoes: ~0 rows (approximately)
/*!40000 ALTER TABLE `sys_permissoes` DISABLE KEYS */;
INSERT INTO `sys_permissoes` (`id`, `id_metodo`, `id_nivel_permissao`, `valor_permissao`) VALUES
	(1, 1, 1, 1),
	(2, 2, 1, 1),
	(3, 3, 1, 1);
/*!40000 ALTER TABLE `sys_permissoes` ENABLE KEYS */;


# Dumping structure for table laercio.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Tabela de Usuários Administrativos (schema do Ion Auth)\n';

# Dumping data for table laercio.usuarios: ~0 rows (approximately)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
	(1, _binary 0x33373436333033303330333033303331, 'admin', '825e14a9c5b1df763fe74daa9a4e8ebb00fd327c', '9462e8eee0', 'leonardo@vegtecnologia.com.br', '', NULL, NULL, NULL, 1268889823, 1346418225, 1, 'Admin', 'istrator', 'ADMIN', '123-2323-232');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;


# Dumping structure for table laercio.usuarios_has_niveis_permissao
CREATE TABLE IF NOT EXISTS `usuarios_has_niveis_permissao` (
  `usuarios_id` mediumint(8) unsigned NOT NULL,
  `niveis_permissao_cod_nivel_permissao` int(11) NOT NULL,
  PRIMARY KEY (`usuarios_id`,`niveis_permissao_cod_nivel_permissao`),
  KEY `fk_usuarios_has_niveis_permissao_niveis_permissao1` (`niveis_permissao_cod_nivel_permissao`),
  KEY `fk_usuarios_has_niveis_permissao_usuarios1` (`usuarios_id`),
  CONSTRAINT `fk_usuarios_has_niveis_permissao_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_niveis_permissao_niveis_permissao1` FOREIGN KEY (`niveis_permissao_cod_nivel_permissao`) REFERENCES `niveis_permissao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table laercio.usuarios_has_niveis_permissao: ~0 rows (approximately)
/*!40000 ALTER TABLE `usuarios_has_niveis_permissao` DISABLE KEYS */;
INSERT INTO `usuarios_has_niveis_permissao` (`usuarios_id`, `niveis_permissao_cod_nivel_permissao`) VALUES
	(1, 1);
/*!40000 ALTER TABLE `usuarios_has_niveis_permissao` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
