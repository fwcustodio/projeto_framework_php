<?
class ContatoForm
{
	private $FormIntancia;
	
	public function ContatoForm($Form)
	{
		$this->FormIntancia = $Form;
	}
	
	public function getFormContato($Cont = null)
	{
		$Metodo = "POST";
						
		$R['ArrayContato'.$Cont] = $this->FormIntancia->inputHidden(array(
		"Nome"   => "ArrayContato[$Cont]",  
		"Valor"  => $Cont),true);		
		
		$R["NomeContato".$Cont] = $this->FormIntancia->inputTexto(array(
		"Nome"       => "NomeContato".$Cont,
		"Identifica" => "NomeContato ".$Cont,
		"Valor"      => $this->FormIntancia->retornaValor($Metodo,"NomeContato".$Cont),
		"Largura"    => 23,
		"Max"        => 50,
		"ValidaJS"   => true),false);	
		
		$R["ContatoObservacao".$Cont] = $this->FormIntancia->textArea(array(
		"Nome"        => "ContatoObservacao".$Cont,
		"Identifica"  => "Observações de Contato ".$Cont,
		"Valor"       => $this->FormIntancia->retornaValor($Metodo,"ContatoObservacao".$Cont),
		"Linhas"      => 2,
		"Colunas"     => 17,
		"Status"      => true,
		"ValidaJS"    => false),false);				

		return $R;	
	}

	public function getFormTipo($Cont = null)
	{
		$Metodo = "POST";
		
		$R["ContatoCategoriaCod".$Cont] = $this->FormIntancia->listBox(array(
		"Nome"        => "ContatoCategoriaCod[".$Cont."][]",
		"Identifica"  => "Categoria do contato ".$Cont,
		"Valor"       => $this->FormIntancia->retornaValor($Metodo,"ContatoCategoriaCod".$Cont),
		"Status"      => true,
		"Tabela"      => "contato_categoria",
		"CampoCod"    => "ContatoCategoriaCod",
		"CampoDesc"   => "CONCAT(': ',ContatoCategoria)",
		"Adicional"   => "style=\"direction:rtl\""),false);		
		
		$R["Contato".$Cont] = $this->FormIntancia->inputTexto(array(
		"Nome"       => "Contato[".$Cont."][]",
		"Identifica" => "Contato ".$Cont,
		"Valor"      => $this->FormIntancia->retornaValor($Metodo,"Contato".$Cont),
		"Largura"    => 23,
		"Max"        => 100),false);	
		
		return $R;	
	}
}
?>