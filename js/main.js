jQuery(document).ready(function($) {

   var existencia_ckeditor = $('.conteudo').is(':has(#editor)');

    if(existencia_ckeditor) {
   		CKEDITOR.replace('editor',{
        	filebrowserBrowseUrl : CI_ROOT + 'js/vendor/kfm-1.4.7/index.php'
   		});
    }
   
  
});