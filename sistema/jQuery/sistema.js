/**
 * Created by Esdras Castro on 18/08/2016.
 */
var Global = {
    vars : {
        basePath : ''
    }
};

var Login = {
    init : function (){
        $('button[name="login[logar]"]').on('click',function(){
            Login.auth($(this).closest('form'));
            return false;
        });

        $('button[name="login[recuperar]"]').on('click',function(){
            Login.recover($(this).closest('form'));
            return false;
        });
    },
    auth : function(form){
        $.ajax({
            url :form.attr('action'),
            type : 'POST',
            data : form.serialize(),
            dataType: 'JSON',
            success : function (response) {
                if(response.status > 0) {
                    Materialize.toast(response.message, 15000);
                    location.href = form.data('redirect');
                }else{
                    Materialize.toast(response.message, 15000);
                }
            },
            error : function(xhr, text){
                Materialize.toast(xhr.statusText, 4000);
            }
        });
    },
    recover : function(form){
        console.log('recuperar');
    }
};