<?php
/*
 * Funções para Manipulação de Datas
 * Silvio Tenfen Junior (11/08/2011)
 * 
 * Contém funções não fornecidas pelo CodeIgniter
 */

// Exibir data e hora de um DATETIME obtido de um banco de dados
if (!function_exists('bd2datahora')) {

    function bd2datahora($datahora) {
        if ($datahora == "0000-00-00 00:00:00") {
            return "";
        } else {
            $datahora = explode(" ", $datahora);
            $data = $datahora[0];
            $hora00 = $datahora[1];
            $dataformat = explode("-", $data);
            $data = $dataformat[2] . "/" . $dataformat[1] . "/" . $dataformat[0];

            $hora = explode(":", $hora00);
            $hora = $hora[0] . ":" . $hora[1];

            return $data . " " . $hora;
        }
    }

}

// Exibir apenas a data de um DATETIME obtido de um banco de dados
if (!function_exists('bd2data')) {

    function bd2data($datahora) {
        if ($datahora == "0000-00-00 00:00:00") {
            return "";
        } else {
            $datahora = explode(" ", $datahora);
            $data = $datahora[0];
            $dataformat = explode("-", $data);
            $data = $dataformat[2] . "/" . $dataformat[1] . "/" . $dataformat[0];

            return $data;
        }
    }

}

// Exibir apenas a hora de um DATETIME obtido de um banco de dados
if (!function_exists('bd2hora')) {

    function bd2hora($datahora) {
        if ($datahora == "0000-00-00 00:00:00") {
            return "";
        } else {
            $datahora = explode(" ", $datahora);
            $hora00 = $datahora[1];
            $hora = explode(":", $hora00);

            return $hora[0] . ":" . $hora[1];
        }
    }

}

// Converter dados de data e hora em um DATETIME de banco de dados
if (!function_exists('datahora2bd')) {

    function datahora2bd($data, $hora) {
        if ($data == "" || $hora == "") {
            return "NULL";
        } else {
            $dataformat = explode("/", $data);
            $data = $dataformat[2] . "-" . $dataformat[1] . "-" . $dataformat[0];

            return $data . " " . $hora;
        }
    }

}

// Converter dados de data e hora (juntos) em um DATETIME de banco de dados
if (!function_exists('datetime2bd')) {

    function datetime2bd($data) {
		$hora = substr($data,10);
		$data = substr($data, 0, 10);
   		$data_array = explode("/", $data);
		$nova_data = @$data_array[2] . "-" . @$data_array[1] . "-" . @$data_array[0];
		return $nova_data . $hora;
    }

}

// Converter uma data em formato de data americano
// Se não estiver completo a expressão DD/MM/AAAA, ela é completada
// com os valores atuais
if (!function_exists('data2famericano')) {

    function data2famericano($data) {
        $dataformat = explode("/", $data);
        $dia = isset($dataformat[0]) ? $dataformat[0] : date('d');
        $mes = isset($dataformat[1]) ? $dataformat[1] : date('m');
        $ano = isset($dataformat[2]) ? $dataformat[2] : date('Y');
        $data = $ano . "-" . $mes . "-" . $dia;

        return $data;
    }

}

// Validar Hora retirado de: http://blog.shiguenori.com/2009/01/14/validar-data-hora-em-php/
if (!function_exists('validar_hora')) {

    function validar_hora($time) {
        list($hour, $minute) = explode(':', $time);

        if ($hour > -1 && $hour < 24 && $minute > -1 && $minute < 60) {
            return true;
        }
    }

}

// Validar Data retirado de: http://blog.shiguenori.com/2009/01/14/validar-data-hora-em-php/
if (!function_exists('validar_data')) {

    function validar_data($date) {
        if (!isset($date) || $date == "") {
            return false;
        }

        list($dd, $mm, $yy) = explode("/", $date);
        if ($dd != "" && $mm != "" && $yy != "") {
            if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)) {
                return checkdate($mm, $dd, $yy);
            }
        }
        return false;
    }

}

// Retirado de: http://forum.wmonline.com.br/topic/163602-formatar-data-sql-server/
if (!function_exists('mssql2datahora')) {

    function mssql2datahora($data) {
        if ($data == "")
            return "";

        $dtTimeStamp = strtotime($data);
        $datahora = date('d/m/Y H:i', $dtTimeStamp);

        return $datahora;
    }

}
?>
