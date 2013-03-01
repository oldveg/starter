<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CONTROLLER Admin_usuarios
 *
 * Controller do Gestor de Usuarios
 * @author TENFEN JUNIOR, Silvio <sitjunior@yahoo.com.br>
 * @version 1.0
 * @package admin
 */

class Admin_usuarios extends CI_Controller {

    private $_layout = 'backend/layout';
    private $_dados;
    private $_cod = 0;
    
    function __construct() {
        parent::__construct();
        $this->load->model('usuarios_model');
        
        $this->_dados['XSYkBHI8'] = $this->session->userdata('XSYkBHI8');
        if (!isset($this->_dados['XSYkBHI8']['cod'])) {
            redirect(base_url().'admin');
        }
    }
    
    function index($offset = 0) {
        $this->load->library('pagination');
        $this->load->library('table');
        
        $por_pagina = 10;
        $this->_dados['registros'] = $this->usuarios_model->get_all($por_pagina,$offset);
        $this->_dados['nregistros'] = $this->usuarios_model->get_count_all();
        
        $config['base_url'] = base_url().'admin/admin_usuarios/index/';
        $config['total_rows'] = $this->_dados['nregistros'];
        $config['per_page'] = $por_pagina;
        $config['first_link'] = '<<';
        $config['last_link'] = '>>';
        $config['num_links'] = 10;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $this->_dados['paginas'] = $this->pagination->create_links();
        
        $this->_dados['pagina'] = 'backend/usuarios';
        $this->load->view($this->_layout, $this->_dados);
    }

    function adicionar() {
        $this->form_validation->set_rules('nome', 'Nome', 'required|trim|xss_clean|max_length[50]|callback__verificar_duplicidade');
        $this->form_validation->set_rules('usuario', 'Usuário', 'required|trim|xss_clean|max_length[50]|callback__verificar_duplicidade_usuario');
        $this->form_validation->set_rules('senha', 'Senha', 'required|trim|xss_clean|min_length[6]|max_length[50]');
        $this->form_validation->set_rules('repetir_senha', 'Repetir Senha', 'required|trim|xss_clean|min_length[6]|max_length[50]|matches[senha]');
        $this->form_validation->set_rules('email', 'E-mail', 'required|trim|xss_clean|max_length[50]|valid_email|callback__verificar_duplicidade_email');

        $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
        
        $this->_dados['funcao'] = 'adicionar';
        $this->_dados['nome_form'] = 'Adicionar Usuário';
        
        $this->_dados['dados'] = array (
            'cod' => '',
            'nome' => '',
            'usuario' => '',
            'email' => ''
        );

        if ($this->form_validation->run() == FALSE) {
            $this->_dados['pagina'] = 'backend/usuarios_form';
            $this->load->view($this->_layout, $this->_dados);
        } else {
            
            $form_data = array(
                'nome' => set_value('nome'),
                'usuario' => set_value('usuario'),
                'senha' => md5(set_value('senha')),
                'email' => set_value('email')
            );

            if ($this->usuarios_model->inserir($form_data) == TRUE) {
                $this->_dados['info'] = 'O Usuário foi adicionado com sucesso!';
            } else {
                $this->_dados['erro'] = 'Houve um erro ao adicionar o Usuário!';
            }
            
            $this->index();
        }
    }

    function editar($cod) {
        $this->form_validation->set_rules('nome', 'Nome', 'required|trim|xss_clean|max_length[50]|callback__verificar_duplicidade');
        $this->form_validation->set_rules('usuario', 'Usuário', 'required|trim|xss_clean|max_length[50]|callback__verificar_duplicidade_usuario');
        $this->form_validation->set_rules('senha', 'Senha', 'required|trim|xss_clean|min_length[6]|max_length[50]');
        $this->form_validation->set_rules('repetir_senha', 'Repetir Senha', 'required|trim|xss_clean|min_length[6]|max_length[50]|matches[senha]');
        $this->form_validation->set_rules('email', 'E-mail', 'required|trim|xss_clean|max_length[50]|valid_email|callback__verificar_duplicidade_email');

        $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
        
        $this->_dados['funcao'] = 'editar';
        $this->_dados['nome_form'] = 'Editar Usuário';
        
        // Usado pelo checagem de duplicidade.
        $this->_cod = $cod;
        
        $consulta = $this->usuarios_model->get_info($cod);
        $this->_dados['dados'] = array (
            'cod' => $cod,
            'nome' => $consulta->nome,
            'usuario' => $consulta->usuario,
            'email' => $consulta->email
        );

        if ($this->form_validation->run() == FALSE) {
            $this->_dados['pagina'] = 'backend/usuarios_form';
            $this->load->view($this->_layout, $this->_dados);
        } else {

            $form_data = array(
                'nome' => set_value('nome'),
                'usuario' => set_value('usuario'),
                'senha' => md5(set_value('senha'))
            );

            if ($this->usuarios_model->atualizar($form_data, $cod) == TRUE) {
                $this->_dados['info'] = 'O Usuário foi atualizado com sucesso!';
            } else {
                $this->_dados['erro'] = 'Houve um erro ao atualizar o Usuário!';
            }
            
            $this->index();
        }
    }

    function _verificar_duplicidade($string) {
        $resultado = $this->usuarios_model->get_duplicidade($string, $this->_cod);
        if ($resultado <= 0) {
            return true;
        } else {
            $this->form_validation->set_message('_verificar_duplicidade', 'O Nome já existe no banco de dados.');
            return false;
        }
    }
    
    function _verificar_duplicidade_usuario($string) {
        $resultado = $this->usuarios_model->get_duplicidade_usuario($string, $this->_cod);
        if ($resultado <= 0) {
            return true;
        } else {
            $this->form_validation->set_message('_verificar_duplicidade_usuario', 'O Usuário já existe no banco de dados.');
            return false;
        }
    }

    function _verificar_duplicidade_email($string) {
        $resultado = $this->usuarios_model->get_duplicidade_email($string, $this->_cod);
        if ($resultado <= 0) {
            return true;
        } else {
            $this->form_validation->set_message('_verificar_duplicidade_email', 'O E-mail já existe no banco de dados.');
            return false;
        }
    }
    
    function msg_remover($registro) {
        $this->_dados['pergunta'] = 'Deseja realmente remover o Usuário?';
        $this->_dados['link_voltar'] = base_url().'admin/admin_usuarios';
        $this->_dados['link_remover'] = base_url().'admin/admin_usuarios/remover/'.$registro;
        
        $this->_dados['pagina'] = 'backend/msg_remover';
        $this->load->view($this->_layout, $this->_dados);
    }
    
    function remover($registro) {
        
        if ($registro != $this->_dados['XSYkBHI8']['cod']) {
        
            $resultado = $this->usuarios_model->remover($registro);

            if ($resultado) {
                $this->_dados['info'] = 'O Usuário foi removido com sucesso!';
            } else {
                $this->_dados['erro'] = 'Houve um erro ao remover o Usuário!';
            }
        
        } else {
            $this->_dados['erro'] = 'Não é permitido remover o Usuário atualmente logado!';
        }

        $this->index();
    }

}

?>