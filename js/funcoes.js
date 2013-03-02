$(document).ready(function() {
    // ----------------------------------------------------------------------

    /**
     * Autocomplete para o nome do usuários
     * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
    **/
    if($('#autocomplete_usuario').length){ 
        $("#autocomplete_usuario").autocomplete({
            source: function(request, response) {
                    $.ajax({
                        url: CI_ROOT+"backend/auth/autocomplete_nome_usuario",
                        data: {
                            term: $("#autocomplete_usuario").val()
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data){  
                            //Retorna os dados
                            response(data);
                        }
                    });
            },
            select: function(event, ui) {
                    //Quando selecionado, altera o valor do input hidden
                    $('#usuario_id').val(ui.item.id);
            },
            minLength: 4
        });           
    }

    // ----------------------------------------------------------------------

    /**
    * Autocomple para o nível de permissão
    * @author ANDRADE, Luís Felipe de <luis_andrade11@hotmail.com>
    */
    if($('#autocomplete_nivel_permissao').length){ 
        $("#autocomplete_nivel_permissao").autocomplete({
            source: function(request, response) {
                    $.ajax({
                        url: CI_ROOT+"backend/auth/autocomplete_nivel_permissao",
                        data: {
                            term: $("#autocomplete_nivel_permissao").val()
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data){  
                            //Retorna os dados
                            response(data);
                        }
                    });
            },
            select: function(event, ui) {
                    //Quando selecionado, altera o valor do input hidden
                    $('#nivel_permissao_id').val(ui.item.id);
            },
            minLength: 4
        });           
    }

    /**
     * Função responsável por avançar ao próximo campo automáticamente quando o maxlength for alcançado
     * @author LAZARINI, Leonardo Filipe <leo.lazarini@gmail.com>
     */
    $('#phone1, #phone2, #phone3').autotab_magic().autotab_filter('numeric');

    /**
     * Função responsável por limpar o value atual dos campos de telefone ao receberem focus. 
     * @author LAZARINI, Leonardo Filipe <leo.lazarini@gmail.com>
     */
    var valor_antigo = '';
    // clear input on focus
    $('.phone').focus(function(){
        valor_antigo = $(this).val();
        $(this).val('');
    });
    //Caso o campo estiver vazio, insere novamente o valor antigo
    $('.phone').blur(function(){
        if($(this).val()==''){
            $(this).val(valor_antigo);
        }
    });

    //Aplica o asterístico vermelho após todo campo setado como obrigatório
    $(".obrigatorio").after("&nbsp;<font color='red'>*</font>");

    

    /**
     * Função responsável por iniciar o plugin jQuery Placeholder 
    **/
    Placeholder.init();
});
