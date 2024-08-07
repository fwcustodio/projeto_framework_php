<?
//Starta Sessão
session_start();

//Chamando Arquivos
include_once('framework/config.conf.php'); ConfigSIS::Conf();
include_once($_SESSION['FMBase'].'acesso.class.php'); 
include_once($_SESSION['DirBase'].'janela.class.php');		$Jan  = new Janela();
include_once($_SESSION['FMBase'].'funcoes_php.class.php');	$FPHP = new FuncoesPHP();


//Acesso
$Ac = new Acesso();
$Ac->verificaEntrada();

//Conexao
try
{
	$Con = Conexao::conectar();	
}
catch(Exception $E) {}

//Metodos Da Classe Janela
$Jan->geraJanelas();
$ArrayModulo = array();
$ColunaA = $Jan->colunaA();
$ColunaB = $Jan->colunaB();
$ColunaC = $Jan->colunaC();

/*
//Gera Janela para todos usuarios mas antes limpa a tabela _janela
$Usuarios = $Con->executar("SELECT UsuarioCod FROM _usuarios");

while($UID = mysqli_fetch_array($Usuarios))
{

	$Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('bvindas', ".$UID['UsuarioCod'].", 'S', 'A', 1)");
	$Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('galeria_midia', ".$UID['UsuarioCod'].", 'S', 'A', 2)");
	$Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('up_arquivos', ".$UID['UsuarioCod'].", 'S', 'B', 1)");
	$Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('enquete', ".$UID['UsuarioCod'].", 'S', 'B', 2)");	
	$Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('noticia', ".$UID['UsuarioCod'].", 'S', 'C', 1)");
	$Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('log', ".$UID['UsuarioCod'].", 'S', 'C', 2)");
	$Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('eventos', ".$UID['UsuarioCod'].", 'S', 'C', 3)");
}
//fim
*/
?>

<? include_once($_SESSION['DirBase'].'includes/cabecalho_html_principal.inc.php') ?>

<link href="css/gadget.css" rel="stylesheet" type="text/css" />
<link href="css/icones.css" rel="stylesheet" type="text/css" />
<link href="css/aba.css" rel="stylesheet" type="text/css" />
<link href="css/calendario.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="<?=$_SESSION['JSBase']?>js/interface.js" />
<script src="<?=$_SESSION['UrlBaseSite']?>js/jquery.lightbox.js" language="javascript" type="text/javascript" />
<link href="<?=$_SESSION['UrlBaseSite']?>css/jquery.lightbox.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="js/aba.js" />
<script type="text/javascript">
$(function() {
	// Use this example, or...
	$('a[@rel*=lightbox]').lightBox({fixedNavigation:true}); // Select all links that contains lightbox in the attribute rel
});
</script>
<script>
function mouseOver(td)
{
	td.style.backgroundColor='#e0e7ef';
	td.style.cursor='pointer';
}
function mouseOut(td)
{
	td.style.backgroundColor= '';
	td.style.cursor='';
} 
function mouseDown(url)
{
	window.open(url);
}
</script>

</head><body>
<?php require_once($_SESSION['DirBase'].'includes/topo_principal.inc.php'); ?>
<div align="right" style="margin-right:20px"><a href="sair/" id="sair"><img src="figuras/bullet_sair2.gif"  border="0"  /></a></div>


<div id="meio">
<? //$FPHP->alertaNavegador();?>

