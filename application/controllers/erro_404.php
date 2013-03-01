<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CONTROLLER Erro_404
 *
 * Controller do Erro 404
 * @author TENFEN JUNIOR, Silvio <sitjunior@yahoo.com.br>
 * @version 1.0
 * @package principal
 */

class Erro_404 extends CI_Controller {

    private $_layout = 'frontend/layout';
    private $_dados = array();
    
    function __construct() {
        parent::__construct();
              $this->_dados['keywords'] = "Palavras chave (SEO)";
        $this->_dados['description'] = "Descrição (SEO)";
        
        $this->_dados['XSYkBHI8'] = $this->session->userdata('XSYkBHI8');
    }
    
    public function index() {
        
        // Mensagens
        $this->_dados['titulo'] = 'Erro 404';
        $this->_dados['mensagem'] = 'A página solicitada não foi encontrada!';
        $this->_dados['mensagem2'] = 'Se disponível, utilize o menu acima para buscar o que procura.';
        
        $this->_dados['pagina'] = '404.php';
        $this->load->view($this->_layout,$this->_dados);
    }

}
