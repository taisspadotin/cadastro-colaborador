<?php
session_start();
include('conexao/conexao.php');	

$id= $_GET['id'];

$sql =  ("SELECT foto from clientes WHERE id = $id");
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($result);

$nome = $row['foto'];
echo "<img src='images/$nome' width=190 height=190>";

?>