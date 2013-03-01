<?php
/*
 * Funções para Manipulação de Valores de Dinheiro
 * Silvio Tenfen Junior (11/08/2011)
 * 
 * Contém funções não fornecidas pelo CodeIgniter
 */

// Exibir decimal(10,2) em formato reais
if (!function_exists('decimal2reais')) {

    function decimal2reais($decimal) {
        if ($decimal) {
            $decimal = str_replace(',', '', $decimal);
            $decimal = str_replace('.', ',', $decimal);
            return $decimal;
        } else {
            return null;
        }
    }

}

// Exibir reais em formato decimal(10,2)
if (!function_exists('reais2decimal')) {

    function reais2decimal($datahora) {
        if ($decimal) {
            $decimal = str_replace('.', '', $decimal);
            $decimal = str_replace(',', '.', $decimal);
            return $decimal;
        } else {
            return null;
        }
    }

}

// Exibir float em formato reais
if (!function_exists('float2reais')) {

    function float2reais($float) {
        if ($float) {
            $float = number_format($float, 2, ',', '.');
            return $float;
        } else {
            return null;
        }
    }

}

// Exibir reais em formato float
// Obs.: Nao realiza arredondamentos
if (!function_exists('reais2float')) {

    function reais2float($float) {
        if ($float) {
            $float = str_replace('.', '', $float);
            $float = str_replace(',', '.', $float);
            return $float;
        } else {
            return null;
        }
    }

}
?>
