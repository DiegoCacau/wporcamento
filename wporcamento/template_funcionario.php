<?php


function wporc_email_template_funcionario($numero_cliente,
				$chose_A,$marcas,$modelo,$ano_modelo,$codigo,
				$preco,$categoria,$nome,$email,$estado,$celular,
				$telefone,$indicado,$adicional,$cidade){
	$template = '';
	if($numero_cliente<10){
		$numero = "0000".$numero_cliente;
	}
	elseif($numero_cliente<100){
		$numero = "000".$numero_cliente;
	}
	elseif($numero_cliente<1000){
		$numero = "00".$numero_cliente;
	}
	elseif($numero_cliente<10000){
		$numero = "0".$numero_cliente;
	}
	else{
		$numero = $numero_cliente;
	}
	

	
	$template = $template.$numero.'';
	

	$template = $template."<p>Tipo: ".$chose_A."</p>". 
				"<p>Marca: ".$marcas."</p>".
				"<p>Modelo: ".$modelo."</p>".
				"<p>Ano: ".$ano_modelo."</p>".
				"<p>Codigo FIPE: ".$codigo."</p>".
				"<p>Preço FIPE: ".$preco."</p>".
				"<p>Categoria: ".$categoria."</p>".
				"<p>Nome do Segurado: ".$nome."</p>".
				"<p>E-mail: ".$email."</p>".
				"<p>Estado: ".$estado."</p>".
				"<p>Estado: ".$cidade."</p>".
				"<p>Celular: ".$celular."</p>";
	if(strlen($telefone) > 5){
		$template = $template."<p>Telefone: ".$telefone."</p>";
	}
	
	if(strlen($indicado) > 5){
		$template = $template."<p>Indicador: ".$indicado."</p>";
	}
	



	$template = $template.$adicional.'';

	return $template;

}

?>