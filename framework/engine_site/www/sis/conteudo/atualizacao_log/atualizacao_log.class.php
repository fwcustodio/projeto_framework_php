<?
class AtualizacaoLog
{	
	public function geraUltimaAtualizacao($ModuloCod,$Codigo,$Acao)
	{
		//Inicia Conex�o
		$Con = Conexao::conectar();
		
		//Executa Sql		
		$Con->executar("INSERT INTO atualizacao_log (AtualizacaoModuloCod, Codigo, Acao, DataCriacao) 
									 		 VALUES ($ModuloCod, $Codigo, '$Acao', NOW())");

	}
	
	public function removerUltimaAtualizacao($ModuloCod,$Codigo)
	{
		//Inicia Conex�o
		$Con = Conexao::conectar();
		
		//Executa Sql		
		$Con->executar("DELETE FROM atualizacao_log WHERE AtualizacaoModuloCod = $ModuloCod AND Codigo = $Codigo");

	}
}
?>