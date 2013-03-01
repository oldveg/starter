<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CONTROLLER Home
 *
 * Controller para gerenciamento/exibição do Backend
 * @author LAZARINI, Leonardo Filipe <leo.lazarini@gmail.com>
 * @copyright VEG Tecnologia
 * @version 1.0
 * @package backend
 */

class Home extends CI_Controller {

    private $_layout = 'backend/layout';
    public $_usuario;
    private $_dados;
    private $_chave = 'XSYkBHI8';

    // -------------------------------------------------------------------------------

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');

        //SESSION KFM CAMINHO
        $_SESSION["base_url"] = base_url();

        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        else {
            $this->_usuario = $this->ion_auth->user()->row();
      
        }
    }

    // ----------------------------------------------------------------------

    function index() {

        $this->_dados['titulo'] = "Home";
        $this->_dados['pagina'] = 'home';
        $this->load->view($this->_layout, $this->_dados);
    }


    // ----------------------------------------------------------------------
    
    /************************************************************************
    * Função -> redireciona o ckeditor
    * Função -> upload de imagem
    * @author PERDONÁ, Francisco Pimentel <francisco.perdona@gmail.com.br>
    * @package function
    * @copyright 19/07/2012
    **/
        public function redireciona_ckeditor() {
            $this->load->view("redireciona_ckeditor");
        }
    /************************************************************************/

}


