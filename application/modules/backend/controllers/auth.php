<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	private $_layout = 'backend/layout';
	private $_layout_login = 'backend/login';

	function __construct()
	{
		parent::__construct();
		$this->load->config('ion_auth', TRUE);
		$this->tables  = $this->config->item('tables', 'ion_auth');
		$this->join    = $this->config->item('join', 'ion_auth');

		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required
		$this->config->item('use_mongodb', 'ion_auth') ?
		$this->load->library('mongo_db') :

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	
		$site_config = $this->config->load('system');
        $this->data['nome_site'] = $site_config['nome_site'];
	}

	//redirect if needed, otherwise display the user list
	function index($offset_usuarios = 0, $offset_niveis_permissao = 0)
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			$this->data['titulo'] = "Usuários";
			$this->data['pagina'] = 'auth/index';

			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			//List the groups
			$this->load->model("Cadastros_model");
			$niveis_permissoes = $this->Cadastros_model->busca_todos($this->tables['groups'], 10, $offset_niveis_permissao,"");
			$dados_tabela_niveis_permissao = array();
			foreach($niveis_permissoes as $nivel_permissao) {

				$quantidade_usuarios = $this->Cadastros_model->count_rows_condition($this->tables['users_groups'], $this->join['groups']." = '".$nivel_permissao->id."'");

				$dados_tabela_niveis_permissao[] = array (
					 $nivel_permissao->name,
					 $quantidade_usuarios,
					 $nivel_permissao->id
				 );		
			}

			$this->data['niveis_permissao'] = $this->Cadastros_model->busca_todos($this->tables['groups']);

			$this->data['dados_tabela_niveis_permissao'] = $dados_tabela_niveis_permissao;
            $config['base_url'] = base_url() . 'backend/auth/index/0/';
            $config['total_rows'] = $this->Cadastros_model->count_rows($this->tables['groups']);
            $config['per_page'] = 10;
            $config['uri_segment'] = 5;
            $config['num_links'] = 10;
            $this->pagination->initialize($config);
            $this->data['paginacao_links'] = $this->pagination->create_links();

			$this->load->view($this->_layout, $this->data);
		}
	}

	//log the user in
	function login()
	{
		$this->data['titulo'] = "Login de Usuário";
		$this->data['pagina'] = 'auth/login';

		//validate form input
		$this->form_validation->set_rules('identity', 'Login', 'required');
		$this->form_validation->set_rules('password', 'Senha', 'required');

		if ($this->form_validation->run() == true)
		{ 
			//check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{ 
				//if the login is successful
				//redirect them back to the home page
				$alert = array('message' => $this->ion_auth->messages(), 'return' => 'alert-success');
				$this->session->set_flashdata('alert', $alert);
				redirect('backend/site', 'refresh');
			}
			else
			{ 
				//if the login was un-successful
				//redirect them back to the login page
				$alert = array('message' => $this->ion_auth->errors(), 'return' => 'alert-error');
				$this->session->set_flashdata('alert', $alert);
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{  
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$alert = array('message' => $this->ion_auth->errors(), 'return' => 'alert-error');
			$this->data['alert'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('alert');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);
			
 			$this->load->view($this->_layout_login, $this->data);
		}
	}

	//log the user out
	function logout() 
	{
		 $this->data['title'] = "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them to the login page
		//$alert = array('message' => $this->ion_auth->messages(), 'return' => 'alert-success');
		//$this->session->set_flashdata('alert', $alert);
		redirect('auth/login', 'refresh');
	}

	//change password
	function change_password()
	{
		$this->form_validation->set_rules('old', 'Senha antiga', 'required');
		$this->form_validation->set_rules('new', 'Nova senha', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', 'Confirmação de Nova Senha', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{ 
			//display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			//render
			$this->load->view('auth/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{ 
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	//forgot password
	function forgot_password()
	{		
		$this->data['titulo'] = "Esqueceu sua senha";
		$this->data['pagina'] = 'auth/forgot_password';

		$this->form_validation->set_rules('email', 'E-mail', 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',	'id' => 'email');

			//set any errors and display the form
			if(validation_errors()){
				$this->data['alert']['message'] = validation_errors();
				$this->data['alert']['return'] = 'alert-error';
			}

			//$this->load->view('auth/forgot_password', $this->data);
			$this->load->view($this->_layout_login, $this->data);
		}
		else
		{
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

			if ($forgotten)
			{ 
				//if there were no errors
				$alert = array('message' => $this->ion_auth->messages(), 'return' => 'alert-success');
				$this->session->set_flashdata('alert', $alert);
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$alert = array('message' => $this->ion_auth->errors(), 'return' => 'alert-error');
				$this->session->set_flashdata('alert', $alert);
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{  
			$this->data['titulo'] = 'Resetar sua Senha';
			$this->data['pagina'] = 'auth/reset_password';

			//if the code is valid then display the password reset form
			$this->form_validation->set_rules('new', 'Nova Senha', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', 'Confirmação de Nova Senha', 'required');

			if ($this->form_validation->run() == false)
			{
				//display the form

				//set the flash data error message if there is one
				if(validation_errors()){
					$this->data['alert']['message'] = validation_errors();
					$this->data['alert']['return'] = 'alert-error';
				}

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->load->view($this->_layout_login, $this->data);
			}
			else
			{
				/* do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) 
				{

					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error('Esse formulário não passou pelos nossos filtros de segurança. Para voltar, <a href="javascript:history.back(1);">clique aqui</a>. ');

				} 
				else 
				{*/
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{ 
						//if the password was successfully changed
						$alert = array('message' => $this->ion_auth->messages(), 'return' => 'alert-success');
						$this->session->set_flashdata('alert', $alert);
						redirect('auth/login', 'refresh');
					}
					else
					{
						$alert = array('message' => $this->ion_auth->errors(), 'return' => 'alert-error');
						$this->session->set_flashdata('alert', $alert);
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				//}
			}
		}
		else
		{ 
			//if the code is invalid then send them back to the forgot password page
			$alert = array('message' => $this->ion_auth->errors(), 'return' => 'alert-error');
			$this->session->set_flashdata('alert', $alert);
			redirect("auth/forgot_password", 'refresh');
		}
	}


	//activate the user
	function activate($id, $code=false)
	{
		if(!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}

		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if($this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Ativar Usuário'))
		{
			$activation = $this->ion_auth->activate($id);
		}
		if ($activation)
		{
			//redirect them to the auth page
			//$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth/index", 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		if(!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}

		$this->data['titulo'] = 'Desativar Usuário';

		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'Confirmação', 'required');
		$this->form_validation->set_rules('id', 'ID do Usuário', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['pagina'] = 'auth/deactivate_user';
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->load->view($this->_layout, $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{				
					show_error('Esse formulário não passou pelos nossos filtros de segurança. Para voltar, <a href="javascript:history.back(1);">clique aqui</a>. ');
				}

				// do we have the right userlevel?
			    if($this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, $this->data['titulo']))
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('auth/index', 'refresh');
		}
	}

	 /**
     * Efetua a confirmação e posteriormente exclusão de um Usuário
     * @author LAZARINI, Leonardo Filipe <leo.lazarini@gmail.com>
     */
	function delete_user($id = NULL)
	{
		if(!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}

		$this->data['titulo'] = 'Excluir Usuário';

		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'Confirmação', 'required');
		$this->form_validation->set_rules('id', 'ID do Usuário', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['pagina'] = 'auth/delete_user';
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->load->view($this->_layout, $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{				
					show_error('Esse formulário não passou pelos nossos filtros de segurança. Para voltar, <a href="javascript:history.back(1);">clique aqui</a>. ');
				}

				// do we have the right userlevel?
				if ($this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, $this->data['titulo']))
				{
					if ($this->ion_auth->delete_user($id))
						$alert = array('message' => $this->ion_auth->messages(), 'return' => 'alert-success');
					else
						$alert = array('message' => $this->ion_auth->errors(), 'return' => 'alert-error');

					$this->session->set_flashdata('alert', $alert);
				}
			}

			//redirect them back to the auth page
			redirect('auth/index', 'refresh');
		}
	}

	//create a new user
	function create_user()
	{
		$this->data['titulo'] = "Novo Usuário";
		$this->data['pagina'] = "auth/create_user";

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/index', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('first_name', 'Primeiro Nome', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Último Nome', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('phone1', 'Primeira parte do Telefone', 'required|xss_clean|min_length[3]|max_length[3]');
		$this->form_validation->set_rules('phone2', 'Segunda parte do Telefone', 'required|xss_clean|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('phone3', 'Terceira parte do Telefone', 'required|xss_clean|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('company', 'Empresa', '|xss_clean');
		$this->form_validation->set_rules('groups', 'Nível de Permissão', 'required');
		$this->form_validation->set_rules('username', 'Login', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Senha', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Confirmação de Senha', 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('username'));
			$email    = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3')
			);

			$group = $this->input->post('groups');
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $group))
		{ 
			//check to see if we are creating the user
			//redirect them back to the admin page
			//$this->session->set_flashdata('message', $this->ion_auth->messages());

			$alert = array('message' => $this->ion_auth->messages(), 'return' => 'alert-success');
			$this->session->set_flashdata('alert', $alert);
			redirect("backend/home", 'refresh');
		}
		else
		{ 
			//display the create user form
			//set the flash data error message if there is one

			if(validation_errors() || $this->ion_auth->errors()){
				if(validation_errors()) 
					$message = validation_errors();
				else 
					$message = $this->ion_auth->errors();

				$this->data['alert']['message'] = $message;
				$this->data['alert']['return'] = 'alert-error';
			}

			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			$this->data['phone1'] = array(
				'name'  => 'phone1',
				'id'    => 'phone1',
				'class' => 'phone',
				'type'  => 'text',
				'maxlength' => '3',
				'value' => $this->form_validation->set_value('phone1')
			);
			$this->data['phone2'] = array(
				'name'  => 'phone2',
				'id'    => 'phone2',
				'class' => 'phone',
				'type'  => 'text',
				'maxlength' => '4',
				'value' => $this->form_validation->set_value('phone2')
			);
			$this->data['phone3'] = array(
				'name'  => 'phone3',
				'id'    => 'phone3',
				'class' => 'phone',
				'type'  => 'text',
				'size'	=> '5',
				'maxlength' => '4',
				'value' => $this->form_validation->set_value('phone3')
			);
			$this->data['username'] = array(
				'name'  => 'username',
				'id'    => 'username',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('username'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$this->load->model('ion_auth_model');
			$this->data['groups'] = $this->ion_auth_model->get_all_groups();
			//echo $this->db->last_query();

			$this->load->view($this->_layout , $this->data);
		}
	}

	//edit a user
	/**
	  * Permite editar ou visualizar os dados do usuário, aceita acesso via link passando o id como parâmetro, ou passando o campo
	  * hidden "usuario_id" via post
	  * @param  int $id Código Identificador do Usuário
	  * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
	  */ 
	 
	function edit_user($id = "0")
	{
		$this->data['titulo'] = "Editar Usário";
		$this->data['pagina'] = "auth/edit_user";

		//Verifica se está passando id ou não
		if($id == 0) {
			//Senão tiver valor via campo, redireciona.
			if($this->input->post("usuario_id")) {
				$id = $this->input->post("usuario_id");
			}
			else {
				$alert = array('message' => 'Favor selecionar o usuário', 'return' => 'alert-fail');
				$this->session->set_flashdata('alert', $alert);

				redirect('auth/index', 'refresh');
			}
		}

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/index', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();


		//process the phone number
		if (isset($user->phone) && !empty($user->phone))
		{
			$user->phone = explode('-', $user->phone);
		}

		if (isset($_POST) && !empty($_POST) &&  empty($_POST["usuario_id"]) )
		{

			//validate form input
			$this->form_validation->set_rules('first_name', 'Primeiro Nome', 'required|xss_clean');
			$this->form_validation->set_rules('last_name', 'Último Nome', 'required|xss_clean');
			$this->form_validation->set_rules('groups', 'Grupos', 'required');
			$this->form_validation->set_rules('phone1', 'Primeira parte do Telefone', 'required|xss_clean|min_length[3]|max_length[3]');
			$this->form_validation->set_rules('phone2', 'Segunda parte do Telefone', 'required|xss_clean|min_length[4]|max_length[4]');
			$this->form_validation->set_rules('phone3', 'Terceira parte do Telefone', 'required|xss_clean|min_length[4]|max_length[4]');
			//$this->form_validation->set_rules('company', 'Empresa', 'required|xss_clean');


			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error('Esse formulário não passou pelos nossos filtros de segurança. Para voltar, <a href="javascript:history.back(1);">clique aqui</a>. ');
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				/*'company'    => $this->input->post('company'),*/
				'phone'      => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3')
			);

			$groups = $this->input->post('groups');

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', 'Senha', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'Confirmação de Password', 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE)
			{ 
				$this->ion_auth->update($user->id, $data, $groups);
				//check to see if we are creating the user
				//redirect them back to the admin page
				//$this->session->set_flashdata('message', "User Saved");
				$alert = array('message' => 'Usuário atualizado com sucesso', 'return' => 'alert-success');
				$this->session->set_flashdata('alert', $alert);
				redirect('auth/index', 'refresh');
			}
		}


		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		if(validation_errors() || $this->ion_auth->errors()){
			if(validation_errors()) 
				$message = validation_errors();
			else 
				$message = $this->ion_auth->errors();

			$this->data['alert']['message'] = $message;
			$this->data['alert']['return'] = 'alert-error';
		}

		//pass the user to the view
		$this->data['user'] = $user;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone1'] = array(
			'name'  => 'phone1',
			'id'    => 'phone1',
			'class' => 'phone',
			'type'  => 'text',
			'maxlength' => '3',
			'value' => $this->form_validation->set_value('phone1', $user->phone[0]),
		);
		$this->data['phone2'] = array(
			'name'  => 'phone2',
			'id'    => 'phone2',
			'class' => 'phone',
			'type'  => 'text',
			'maxlength' => '4',
			'value' => $this->form_validation->set_value('phone2', $user->phone[1]),
		);
		$this->data['phone3'] = array(
			'name'  => 'phone3',
			'id'    => 'phone3',
			'class' => 'phone',
			'type'  => 'text',
			'size'	=> '5',
			'maxlength' => '4',
			'value' => $this->form_validation->set_value('phone3', $user->phone[2]),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->load->model('ion_auth_model');
		$this->data['groups'] = $this->ion_auth_model->get_all_groups();
		$this->data['groups_user'] = $this->ion_auth_model->get_groups_user($user->id);

		$this->load->view($this->_layout , $this->data);
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	// ----------------------------------------------------------------------
	
	/**
	 * Busca e retorna o autocomplete com o nome do usuário (nome, não login);
	 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
	 * @return json
	 */

	function autocomplete_nome_usuario() {		
		
		$term = $this->input->post('term', TRUE);
        
        $condicao = array ("first_name" => $term);
        $this->load->model("Cadastros_model");
        $tabela = $this->config->item('tables', 'ion_auth');
        $rows = $this->Cadastros_model->autocomplete($tabela['users'], $condicao );

        $keywords = array();

        foreach ($rows as $row) {
            
        	$row_array['value'] = $row->first_name.' '.$row->last_name;
            $row_array['label'] = $row->first_name.' '.$row->last_name;
            $row_array['id'] = $row->id;

            array_push($keywords, $row_array);

        }
        echo json_encode($keywords);
	}


	// ----------------------------------------------------------------------

	function novo_nivel_permissao() {
		$this->data["titulo"] = "Nível de Permissão";
		$this->data['pagina'] = "auth/novo_nivel_permissao";

		$this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, $this->data['titulo']);   
	
		$this->load->model("Cadastros_model");

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/index', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('nome', 'Nome', 'required|xss_clean|max_length[20]|callback__check_nome_nivel_permissao');
		$this->form_validation->set_rules('descricao', 'Descrição', "xss_clean|max_length[100]");
		$inseriu_nivel = false; 

		if ($this->form_validation->run() == true )
		{
			//Insere o nível de permissão
			$dados_nivel_permissao = array(
				"name" => $this->input->post("nome"),
				"description" => $this->input->post("descricao")
			);
			if($this->Cadastros_model->incluir($this->tables['groups'], $dados_nivel_permissao)) {

                $codigo_nivel = $this->db->insert_id();

                //Insere a permissão de cada método para aquele nível
                $metodos_existentes = $this->Cadastros_model->busca_todos($this->tables['sys_metodos'], "");
                foreach ($metodos_existentes as $row) {
                    $dados_sys_permissoes = array(
                        "id_metodo" => $row->id,
                        "id_nivel_permissao" => $codigo_nivel,
                        "valor_permissao" => $this->input->post($row->id)
                    );

                    $this->Cadastros_model->incluir($this->tables['sys_permissoes'], $dados_sys_permissoes);
                }
                $inseriu_nivel = true;
			}
			else {
				$inseriu_nivel = false; 
			}
		}
		if ($inseriu_nivel)
		{ 
			//check to see if we are creating the user
			//redirect them back to the admin page
			//$this->session->set_flashdata('message', $this->ion_auth->messages());

			$alert = array('message' => "O Nível de Permissão cadastrado com sucesso!", 'return' => 'alert-success');
			$this->session->set_flashdata('alert', $alert);

			redirect("backend/home", 'refresh');
		}
		else
		{ 
			//Exibe o formulário para cadastro de nível de permissão
			//set the flash data error message if there is one

			if(validation_errors() || $this->ion_auth->errors()){
				if(validation_errors()) 
					$message = validation_errors();
				else 
					$message = $this->ion_auth->errors();

				$this->data['alert']['message'] = $message;
				$this->data['alert']['return'] = 'alert-error';
			}

			//Array que armazena o código do método e o apelido utilizado pelo sistema
	        $array_metodos_apelidos = array();

	        //Pega todos os métodos já encontrados

	   		$metodos = $this->Cadastros_model->busca_todos($this->tables['sys_metodos'], "");

        	foreach ($metodos as $key) {
            	$array_metodos_apelidos[$key->id] = $key->apelido;
        	}

	        $this->data['array_metodos_apelidos'] = $array_metodos_apelidos;


	        //Reseta os dados pro formuláiro
	        $array_reset = array();
	        foreach ($array_metodos_apelidos as $key => $row) {
	            $array_reset[$key] = 0;
	        }
	        $array_reset["nome_perfil"] = "";

        	$this->data['dados'] = $array_reset;


			$this->data['nome'] = array(
				'name'  => 'nome',
				'id'    => 'nome',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('nome'),
				'placeholder' => "Nome do Nível"
			);
			$this->data['descricao'] = array(
				'name'  => 'descricao',
				'id'    => 'descricao',
				'value' => $this->form_validation->set_value('descricao'),
				'rows' => 3,
				'placeholder' => "O que o nível de permissão faz, quais as atividades"
			);
			
			$this->load->view($this->_layout , $this->data);
		}

	}

	// ----------------------------------------------------------------------
	
	/**
	 * Permite  editar os niveis de permissao, caso nao sej passado nenhum dado exibe
	 * o formulario. Aceita o código identificador via parametro na url e via post (para autocomplete)
	 * @param int $id Codigo Identificador do Nivel de Permissao 
	 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
	 * 
	 */
	public function editar_nivel_permissao($id = 0) {
		$this->data['titulo'] = "Editar Nível de Permissão";
		$this->data['pagina'] = "auth/editar_nivel_permissao";

		$this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, $this->data['titulo']);   

		$this->load->model("Cadastros_model");	

		//Verifica se está passando id ou não
		if($id == 0) {
			//Senão tiver valor via campo, redireciona.
			if($this->input->post("nivel_permissao_id")) {
				$id = $this->input->post("nivel_permissao_id");
			}
			else {
				$alert = array('message' => 'Favor selecionar o Nível de Permissão', 'return' => 'alert-fail');
				$this->session->set_flashdata('alert', $alert);

				redirect('auth/index', 'refresh');
			}
		}

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/index', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();

		if (isset($_POST) && !empty($_POST) &&  empty($_POST["nivel_permissao_id"]) )
		{

			//Validação
			$this->form_validation->set_rules('nome', 'Nome', 'required|xss_clean|max_length[20]|callback__check_nome_nivel_permissao['.$id.']');
			$this->form_validation->set_rules('descricao', 'Descrição', "xss_clean|max_length[100]");
			$inseriu_nivel = false; 

			$dados_nivel_permissao = array(
				"name" => $this->input->post("nome"),
				"description" => $this->input->post("descricao")
			);

			if ($this->form_validation->run() === TRUE)
			{ 
				 

				//Atualiza os dados do nível de permissão
				$this->Cadastros_model->atualizar($this->tables['groups'], "id", $id, $dados_nivel_permissao);

				//Deleta todas as permissões antigas.
            	$this->Cadastros_model->deletar($this->tables['sys_permissoes'], "id_nivel_permissao", $id);

            	//Adiciona as permissões novamente
            	$metodos_existentes = $this->Cadastros_model->busca_todos($this->tables['sys_metodos'], "");
            	foreach ($metodos_existentes as $row) {
	                $dados_sys_permissoes = array(
	                    "id_metodo" => $row->id,
	                    "id_nivel_permissao" => $id,
	                    "valor_permissao" => $this->input->post($row->id)
	                );

                	$this->Cadastros_model->incluir($this->tables['sys_permissoes'], $dados_sys_permissoes);
            	}


				$alert = array('message' => 'Nível de Permissão atualizado com sucesso!', 'return' => 'alert-success');
				$this->session->set_flashdata('alert', $alert);
		
				
				redirect('auth/index', 'refresh');
			}
		}


		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		if(validation_errors() || $this->ion_auth->errors()){
			if(validation_errors()) 
				$message = validation_errors();
			else 
				$message = $this->ion_auth->errors();

			$this->data['alert']['message'] = $message;
			$this->data['alert']['return'] = 'alert-error';
		}

		//Pega os dados do nível de permissão 
		$nivel_permissao = $this->Cadastros_model->get_by_info($this->tables['groups'], "id", $id);

		//Consulta os valores das permissões no banco
        $valores_permissao = array();
        $condicao_get_by_info = array ("id_nivel_permissao" => $id);
        $consulta_nivel_permissao = $this->Cadastros_model->busca_todos($this->tables['sys_permissoes'],  "", 0, "", $condicao_get_by_info);

        foreach($consulta_nivel_permissao as $key => $row) {
            $valores_permissao[$row->id_metodo] = $row->valor_permissao;
        }

		//Array que armazena o código do método e o apelido utilizado pelo sistema
        $array_metodos_apelidos = array();
   		$metodos = $this->Cadastros_model->busca_todos($this->tables['sys_metodos'], "");
    	foreach ($metodos as $key) {
        	$array_metodos_apelidos[$key->id] = $key->apelido;
    	}
        $this->data['array_metodos_apelidos'] = $array_metodos_apelidos;

        //Reseta os dados pro formuláiro
        $array_reset = array();
        foreach ($array_metodos_apelidos as $key => $row) {
            
            if(!isset($valores_permissao[$key])) {
            	$array_reset[$key] = 0;
            }
            else {
            	$array_reset[$key] = $valores_permissao[$key];
        	}
        }
        $array_reset["nome_perfil"] = "";

    	$this->data['dados'] = $array_reset;


		$this->data['nome'] = array(
			'name'  => 'nome',
			'id'    => 'nome',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('nome', $nivel_permissao->name),
			'placeholder' => "Nome do Nível"
		);
		$this->data['descricao'] = array(
			'name'  => 'descricao',
			'id'    => 'descricao',
			'value' => $this->form_validation->set_value('descricao', $nivel_permissao->description),
			'rows' => 3,
			'placeholder' => "O que o nível de permissão faz, quais as atividades"
		);

		$this->load->view($this->_layout , $this->data);

	}

	// ----------------------------------------------------------------------

	/**
	 * Verifica se o nome do nivel de permissao esta ja em uso ou nao
	 * 
	 * @param string $nome_nivel  Nome do Nivel de Permissao desejado
	 * @param int $id Código Idenficador do Nível de Permissao
	 * @return bool 
	 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
	 *
	 */
	public function _check_nome_nivel_permissao($nome_nivel, $id = 0) {


		$dados_nome_nivel_permissao = $this->Cadastros_model->get_by_info($this->tables['groups'], "name", $nome_nivel);
	
		if(!empty($dados_nome_nivel_permissao)) {

			//Se quem estiver editando o nivel depermissão for o próprio nível (edit), libera a
			if($id == $dados_nome_nivel_permissao->id) {
				return TRUE;
			}
			else {
				$this->form_validation->set_message('_check_nome_nivel_permissao', 'O Nome do Nível de Permissão já está sendo usado');
				return FALSE;
			}
		}
		else {

			return TRUE;
		}

	}

	// ----------------------------------------------------------------------
	
	/**
	 *  Requisita a confirmação, após isso verifica se pode realizar a exclusão do nível, só o fazer senão tiverem usuários registrados ao nível..
	 * @param int $id Código Identificador do Nível de Permissão 
	 * @return type
	 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
	 */
	
	public function excluir_nivel_permissao($id = 0) {

		$this->data['titulo'] = 'Excluir Nível de Permissão';
		$this->data['pagina'] = 'auth/delete_nivel_permissao';

		$this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, $this->data['titulo']);  

		$this->load->model("Cadastros_model");
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'Confirmação', 'required');
		$this->form_validation->set_rules('id', 'ID do Nível de Permissao', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{

			$this->data['nivel_permissao'] = $this->Cadastros_model->get_by_info($this->tables['groups'], "id" , $id);
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->load->view($this->_layout, $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{				
					show_error('Esse formulário não passou pelos nossos filtros de segurança. Para voltar, <a href="javascript:history.back(1);">clique aqui</a>. ');
				}
				
				$quantidade_usuarios = $this->Cadastros_model->count_rows_condition($this->tables['users_groups'], $this->join['groups']." = '".$this->input->post('id')."'");

				if($quantidade_usuarios > 0 ) {
					$alert = array('message' => "Não será possível excluir o nível de permissão, pois há usuários registrados com esse nível!", 'return' => 'alert-warning');
				}
				else {
					if($this->Cadastros_model->deletar($this->tables['sys_permissoes'], "id_nivel_permissao", $this->input->post('id') ) && $this->Cadastros_model->deletar($this->tables['groups'], "id", $this->input->post("id"))) {
						$alert = array('message' => "Nível de Permissão excluído com sucesso!", 'return' => 'alert-success');
					}
					else {
						$alert = array('message' =>  "Não foi possível excluir o Nível de Permissão", 'return' => 'alert-error');
					}
				}

				$this->session->set_flashdata('alert', $alert);
			}
			redirect('auth/index', 'refresh');
		}
	}


	// ----------------------------------------------------------------------
	
	/**
	 * Busca e retorna o autocomplete com o nível de permissão ;
	 * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
	 * @return json
	 */

	function autocomplete_nivel_permissao() {		
		
		$term = $this->input->post('term', TRUE);
        
        $condicao = array ("name" => $term);
        $this->load->model("Cadastros_model");
        $tabela = $this->config->item('tables', 'ion_auth');
        $rows = $this->Cadastros_model->autocomplete($this->tables['groups'], $condicao);

        $keywords = array();

        foreach ($rows as $row) {
            
        	$row_array['value'] = $row->name;
            $row_array['label'] = $row->name;
            $row_array['id'] = $row->id;

            array_push($keywords, $row_array);

        }
        echo json_encode($keywords);
	}


	// ----------------------------------------------------------------------
}
