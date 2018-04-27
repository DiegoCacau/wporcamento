<?php

/* 
Plugin Name: Wp Orçamento
Plugin URI: https://github.com/DiegoCacau/
Description: Cria um formulário para orcamentos  
Version: 0.1
Author: Diego Cacau
Author URI: https://github.com/DiegoCacau/
License: GPLv2 or later
*/

require_once('wporc.php');


register_activation_hook( __FILE__, 'create_wporc_db' );
register_activation_hook( __FILE__, 'create_wporc_preco_carro' );
register_activation_hook( __FILE__, 'create_wporc_preco_caminhao' );
register_activation_hook( __FILE__, 'create_wporc_preco_moto' );
register_activation_hook( __FILE__, 'create_wporc_cidades' );


function create_wporc_db()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'wporc_aditional';

    $sql = "CREATE TABLE $table_name (
      id int(10) NOT NULL AUTO_INCREMENT,
      descricao varchar(500) DEFAULT '' NOT NULL,
      preco varchar(70) DEFAULT '' NOT NULL,
      incluso int(1) DEFAULT 0 NOT NULL,
      UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function create_wporc_preco_carro()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'wporc_valor_carro';

    $sql = "CREATE TABLE $table_name (
      id int(10) NOT NULL AUTO_INCREMENT,
      valor varchar(70) DEFAULT '' NOT NULL,
      UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    for ($i=0; $i < 14; $i++) { 
    	$wpdb->insert( 
	        $table_name, 
	        array( 
	            'valor' => 0
	        ) ,

	        array(
	                '%d'
	                
	        )
	    );
    }
}

function create_wporc_preco_moto()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'wporc_valor_moto';

    $sql = "CREATE TABLE $table_name (
      id int(10) NOT NULL AUTO_INCREMENT,
      valor varchar(70) DEFAULT '' NOT NULL,
      UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    for ($i=0; $i < 17; $i++) { 
    	$wpdb->insert( 
	        $table_name, 
	        array( 
	            'valor' => 0
	        ) ,

	        array(
	                '%d'
	                
	        )
	    );
    }
}

function create_wporc_preco_caminhao()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'wporc_valor_caminhao';

    $sql = "CREATE TABLE $table_name (
      id int(10) NOT NULL AUTO_INCREMENT,
      preco varchar(500) DEFAULT '' NOT NULL,
      valor varchar(70) DEFAULT '' NOT NULL,
      UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    for ($i=0; $i < 18; $i++) { 
    	$wpdb->insert( 
	        $table_name, 
	        array( 
	            'valor' => 0
	        ) ,

	        array(
	                '%d'
	                
	        )
	    );
    }
}


function create_wporc_cidades()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'wporc_cidades';

    $sql = "CREATE TABLE $table_name (
      id int(10) NOT NULL AUTO_INCREMENT,
      cidade varchar(100) DEFAULT '' NOT NULL,
      estado varchar(70) DEFAULT '' NOT NULL,
      UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

}

function add_table_prices(){

	global $wpdb;

    $table_name = $wpdb->prefix . 'wporc_valor_caminhao';
    $table_name_2 = $wpdb->prefix . 'wporc_valor_moto';
    $table_name_3 = $wpdb->prefix . 'wporc_valor_carro';


    for ($i=0; $i < 18; $i++) { 
    	$wpdb->insert( 
	        $table_name, 
	        array( 
	            'valor' => 0
	        ) ,

	        array(
	                '%d'
	                
	        )
	    );
    }


    for ($i=0; $i < 17; $i++) { 
    	$wpdb->insert( 
	        $table_name_2, 
	        array( 
	            'valor' => 0
	        ) ,

	        array(
	                '%d'
	                
	        )
	    );
    }


    for ($i=0; $i < 14; $i++) { 
    	$wpdb->insert( 
	        $table_name_3, 
	        array( 
	            'valor' => 0
	        ) ,

	        array(
	                '%d'
	                
	        )
	    );
    }

}


add_action('admin_init', 'wporc_admin_init');

function wporc_admin_init(){
	add_option("wporc_email","E-mail");
	add_option("wporc_envio","E-mail");
	add_option("wporc_assunto","Assunto");
	add_option("wporc_contador",0);

}


add_action('admin_menu', 'menu_wpoform');

add_shortcode("form_orcamento", "wpo_init");



function menu_wpoform(){
	add_menu_page( 'WP Orçamento', 'WP Orçamento', 'manage_options', 'wporcamento', 'wpo_adm' );
	add_submenu_page('wporcamento','Coberturas Inclusas', 'Coberturas Inclusas', 'manage_options', 'wporcamento_cob_inclu' ,'wporcamento_cob_inclu_page' );      
	add_submenu_page('wporcamento','Coberturas Opcionais', 'Coberturas Opcionais', 'manage_options', 'wporcamento_cob_opc' ,'wporcamento_cob_opc_page' );
	add_submenu_page('wporcamento','Tabela Motos','Tabela Motos', 'manage_options', 'wporcamento_preco_moto' ,'wporcamento_pagina_moto' );
	add_submenu_page('wporcamento','Tabela Carro', 'Tabela Carro', 'manage_options', 'wporcamento_preco_carro' ,'wporcamento_pagina_carro' );
	add_submenu_page('wporcamento','Tabela Caminhão', 'Tabela Caminhão', 'manage_options', 'wporcamento_preco_caminhao' ,'wporcamento_pagina_caminhao' );
}


function prefix_enqueue() 
{       
    // JS
    wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
    wp_enqueue_script('prefix_bootstrap');

    // CSS
    //wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    //wp_enqueue_style('prefix_bootstrap');

    wp_register_style('wporc_css_bootstrap', plugins_url( '/wporcamento/bootstrap.min.css' ));
    wp_enqueue_style('wporc_css_bootstrap');

    wp_register_style('wporc_css_bootstrap_2', plugins_url( '/wporcamento/bootstrap-theme.min.css' ));
    wp_enqueue_style('wporc_css_bootstrap_2');

    wp_register_style('wporc_css', plugins_url( '/wporcamento/wporc.css' ));
    wp_enqueue_style('wporc_css');

    wp_register_script( 'wporc_js', plugins_url( '/wporcamento/wporc.js' ));
    wp_enqueue_script( 'wporc_js' );
    
}

function removeAccents($string) {
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'))), ' '));
}


