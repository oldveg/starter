<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CONTROLLER Exemplo
 *
 * Controller de Exemplo para exibição do frontend
 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
 * @copyright VEG Tecnologia
 * @version 1.0
 * @package frotend
 */

class Exemplo extends CI_Controller {

    private $_layout = 'frontend/layout';
    private $_usuario;
    private $_dados;
    private $_chave = 'XSYkBHI8';

    // -------------------------------------------------------------------------------

    function __construct() {
        parent::__construct();

        
        $this->_dados['titulo'] = "Título - Site Exemplo";
        $this->_dados['keywords'] = "Palavras chave (SEO)";
        $this->_dados['description'] = "Descrição (SEO)";

    }

    // ----------------------------------------------------------------------

    function index() {
 
    

        $this->_dados['pagina'] = 'home';
        $this->load->view($this->_layout, $this->_dados);
    }


    // ----------------------------------------------------------------------

}


