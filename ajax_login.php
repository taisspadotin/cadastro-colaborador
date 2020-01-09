<?php
//ajax que verifica a senha:
session_start();
include('conexao/conexao.php');

$usuario  = $_GET['usuario'];
$senha    = $_GET['senha'];
$_SESSION['perfil'] = 0;
$_SESSION['login'] = 0;
$retorno = 0;

$sql = "SELECT perfil FROM usuarios where usuario = '$usuario' AND senha = '$senha'"; 
//DIE($sql);
$result = mysqli_query($db, $sql);

//$rs = 
$num_rows = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
	{
		$perfil = $row['perfil'];
	}


if($num_rows >0)
{
$_SESSION['login'] = 1; 
if($perfil == 1)//administrador
{
$_SESSION['perfil'] = 1;
}
$retorno = 1;
}

echo json_encode($retorno);
?>