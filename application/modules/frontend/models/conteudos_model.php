<?php

/**
 * Model Conteudos
 *
 * Realiza as operacoes da tabela "conteudos"
 * @author PERDONA, Francisco Pimentel <francisco.perdona@gmail.com>
 * @package model
 */
class Conteudos_model extends CI_Model {

    private $_tabela_padrao = "conteudos";
    private $_chave_primaria = "cod_conteudo";

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //busca todos os conteudos
    function get_all_for_select() {
        $query = $this->db->get($this->_tabela_padrao);
        return $query->result();
    }

    //atualiza o conteudo, só passar os dados + cod do conteudo
    function atualizar($lista_edicao, $cod_conteudo) {
        $this->db->where($this->_chave_primaria, $cod_conteudo);
        $this->db->update($this->_tabela_padrao, $lista_edicao);
        return $this->db->affected_rows();
    }

    //pesquisa o conteudo e retorna por row
    function get_by_codigo_row($cod_conteudo) {
        $this->db->where($this->_chave_primaria, $cod_conteudo);
        $query = $this->db->get($this->_tabela_padrao);
        return $query->row();
    }

    //pesquisa o conteudo e retorna por result
    function get_by_codigo($cod_conteudo) {
        $this->db->where($this->_chave_primaria, $cod_conteudo);
        $query = $this->db->get($this->_tabela_padrao);
        return $query->result();
    }

    //inseri newsletter
    function inserir($dados) {
        $this->db->insert("newsletter", $dados);
        return $this->db->affected_rows();
    }

    //pesquisa newsletter
    function pesquisa_newsletter() {
        $query = $this->db->get("newsletter");
        return $query->result();
    }

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

}

?>
