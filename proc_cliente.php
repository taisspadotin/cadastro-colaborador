<?php
session_start();
//fazer a conexão com o banco de dados
include('conexao/conexao.php');	

//Recebendo variaveis
$nome       = $_POST['nome'];
$data_nasc  = $_POST['data_nasc'] != "" ? $_POST['data_nasc']     : 'null';

$breve_desc = $_POST['breve_desc']!= "" ? $_POST['breve_desc']     : 'null';
$target = "images/".basename($_FILES['foto']['name']);
$image = $_FILES['foto']['name'];

$id_cliente = $_POST['id_cliente'];

$data = explode('/',$data_nasc);
$data_nasc = ($data[2].'-'.$data[1].'-'.$data[0]);
//$data_nasc = ($data[2].'-'$data[1].'-'$data[0]);
//$data_nasc = date("Y-m-d", $data_nasc);
	//die($data_nasc);

$acao       = $_POST['acao'];

if($acao == 1)//salvar
{
	if(move_uploaded_file($_FILES['foto']['tmp_name'], $target)){
		$msg = "Image uploaded succefully";
		
	}else{
		$msg = "There was a problem uploading image";
	}
	
	$sql = "INSERT INTO clientes(nome, data_nasc, breve_desc, foto) values ('$nome', $data_nasc, '$breve_desc','$image')";
	//die($sql);
	mysqli_query($db, $sql) or die('erro na insercao');
}
else if($acao == 2)//alterar
{
	$complemento = "";
	if($image!='')
	{
		if(move_uploaded_file($_FILES['foto']['tmp_name'], $target)){
			$msg = "Imagem salva com sucesso";
			$complemento = ",foto = '$image'";
			
		}else{
			$msg = "Ocorreu um problema ao salvar a imagem";
		}
	}
	
	$sql = "UPDATE clientes
	SET nome='$nome',
	breve_desc ='$breve_desc',
	data_nasc	= '$data_nasc'
	$complemento
	WHERE id = $id_cliente";
	//die($sql);
	
	mysqli_query($db, $sql) or die('erro ao alterar o registro');
}
else if($acao == 3)//deletar
{
	$sql = "DELETE FROM clientes WHERE id = $id_cliente";
	mysqli_query($db, $sql) or die('erro ao deletar o registro');
}

header('Location:index.html');
?>