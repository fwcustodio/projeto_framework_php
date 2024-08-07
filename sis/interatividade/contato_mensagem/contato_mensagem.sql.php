<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class MensagemSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);

		$Sql = "SELECT a.ContatoMensagemCod, a.ContatoDepartamentoCod, b.Departamento, c.Assunto, a.Nome, a.Email, a.Telefone, a.Pais, a.UF, a.Cidade, a.Mensagem,
						a.Criacao,
						CASE
						WHEN a.Status = 'L' THEN 'Lida'
						WHEN a.Status = 'NL' THEN 'Não Lida'
						END AS Status
				FROM contato_mensagem a, contato_departamento b, contato_assunto c, contato_responsavel e
				WHERE a.ContatoDepartamentoCod = b.ContatoDepartamentoCod
				AND a.AssuntoCod = c.ContatoAssuntoCod 
				AND b.ContatoDepartamentoCod = e.ContatoDepartamentoCod";


		$Sql .= $Fil->getStringSql("ContatoDepartamentoCod","a.ContatoDepartamentoCod");
		$Sql .= $Fil->getStringSql("Assunto","c.Assunto");
		$Sql .= $Fil->getStringSql("Nome","a.Nome");
		$Sql .= $Fil->getStringSql("Criacao","DATE_FORMAT(a.Criacao,'%Y/%m/%d')","Data");
		$Sql .= $Fil->getStringSql("Status","a.Status");
		//Sql de Impressão
		$Sql .= $Fil->printSql("ContatoMensagemCod",$_GET['SisReg']);
			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT ContatoMensagemCod, Departamento, Assunto, Nome, Email, Telefone, Pais, UF, Cidade, Mensagem, Observacoes,
						DATE_FORMAT(Criacao, '%d/%m/%Y ás %H:%i') AS Criacao, 
						CASE
						WHEN a.Status = 'L' THEN 'Lida'
						WHEN a.Status = 'NL' THEN 'Não Lida'
						END AS Status
				FROM contato_mensagem a, contato_departamento b, contato_assunto c
				WHERE a.ContatoDepartamentoCod = b.ContatoDepartamentoCod
				AND a.AssuntoCod = c.ContatoAssuntoCod 
				AND a.ContatoMensagemCod  = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
//		$Sql = "INSERT ";
//
//		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
//		//Variaveis
//		$VAR[] = $ObjForm->getCampoRetorna("ContatoDepartamentoCod",false,"Inteiro");
//		$VAR[] = $ObjForm->getCampoRetorna("AssuntoCod",false,"Inteiro");
//		$VAR[] = $ObjForm->getCampoRetorna("Status",false,"Texto");
//		$VAR[] = $ObjForm->getCampoRetorna("Observacoes",true,"Texto");
//		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");
//
//		$Sql = "UPDATE contato_mensagem SET
//				ContatoDepartamentoCod = %s, AssuntoCod = %s, Status = %s, Observacoes = %s
//				WHERE ContatoMensagemCod = %s";
//
//		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ContatoMensagemCod, ContatoDepartamentoCod, AssuntoCod, Nome, Email, Telefone, Pais, UF, Cidade, Mensagem, Observacoes, Status
				FROM contato_mensagem 
				WHERE ContatoMensagemCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM contato_mensagem 
				WHERE ContatoMensagemCod = $Cod";
		
		return $Sql;
	}
	
	public function marcarLidaSql($Cod)
	{
		$Sql = "UPDATE contato_mensagem SET Status = 'L'
				WHERE ContatoMensagemCod = $Cod";
		
		return $Sql;
	}
	
	public function marcarNaoLidaSql($Cod)
	{
		$Sql = "UPDATE contato_mensagem SET Status = 'NL'
				WHERE ContatoMensagemCod = $Cod";
		
		return $Sql;
	}

	public function getDepartamentoSql()
	{
		$Id	 = $_POST['Id'];	
		
		$Sql = "SELECT ContatoDepartamentoCod
				FROM contato_mensagem 
				WHERE ContatoMensagemCod = $Id";
		
		return $Sql;
	}
	
	public function visualizarAlteraStatusSql($Id)
	{
		
		$Sql = "UPDATE contato_mensagem SET Status = 'L'
				WHERE ContatoMensagemCod = $Id";
		
		return $Sql;
	}

	public function alterarStatusSql()
	{
		$Id	 = $_POST['Id'];	
		
		$Sql = "UPDATE contato_mensagem SET Status = 'NL'
				WHERE ContatoMensagemCod = $Id";
		
		return $Sql;
	}

	public function geraMensagemEmailSql() 
	{
		$Id	 = $_POST['Id'];

		$Sql = "SELECT ContatoMensagemCod, a.ContatoDepartamentoCod, Departamento, Assunto, Nome, Email, Telefone, Pais, UF, Cidade, Mensagem, Observacoes, 
						DATE_FORMAT(Criacao, '%d/%m/%Y ás %H:%i') AS Criacao, 
						CASE
						WHEN a.Status = 'L' THEN 'Lida'
						WHEN a.Status = 'NL' THEN 'Não Lida'
						END AS Status
				FROM contato_mensagem a, contato_departamento b, contato_assunto c, contato_responsavel e
				WHERE a.ContatoDepartamentoCod = b.ContatoDepartamentoCod
				AND a.AssuntoCod = c.ContatoAssuntoCod 
				AND b.ContatoDepartamentoCod = e.ContatoDepartamentoCod
				AND ContatoMensagemCod = ".$Id."";

		return $Sql;
	}
	
	public function getDepartamentoAntigoSql($Cod)
	{
		
		$Sql = "SELECT ContatoDepartamentoCod, Departamento
				FROM contato_departamento 
				WHERE ContatoDepartamentoCod = $Cod";
		
		return $Sql;
	}
	
	public function geraListaUsuariosSql($Cod)
	{
		
		$Sql = "SELECT ContatoDepartamentoCod, Email
				FROM contato_responsavel a, _usuarios b
				WHERE a.UsuarioCod = b.UsuarioCod
				AND ContatoDepartamentoCod = $Cod";
		
		return $Sql;
	}
	
}
?>