function wpo_init(){
    wp_register_style('awesome_font', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
    wp_enqueue_style('awesome_font');
    prefix_enqueue();
    global $wpdb;
    $table_name = $wpdb->prefix . 'wporc_aditional';

    if(isset($_POST['wporc_tipo'])) $chose_A = sanitize_text_field($_POST['wporc_tipo']);
    if(isset($_POST['wporc_marca_2'])) $marcas = sanitize_text_field($_POST['wporc_marca_2']);
    if(isset($_POST['wporc_modelo_2'])) $modelo = sanitize_text_field($_POST['wporc_modelo_2']);
    if(isset($_POST['wporc_ano_modelo_2'])) $ano_modelo = sanitize_text_field($_POST['wporc_ano_modelo_2']);
    if(isset($_POST['wporc_categoria_2'])) $categoria = sanitize_text_field($_POST['wporc_categoria_2']);
    if(isset($_POST['wporc_nome_2'])) $nome = sanitize_text_field($_POST['wporc_nome_2']);
	if(isset($_POST['wporc_email_2'])) $email = sanitize_email($_POST['wporc_email_2']);
	if(isset($_POST['wporc_indicado_2'])) $indicado = sanitize_email($_POST['wporc_indicado_2']);
	if(isset($_POST['wporc_telefone_2'])) $telefone = $_POST['wporc_telefone_2'];
	if(isset($_POST['wporc_celular_2'])) $celular = $_POST['wporc_celular_2'];
	if(isset($_POST['wporc_estado_2'])) $estado = sanitize_text_field($_POST['wporc_estado_2']);
	if(isset($_POST['wporc_cidade_2'])) $cidade = sanitize_text_field($_POST['wporc_cidade_2']);
	if(isset($_POST['wporc_codigo_fipe_2'])) $codigo = sanitize_text_field($_POST['wporc_codigo_fipe_2']);
	if(isset($_POST['wporc_valor_2'])) $preco = sanitize_text_field($_POST['wporc_valor_2']);
	if(isset($_POST['wporc_copia_2'])) $enviar_copia = sanitize_text_field($_POST['wporc_copia_2']);
	if(isset($_POST['wporc_adicionais_2'])) $wporc_adicionais_2 = sanitize_text_field($_POST['wporc_adicionais_2']);


    if(isset($_POST["submit_dados_2"])){

		if((isset($chose_A) and isset($marcas) and isset($modelo) and isset($ano_modelo) and isset($categoria) and isset($nome) and isset($email) and isset($celular) and isset($estado) and isset($cidade) )){

			
			
			$to = get_option("wporc_email");
			$subject = '=?UTF-8?B?'.base64_encode(get_option("wporc_assunto")).'?=';

			require_once('template_funcionario.php');
			require_once('template_cliente.php');

			

			$numero_cliente = get_option("wporc_contador");
			update_option("wporc_contador",$numero_cliente+1);

			$opcoes = $wpdb->get_results(("SELECT * FROM  $table_name WHERE incluso = 0")) ;
			
			$adicional = '';
			if(isset($opcoes)){
				$adicionais = explode(" ",$wporc_adicionais_2);
				$adicional = $adicional."<h3 style='text-align: center'>ADICIONAIS</h3><ul>";
				foreach ($opcoes as $opcoes) {
					if(in_array($opcoes->id,$adicionais) ){
						$adicional = $adicional."<li><p>".$opcoes->descricao."</p></li>";
					}
				}
				$adicional = $adicional."</ul>";

				$content = wporc_email_template_funcionario($numero_cliente,
						$chose_A,$marcas,$modelo,$ano_modelo,$codigo,
						$preco,$categoria,$nome,$email,$estado,$celular,
						$telefone,$indicado,$adicional,$cidade);

				$content_cliente = wporc_email_template_cliente($numero_cliente,$chose_A,$marcas,$modelo,$ano_modelo,$codigo,
						$preco,$categoria,$nome,$email,$estado,$celular,
						$telefone,$indicado,$adicional,$cidade);
			}
			else{
				$content = wporc_email_template_funcionario($numero_cliente,
						$chose_A,$marcas,$modelo,$ano_modelo,$codigo,
						$preco,$categoria,$nome,$email,$estado,$celular,
						$telefone,$indicado,"",$cidade);

				$content_cliente = wporc_email_template_cliente($numero_cliente,$chose_A,$marcas,$modelo,$ano_modelo,$codigo,
						$preco,$categoria,$nome,$email,$estado,$celular,
						$telefone,$indicado,"",$cidade);
			}	
			
			

			$envio = get_option("wporc_envio");

			$headers = 'From: '. $envio . "\r\n";
			$headers  .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

			mail($to,$subject,$content,$headers);
			
			if($enviar_copia == "1"){
			
				mail($email,$subject,$content_cliente,$headers);
			}
			
			$msg = '<div align="center" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
			  <img src="'.plugin_dir_url( __FILE__ ).'256x256.png" width="256" height="256" /><br>
			  <h1>Sua simulação foi recebida com sucesso pela nossa equipe!</h1><br>
			  <h3>Em breve um membro do nosso staff entrará em contato com você para confirmar a contratação.</h3>
			  
			</div>';			
			
			return $msg;

		};
	};

	if( isset($_POST["submit_dados"])){

		$table_cidade = $wpdb->prefix . 'wporc_cidades';

		$cidades = $wpdb->get_results(("SELECT * FROM  $table_cidade WHERE estado='".$estado."'")) ;

		
		$fator_soma=0;
		foreach ($cidades as $cidades) {
			

			if (removeAccents($cidades->cidade) == removeAccents($cidade)) {
			    $fator_soma = $cidades->id;
			}
		}

		
		



		$preco_2 = str_replace("R$","",$preco);
		$preco_2 = str_replace(".","",$preco_2);
		$preco_2 = str_replace(",",".",$preco_2);
		$preco_2 = floatval($preco_2);

		

		$valor_final = 0;
		if($chose_A == "Carro"){
			if($preco_2 > 140000){
				$ret = "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center' style='font-size: 25px;font-weight: bold;color:red;'> 
							Valor do veículo superior ao máximo permitido.
						</div>";

				return $ret;	
			}

			$table = $wpdb->prefix . 'wporc_valor_carro';

			

			$i=1;
			
			while ( $i < 15) {
				if($i*10000 >= $preco_2){
					break;
				}
				$i=$i+1;
			}
			$valores = $wpdb->get_results(("SELECT * FROM  $table WHERE id = ".strval($i + intval($fator_soma)*14))) ;
			
			//$valores = $wpdb->get_results(("SELECT * FROM  $table WHERE id=".$i)) ;

			foreach ($valores as $valores) {
				$valor_final = $valores->valor;
			}

			$modelo_2 = strtoupper($modelo);
			
			if(($marcas == "EFFA") or ($marcas == "LIFAN") or
				($marcas == "AUDI") or ($marcas == "HAFEI") or
				($marcas == "JAC") or ($marcas == "CHERY") or
				($marcas == "KIA") or ($marcas == "HYUNDAY") or
				($marcas == "MERCEDES-BENZ") or ($marcas == "CITRÖEN") or
				($marcas == "BMW") or ($marcas == "VOLVO") or
				($marcas == "NISSAN") or ($marcas == "SMART") or
				($marcas == "MINI") or ($marcas == "EFFA") or
				($marcas == "SUZUKI")){
				$valor_final = intval($valor_final) + (0.25*intval($valor_final));
			}
			elseif(($marcas == "HONDA")and( (strpos($modelo_2, 'ACCORD') !== false) or (strpos($modelo_2, 'CRV') !== false) )) {

				$valor_final = intval($valor_final) + (0.25*intval($valor_final));
			}
			elseif(($marcas == "MITSUBISHI")and( (strpos($modelo_2, 'LANCER') !== false) )){
				$valor_final = intval($valor_final) + (0.25*intval($valor_final));
			}
			elseif(($marcas == "PEUGEOT")and( (strpos($modelo_2, '308') !== false) or (strpos($modelo_2, '307') !== false)  or (strpos($modelo_2, '408') !== false)  or (strpos($modelo_2, 'RCZ') !== false) )) {

				$valor_final = intval($valor_final) + (0.25*intval($valor_final));
			}
			elseif(($marcas == "RENAULT")and( (strpos($modelo_2, 'DUSTER') !== false) or (strpos($modelo_2, 'FLUENCE') !== false) )) {

				$valor_final = intval($valor_final) + (0.25*intval($valor_final));
			}
			elseif(($marcas == "TOYOTA")and( (strpos($modelo_2, 'CAMRY') !== false) or (strpos($modelo_2, 'RAV4') !== false) )) {

				$valor_final = intval($valor_final) + (0.25*intval($valor_final));
			}
			elseif(($marcas == "VOLKSWAGEN")and( (strpos($modelo_2, 'JETTA') !== false) or (strpos($modelo_2, 'TUAREG') !== false) or (strpos($modelo_2, 'TIGUAN') !== false) or (strpos($modelo_2, 'BEATLE') !== false)  or (strpos($modelo_2, 'PASSAT') !== false) )) {

				$valor_final = intval($valor_final) + (0.25*intval($valor_final));
			}
			
			
		}
		elseif($chose_A == "Moto"){

			if($preco_2 > 70000){
				$ret = "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center' style='font-size: 25px;font-weight: bold;color:red;'> 
							Valor do veículo superior ao máximo permitido.
						</div>";

				return $ret;	
			}

			$base=5000;
			$table = $wpdb->prefix . 'wporc_valor_moto';
			
			//$valores = $wpdb->get_results(("SELECT * FROM  $table")) ;

			$i=1;
			
			while ( $i < 18) {
				if($base >= $preco_2){
					break;
				}
				else{
					if($base < 30000){
						$base = $base + 3000;
					}
				else{
					$base = $base + 5000;
				}
				}
				$i=$i+1;
			}
			//$valores = $wpdb->get_results(("SELECT * FROM  $table WHERE id=".$i)) ;
			
			
			$valores = $wpdb->get_results(("SELECT * FROM  $table WHERE id = ".strval($i + intval($fator_soma)*17))) ;

			foreach ($valores as $valores) {
				$valor_final = $valores->valor;
			}

		}
		elseif($chose_A == "Caminhão"){
			if($preco_2 > 340000){
				$ret = "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center' style='font-size: 25px;font-weight: bold;color:red;'> 
							Valor do veículo superior ao máximo permitido.
						</div>";

				return $ret;	
			}

			$base=50000;
			$table = $wpdb->prefix . 'wporc_valor_caminhao';
			
			//$valores = $wpdb->get_results(("SELECT * FROM  $table")) ;

			$i=1;
			
			while ( $i < 19) {
				if($base >= $preco_2){
					break;
				}
				else{
					if($base < 100000){
						$base = $base + 10000;
					}
				else{
					$base = $base + 20000;
				}
				}
				$i=$i+1;
			}
			//$valores = $wpdb->get_results(("SELECT * FROM  $table WHERE id=".$i)) ;
			

			$valores = $wpdb->get_results(("SELECT * FROM  $table WHERE id = ".strval($i + intval($fator_soma)*18))) ;

			foreach ($valores as $valores) {
				$valor_final = $valores->valor;
			}

		}

		$valor_final = str_replace(".",",",$valor_final);
		$valor_final_1 = explode(",",$valor_final);
		
		if ((count($valor_final_1)>1) and  (strlen($valor_final_1[1]) > 0) and (strlen($valor_final_1[1]) < 2)){
			$valor_final = $valor_final."0";
		}
		else if((count($valor_final_1)<2) or (strlen($valor_final_1[1]) < 1)){
			$valor_final = $valor_final.",00";
		}


		
		
		

		?>
			<div id='app_1_wporc' class="container">
				<form class = "form-horizontal" method="post" enctype="multipart/form-data">
					<input name="wporc_tipo" id="wporc_tipo"  style="display:none;" value='<?php echo $chose_A;?>'>	
					<input name="wporc_marca_2" id="wporc_marca_2" style="display:none;" value='<?php echo $marcas;?>'>	
					<input name="wporc_modelo_2" id="wporc_modelo_2" style="display:none;" value='<?php echo $modelo;?>'>	
					<input name="wporc_ano_modelo_2" id="wporc_ano_modelo_2" style="display:none;" value='<?php echo $ano_modelo;?>'>
					<input name="wporc_categoria_2" id="wporc_categoria_2" style="display:none;" value='<?php echo $categoria;?>'>	
					<input name="wporc_codigo_fipe_2" id="wporc_codigo_fipe_2" style="display:none;" value='<?php echo $codigo;?>'>	
					<input name="wporc_nome_2" id="wporc_nome_2" style="display:none;" value='<?php echo $nome;?>' >	
					<input name="wporc_email_2" id="wporc_email_2" style="display:none;" value='<?php echo $email;?>' >	
					<input name="wporc_telefone_2" id="wporc_telefone_2" style="display:none;" value='<?php echo $telefone;?>' >	
					<input name="wporc_celular_2" id="wporc_celular_2" style="display:none;" value='<?php echo $celular;?>' >	
					<input name="wporc_estado_2" id="wporc_estado_2" style="display:none;" value='<?php echo $estado;?>' >
					<input name="wporc_cidade_2" id="wporc_cidade_2" style="display:none;" value='<?php echo $cidade;?>' >
					<input name="wporc_indicado_2" id="wporc_indicado_2" style="display:none;" value='<?php echo $indicado;?>' >	
					<input name="wporc_valor_2" id="wporc_valor_2" style="display:none;" value='<?php echo $preco;?>' >	
					<input name="wporc_copia_2" id="wporc_copia_2" style="display:none;" value='<?php echo $enviar_copia;?>' >
					<input name="wporc_adicionais_2" id="wporc_adicionais_2" style="display:none;" value='' >
					<input name="wporc_valor_base_2" id="wporc_valor_base_2" style="display:none;" value='<?php echo $valor_final;?>' >	
					<button id="submit_dados_2" name="submit_dados_2" style="display:none;"></button>	

				</form>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				Veja abaixo os valores para proteger seu veículo <b><?php echo $marcas;?> / <?php echo $modelo;?></b> com valor de mercado <b><?php echo $preco;?></b> 
				</div>
					

				<div class=" wporc_div col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 wporc_tabelas1_2">
						<table class="table table_wporc">
							<thead class="thead-inverse wporc_title_header">
								<tr>
								  <th class="text-center">Plano</th>
								</tr>
							</thead>
							<tbody >
								<tr>
									<td class="text-center wporc_titulo"><h2>Proteção Veicular</h2></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 wporc_tabelas_centro">
						<table class="table table_wporc">
							<thead class="thead-inverse wporc_title_header text-center">
								<tr>
								  <th class="text-center">Resumo</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-center">Agora você já sabe quanto custa proteger seu veículo e ficar tranquilo. Contrate agora mesmo online e sem burocracia.</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 wporc_tabelas1_2">
						<table class="table table_wporc">
							<thead class="thead-inverse wporc_title_header text-center">
								<tr>
								  <th class="text-center">Preço</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-center wporc_titulo">
									Apenas <br>
									<span class="valor_final"><h2><?php echo "R$ " .$valor_final ;?></h2></span>
									<span>/Mês</span> 
									</td>
								</tr>
							</tbody>
						</table>
					</div>


					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wporc_title_header">
						<h2 class="title_bg_2 text-center">Coberturas Inclusas</h2>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped  table-hover table_wporc wporc_tabela_meio">
							<tbody>
								
								<?php
									
									
									$opcoes = $wpdb->get_results(("SELECT * FROM  $table_name WHERE incluso = 1")) ;

									foreach ($opcoes as $opcoes) {
										echo "<tr>";
										echo "<td  class='text-center'>";
										echo	"<h5><strong>".$opcoes->descricao."</strong></h5>";
										echo "</td>";
										echo "<td>";
										echo "<h5>Incluso</h5>";
										echo "</td>";
										echo "<td>";
										echo "<i class='fa fa-check-circle-o'></i>";
										echo "</td>	";
										echo "</tr>";
									}	
								
								?>
								
							</tbody>
						</table>
					</div>
				</div>	

				<div class=" wporc_div col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wporc_title_header">
						<h2 class="title_bg_2 text-center">Coberturas Opcionais</h2>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped  table-hover table_wporc wporc_tabela_meio">
							<thead class="thead-inverse">
								<tr>
								  <th class="text-center">Descrição</th>
								  <th class="text-center">Valor</th>
								  <th class="text-center">Adicionar</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$opcoes = $wpdb->get_results(("SELECT * FROM  $table_name WHERE incluso = 0")) ;
									foreach ($opcoes as $opcoes) {
										$id = $opcoes->id;
										$preco = $opcoes->preco;

										echo "<tr>";
										echo "	<td  class='text-center'>";
										echo 	"<strong>".$opcoes->descricao."</strong>";		
										echo "</td>";
										echo "<td class='text-center'>";
										echo 	"<strong>R$".$preco."</strong>";
										echo "<br>";
										echo "<span>/Mês</span>";
										echo "</td>";
										echo "<td class='text-center'>";
										echo "<a id='wporc_botao_".$id."' class='btn btn-large btn-primary btn-circle btn_clickable'>";
										echo "<i class='fa fa-plus-circle'></i>";
										echo "<input id=input_".$id." style='display:none;' value = ".$preco.">";			
										echo "</a>";
										echo "</td>";
										echo "</tr>";
									}
										
								?>
							</tbody>
						</table>
					</div>
				</div>

				<div class=" wporc_div col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 wporc_tabelas1_2">
						<table class="table table_wporc">
							<thead class="thead-inverse wporc_title_header">
								<tr>
								  <th class="text-center">Plano</th>
								</tr>
							</thead>
							<tbody >
								<tr>
									<td class="text-center wporc_titulo"><h2>Constratação On-Line</h2></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 wporc_tabelas_centro">
						<table class="table table_wporc">
							<thead class="thead-inverse wporc_title_header text-center">
								<tr>
								  <th class="text-center">Resumo</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td  class="text-center">Agora você já sabe quanto custa proteger seu veículo e ficar tranquilo. Contrate agora mesmo online e sem burocracia.</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 wporc_tabelas1_2">
						<table class="table table_wporc">
							<thead class="thead-inverse wporc_title_header text-center">
								<tr>
								  <th class="text-center">Preço</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-center wporc_titulo">
									Apenas <br>
									<span class="valor_final"><h2><?php echo "R$" .$valor_final ;?></h2></span>
									<span>/Mês</span>
									</td>
								</tr>
							
								<tr>
									<td class="text-center">
									<button class="btn wporc_btn_enviar" id="enviar_dados_2">Simular Proteção</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				
					

			</div>

		<?php 
	}
	else{

		?>
			<div id='app_1_wporc' class="container">
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="wporc_chose_A" class="control-label">Tipo do veículo*</label>
							<select  id='wporc_chose_A' name='wporc_chose_A' class="col-6 form-control">
								<option value="Selecione">Selecione</option>
								<option value="carros">Carro</option>
								<option value="motos">Moto</option>
								<option value="caminhoes">Caminhão</option>
							</select>
						</div>

						<div class="form-group col-sm-6">
							<label for="wporc_marcas" class="control-label">Marca*</label>
							<select id="wporc_marcas" name="wporc_marcas" class="col-6 form-control">
							</select>
						</div>
					</div>
				
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="wporc_marcas" class="control-label">Modelo*</label>		
							<select id="wporc_modelo" name="wporc_modelo" class="col-sm-4 form-control">
							</select>
						</div>

						<div class="form-group col-sm-6">
							<label for="wporc_ano_modelo" class="control-label">Ano Modelo*</label>		
							<select id="wporc_ano_modelo" name="wporc_ano_modelo" class="col-sm-4 form-control">
							</select>
						</div>	
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="wporc_categoria" class="control-label">Categoria*</label>		
							<select id="wporc_categoria" name="wporc_categoria" class="col-sm-4 form-control">
								<option value="Selecione">Selecione</option>
								<option value="particular">Particular</option>
								<option value="aluguel">Aluguel</option>
							</select>
						</div>	

						<div class="form-group col-sm-6">
							<label for="wporc_codigo_fipe" class="control-label">Codigo Fipe*</label>		
							<input id="wporc_codigo_fipe"  type="text" name="wporc_codigo_fipe" class="col-sm-4 form-control" disabled>
							
						</div>	
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="wporc_nome" class="control-label">Nome*</label>		
							<input id="wporc_nome"  type="text" name="wporc_nome" class="col-sm-4 form-control">
							
						</div>

						<div class="form-group col-sm-6">
							<label for="wporc_email" class="control-label">E-Mail*</label>	
							<div class="input-group mb-2 mr-sm-2 mb-sm-0">
								<input type="email" id="wporc_email" name="email" class="col-sm-4 form-control">
			    				<div class="input-group-addon">
			    					<i class="fa fa-envelope"></i>
			    				</div>			
							</div>	
							
						</div>	
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="wporc_telefone" class="control-label">Telefone</label>
							<div class="input-group mb-2 mr-sm-2 mb-sm-0">
			    				
								<input id="wporc_telefone"  type="text" name="wporc_telefone" class="col-sm-4 form-control" maxlength="11">
								<div class="input-group-addon">
									<i class="fa fa-phone"></i>
								</div>		
							</div>
						</div>

						<div class="form-group col-sm-6">
							<label for="wporc_celular" class="control-label">Celular*</label>		
							<div class="input-group mb-2 mr-sm-2 mb-sm-0">
			    				
								<input  id="wporc_celular" name="wporc_celular" class="col-sm-4 form-control" maxlength="11">
								<div class="input-group-addon">
									<i class="fa fa-mobile"></i>
								</div>
							</div>		
						</div>	
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="wporc_estado" class="control-label">Estado*</label>		
							<select  id='wporc_estado' name='wporc_estado' class="col-6 form-control">
								<option value="">UF</option>
								<option value="AC">AC</option>
								<option value="AL">AL</option>
								<option value="AM">AM</option>
								<option value="AP">AP</option>
								<option value="BA">BA</option>
								<option value="CE">CE</option>
								<option value="DF">DF</option>
								<option value="ES">ES</option>
								<option value="GO">GO</option>
								<option value="MA">MA</option>
								<option value="MG">MG</option>
								<option value="MS">MS</option>
								<option value="MT">MT</option>
								<option value="PA">PA</option>
								<option value="PB">PB</option>
								<option value="PE">PE</option>
								<option value="PI">PI</option>
								<option value="PR">PR</option>
								<option value="RJ">RJ</option>
								<option value="RN">RN</option>
								<option value="RO">RO</option>
								<option value="RR">RR</option>
								<option value="RS">RS</option>
								<option value="SC">SC</option>
								<option value="SE">SE</option>
								<option value="SP">SP</option>
								<option value="TO">TO</option>
							</select>
						</div>

						<div class="form-group col-sm-6">
							<label for="wporc_cidade" class="control-label">Cidade*</label>
							<input  id="wporc_cidade"  name="wporc_cidade" class=" form-control">	
						</div>

						<div class="form-group col-sm-6">
							<label for="wporc_indicado" class="control-label">Foi indicado por alguém?</label>
							<div class="input-group mb-2 mr-sm-2 mb-sm-0">
			    							
								<input  id="wporc_indicado"  type="email" name="wporc_indicado" class=" form-control">	
								<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
							</div>		
							
						</div>	
					</div>

					<div class="row form-group col-sm-12">
						<div class="form-check mb-2 mr-sm-2 mb-sm-0">
							<label class="form-check-label">
								<input name="wporc_copia"class="form-check-input" type="checkbox" style="display:none;"> 
									<i class="fa fa-square-o wporc_select" aria-hidden="true"></i>Enviar uma cópia para o meu email
							</label>
						</div>
					</div>
				
					<form class = "form-horizontal" method="post" enctype="multipart/form-data">
						<input name="wporc_tipo" id="wporc_tipo" style="display:none;">	
						<input name="wporc_marca_2" id="wporc_marca_2" style="display:none;">	
						<input name="wporc_modelo_2" id="wporc_modelo_2" style="display:none;">	
						<input name="wporc_ano_modelo_2" id="wporc_ano_modelo_2" style="display:none;">
						<input name="wporc_categoria_2" id="wporc_categoria_2" style="display:none;">	
						<input name="wporc_codigo_fipe_2" id="wporc_codigo_fipe_2" style="display:none;">	
						<input name="wporc_nome_2" id="wporc_nome_2" style="display:none;">	
						<input name="wporc_email_2" id="wporc_email_2" style="display:none;">	
						<input name="wporc_telefone_2" id="wporc_telefone_2" style="display:none;">	
						<input name="wporc_celular_2" id="wporc_celular_2" style="display:none;">	
						<input name="wporc_estado_2" id="wporc_estado_2" style="display:none;">
						<input name="wporc_indicado_2" id="wporc_indicado_2" style="display:none;">	
						<input name="wporc_valor_2" id="wporc_valor_2" style="display:none;">	
						<input name="wporc_copia_2" id="wporc_copia_2" style="display:none;">	
						<input name="wporc_cidade_2" id="wporc_cidade_2" style="display:none;">	
						<button id="submit_dados" name="submit_dados" style="display:none;"></button>	

					</form>	

					<div class="row form-group col-sm-12 text-center">
						<button class="btn wporc_btn_enviar" id="enviar_dados">Simular Proteção</button>
					</div>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 wporc_tabelas1_3">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table_wporc text-center">
							<h4 class="titulo_tabela_wporc">Resumo</h4>
					</div>
					<table class="table table-hover">
						<tbody>
							<tr>
								<th>Marca</th>
								<td id="wporc_table_marca">
								</td>
							</tr>
							<tr>
								<th>Modelo</th>
								<td id="wporc_table_modelo">
								</td>
							</tr>
							<tr>
								<th>Código FIPE</th>
								<td id="wporc_table_codigo">
								</td>
							</tr>
							<tr>
								<th>Valor do Veículo</th>
								<td id="wporc_table_valor">
								</td>
							</tr>
						</tbody>
					</table>	
				</div>
			</div>


		<?php
	}
}


function wpo_adm(){
	prefix_enqueue();

	if(isset($_POST['wporc_assunto'])) $assunto = sanitize_text_field($_POST['wporc_assunto']);
	if(isset($_POST['wporc_email'])) $email = sanitize_email($_POST['wporc_email']);
	if(isset($_POST['wporc_envio'])) $envio = sanitize_email($_POST['wporc_envio']);


	if (isset($assunto) or isset($email) or isset($envio)) {
		update_option("wporc_email",$email);
		update_option("wporc_assunto",$assunto);
		update_option("wporc_envio",$envio);
	}

	
	$assunto = get_option("wporc_assunto");
	$email = get_option("wporc_email");
	$envio = get_option("wporc_envio");



	if(isset($_POST['wporc_estado'])) $estado = sanitize_text_field($_POST['wporc_estado']);
	if(isset($_POST['wporc_cidade'])) $cidade = sanitize_text_field($_POST['wporc_cidade']);

	if (isset($cidade) and isset($estado) and ($estado != "")) {
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wporc_cidades';

		$wpdb->insert( 
	        $table_name, 
	        array( 
	            'cidade' => $cidade,
	            'estado' => $estado
	        ) ,

	        array(
	                '%s',
	                '%s'
	                
	        )
	    );

	    add_table_prices();
	    
	}

?>
	<div class="container">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="row  col-lg-12 col-md-12 col-sm-12 col-xs-12  text-center">
				<h2 style="text-align:center;">Dados de e-mail.</h2>
			</div>
			<form class = "form-horizontal" method="post" enctype="multipart/form-data">

				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label for="formGroupExampleInput">Email para onde serão enviados os emails de orçamento</label>
					<input type="text" class="form-control" id="wporc_email" name="wporc_email" value='<?php echo $email;?>'>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label for="formGroupExampleInput">Email que constará nos emails enviados</label>
					<input type="text" class="form-control" id="wporc_envio" name="wporc_envio" value='<?php echo $envio;?>'>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label for="formGroupExampleInput2">Assunto que constará nos emails</label>
					<input type="text" class="form-control" id="wporc_assunto" name="wporc_assunto" value='<?php echo $assunto;?>'>
				</div>
				<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<button>Salvar Dados</button>
				</div>
			</form>	
		</div>
	


		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="row  col-lg-12 col-md-12 col-sm-12 col-xs-12  text-center">
				<h2 style="text-align:center;">Adicionar novo local de cobertura.</h2>
			</div>

			<form class = "form-horizontal" method="post" enctype="multipart/form-data">
				<div class="row  col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<div class="form-group ">
						<label for="wporc_estado" class="control-label">Estado*</label>		
						<select  id='wporc_estado' name='wporc_estado' class=" col-lg-8 col-md-8 col-sm-8 form-control">
							<option value="">UF</option>
							<option value="AC">AC</option>
							<option value="AL">AL</option>
							<option value="AM">AM</option>
							<option value="AP">AP</option>
							<option value="BA">BA</option>
							<option value="CE">CE</option>
							<option value="DF">DF</option>
							<option value="ES">ES</option>
							<option value="GO">GO</option>
							<option value="MA">MA</option>
							<option value="MG">MG</option>
							<option value="MS">MS</option>
							<option value="MT">MT</option>
							<option value="PA">PA</option>
							<option value="PB">PB</option>
							<option value="PE">PE</option>
							<option value="PI">PI</option>
							<option value="PR">PR</option>
							<option value="RJ">RJ</option>
							<option value="RN">RN</option>
							<option value="RO">RO</option>
							<option value="RR">RR</option>
							<option value="RS">RS</option>
							<option value="SC">SC</option>
							<option value="SE">SE</option>
							<option value="SP">SP</option>
							<option value="TO">TO</option>
						</select>
					</div>

				</div>
				<div class="row col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<label for="wporc_cidade">Cidade</label>
					<input type="text" class="form-control" id="wporc_cidade" name="wporc_cidade">
				</div>

				<div class="row col-lg-8 col-md-8 col-sm-8 col-xs-12" style="margin-top:20px;">
					<button>Salvar Cidade</button>
				</div>
			</form>
		</div>



	</div>
	
<?php	

}



function wporcamento_cob_inclu_page(){
	prefix_enqueue();
	global $wpdb;
	$table_name = $wpdb->prefix . 'wporc_aditional';

	if(isset($_POST['wporc_cob_incl'])) $wporc_cob_incl = sanitize_text_field($_POST['wporc_cob_incl']);

	if(isset($_POST['wporc_delete'])) $wporc_delete = sanitize_text_field($_POST['wporc_delete']);

	if (isset($wporc_cob_incl)) {

	    $wpdb->insert( 
	        $table_name, 
	        array( 
	            'descricao' => $wporc_cob_incl, 
	            'preco' => "0",
	            'incluso' => 1
	        ) ,

	        array(
	                '%s',
	                '%s',
	                '%d'
	                
	        )
	    );
	}
	elseif (isset($wporc_delete)){
		$wpdb->query( "DELETE FROM $table_name WHERE id = '".$wporc_delete."'");
	}

	
	$opcoes = $wpdb->get_results(("SELECT * FROM  $table_name WHERE incluso = 1")) ;
	


	?>
	<div class="container">
		<form class = "form-horizontal" method="post" enctype="multipart/form-data">

			<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label for="formGroupExampleInput">Descrição da cobertura inclusa</label>
				<input type="text" class="form-control" id="wporc_cob_incl" name="wporc_cob_incl">
			</div>
			<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<button>Salvar Dados</button>
			</div>
		</form>	


		<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2">
			<table class="table table_wporc">
				<thead class="thead-inverse wporc_title_header text-center">
					<tr>
					  <th class="text-center">Opção Inclusa</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($opcoes as $opcoes) {
						echo "<tr id='wporc_".$opcoes->id."' class='text-center tabela_clicavel'>";
						echo 	"<td >";
						echo 		$opcoes->descricao;
						echo 	"</td>";
						echo "</tr>";
					}	
					?>
				</tbody>
			</table>
		</div>
		<form class = "form-horizontal" method="post" enctype="multipart/form-data">

			<input type="text" class="form-control wporc_delete" name="wporc_delete" style="display:none;">

			<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<button>Deletear Selecionado</button>
			</div>
		</form>	
	</div>
	
<?php	

}

function wporcamento_cob_opc_page(){
	prefix_enqueue();
	global $wpdb;
	$table_name = $wpdb->prefix . 'wporc_aditional';
	
	if(isset($_POST['wporc_cob_opc'])) $wporc_cob_opc = sanitize_text_field($_POST['wporc_cob_opc']);

	if(isset($_POST['wporc_cob_preco'])) $wporc_cob_preco = sanitize_text_field($_POST['wporc_cob_preco']);

	if(isset($_POST['wporc_delete'])) $wporc_delete = sanitize_text_field($_POST['wporc_delete']);

	if (isset($wporc_cob_opc) and isset($wporc_cob_preco) and (strlen($wporc_cob_preco) > 1)) {

	    $wpdb->insert( 
	        $table_name, 
	        array( 
	            'descricao' => $wporc_cob_opc, 
	            'preco' => (string)$wporc_cob_preco,
	            'incluso' => 0
	        ) ,

	        array(
	                '%s',
	                '%s',
	                '%d'
	                
	        )
	    );
	}
	elseif (isset($wporc_delete)){
		$wpdb->query( "DELETE FROM $table_name WHERE id = '".$wporc_delete."'");
	}

	
	$opcoes = $wpdb->get_results(("SELECT * FROM  $table_name WHERE incluso = 0")) ;
	

?>
	<div class="container">
		<form class = "form-horizontal" method="post" enctype="multipart/form-data">

			<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label for="wporc_cob_opc">Descrição da cobertura opcional</label>
				<input type="text" class="form-control" id="wporc_cob_opc" name="wporc_cob_opc">
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<label for="wporc_cob_preco">Preço</label>
				<input type="text" class="form-control" id="wporc_cob_preco" name="wporc_cob_preco">
			</div>
			<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<button>Salvar Dados</button>
			</div>
		</form>	


		<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2">
			<table class="table table_wporc">
				<thead class="thead-inverse wporc_title_header text-center">
					<tr>
					  <th class="text-center">Cobertura Opcional</th>
					  <th class="text-center">Preço</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($opcoes as $opcoes) {
						echo "<tr class='tabela_clicavel' id='wporc_".$opcoes->id."'>";
						echo 	"<td class='text-center tabela_clicavel'>";
						echo 		$opcoes->descricao;
						echo 	"</td>";
						echo 	"<td class='text-center tabela_clicavel'>";
						echo 		$opcoes->preco;
						echo 	"</td>";
						echo "</tr>";
					}	
					?>
				</tbody>
			</table>
		</div>
		<form class = "form-horizontal" method="post" enctype="multipart/form-data">

			<input type="text" class="form-control wporc_delete" name="wporc_delete" style="display:none;">

			<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<button>Deletear Selecionado</button>
			</div>
		</form>
	</div>

<?php	
}

function wporcamento_pagina_carro(){
	prefix_enqueue();
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'wporc_valor_carro';
	$table_name_2 = $wpdb->prefix . 'wporc_cidades';


	$cidades = $wpdb->get_results(("SELECT * FROM  $table_name_2")) ;

	$wporc_cidade = 0;
	$wporc_id_cidade = 0;
	$wporc_nome_cidade = "";
	if(isset($_POST['wporc_cidade'])) $wporc_cidade_2 = sanitize_text_field($_POST["wporc_cidade"]);
	if(isset($_POST['wporc_id_cidade'])) $wporc_id_cidade = sanitize_text_field($_POST["wporc_id_cidade"]);
	
	if(isset($wporc_cidade_2)){
		$wporc_cidade = $wporc_cidade_2;

		//$cidades2 = $wpdb->get_results(("SELECT * FROM  $table_name WHERE id >= '".strval(intval($wporc_cidade_2)*15)."' AND id <= '".strval(intval($wporc_cidade_2)*15 + 15)."'"));
	}
	

	for ($i=1; $i < 15; $i++) { 
		$strin = "wporc_carro_".$i;
		if(isset($_POST[$strin])) 
		{
			$wporc_valor = sanitize_text_field($_POST[$strin]);
			
		}


		if(isset($wporc_valor)){
			
			$matches2 = array('id' => ($i + (intval($wporc_id_cidade)*14)));
	        $data2 = array('valor' => $wporc_valor);
	        $wpdb->update( $table_name, $data2, $matches2);
		}
	}


	$opcoes = $wpdb->get_results(("SELECT * FROM  $table_name WHERE id > '".strval(intval($wporc_cidade)*14)."' AND id <= '".strval(intval($wporc_cidade)*15 + 14)."'")) ;
	$valores = array();
	
	foreach ($opcoes as $opcoes) {
		array_push($valores, $opcoes->valor);
	}

	$options="";
	foreach ($cidades as $cidades) {
		$id = $cidades->id;
		$cidade = $cidades->cidade;
		$options = $options . "<option class='".$cidades->estado." cidades' value='".$id."''>".$cidade."</option>";

		if(($id) == strval($wporc_cidade)){
			$wporc_nome_cidade = $cidade;
		}

	}




	?>

			<div class="container" style="background-color:white;">
				<form class = "form-horizontal" method="post" enctype="multipart/form-data">
					
					<div class="form-group col-sm-6">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 style="text-align:center;">Selecione a cidade.</h2>
						</div>
						<div class="row">
							<div class="form-group col-sm-8">
								<label for="wporc_estado_2" class="control-label">Estado</label>		
								<select  id='wporc_estado_2' name='wporc_estado_2' class="col-12 form-control">
									<option value="">UF</option>
									<option value="AC">AC</option>
									<option value="AL">AL</option>
									<option value="AM">AM</option>
									<option value="AP">AP</option>
									<option value="BA">BA</option>
									<option value="CE">CE</option>
									<option value="DF">DF</option>
									<option value="ES">ES</option>
									<option value="GO">GO</option>
									<option value="MA">MA</option>
									<option value="MG">MG</option>
									<option value="MS">MS</option>
									<option value="MT">MT</option>
									<option value="PA">PA</option>
									<option value="PB">PB</option>
									<option value="PE">PE</option>
									<option value="PI">PI</option>
									<option value="PR">PR</option>
									<option value="RJ">RJ</option>
									<option value="RN">RN</option>
									<option value="RO">RO</option>
									<option value="RR">RR</option>
									<option value="RS">RS</option>
									<option value="SC">SC</option>
									<option value="SE">SE</option>
									<option value="SP">SP</option>
									<option value="TO">TO</option>
								</select>
							</div>

							<div class="form-group col-sm-8">
								<label for="wporc_cidade" class="control-label">Cidade*</label>		
								<select  id='wporc_cidade' name='wporc_cidade' class="col-12 form-control">
									<option class="nop" value=""></option>
									<?php

										echo $options;


									?>
									
								</select>
							</div>
						<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<button>Selecionar Cidade</button>
						</div>	
						</div>
					</div>
				</form>	

				<form class = "form-horizontal" method="post" enctype="multipart/form-data">
					<input style="display:none;" id="wporc_id_cidade" name="wporc_id_cidade" value='<?php echo $wporc_cidade;?>'>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div>
						<?php
							if(strlen($wporc_nome_cidade) < 3){
								echo '<h2 style="text-align:center;">Valores da Tabela Base.</h2>';
							}
							else{
								echo '<h2 style="text-align:center;">Valores para a cidade '.$wporc_nome_cidade .'.</h2>';
							}
						
						?>
					</div>	
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_1" >R$ 0 à R$10.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_1" name="wporc_carro_1" value='<?php echo $valores[0];?>'>
					</div>

					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_2">R$10.001,00 à R$20.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_2" name="wporc_carro_2" value='<?php echo $valores[1];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_3">R$20.001,00 à R$30.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_3" name="wporc_carro_3" value='<?php echo $valores[2];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_4">R$30.001,00 à R$40.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_4" name="wporc_carro_4" value='<?php echo $valores[3];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_5">R$40.001,00 à R$50.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_5" name="wporc_carro_5" value='<?php echo $valores[4];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_6">R$50.001,00 à R$60.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_6" name="wporc_carro_6" value='<?php echo $valores[5];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_7">R$60.001,00 à R$70.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_7" name="wporc_carro_7" value='<?php echo $valores[6];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_8">R$70.001,00 à R$80.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_8" name="wporc_carro_8" value='<?php echo $valores[7];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_9">R$80.001,00 à R$90.000,00</label >
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_9" name="wporc_carro_9" value='<?php echo $valores[8];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_10">R$90.001,00 à R$100.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_10" name="wporc_carro_10" value='<?php echo $valores[9];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_11">R$100.001,00 à R$110.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_11" name="wporc_carro_11" value='<?php echo $valores[10];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_12">R$110.001,00 à R$120.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_12" name="wporc_carro_12" value='<?php echo $valores[11];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_13">R$120.001,00 à R$130.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_13" name="wporc_carro_13" value='<?php echo $valores[12];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_14">R$130.001,00 à R$140.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_14" name="wporc_carro_14" value='<?php echo $valores[13];?>'>
					</div>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right text-center">
							<button class="btn  text-center">Atualizar Valores</button>
						</div>
					</div>


				</form>
				
			</div>


	<?php
}

function wporcamento_pagina_moto(){

	prefix_enqueue();
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'wporc_valor_moto';
	$table_name_2 = $wpdb->prefix . 'wporc_cidades';


	$cidades = $wpdb->get_results(("SELECT * FROM  $table_name_2")) ;

	$wporc_cidade = 0;
	$wporc_id_cidade = 0;
	$wporc_nome_cidade = "";
	if(isset($_POST['wporc_cidade'])) $wporc_cidade_2 = sanitize_text_field($_POST["wporc_cidade"]);
	if(isset($_POST['wporc_id_cidade'])) $wporc_id_cidade = sanitize_text_field($_POST["wporc_id_cidade"]);
	
	if(isset($wporc_cidade_2)){
		$wporc_cidade = $wporc_cidade_2;
	}
	

	for ($i=1; $i < 18; $i++) { 
		$strin = "wporc_carro_".$i;
		if(isset($_POST[$strin])) 
		{
			$wporc_valor = sanitize_text_field($_POST[$strin]);
			
		}


		if(isset($wporc_valor)){
			
			$matches2 = array('id' => ($i + (intval($wporc_id_cidade)*17)));
	        $data2 = array('valor' => $wporc_valor);
	        $wpdb->update( $table_name, $data2, $matches2);
		}
	}


	$opcoes = $wpdb->get_results(("SELECT * FROM  $table_name WHERE id > '".strval(intval($wporc_cidade)*17)."' AND id <= '".strval(intval($wporc_cidade)*17 + 18)."'")) ;
	$valores = array();
	
	foreach ($opcoes as $opcoes) {
		array_push($valores, $opcoes->valor);
	}

	$options="";
	foreach ($cidades as $cidades) {
		$id = $cidades->id;
		$cidade = $cidades->cidade;
		$options = $options . "<option class='".$cidades->estado." cidades' value='".$id."''>".$cidade."</option>";

		if(($id) == strval($wporc_cidade)){
			$wporc_nome_cidade = $cidade;
		}

	}

	

	?>

			<div class="container" style="background-color:white;">
				<form class = "form-horizontal" method="post" enctype="multipart/form-data">
					
					<div class="form-group col-sm-6">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 style="text-align:center;">Selecione a cidade.</h2>
						</div>
						<div class="row">
							<div class="form-group col-sm-8">
								<label for="wporc_estado_2" class="control-label">Estado</label>		
								<select  id='wporc_estado_2' name='wporc_estado_2' class="col-12 form-control">
									<option value="">UF</option>
									<option value="AC">AC</option>
									<option value="AL">AL</option>
									<option value="AM">AM</option>
									<option value="AP">AP</option>
									<option value="BA">BA</option>
									<option value="CE">CE</option>
									<option value="DF">DF</option>
									<option value="ES">ES</option>
									<option value="GO">GO</option>
									<option value="MA">MA</option>
									<option value="MG">MG</option>
									<option value="MS">MS</option>
									<option value="MT">MT</option>
									<option value="PA">PA</option>
									<option value="PB">PB</option>
									<option value="PE">PE</option>
									<option value="PI">PI</option>
									<option value="PR">PR</option>
									<option value="RJ">RJ</option>
									<option value="RN">RN</option>
									<option value="RO">RO</option>
									<option value="RR">RR</option>
									<option value="RS">RS</option>
									<option value="SC">SC</option>
									<option value="SE">SE</option>
									<option value="SP">SP</option>
									<option value="TO">TO</option>
								</select>
							</div>

							<div class="form-group col-sm-8">
								<label for="wporc_cidade" class="control-label">Cidade*</label>		
								<select  id='wporc_cidade' name='wporc_cidade' class="col-12 form-control">
									<option class="nop" value=""></option>
									<?php

										echo $options;


									?>
									
								</select>
							</div>
						<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<button>Selecionar Cidade</button>
						</div>	
						</div>
					</div>
				</form>	



				<form class = "form-horizontal" method="post" enctype="multipart/form-data">
					<input style="display:none;" id="wporc_id_cidade" name="wporc_id_cidade" value='<?php echo $wporc_cidade;?>'>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div>
						<?php
							if(strlen($wporc_nome_cidade) < 3){
								echo '<h2 style="text-align:center;">Valores da Tabela Base.</h2>';
							}
							else{
								echo '<h2 style="text-align:center;">Valores para a cidade '.$wporc_nome_cidade .'.</h2>';
							}
						
						?>
					</div>	

						
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_1" >R$ 0 à R$5.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_1" name="wporc_carro_1" value='<?php echo $valores[0];?>'>
					</div>

					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_2">R$5.001,00 à R$8.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_2" name="wporc_carro_2" value='<?php echo $valores[1];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_3">R$8.001,00 à R$11.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_3" name="wporc_carro_3" value='<?php echo $valores[2];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_4">R$11.001,00 à R$14.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_4" name="wporc_carro_4" value='<?php echo $valores[3];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_5">R$14.001,00 à R$17.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_5" name="wporc_carro_5" value='<?php echo $valores[4];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_6">R$17.001,00 à R$21.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_6" name="wporc_carro_6" value='<?php echo $valores[5];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_7">R$21.001,00 à R$24.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_7" name="wporc_carro_7" value='<?php echo $valores[6];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_8">R$24.001,00 à R$27.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_8" name="wporc_carro_8" value='<?php echo $valores[7];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_9">R$27.001,00 à R$30.000,00</label >
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_9" name="wporc_carro_9" value='<?php echo $valores[8];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_10">R$30.001,00 à R$35.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_10" name="wporc_carro_10" value='<?php echo $valores[9];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_11">R$35.001,00 à R$40.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_11" name="wporc_carro_11" value='<?php echo $valores[10];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_12">R$40.001,00 à R$45.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_12" name="wporc_carro_12" value='<?php echo $valores[11];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_13">R$45.001,00 à R$50.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_13" name="wporc_carro_13" value='<?php echo $valores[12];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_14">R$50.001,00 à R$55.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_14" name="wporc_carro_14" value='<?php echo $valores[13];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_15">R$55.001,00 à R$60.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_15" name="wporc_carro_15" value='<?php echo $valores[14];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_16">R$60.001,00 à R$65.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_16" name="wporc_carro_16" value='<?php echo $valores[15];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_17">R$65.001,00 à R$70.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_17" name="wporc_carro_17" value='<?php echo $valores[16];?>'>
					</div>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right text-center">
							<button class="btn  text-center">Atualizar Valores</button>
						</div>
					</div>


				</form>
				
			</div>


	<?php


}

function wporcamento_pagina_caminhao(){

	prefix_enqueue();
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'wporc_valor_caminhao';
	$table_name_2 = $wpdb->prefix . 'wporc_cidades';


	$cidades = $wpdb->get_results(("SELECT * FROM  $table_name_2")) ;

	$wporc_cidade = 0;
	$wporc_id_cidade = 0;
	$wporc_nome_cidade = "";
	if(isset($_POST['wporc_cidade'])) $wporc_cidade_2 = sanitize_text_field($_POST["wporc_cidade"]);
	if(isset($_POST['wporc_id_cidade'])) $wporc_id_cidade = sanitize_text_field($_POST["wporc_id_cidade"]);
	
	if(isset($wporc_cidade_2)){
		$wporc_cidade = $wporc_cidade_2;
	}
	

	for ($i=1; $i < 19; $i++) { 
		$strin = "wporc_carro_".$i;
		if(isset($_POST[$strin])) 
		{
			$wporc_valor = sanitize_text_field($_POST[$strin]);
			
		}


		if(isset($wporc_valor)){
			
			$matches2 = array('id' => ($i + (intval($wporc_id_cidade)*18)));
	        $data2 = array('valor' => $wporc_valor);
	        $wpdb->update( $table_name, $data2, $matches2);
		}
	}


	$opcoes = $wpdb->get_results(("SELECT * FROM  $table_name WHERE id > '".strval(intval($wporc_cidade)*18)."' AND id <= '".strval(intval($wporc_cidade)*19 + 18)."'")) ;
	$valores = array();
	
	foreach ($opcoes as $opcoes) {
		array_push($valores, $opcoes->valor);
	}

	$options="";
	foreach ($cidades as $cidades) {
		$id = $cidades->id;
		$cidade = $cidades->cidade;
		$options = $options . "<option class='".$cidades->estado." cidades' value='".$id."''>".$cidade."</option>";

		if(($id) == strval($wporc_cidade)){
			$wporc_nome_cidade = $cidade;
		}

	}

	

	?>

			<div class="container" style="background-color:white;">
				<form class = "form-horizontal" method="post" enctype="multipart/form-data">
					
					<div class="form-group col-sm-6">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 style="text-align:center;">Selecione a cidade.</h2>
						</div>
						<div class="row">
							<div class="form-group col-sm-8">
								<label for="wporc_estado_2" class="control-label">Estado</label>		
								<select  id='wporc_estado_2' name='wporc_estado_2' class="col-12 form-control">
									<option value="">UF</option>
									<option value="AC">AC</option>
									<option value="AL">AL</option>
									<option value="AM">AM</option>
									<option value="AP">AP</option>
									<option value="BA">BA</option>
									<option value="CE">CE</option>
									<option value="DF">DF</option>
									<option value="ES">ES</option>
									<option value="GO">GO</option>
									<option value="MA">MA</option>
									<option value="MG">MG</option>
									<option value="MS">MS</option>
									<option value="MT">MT</option>
									<option value="PA">PA</option>
									<option value="PB">PB</option>
									<option value="PE">PE</option>
									<option value="PI">PI</option>
									<option value="PR">PR</option>
									<option value="RJ">RJ</option>
									<option value="RN">RN</option>
									<option value="RO">RO</option>
									<option value="RR">RR</option>
									<option value="RS">RS</option>
									<option value="SC">SC</option>
									<option value="SE">SE</option>
									<option value="SP">SP</option>
									<option value="TO">TO</option>
								</select>
							</div>

							<div class="form-group col-sm-8">
								<label for="wporc_cidade" class="control-label">Cidade*</label>		
								<select  id='wporc_cidade' name='wporc_cidade' class="col-12 form-control">
									<option class="nop" value=""></option>
									<?php

										echo $options;


									?>
									
								</select>
							</div>
						<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<button>Selecionar Cidade</button>
						</div>	
						</div>
					</div>
				</form>	


				<form class = "form-horizontal" method="post" enctype="multipart/form-data">
					<input style="display:none;" id="wporc_id_cidade" name="wporc_id_cidade" value='<?php echo $wporc_cidade;?>'>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div>
						<?php
							if(strlen($wporc_nome_cidade) < 3){
								echo '<h2 style="text-align:center;">Valores da Tabela Base.</h2>';
							}
							else{
								echo '<h2 style="text-align:center;">Valores para a cidade '.$wporc_nome_cidade .'.</h2>';
							}
						
						?>
					</div>	

					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_1" >R$ 0 à R$50.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_1" name="wporc_carro_1" value='<?php echo $valores[0];?>'>
					</div>

					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_2">R$50.001,00 à R$60.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_2" name="wporc_carro_2" value='<?php echo $valores[1];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_3">R$60.001,00 à R$70.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_3" name="wporc_carro_3" value='<?php echo $valores[2];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_4">R$70.001,00 à R$80.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_4" name="wporc_carro_4" value='<?php echo $valores[3];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_5">R$80.001,00 à R$90.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_5" name="wporc_carro_5" value='<?php echo $valores[4];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_6">R$90.001,00 à R$100.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_6" name="wporc_carro_6" value='<?php echo $valores[5];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_7">R$100.001,00 à R$120.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_7" name="wporc_carro_7" value='<?php echo $valores[6];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_8">R$120.001,00 à R$140.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_8" name="wporc_carro_8" value='<?php echo $valores[7];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_9">R$140.001,00 à R$160.000,00</label >
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_9" name="wporc_carro_9" value='<?php echo $valores[8];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_10">R$160.001,00 à R$180.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_10" name="wporc_carro_10" value='<?php echo $valores[9];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_11">R$180.001,00 à R$200.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_11" name="wporc_carro_11" value='<?php echo $valores[10];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_12">R$200.001,00 à R$220.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_12" name="wporc_carro_12" value='<?php echo $valores[11];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_13">R$220.001,00 à R$240.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_13" name="wporc_carro_13" value='<?php echo $valores[12];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_14">R$240.001,00 à R$260.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_14" name="wporc_carro_14" value='<?php echo $valores[13];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_15">R$260.001,00 à R$280.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_15" name="wporc_carro_15" value='<?php echo $valores[14];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_16">R$280.001,00 à R$300.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_16" name="wporc_carro_16" value='<?php echo $valores[15];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_17">R$300.001,00 à R$320.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_17" name="wporc_carro_17" value='<?php echo $valores[16];?>'>
					</div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="wporc_carro_18">R$320.001,00 à R$340.000,00</label>
						<input type="text" class="form-control wporc_dinheiro" id="wporc_carro_18" name="wporc_carro_18" value='<?php echo $valores[17];?>'>
					</div>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right text-center">
							<button class="btn  text-center">Atualizar Valores</button>
						</div>
					</div>


				</form>
				
			</div>


	<?php

}