function addView(){
    var view = $('#InputAddView').val();

    if(view == '' || view == null){
        alert('O nome da view não pode estar em branco!');
    }
    else{
        var adicional = Math.floor((Math.random()*100)+1);
        $('#divViews').append('<div id="viewItem'+adicional+'" class="viewItem"><span>'+view+'</span><span onclick="removerView('+adicional+')" class="removerView"><img width="12" src="'+UrlBaseSite+'figuras/excluir.png" alt="bt_remover"/></span></div>');
        $('#InputAddView').val('');
    }
}

function removerView(num){
    $('#viewItem'+num).remove();
}

$().ready(function(){
    $("#InputAddView").keypress(function(event){
        if(event.keyCode==13 || event.wich==13)
            addView();
    });
});


function selecionarTodos(arg){
    if(arg){
        $('.checkBoxItens').each(function(){
            $(this).attr('checked','');
        });
    }
    else{
        $('.checkBoxItens').each(function(){
            $(this).attr('checked',false);
        });
    }
}

function gerarArquivos(){
    var views = getViewsForm();
    $.ajax({
        url: UrlBaseSite+'engine_site/ajax/engine_site_ajax.php?Op=geraArquivos&Env=true',
        type: 'POST',
        datatype:'html',
        data: $('#FormManu').serialize()+'&Views='+views,
        complete:function(Req){
            retornoGerarArquivos(Req.responseText);
        }
    });
}

function retornoGerarArquivos(Resp){
    if(Resp == true || Resp == 1){
        alert('Arquivos gerados com sucesso!');
    }
    else if(Resp == false || Resp == 0) {
        alert('Não foi possível gerar os arquivos!');
    }
    else{
        alert(Resp);
    }
}

function getViewsForm(){
    var views = '';
    $('.viewItem').each(function(i){
        views += $(this).children('span').html();
        views +='|';
    });
    return views.substr(0, views.length -1);
}