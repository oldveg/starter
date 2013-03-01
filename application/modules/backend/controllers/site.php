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

class Site extends CI_Controller {

    private $_layout = 'backend/layout';
    private $_dados = array();

    // -------------------------------------------------------------------------------

    function __construct() {
        parent::__construct();

        $site_config = $this->config->load('system');
        $this->_dados['nome_site'] = $site_config['nome_site'];

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

    public function index() {
        $this->_dados['titulo'] = "Home";
        $this->_dados['pagina'] = 'home';
        $this->load->view($this->_layout, $this->_dados);
    }

    // ----------------------------------------------------------------------

    public function suporte(){
        // Chama a biblioteca de validação de formulário
        $this->load->library('form_validation');

        // Faz a validação dos campos do formulário
        $this->form_validation->set_rules("nome", "Nome", "required");
        $this->form_validation->set_rules("email", "E-mail", "required|valid_mail");
        $this->form_validation->set_rules("tipo", "Tipo", "required|trim");
        // $this->form_validation->set_rules("arquivo", "Arquivo", "");
        $this->form_validation->set_rules("assunto", "Assunto", "required");
        $this->form_validation->set_rules("mensagem", "Mensagem", "required");

        //Configura as opções do upload da imagem do instrumento
        $config['upload_path'] = 'uploads/arquivos';
        $config['allowed_types'] = '*';
        $config['file_name'] = sha1(md5(rand()));
        $this->load->library('upload', $config);

        //Verifica a validação do formulário
        if ($this->form_validation->run() == FALSE) {
            $this->_dados['titulo'] = "Suporte";
            $this->_dados['pagina'] = 'suporte';

            // Seta os erros encontrados na validação
            if(validation_errors()){
                $this->_dados['alert']['message'] = validation_errors();
                $this->_dados['alert']['return'] = 'error';
            }

            $this->_dados['nome'] = array(
                "name"=>"nome",
                "id"=>"nome",
                "class"=>"obrigatorio",
                "placeholder"=>"Nome",
                "value" => $this->form_validation->set_value("nome")
            );

            $this->_dados['email'] = array(
                "name"=>"email",
                "id"=>"email",
                "class"=>"obrigatorio",
                "placeholder"=>"E-mail",
                "value" => $this->form_validation->set_value("email")
            );

            $this->_dados['assunto'] = array(
                "name"=>"assunto",
                "id"=>"assunto",
                "class"=>"obrigatorio",
                "placeholder"=>"Assunto",
                "value" => $this->form_validation->set_value("assunto")
            );

            $this->_dados['arquivo'] = array(
                "type"  => "file",
                "name" => "arquivo",
                "id" => "arquivo"
            );

            $this->_dados["mensagem"] = array(
                "type" => "textarea",
                "name" => "mensagem",
                "id" => "conteudo_gerenciavel",
                "value" => $this->form_validation->set_value("mensagem")
            );

            $this->_dados['submit'] = array(
                "name"=>"salvar",
                "type"=>"submit",
                "value"=>"Enviar",
                "class"=>"pull-right btn btn-primary",
            );

            $this->load->view($this->_layout, $this->_dados);
        } 
        else{
            if(!$this->upload->do_upload("arquivo")) {
                //Se não encontrar o arquivo, será exibida uma mensagem de alerta
                $array = array('message' => $this->upload->display_errors(), 'return' => 'alert-error');
                $this->load->library('session');
                $this->session->set_flashdata('alert', $array);

                redirect(current_url(), 'refresh');
            } else {
                // Todos os campos preenchidos
                $file_info = $this->upload->data();
                $arquivo = $file_info['file_path'].$file_info['file_name'];
                $nome     = $this->input->post('nome');
                $email    = $this->input->post('email');
                switch($this->input->post('tipo')){
                    case 1: $tipo = "Dúvida"; break;
                    case 2: $tipo = "Crítica"; break;
                    case 3: $tipo = "Sugestão"; break;
                    case 4: $tipo = "Elogio";
                }
                $assunto  = $this->input->post('assunto');
                $mensagem = $this->input->post('mensagem');
                
                $titulo   = "SUPORTE: ".$tipo." - ".$assunto;
                $conteudo = '
                <html>
                    <head>
                        <meta charset="utf-8">
                        <title>Suporte</title>
                    </head>
                    <body>
                        <h1 align="center"><font face="Verdana, Geneva, sans-serif">Contato de Suporte</font></h1>
                        <table align="center">
                          <tr>
                            <td align="right"><font face="Verdana, Geneva, sans-serif"><strong>Nome:</strong></font></td>
                            <td><font face="Verdana, Geneva, sans-serif">'.$nome.'</font></td>
                          </tr>
                          <tr>
                            <td align="right"><font face="Verdana, Geneva, sans-serif"><strong>E-mail:</strong></font></td>
                            <td><font face="Verdana, Geneva, sans-serif">'.$email.'</font></td>
                          </tr>
                          <tr>
                            <td align="right"><font face="Verdana, Geneva, sans-serif"><strong>Tipo:</strong></font></td>
                            <td><font face="Verdana, Geneva, sans-serif">'.$tipo.'</font></td>
                          </tr>
                          <tr>
                            <td align="right"><font face="Verdana, Geneva, sans-serif"><strong>Assunto:</strong></font></td>
                            <td><font face="Verdana, Geneva, sans-serif">'.$assunto.'</font></td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" align="center"><font face="Verdana, Geneva, sans-serif"><strong>Mensagem:</strong></font></td>
                          </tr>
                          <tr>
                            <td colspan="2"><font face="Verdana, Geneva, sans-serif">'.$mensagem.'</font></td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2"><font face="Verdana, Geneva, sans-serif">Enviada as '.date("d/m/Y H:i").'</font></td>
                          </tr>
                        </table>
                    </body>
                </html>
                ';
                
                //Carrega a library para o envio de e-mail
                $this->load->library('email');
                $this->email->initialize();

                $this->email->from("suporte@vegtecnologia.com.br", "Contato de Suporte"); // Deve ser o mesmo descrito em config/email.php
                $this->email->to("leonardo@vegtecnologia.com.br", "Suporte VEG Tecnologia");
                $this->email->reply_to($email, $nome);
                
                $this->email->subject($titulo);
                $this->email->message($conteudo);

                $this->email->attach($arquivo);

                $enviar = $this->email->send();

                $this->email->clear(TRUE);

                if($enviar){
                    $array = array('message' => '<b>Sucesso!</b> Sua mensagem foi enviada com sucesso, aguarde nosso contato em breve!', 'return' => 'alert-success');            
                } else {
                    $array = array('message' => '<b>Erro!</b> Não foi possível enviar sua mensagem, estamos com um problema técnico no momento!', 'return' => 'alert-error');
                }

                $this->session->set_flashdata('alert', $array);
                redirect(base_url().'backend/site/suporte', 'refresh');
            }
        }
    }

    // ----------------------------------------------------------------------
    
    public function upload_imagem_ckeditor($nome_campo = "upload") {
        /* mkdir cria um diretorio */
        $caminho = 'img/galeria/';
        $config['upload_path'] = $caminho;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload($nome_campo)) {
            
        } else {
            $data = array('upload_data' => $this->upload->data());
            $info_foto = $this->upload->data();
            $this->load->library('image_lib');
            /* Inicializa */
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->redireciona_ckeditor();
        }
    }

    // ----------------------------------------------------------------------

    public function redireciona_ckeditor() {
        $this->load->view("redireciona_ckeditor");
    }

    // ----------------------------------------------------------------------

}


