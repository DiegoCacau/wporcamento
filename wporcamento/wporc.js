jQuery(document).ready(function() {

	var data_final = "";

	jQuery("#wporc_chose_A").change(function() {

        jQuery("#wporc_marcas").empty();
        jQuery( "#wporc_marcas" ).append("<option value='Selecione'>Selecione</option>");

        jQuery("#wporc_modelo").empty();
        jQuery( "#wporc_modelo" ).append("<option value='Selecione'>Selecione</option>");

        jQuery("#wporc_ano_modelo").empty();
        jQuery( "#wporc_ano_modelo" ).append("<option value='Selecione'>Selecione</option>");

        jQuery( "#wporc_codigo_fipe" ).val("");

        jQuery("#wporc_table_marca").empty();
        jQuery("#wporc_table_modelo").empty();
        jQuery("#wporc_table_codigo").empty();
		jQuery("#wporc_table_valor").empty();

		data_final = "";

        var valor = jQuery("#wporc_chose_A").val();

        if(valor !== "Selecione"){
        	jQuery.getJSON( "http://fipeapi.appspot.com/api/1/"+valor+"/marcas.json", function( data ) {
				var items = [];

				for(var i in data){
					items.push( "<option value='" + data[i].id + "'>" + data[i].name + "</option>" );
				};

				for (var i in items){
					jQuery( "#wporc_marcas" ).append(items[i]);
				}

			});

        }
           
	});


	jQuery("#wporc_marcas").change(function() {
        
		

        jQuery("#wporc_modelo").empty();
        jQuery( "#wporc_modelo" ).append("<option value='Selecione'>Selecione</option>");

        jQuery("#wporc_ano_modelo").empty();
        jQuery( "#wporc_ano_modelo" ).append("<option value='Selecione'>Selecione</option>");

        jQuery( "#wporc_codigo_fipe" ).val("");

        jQuery("#wporc_table_marca").empty();
        jQuery("#wporc_table_modelo").empty();
        jQuery("#wporc_table_codigo").empty();
		jQuery("#wporc_table_valor").empty();

		data_final = "";

		jQuery("#wporc_table_marca").append("<span>"+jQuery("#wporc_marcas option:selected").text()+"</span>");        


        var valor = jQuery("#wporc_marcas").val();
        var tipo = jQuery("#wporc_chose_A").val();

        if((valor !== "Selecione")&&(tipo !== "Selecione")){
        	jQuery.getJSON( "http://fipeapi.appspot.com/api/1/"+tipo+"/veiculos/"+valor+".json", function( data ) {
				var items = [];

				for(var i in data){
					items.push( "<option value='" + data[i].id + "'>" + data[i].name + "</option>" );
				};

				for (var i in items){
					jQuery( "#wporc_modelo" ).append(items[i]);
				}

			});

        }
           
	});



	jQuery("#wporc_modelo").change(function() {        


        jQuery("#wporc_ano_modelo").empty();
        jQuery( "#wporc_ano_modelo" ).append("<option value='Selecione'>Selecione</option>");

        jQuery( "#wporc_codigo_fipe" ).val("");

        jQuery("#wporc_table_modelo").empty();
        jQuery("#wporc_table_codigo").empty();
		jQuery("#wporc_table_valor").empty();

				jQuery("#wporc_table_modelo").append("<span>"+jQuery("#wporc_modelo option:selected").text()+"</span>");

        var valor = jQuery("#wporc_marcas").val();
        var tipo = jQuery("#wporc_chose_A").val();
        var modelo = jQuery("#wporc_modelo").val();

        data_final = "";

        if((valor !== "Selecione")&&(tipo !== "Selecione")&&(modelo !== "Selecione")){
        	jQuery.getJSON( "http://fipeapi.appspot.com/api/1/"+tipo+"/veiculo/"+valor+"/"+modelo+".json", function( data ) {
				var items = [];

				for(var i in data){
					var res = "";
					if(data[i].id){
						if(data[i].id.includes("-")){
							res = data[i].id.split("-");
						}
						else{
							res = data[i].id;
						}
						items.push( "<option value='" + data[i].id + "'>" + res[0] + "</option>" );
					}
				};

				for (var i in items){
					jQuery( "#wporc_ano_modelo" ).append(items[i]);
				}

			});

        }
           
	});


	jQuery("#wporc_ano_modelo").change(function() {

        
        jQuery("#wporc_codigo_fipe").empty();


        var valor = jQuery("#wporc_marcas").val();
        var tipo = jQuery("#wporc_chose_A").val();
        var modelo = jQuery("#wporc_modelo").val();
        var ano_modelo = jQuery("#wporc_ano_modelo").val();

        jQuery("#wporc_table_codigo").empty();
		jQuery("#wporc_table_valor").empty();

		data_final = "";

        if((valor !== "Selecione")&&(tipo !== "Selecione")&&(modelo !== "Selecione")&&(ano_modelo !== "Selecione")){
        	jQuery.getJSON( "http://fipeapi.appspot.com/api/1/"+tipo+"/veiculo/"+valor+"/"+modelo+"/"+ano_modelo+".json", function( data ) {	
				jQuery( "#wporc_codigo_fipe" ).val(data.fipe_codigo);

				jQuery("#wporc_table_codigo").empty();
				jQuery("#wporc_table_valor").empty();

				jQuery("#wporc_table_codigo").append("<span>"+data.fipe_codigo+"</span>");
				jQuery("#wporc_table_valor").append("<span id='wporc_preco'>"+data.preco+"</span>");        

				data_final = data;

			});

        }
           
	});


	jQuery("#enviar_dados").click(function(){


		
		var nome = jQuery("#wporc_nome").val();
		var email = jQuery("#wporc_email").val();
		var celular = jQuery("#wporc_celular").val();
		var telefone = jQuery("#wporc_telefone").val();
		var codigo = jQuery("#wporc_codigo_fipe").val();
		var indicador = jQuery("#wporc_indicado").val();

		var tipo = jQuery("#wporc_chose_A option:selected").text();
		var marca = jQuery("#wporc_marcas option:selected").text();
		var modelo = jQuery("#wporc_modelo option:selected").text();
		var ano_modelo = jQuery("#wporc_ano_modelo option:selected").text();
		var categoria = jQuery("#wporc_categoria option:selected").text();
		var estado = jQuery("#wporc_estado option:selected").text();

		var preco = jQuery("#wporc_preco").html();
		var cidade = jQuery("#wporc_cidade").val();

		if((chec(nome))&&(chec(email))
			&&(chec(celular))&&(chec(codigo))
			&&(chec(tipo))&&(chec(marca))
			&&(chec(modelo))&&(chec(ano_modelo))
			&&(chec(categoria))&&(chec(estado))
			&&(chec(preco))
			&&(chec(cidade))){

			if(jQuery("input[name='wporc_copia']:checked").length > 0){
				jQuery("#wporc_copia_2").val("1");
			}

			jQuery("#wporc_tipo").val(tipo);
			jQuery("#wporc_marca_2").val(marca);
			jQuery("#wporc_modelo_2").val(modelo);
			jQuery("#wporc_ano_modelo_2").val(ano_modelo);
			jQuery("#wporc_categoria_2").val(categoria);
			jQuery("#wporc_codigo_fipe_2").val(codigo);
			jQuery("#wporc_nome_2").val(nome);
			jQuery("#wporc_email_2").val(email);
			jQuery("#wporc_telefone_2").val(telefone);
			jQuery("#wporc_celular_2").val(celular);
			jQuery("#wporc_estado_2").val(estado);
			jQuery("#wporc_indicado_2").val(indicador);
			jQuery("#wporc_valor_2").val(preco);
			jQuery("#wporc_cidade_2").val(cidade);
			

			
			jQuery('#submit_dados').simulateClick('click');

			//console.log(data_final);
		}
		else{
			alert("Preencha corretamente o formulário!");
		}
		
	});

	jQuery("#enviar_dados_2").click(function(){
		var final = "";
		jQuery(".btn-selected").each(function(){
			var id1 = jQuery(this).attr("id");
			id1 = id1.replace("wporc_botao_", "");
			final =final + " " + id1 ;

		});

		jQuery("#wporc_adicionais_2").val(final);

		jQuery('#submit_dados_2').simulateClick('click');
	});	

	function chec(value){
		if ((value === "") || (value === "Selecione")){
			return false;
		}
		return true
	}

	jQuery(".btn_clickable").click(function(){
		jQuery(this).toggleClass("btn-circle");
		jQuery("i", this).toggleClass("fa-plus-circle");
		jQuery(this).toggleClass("btn-circle_2");
		jQuery(this).toggleClass("btn-selected");
		jQuery("i", this).toggleClass("fa-check-circle-o");

		var stringValor = jQuery("#wporc_valor_base_2").val();
		stringValor = stringValor.replace("R$", "");
		stringValor = stringValor.replace(".", "");
		stringValor = stringValor.replace(",", ".");
		var preco = parseFloat(stringValor);

		jQuery(".btn-selected").each(function(){
			var id1 = jQuery(this).attr("id");
			id1 = id1.replace("wporc_botao_", "");
			var somar = jQuery("#input_"+id1).val();
			somar = somar.replace(",", ".");



			preco = preco + parseFloat(somar);
			
		});
		preco = preco.toFixed(2);
		var preco_final = preco.toString();
		preco_final = preco_final.replace(".", ",");

		jQuery(".valor_final").val(preco_final);
		jQuery(".valor_final").empty();
		jQuery(".valor_final").append("<h2>R$ "+ preco_final+"</h2>");

	});

	jQuery(".wporc_select").click(function(){
		jQuery(this).toggleClass("fa-square-o");
		jQuery(this).toggleClass("fa-check-square-o");

		if(jQuery(this).hasClass("fa-check-square-o")){
			jQuery("#wporc_indicado").prop( "checked", true );
		}
		else{
			jQuery("#wporc_indicado").prop( "checked", false );
		}
	});

	jQuery(".tabela_clicavel").click(function(){

		if(jQuery(this).hasClass("wporc_deletar")){
			jQuery(".wporc_delete").val("");	
			jQuery(".wporc_deletar").removeClass("wporc_deletar");
		}
		else{
			jQuery(".wporc_deletar").removeClass("wporc_deletar");
			jQuery(this).addClass("wporc_deletar");
			var id = jQuery(this).attr("id");
			jQuery(".wporc_delete").val(id.replace("wporc_", ""));
		}

		
	});

	jQuery.fn.simulateClick = function() {
        return this.each(function() {
        	if('createEvent' in document) {
                var doc = this.ownerDocument,
                evt = doc.createEvent('MouseEvents');
                evt.initMouseEvent('click', true, true, doc.defaultView, 1, 0, 0, 0, 0, false, false, false, false, 0, null);
                this.dispatchEvent(evt);
            } else {
                this.click(); // IE Boss!
            }
        });
    }


    jQuery("#wporc_cob_preco").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter , ,
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 188]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    jQuery(".wporc_dinheiro").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter , ,
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 188]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    jQuery("#wporc_celular").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter 
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    jQuery("#wporc_telefone").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter 
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


    jQuery("#wporc_estado_2").change(function(){
    	var estado = jQuery("#wporc_estado_2").val();

    	jQuery(".cidades").hide();
    	jQuery("."+estado).show();	

    	
    });



});