<table width="100%" border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td width="33%" valign="top">    <div id="sort1" class="groupWrapper">
    <? foreach($ColunaA as $Janelas1){?>
        
		<?='<div id="'.$Janelas1['Modulo'].'" class="groupItem">
                <div style="-moz-user-select: none; " class="itemHeader">'.$Janelas1['Titulo'].'
                    
                    '.$Janelas1['Conteudo'].'
                    '.$Janelas1['Rodape'].'
                   
            </div></div>'?>
    <? }?>
    </div></td>
    <td width="33%" valign="top"><div id="sort2" class="groupWrapper">
    <? foreach($ColunaB as $Janelas2){?>
        <?='<div id="'.$Janelas2['Modulo'].'" class="groupItem">
                <div style="-moz-user-select: none; " class="itemHeader">'.$Janelas2['Titulo'].'
                   
                    '.$Janelas2['Conteudo'].'
                    '.$Janelas2['Rodape'].'
                    
            </div></div>'?>       
    <? }?>
    </div></td>
    <td width="33%" valign="top"> <div id="sort3" class="groupWrapper">
    <? foreach($ColunaC as $Janelas3){?>
        <?='<div id="'.$Janelas3['Modulo'].'" class="groupItem">
                <div style="-moz-user-select: none; " class="itemHeader">'.$Janelas3['Titulo'].'
                    
                    '.$Janelas3['Conteudo'].'
                    '.$Janelas3['Rodape'].'
                    
            </div></div>'?>
    <? }?>
    </div></td>
  </tr>
</table>

<!--INICIO DAS GADGETS-->	


    

   
</div>



<!--FINAL DAS GADGETS-->
<script type="text/javascript">
$(document).ready
(
	function () 
	{		
		$('div.groupWrapper').Sortable(
			{
				accept: 'groupItem',
				helperclass: 'sortHelper',
				activeclass :'sortableactive',
				hoverclass :'sortablehover',
				handle: 'div.itemHeader',
				tolerance: 'pointer',
				onChange : function(ser)
				{
					serialize();
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
	}
);

function minimizarGadget(t)
{
	//var Url = 'sistema/gadgets/gadgets.ajax.php?Op=MiniMax'; //Url Ajax
	var idM = $(t).parent().parent().attr("id");//Id Para minimizar e Maximizar
	var targetContent = $('div.itemContent', $(t).parent().parent());//Endereço do Compoenente

	if (targetContent.css('display') == 'none') 
	{
		targetContent.slideDown(300);
		$(t).html('<img src="figuras/max.jpg"  border="0"/>');
		var Parametros = "GadGetCod="+idM+"&Modo=Max";
		var ParametrosJanela = "TipoInteracao=MaxORMin&Situacao=Max&Modulo="+idM; 
		var Url = 'janela.ajax.php';
		$.post(Url,ParametrosJanela);
	} else {
		targetContent.slideUp(300);
		$(t).html('<img src="figuras/min.jpg"  border="0"/>');
		var Parametros = "GadGetCod="+idM+"&Modo=Min";
		var ParametrosJanela = "TipoInteracao=MaxORMin&Situacao=Min&Modulo="+idM; 
		var Url = 'janela.ajax.php';
		$.post(Url,ParametrosJanela);
	}
	return false;
}

function fecharGadget(t)
{
	if(confirm("Tem certeza que deseja remover esta gadget?"))
	{
		//Recupera o Nome da Janela
		var nomeJanela = $(t).parent().text();
		//Verifica se ja existe a div
		if($('#janelamin').attr("id") == undefined)
		{
		//Se não existe cria
		$('#meio').append('<div id="janelamin" style="display:none"></div>');
		}
		//Adiciona Itens
		$('#janelamin').append('<a href="javascript:void(0)" id="linkCaixa" onClick="retiraDaCaixa(this)">'+nomeJanela+'</a>');
		$(t).parent().parent().hide();
		//serialize();
	}
	else
	{
		return false;
	}
}
//Retira elemento da caixa
function retiraDaCaixa(nomeJanela, t)
{	
	//Mostra a Janela Novamente
	$(t).parent().show();
	
	//Remove o Iten
	$(nomeJanela).remove();
	
	//Se num tiver mais ninguem apaga a caixa
	if($("#janelamin").text() == "")
	{
		$("#janelamin").remove();
	
		
	}
}
function serialize(s)
{
	serial = $.SortSerialize(s);
	var Parametros = serial.hash;
	var Url = 'janela.ajax.php?TipoInteracao=Serial';
	$.post(Url,{'Parametros':''+Parametros});
}
</script>

<?php include_once($_SESSION['DirBase'].'includes/rodape.inc.php');?>

</body>
</html>
