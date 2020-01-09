<?php
session_start();

if(!isset($_SESSION['login']))
{
   header('Location:login.html'); 
   die("usuário não Logado no sistema");
} 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cadastro de colaborador</title>
        <meta charset="UTF-8">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="shortcut icon" href="https://image.flaticon.com/icons/png/512/57/57134.png" type="image/x-icon" />
<script src="js/moment.js"></script>
<script src="js/Script.js"></script>
<style>
input[type=button]
{
	width:15%;
}
fieldset.scheduler-border 
{
	border: 1px groove #ddd !important;
	padding: 0 1.4em 1.4em 1.4em !important;
	margin: 0 0 1.5em 0 !important;
	-webkit-box-shadow:  0px 0px 0px 0px #000;
	box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border 
{
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	border:none;
	width:100px;
}
</style>
 
    </head>
<form id="frm_form" name="frm_form" action="proc_cliente.php" method="POST" enctype='multipart/form-data'>
    <body style="background:#ffffff; align:left; text-align:left;">

<!--Barra de navegação-->
<nav class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
		  <a class="navbar-brand" href="#">Cadastro de colaborador</a>
		</div>
		<ul class="nav navbar-nav">
		  <li class="active"><a href="#">Home</a></li>
		</ul>
	</div>
</nav>

    <!--Conteudo-->
	<div id="conteudo" style="margin-left:1%;">
	<div style="<?php if($_SESSION['perfil'] == 1){echo 'display:block;';}else{echo 'display:none;';}?>">
	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Informações:</legend>
		
		 <div class="row">
			 <div class="col-md-8">
			 <label for="nome">Nome:</label>
			 <input type="text"  class="form-control form-control-sm"  name="nome" id="nome" required>
			 </div> 
		 </div><br>
		 <div class="row">
			 <div class="col-md-4">
				 <label for="data_nasc">Data nascimento:</label>
				 <input type="text"  class="form-control form-control-sm"  name="data_nasc" id="data_nasc">
			 </div>
			 <div class="col-md-4">
				 <label for="foto">Foto:</label>
				 <input type="file" class="form-control-file" id="foto" name="foto" value="Buscar foto...">
			 </div>
		 </div><br>
		 <div class="row">
			 <div class="col-md-8">
				<label for="breve_desc">Breve descrição:</label>
				<textarea id="breve_desc" class="form-control form-control-sm" name="breve_desc" rows="2" cols="70" placeholder="descrição ..."></textarea>
			</div> 
		</div>
		
	</fieldset>
</div>
		<div id="container_imagem" style=" <?php if($_SESSION['perfil'] == 0){echo 'margin-top:15%; margin-left:2%;';}else{ echo 'margin-top:8%;';}?>position:absolute; top:0;  text-align:right; float:rigth; margin-left:80%; display:none">
			<fieldset class="scheduler-border">
			<legend class="scheduler-border">Foto:</legend>
				<div id="carrega_imagem" >
				</div>
			</fieldset>
		</div>
	<br>
	
	<div class="row" style="margin-left:1%; ">
		<div style="<?php if($_SESSION['perfil'] == 1){echo 'display:block;';}else{echo 'display:none;';}?>; width:50%; float:left;">
			<input type="button" value="Novo" id="btn_novo"  class="btn btn-dark"  style="width:32.8%;" onclick="window.location.href = 'index.php'">
			<input type="button" value="Salvar" id="btn_salvar"  class="btn btn-dark" style="width:32.8%;"  onclick="if(Validar())Finalizar(1);">
			<input type="button" value="Alterar" id="btn_alterar" class="btn btn-dark" style="width:32.8%;" onclick="Finalizar(2);">
		</div>
			<input type="button" value="Buscar" id="btn_buscar" class="btn btn-dark" onclick="abrirDialog();">
		<div style="<?php if($_SESSION['perfil'] == 1){echo 'display:block;';}else{echo 'display:none;';}?>; width:15%; float:left;">
			<input type="button" value="Excluir" id="btn_excluir" class="btn btn-dark" style="width:99%;" onclick="Finalizar(3);">
		</div>
	</div>
	<input type="hidden" id="acao" name="acao">
	<input type="hidden" id="id_cliente" name="id_cliente">
	<input type="hidden" id="ultimo_dia_semana">
	<input type="hidden" id="primeiro_dia_semana">
	<input type="hidden" id="mes">

	</div>
 <br><br><br>
  <div id="div_busca" class="control-group" style="display:none; background:#ffffff; width:80%;">
	<fieldset class="scheduler-border">
	<legend class="scheduler-border">Filtros:</legend>
	<div class="row">
		<div class="col-md-5">
			 <label for="busca_mes">Mês de aniversario:</label>
			 <select name="busca_mes" id="busca_mes"  class="form-control form-control-sm" >
				<option value="0" selected> --Selecione--</option>
				<option value="1">Janeiro</option> 
				<option value="2">Fevereiro</option> 
				<option value="3">Março</option> 
				<option value="4">Abril</option> 
				<option value="5">Maio</option> 
				<option value="6">Junho</option> 
				<option value="7">Julho</option> 
				<option value="8">Agosto</option>
				<option value="9">Setembro</option> 
				<option value="10">Outubro</option> 
				<option value="11">Novembro</option> 
				<option value="12">Dezembro</option> 
			</select>
		</div>
	
		<div class="col-md-5">
			<label for="busca_semana_1">Semana de aniversario:</label>
			<br>
			
				<select id="busca_semana_1" class="form-control form-control-sm" onblur="Semana(this.value);">
				<option value="0">--Selecione--</option>
					<script>
					var i=1;
					while(i<53)
					{
					var comboCidades = document.getElementById("busca_semana_1");
					var opt = document.createElement("option");
						opt.value = i;
						opt.text = i;
						comboCidades.add(opt, comboCidades.options[i]);
					 i++;
					}
					</script>
				</select>
		</div>
		
		
	 </div>
	 <br>
	 <div class="row">
		<div class="col-md-5">
		 <label for="busca_dia">Dia de aniversario:</label>
		 <input type="text" id="busca_dia" class="form-control">
		</div>
		<div class="col-md-5">
		 <label for="busca_nome">Nome:</label>
		 <input type="text" id="busca_nome" class="form-control">
		</div>
	 </div>
	 <br>
	 <div class="row">
		<div class="col-md-5">
			<label for="ordenacao">Ordenação:</label>
			<select class="form-control" id="ordenacao">
				<option value='0'>--Selecione--</option>
				<option value='1'>Nome</option>
				<option value='2'>Data de nascimento</option>
			</select>
		</div>
		<div class="col-md-5">
			<label for="tipo_ordenacao">&nbsp</label>
			<select class="form-control" id="tipo_ordenacao">
				<option value='0'>--Selecione--</option>
				<option value='1'>Crescente</option>
				<option value='2'>Decrescente</option>
			</select>
		</div>
	 </div>
	 </fieldset>
	 <input type="button" id="btn_busca_aniversario" name="btn_busca_aniversario" class="btn btn-dark" value="Buscar" onclick="Buscar();">
	 <input type="button" id="btn_busca_aniversario" name="btn_busca_aniversario" class="btn btn-dark" style="width:20%" value="Relatório PDF" onclick="imprimir('imprimir.php', 'd');">
 </div>
 <div id="combo_resultado" style="display:none; width:80%; margin-left:1%; background:#FAFAFA;">
	 <fieldset class="scheduler-border">
		 <div id="resultado" >
		 
		 </div>
	</fieldset>
 </div>
    </body>
</form>
</html>


