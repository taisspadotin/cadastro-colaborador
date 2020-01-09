<?php
session_start();
include('conexao/conexao.php');	


$id= $_GET['id'];



$sql =  ("SELECT id, nome, data_nasc, breve_desc, foto from clientes WHERE id = $id");
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($result);

$nome = $row['nome'];
$data_nasc =$row['data_nasc'];
$breve_desc =$row['breve_desc'];
$foto =$row['foto'];

$data = explode('-',$data_nasc);
$data_nasc = ($data[2].'/'.$data[1].'/'.$data[0]);

$retorno = array(
	"id" => $id,
	"nome" => $nome,
	"data_nasc" => $data_nasc,
	"breve_desc" => $breve_desc,
	"foto" => $foto
);

echo json_encode($retorno);

?>