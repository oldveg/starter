<?php

/**
 * Model Geral para busca simples, inclusão, alteração e exclusão no banco
 * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
 * 
 */
class Cadastros_model extends CI_Model {

    private $_chave_primaria = "cod";

    // -----------------------------------------------------------------------

    /**
     *  Função construtora
     * */
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // -----------------------------------------------------------------------

    /**
     * Função responsável por incluir no banco de dados
     * 
     * @param int $table - Tabela que fará a inserção
     * @param array $values - Array com o nome do campo e valor
     * 
     * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
     * @copyright 15/03/2012
     */
    function incluir($table, $values = array()) {
        $this->db->insert($table, $values);
        return $this->db->affected_rows();
    }

    // -----------------------------------------------------------------------

    /**
     * Função responsável por alterar os dados no banco
     * 
     * @param string $table - Tabela que fará alteração
     * @param int $cod - Código para condição
     * @param array $options - Campo e valor alterado
     * 
     * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
     * @copyright 15/03/2012
     */
    function atualizar($table, $nomecod, $cod, $options = array()) {

        $this->db->where($nomecod, $cod);
        $this->db->update($table, $options);

        return $this->db->affected_rows();
    }

    // -----------------------------------------------------------------------

    /**
     * Função responsável por deletar os registros do banco
     * 
     * @param string $table - Tabela que irá excluir registro
     * @param int $cod - Código para condição
     * 
     * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
     * @copyright 15/03/2012 
     */
    function deletar($table, $nomecod, $cod) {
        $this->db->where($nomecod, $cod);
        $this->db->delete($table);

        return $this->db->affected_rows();
    }

    //------------------------------------------------------------------------

    /**
     * Função responsável por deletar os registros relacionáveis
     * 
     *          * 
     * @param string $table - Tabela que irá excluir registro
     * @param string $campo - Nome do campo verificador
     * @param int $cod - Código para condição
     * 
     * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
     * @copyright 15/03/2012 
     */
    function deletar_relacionamento($table, $campo, $cod) {
        $this->db->where($campo, $cod);
        $this->db->delete($table);
        //echo $this->db->last_query();
        return $this->db->affected_rows();
    }

    //------------------------------------------------------------------------

    /**
     * Função responsável por buscar registro por condição
     * 
     * @param string $table - Tabela que deseja fazer a busca
     * @param string $campo - Campo que fará a verificação
     * @param string $value - Valor para igualar ao campo na condição
     * 
     * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
     * @copyright 15/03/2012
     */
    function get_by_info($table, $campo, $value) { //parâmetros campo e valor na condição
        $this->db->where($campo, $value);
        $query = $this->db->get($table);

        return $query->row();
    }

    //------------------------------------------------------------------------

    /**
     * Função reponsável para buscar todos registros da tabela, já com parâmetros para paginação, quando necessário.
     * 
     * @param string $table - Tabela que irá fazer a busca
     * @param int $por_pagina - Números de registros por página, no caso de paginação
     * @param int $offset - Inicio da busca, no caso de paginação
     * @param string $orderby - Campo que irá ordernar os registros na busca
     * 
     * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
     * @copyright 15/03/2012 
     */
    function busca_todos($table, $por_pagina = "", $offset = 0, $orderby = "", $condicao = array(), $orderby2 = "asc") {

        if ($orderby != "")
            $this->db->order_by($orderby, $orderby2);
        if ($condicao) {
            foreach ($condicao as $row => $cont) {
                $this->db->where($row, $cont);
            }
        }
        if($por_pagina != "") {
              $query = $this->db->get($table, $por_pagina, $offset);
        }
        else {
              $query = $this->db->get($table);
        }



        return $query->result();
    }

    //-------------------------------------------------------------------------

    /**
     * Função responsável por carregar o select
     * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
     * @param string $table - Tabela que buscará os registros
     * @param string $orderby - campo para ordenar a busca
     * @copyright 20/03/2012
     * 
     */
    function get_for_select($table, $orderby = "", $condicao = array()) {
        if ($orderby != "")
            $this->db->order_by($orderby, "asc");
        $query = $this->db->get($table);

        if ($condicao) {
            foreach ($condicao as $row => $cont) {
                $this->db->where($row, $cont);
            }
        }

        //echo $this->db->last_query();
        return $query->result();
    }

    //-------------------------------------------------------------------------

