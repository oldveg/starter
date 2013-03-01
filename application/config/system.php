<?php
// Arquivo de configuracoes do Contato

// Pega dados de localizacao da framework
$CI =& get_instance();
$CI->load->helper('url');

// Nome do Site
$config['nome_site'] = '';
$config['logo_site'] = base_url().'images/logo.png';
$config['nome_empresa'] = '';


// Empresa desenvolvedora
$config['nome_empresa_desenv'] = 'VEG Tecnologia';
$config['site_empresa_desenv'] = 'http://www.vegtecnologia.com.br/';


// Configuracoes do encaminhamento de e-mail
$config['email_contato'] = 'suporte@vegtecnologia.com.br';
$config['logo_email'] = base_url().'images/logo_email.png';
?>
