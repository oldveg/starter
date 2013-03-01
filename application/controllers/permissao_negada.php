<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CONTROLLER Permissao_negada
 *
 * Controller da Pagina de Permissao Negada
 * @author TENFEN JUNIOR, Silvio <sitjunior@yahoo.com.br>
 * @version 1.0
 * @package principal
 */

class Permissao_negada extends CI_Controller {

    private $_layout = 'layout';
    private $_dados = array();
    
    function __construct() {
        parent::__construct();
        
        $this->_dados['XSYkBHI8'] = $this->session->userdata('XSYkBHI8');
    }
    
    public function index() {
        
        // Mensagens
        $this->_dados['titulo'] = 'Permissão Negada';
        $this->_dados['mensagem'] = 'Você não possui permissão para acessar essa página!';
        $this->_dados['mensagem2'] = '<a href="javascript:history.back(-1)">Voltar</a>';
        
        $this->_dados['pagina'] = 'erro';
        $this->load->view($this->_layout,$this->_dados);
    }

}
