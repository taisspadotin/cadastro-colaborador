<?php
error_reporting(E_ALL);
session_start();
include('conexao/conexao.php');	

$busca_dia = $_GET['busca_dia'];
$busca_mes = $_GET['busca_mes'];
$busca_nome = $_GET['busca_nome'];
$busca_semana_1 = $_GET['busca_semana_1'];
$busca_semana_2 = $_GET['busca_semana_2']; 
$mes = $_GET['mes'];
$ordenacao = $_GET['ordenacao'];
$tipo_ordenacao = $_GET['tipo_ordenacao'];

$order_by = "";
$tp_ordenacao = "";
$retorno=0;
$where = '';
if($busca_dia != '' && $busca_dia != 0 )
{
	$where  .= "AND DAY(`data_nasc`) = $busca_dia";
}
if($busca_nome != '')
{
	$where .= " AND  LOWER(nome) LIKE LOWER('%$busca_nome%')";
}
if($busca_mes != '' && $busca_mes!= 0)
{
	$where  .= " AND MONTH(data_nasc) = $busca_mes";
}
if($busca_semana_1 != 0)
{
	$where .= " AND (DAY(data_nasc) >=$busca_semana_1 AND DAY(data_nasc) <=$busca_semana_2)
	AND MONTH(data_nasc)=$mes
	";
}
if($ordenacao !=0)
{
	if($tipo_ordenacao == 0)
		$tp_ordenacao = "ASC";
	else if($tipo_ordenacao == 1)
		$tp_ordenacao = "ASC";
	else if($tipo_ordenacao == 2)
		$tp_ordenacao = "DESC";
	
	if($ordenacao == 1)
	{
		$order_by = "ORDER BY nome $tp_ordenacao";
	}
	else if($ordenacao == 2)
	{
		$order_by = "ORDER BY data_nasc $tp_ordenacao";
	}
}

$sql =  ("SELECT id, nome, data_nasc, breve_desc from `clientes` WHERE 1=1 $where $order_by");
//die($sql);
$result = mysqli_query($db, $sql);
if(mysqli_num_rows($result)>0 )
{ 
echo ' <table class="table">
			  <thead>
				<tr>
				  <th scope="col" style="width:20%;">Codigo</th>
				  <th scope="col" style="width:20%;">Nome</th>
				  <th scope="col" style="width:20%;">Data Nascimento</th>
				  <th scope="col" style="width:20%;">Descricao</th>
				</tr>
				</thead>
			  <tbody>
			  </tbody>
			</table>';
	
		
	echo '
	<table class="table">
	<tbody>
    <tr>
	';
	while($row = mysqli_fetch_array($result))
	{
		$id = $row['id'];
		$data = date("d/m/Y", strtotime($row['data_nasc']));
		
		
      echo" 
		<tr onclick='seleciona($id);'>
			  <td id='id' style='width:20%;'>".$row['id']."</td>
			  <td style='width:20%;'>".$row['nome']."</td>
			  <td style='width:20%;'>".$data."</td>
			  <td style='width:20%;'>".$row['breve_desc']."</td>
	    </tr>";
	}
	echo'
  
    </tr>
  </tbody>
</table>';
	
}
else
{
	echo 'Nenhum Resultado encontrado!';
}

//echo json_encode($retorno);

?>