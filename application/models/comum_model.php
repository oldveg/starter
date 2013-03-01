<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * MODEL Comum
 *
 * Model de acesso simples a tabelas
 * @author NICOLODI, Priscilla <priscillanicolodi@gmail.com>
 * @version 1.0
 * @package backend
 * @subpackage model
 */

class Comum_model extends CI_Model {

    //Atributos auxiliares 
    private $_chave_primaria = 'cod_';

    // -----------------------------------------------------------------------

    /**
     *  Função construtora
     * */
    function __construct() {
        parent::__construct();
        //$this->load->database(); -> A chamada e feita via autoload
    }

    // -----------------------------------------------------------------------
    //Função responsável por adicionar 
    function incluir($table, $values = array()) {
        
        $this->db->insert($table, $values);
        return $this->db->affected_rows();
    }

    // -----------------------------------------------------------------------
    //Função responsável por alterar os dados  
    //Recebe como parâmetro os valores dentro de um array
    function atualizar($table, $id, $options = array()) {

        $this->db->where($this->_chave_primaria . $table, $id);
        $this->db->update($table, $options);
        return $this->db->affected_rows();
    }

    // -----------------------------------------------------------------------
    //Função responsável por deletar 
    function deletar($table, $id) {
        
        $this->db->where($this->_chave_primaria . $table, $id);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    //------------------------------------------------------------------------
    //Função responsável por buscar com condição
    function busca_condicao($table, $campo, $value) { //parâmetros campo e valor na condição
        $this->db->where($campo, $value);
        $query = $this->db->get($table);

        return $query->row();
    }

    //------------------------------------------------------------------------

    function busca_2condicao($table, $campo1, $campo2, $value1, $value2) { //parâmetros campo e valor na condição
        $this->db->where($campo1, $value1);
        $this->db->where($campo2, $value2);
        $query = $this->db->get($table);

        return $query->row();
    }

    //------------------------------------------------------------------------
    //Função responsável por buscar TODOS
    function busca_todos($table, $por_pagina = 10, $offset = 0, $orderby) {
        $this->db->order_by($orderby, "asc");
        $query = $this->db->get($table, $por_pagina, $offset);
        return $query->result();
    }

    //Conta o numero de registros na Tabela
    function cont_rows($table) {
        $query = $this->db->count_all($table);
        return $query;
    }

    //----------------------------------------------------------------------------
}
