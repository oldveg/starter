<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Classe de Auth
 * @link http://code.google.com/p/codeigniter-auth-library/
 * 
 */
class Auth_library {

    private $ci;
   
    
    public function __construct() {
        $this->ci = &get_instance();

    }
    
    // -----------------------------------------------------------------------

    function check_logged($modulo, $classe, $metodo, $apelido) {
        /*
         * Criando uma instância do CodeIgniter para poder acessar
         * banco de dados, sessionns, models, etc... 
         */
        $this->CI = & get_instance();

        /**
         * Buscando a classe e metodo da tabela sys_metodos
         */
        $array = array("modulo" => $modulo , 'classe' => $classe, 'metodo' => $metodo);
        $this->CI->db->where($array);
        $query = $this->CI->db->get('sys_metodos');
        $result = $query->result();

       
        // Se este metodo ainda não existir na tabela sera cadastrado
       if (count($result) == 0) {
            $data = array(
                "modulo" => $modulo,
                'classe' => $classe,
                'metodo' => $metodo,
                'apelido' => $apelido,
                'privado' => 1
            );

            $this->CI->db->insert('sys_metodos', $data);

            // Inclui as permissões pro nível de permissão de admins da VEG (-1) por padrão
            $data2 = array(
                'id_metodo' => $this->ci->db->insert_id(),
                'id_nivel_permissao' => '-1',
                'valor_permissao' => 1
            );

            $this->ci->db->insert('sys_permissoes', $data2);
            redirect(base_url() . $modulo . '/'. $classe . '/' . $metodo, 'refresh');
        }
        //Se ja existir tras as informacoes de publico ou privado
        else {
         
            if ($result[0]->privado == 0) {
                // Escapa da validacao e mostra o metodo.
 
                return false;
            } else {
                $id_nivel_permissao = $this->ci->session->userdata('group_id');

                $id_sys_metodos = $result[0]->id;
    
                // Se o usuario estiver logado vai verificar se tem permissao na tabela.
                if ($id_nivel_permissao) {
                    $array = array('id_metodo' => $id_sys_metodos, 'id_nivel_permissao' => $id_nivel_permissao, "valor_permissao" => 1) ;
                    $this->CI->db->where($array);
                    $query2 = $this->CI->db->get('sys_permissoes');
                    $result2 = $query2->result();
                    
                    // Se não vier nenhum resultado da consulta, manda para página de 
                    // usuario sem permissão.
                    if (count($result2) == 0) {
                        redirect(base_url() . 'erro_404/erro_permissao', 'refresh');
                    } else {
                        return true;
                    }
                }
                // Se não estiver logado, sera redirecionado para o login.
                else {
                   redirect(base_url() . 'erro_404/erro_permissao', 'refresh');
                }
            }
        }
    }
    
    // -----------------------------------------------------------------------

    /**
     * Método auxiliar para autenticar entradas em menu.
     * Não faz parte do plugin como um todo.
     */
    function check_menu($classe, $metodo) {
        $this->CI = & get_instance();
        $sql = "SELECT SQL_CACHE
				count(sys_permissoes.id) as found
				FROM
				sys_permissoes
				INNER JOIN sys_metodos
				ON sys_metodos.id = sys_permissoes.id_metodo
				WHERE id_usuario = '" . $this->ci->session->userdata('id_usuario') . "'
				AND classe = '" . $classe . "'
				AND metodo = '" . $metodo . "'";
        $query = $this->CI->db->query($sql);
        $result = $query->result();
        return $result[0]->found;
    }
    
    // -----------------------------------------------------------------------

}

?>
