<?php
session_start();
include('conexao/conexao.php');	

$busca_dia = $_GET['busca_dia'];
$busca_mes = $_GET['busca_mes'];
$busca_nome = $_GET['busca_nome'];
//die('busca_dia'.$busca_dia);
$retorno=0;
$where = '';
if($busca_dia != '' && $busca_dia != 0 )
{
	$where  .= "AND DAY(data_nasc) = $busca_dia";
}
if($busca_nome != '' && $busca_nome!= 0)
{
	$where .= " AND nome ILIKE '%$busca_nome%'";
}

$sql =  ("SELECT * from clientes WHERE 1=1 $where");
die($sql);
$result = mysqli_query($db, $sql);
if(mysqli_num_rows($result)>1 )
{ echo 'codigo Nome  Nascimento Descrição';
	while($row = mysqli_fetch_array($result))
	{
		$id = $row['id'];
		
		echo "<div onclick='seleciona($id)'>";
      echo "id:".$id."";
	  echo '<br></div>';
	  
	  
		
	}
	
	
}
else
{
	echo 'Nenhum Resultado encontrado!';
}

//echo json_encode($retorno);

?>