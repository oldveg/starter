<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * LIBRARY Enviar_emails
 *
 * Biblioteca para envio padronizado de e-mails
 * @author TENFEN JUNIOR, Silvio <sitjunior@yahoo.com.br>
 * @version 1.0
 * @package principal
 */

class Enviar_emails {
    
    function enviar($responder_para, $destinatario, $assunto, $mensagem) {
        
        // Iniciando envio de e-mail
        // *******************************************************************************************************
        // Os codigos abaixo seguem as recomendacoes da Locaweb para "Boas Praticas de HTML para E-mail" e tambem
        // "Boas Praticas para Envio de E-mail por Seu Site" que encontram-se respectivamente em:
        //
        // > http://wiki.locaweb.com.br/pt-br/Boas_pr%C3%A1ticas_de_HTML_para_Email
        // > http://wiki.locaweb.com.br/pt-br/Boas_pr%C3%A1ticas_de_envio_de_e-mail_por_seu_site
        // *******************************************************************************************************

        // Obter instancia do CodeIgniter
        $CI =& get_instance();
        
        $nome_site = $CI->config->item('nome_site');
        $email_contato = $CI->config->item('email_contato');
        $logo_email = $CI->config->item('logo_email');

        $titulo = 'Registre Agor@ - '.$assunto;
        $remetente = $email_contato;
        $remetente_nome = "Contato";
        $conteudo = '
        <html>
        <head>
        </head>
        <body>
        <p align="center"><img src="' . $logo_email . '" alt="Logo" /></p>
        '.$mensagem.'
        </body>
        </html>
        ';

        $CI->load->library('email');

        $CI->email->from($remetente, $remetente_nome);
        $CI->email->to($destinatario);
        $CI->email->reply_to($responder_para);

        $CI->email->subject($titulo);
        $CI->email->message($conteudo);

        $enviar = $CI->email->send();
        
        return $enviar;
    }
    
}
?>