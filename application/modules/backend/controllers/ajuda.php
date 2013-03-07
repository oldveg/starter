<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CONTROLLER Ajuda
 * 
 * Exibe um conteúdo específico para auxiliar o usuário no uso do site/sistema	
 * @author ANDRADE, Luis Felipe de <luis_andrade11@hotmail.com>
 * @subpackage controller
 * @package backend
 * @copyright VEG Tecnologia
 * 
 */

class Ajuda extends CI_Controller {

	private $_layout = 'backend/layout';
    public $_usuario;
    private $_dados;

    // -------------------------------------------------------------------------------

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        else { 
            $this->_usuario = $this->ion_auth->user()->row();
        }
    }


	// ----------------------------------------------------------------------

    /**
     * Exibe o conteudo da ajuda, com os principais tópicos e o link deles 
     * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
     */
	public function index()
	{

		$this->_dados['titulo'] = "Ajuda - Auto Gerenciável";
        $this->_dados['pagina'] = 'ajuda/ajuda';
        $this->load->view($this->_layout, $this->_dados);
		
	}


	// ----------------------------------------------------------------------

}

/* End of file ajuda.php */
/* Location: ./application/modules/backend/controllers/ajuda.php */