    /**
     * Função responsável por contar o numero de registros na tabela
     * 
     * @param string $table - Tabela para contar os registros
     * 
     * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
     * @copyright 15/03/2012
     */
    function count_rows($table) {
        $query = $this->db->count_all($table);
        return $query;
    }

    // ----------------------------------------------------------------------
    

    /**
     * Função responsável por contar o numero de registros na tabela
     * 
     * @param string $table - Tabela para contar os registros
     * 
     * @author PERDONA, Francisco Pimentel <francisco.perdona@vegtecnologia.com.br>
     * @copyright 20/07/2012
     */
    function count_rows_condition($table, $where) {
        $query = $this->db->query('SELECT COUNT(*) as count FROM '.$table.' WHERE '.$where);
        $result = $query->result();
        return $result[0]->count;
    }

    // ----------------------------------------------------------------------
    
    /**
     * AUTO INCREMENTO
     */
    function auto_incremento($table) { 
       // $this->db->select('Auto_increment');
        //$this->db->from('information_schema.tables');
        //$this->db->where('table_name', $table);

       $query = $this->db->query("SELECT Auto_increment FROM information_schema.tables WHERE table_name='".$table."'");
       $result = $query->result();
       return $result[0]->Auto_increment;
    }

    // ------------------------------------------------------------------------

    function get_count_all_by_cod($cod, $verifica, $table) {
        $this->db->where($verifica, $cod);
        $query = $this->db->get($table);
        return $query->num_rows();
    }

    //----------------------------------------------------------------------------

    /**
     * 
     */
    function get_cities_by_state($uf) {

        $this->db->select('cod_cidades, nome');
        $this->db->where('estados_cod_estados', $uf);

        $query = $this->db->get('cidades');
        $result_array = array();

        if ($query->result()) {
            foreach ($query->result() as $cidade) {
                $result_array[$cidade->cod_cidades] = $cidade->nome;
            }
        }
        return $result_array;
    }

    // ------------------------------------------------------------------------
    //pesquisa distinct
    function pesquisa_distinct($table,$campo) {
        $this->db->select($campo);
        $this->db->group_by($campo); 
        $query = $this->db->get($table);
        return $query->result();
    }
    //pesquisa dropdown
    function pesquisa_dropdown($table,$campo,$value,$group){
        $this->db->where($campo,$value);
        $this->db->group_by($group);
        $query = $this->db->get($table);
        return $query->result();
    }
    
    /****************BANNER************************/
      //pesquisa banner
    function pesquisa_banner() {
        $query = $this->db->get("banner");
        return $query->result();
    }
    function pesquisa_banner_desc() {
        $this->db->order_by("cod_banner","DESC");
        $query = $this->db->get("banner");
        return $query->result();
    }

    function atualiza_banner($lista_edicao, $cod_banner) {
        $this->db->where("cod_banner", $cod_banner);
        $this->db->update("banner", $lista_edicao);
        return $this->db->affected_rows();
    }

    function inserir_banner($dados_banner) {
        $this->db->insert("banner", $dados_banner);
        return $this->db->affected_rows();
    }
    // exclui banner
    function apagar_banner($cod_banner) {
        $this->db->where("cod_banner", $cod_banner);
        $this->db->delete("banner");
        return $this->db->affected_rows();
    }
    function linhas_banner() {
        $query = $this->db->get("banner");
        return $query->num_rows();
    }
    /**********************************************************/

    function busca_todos_joiner($table, $por_pagina = "", $offset = 0, $orderby = "", $condicao = array(), $orderby2 = "asc") {

         $this->db->select('produtos.*,
                            categorias.nome nome_categoria
                              ');

        if ($orderby != "")
            $this->db->order_by($orderby, $orderby2);
        if ($condicao) {
            foreach ($condicao as $row => $cont) {
                $this->db->where($row, $cont);
            }
        }

        $this->db->join('categorias', 'categorias.cod_categoria = produtos.categorias_cod_categoria');

        $query = $this->db->get($table, $por_pagina, $offset);

        return $query->result();
    }

    // ----------------------------------------------------------------------

    /**
     * Realiza a busca pelo nome para o auto complete
     * @param array $options
     * @return object
     */
    function autocomplete($table, $condicao = array()) {       
        if ($condicao) {
            foreach ($condicao as $campo => $valor) {
               $this->db->like($campo,$valor);
            }
        }
        $query = $this->db->get($table);
        return $query->result();
    }

    // ----------------------------------------------------------------------
}

