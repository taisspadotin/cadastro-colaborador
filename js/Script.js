$(function() {
	//MASCARA DE DATAS
	$( "#data_nasc" ).mask('99/99/9999');
	//$( "#data_nasc" ).datepicker();
	
	//$( "#busca_semana_1" ).mask('99/99/9999');
	//$( "#busca_semana_1" ).datepicker();
	//$( "#busca_semana_2" ).mask('99/99/9999');
	//$( "#busca_semana_2" ).datepicker();
	setDialog();
});
function seleciona(id)
{
    obj = document.frm_form;
	//ajax que carrega os dados
	$.ajax({
    method: "POST",
    url: "ajax_busca.php",
    data: {
		'id' :id		
		},
	success: function(data) 
		{
		
			obj.id_cliente.value = data.id;
			obj.nome.value = data.nome;
			obj.data_nasc.value = data.data_nasc;
			obj.breve_desc.value = data.breve_desc;
			adiciona_imagem(id);
		},	
		dataType:"json"
   })

	
}
function adiciona_imagem(id)
{
obj = document.frm_form;
	//ajax que carrega os dados
	$.ajax({
    method: "POST",
    url: "ajax_carrega_imagem.php",
    data: {
		'id' :id		
		},
	success: function(data) 
		{
			var display = document.getElementById('container_imagem').style.display;
			if(display == "none")
			document.getElementById('container_imagem').style.display = 'block';
			
			//Mudarestado('container_imagem');
			$("#carrega_imagem").html(data);
			
		}
   })
}
function Finalizar(parametro)
{
	obj = document.frm_form;
	if(parametro == 1)
	{
		
		if(obj.id_cliente.value != '')
		{
			alert('Por favor insira um novo registro!');
		}else{
			obj = document.frm_form;
			obj.acao.value = parametro;
			obj.submit();
			}
	}
	else if(parametro == 3)
	{
		if(obj.id_cliente.value == '')
		{
				alert('Por favor selecione um registro!');
		}
			else{
		obj = document.frm_form;
		obj.acao.value = parametro;
		obj.submit();
		}

	}
	else if(parametro == 2){
		if(obj.id_cliente.value == '')
		{
				alert('Por favor selecione um registro!');
		}
		else{
				obj = document.frm_form;
				obj.acao.value = parametro;
				obj.submit();
			}
	}
}
function Validar()
{
obj = document.frm_form;

	if(obj.nome.value == '')
	{
	alert('Favor preencher o Nome!');
	obj.nome.focus();
	return false;
	}
	else if(obj.data_nasc.value == '')
	{
	alert('Favor preencher a Data de nascimento!');
	obj.data_nasc.focus();
	return false;
	}
	else if(obj.breve_desc.value == '')
	{
	alert('Favor preencher a Descrição!');
	obj.breve_desc.focus();
	return false;
	}
	else return true;
}
function Mudarestado(el) 
{
	var display = document.getElementById(el).style.display;
	if(display == "none")
	document.getElementById(el).style.display = 'block';
	else
	document.getElementById(el).style.display = 'none';
}
function Buscar()
{
	/*obj = document.frm_form;
	obj.acao.value = '4';
	obj.submit();*/
	var busca_dia      = document.getElementById('busca_dia').value;
	var busca_mes      = document.getElementById('busca_mes').value;
	var busca_semana_1 = document.getElementById('primeiro_dia_semana').value;
	var busca_semana_2 = document.getElementById('ultimo_dia_semana').value;
	var mes            = document.getElementById('mes').value;
	var busca_nome     = document.getElementById('busca_nome').value;
	var ordenacao      = document.getElementById('ordenacao').value;
	var tipo_ordenacao = document.getElementById('tipo_ordenacao').value;
	//alert(busca_semana_1);
	
	$.ajax({
    method: "POST",
    url: "ajax_carrega_dados.php",
    data: {
		'busca_dia': busca_dia, 
		'busca_mes': busca_mes,
		'busca_semana_1': busca_semana_1,
		'busca_semana_2': busca_semana_2,
		'busca_nome': busca_nome,
		'mes': mes,
		'ordenacao':ordenacao,
		'tipo_ordenacao': tipo_ordenacao
		},
	success: function(data) 
		{
			$("#resultado").html(data);
			fecharDialog();
			
	document.getElementById('combo_resultado').style.display = 'block';
			//Mudarestado('combo_resultado');
			//if(dat ==1)
		}	
   })

} 

function Semana(semana)
{
	var primeiro_dia_semana = 0, ultimo_dia_semana = 0;
	var mes = 0;
	var mes_ultimo_dia =0;
	
		var {_d} = moment().day('Sunday').week(semana);
		
		if (moment().get('year') != _d.getFullYear()) {
			if (moment().get('year') > _d.getFullYear()) {
				primeiro_dia_semana = 1;
				mes = 1;
			}
			else {
				dia = moment().day('Sunday').week(semana - 1);
				ultimo_dia_semana = 31;
				mes = 12;
			}
		}
	//var {_d} = moment().day('Sunday').week(semana);
	
			//mes = _d.getMonth();
		if(primeiro_dia_semana == 0)
		{
			primeiro_dia_semana = _d.getDate();
			mes =(_d.getMonth())+1;
		}
		var {_d} = moment().day('Saturday').week(semana);
		mes_ultimo_dia = _d.getMonth()+1;
		if(ultimo_dia_semana==0)
			ultimo_dia_semana = _d.getDate();
		if(mes != mes_ultimo_dia)
		{
			var {_d} = moment(`${moment().get('year')}-${mes}`).endOf('month');
			ultimo_dia_semana = _d.getDate();
		}	
		//var {_d} = moment().day('Saturday').week(semana);
		document.getElementById('ultimo_dia_semana').value = ultimo_dia_semana;
		document.getElementById('primeiro_dia_semana').value = primeiro_dia_semana;
		document.getElementById('mes').value = mes;
		
		if(semana == 0)
		{
			document.getElementById('ultimo_dia_semana').value = '';
			document.getElementById('primeiro_dia_semana').value = '';
			document.getElementById('mes').value = '';
		}
}

function setDialog() {
$("#div_busca").dialog({
autoOpen: false,
width: 800,
modal: true,
resizable: false,
title: 'Buscar',
close: function() { 
}
});
}

function abrirDialog() {
$('#div_busca').dialog('open');
}

function fecharDialog() {
$('#div_busca').dialog('close'); 
}
function imprimir(valor, versao)
{
	obj = document.frm_form;
	var busca_dia      = document.getElementById('busca_dia').value;
	var busca_mes      = document.getElementById('busca_mes').value;
	var busca_semana_1 = document.getElementById('primeiro_dia_semana').value;
	var busca_semana_2 = document.getElementById('ultimo_dia_semana').value;
	var mes			   = document.getElementById('mes').value;
	var busca_nome     = document.getElementById('busca_nome').value;
	var ordenacao      = document.getElementById('ordenacao').value;
	var tipo_ordenacao = document.getElementById('tipo_ordenacao').value;

List = window.open(valor+"?busca_dia="+busca_dia+"&busca_mes="+busca_mes+"&busca_semana_1="+busca_semana_1+"&busca_semana_2="+busca_semana_2+"&mes="+mes+"&busca_nome="+busca_nome+"&ordenacao="+ordenacao+"&tipo_ordenacao="+tipo_ordenacao,"list", "scrollbars=no, width=900,height=700");
}

