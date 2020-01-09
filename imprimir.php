<?php
define('FPDF_FONTPATH','fpdf/font/'); 
require('fpdf/fpdf.php'); 
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
$retorno=0;
$tp_ordenacao = "";
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
$result = mysqli_query($db, $sql);

class PDF extends FPDF {

    function Header(){ //CABECALHO
        global $codigo; 
        $l=2; //altura da linha
        $this->SetXY(10,10); 
        $this->Rect(10,10,190,280); 

        $this->SetFont('Arial','B',8); 

        
        $this->Cell(190,10,'',0,0,'L');
        $this->Ln();
		$l=6;
        $this->SetTextColor(255,255,255);
        $this->Cell(190,$l, utf8_decode('Relatório de cadastro de colaboradores'),1,0,'C',1);
        $this->Ln();
		$this->Ln();

        
    }

}


$pdf=new PDF('P','mm','A4'); 
$pdf->AddPage();
$pdf->AliasNbPages(); 
$pdf->SetFont('Arial','',8);
$y = 59; 
$l=5; 
 
 $pdf->SetFillColor(232,232,232);
       $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(70,$l,'Nome do colaborador',1,0,'L',1);
        $pdf->Cell(30,$l,'Data de nascimento',1,0,'l',1);
        $pdf->Cell(90,$l,utf8_decode('Breve Descrição'),1,0,'C',1);
        $pdf->Ln();
		
while($row = mysqli_fetch_array($result))
{
		$pdf->SetFillColor(250,250,250);
		$nome = utf8_decode($row["nome"]); 
		$data = explode('-',$row["data_nasc"]);
		$data_nasc = ($data[2].'/'.$data[1].'/'.$data[0]);
		
		$pdf->SetFont('Arial','B',8);
        $pdf->Cell(70,$l,utf8_decode($nome),1,0,'L',1);
        $pdf->Cell(30,$l,$data_nasc,1,0,'l',1);
        $pdf->Cell(90,$l,substr($row["breve_desc"], 0, 60),1,0,'C',1);
        $pdf->Ln();
}
if($busca_dia!='' || $busca_mes != 0|| $busca_semana_1 != 0 || $busca_nome != '')
{
		$pdf->Ln();
		$pdf->SetFillColor(265,265,265);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(15,$l,"Filtros:",0,0,'C',0);
		$pdf->SetFont('Arial','',8);
		if($busca_dia!='')
		{
			$pdf->Ln();
			$pdf->Cell(32,$l,utf8_decode("Dia de aniversário: ").$busca_dia,0,0,'C',0);
		}
		if($busca_mes!=0)
		{
			$pdf->Ln();
			$mes_e = '';
			if($busca_mes == 1)
				$mes_e='Janeiro';
			else if($busca_mes == 2)
				$mes_e='Fevereiro';
			else if($busca_mes == 3)
				$mes_e='Março';
			else if($busca_mes == 4)
				$mes_e='Abril';
			else if($busca_mes == 5)
				$mes_e='Maio';
			else if($busca_mes == 6)
				$mes_e='Junho';
			else if($busca_mes == 7)
				$mes_e='Julho';
			else if($busca_mes == 8)
				$mes_e='Agosto';
			else if($busca_mes == 9)
				$mes_e='Setembro';
			else if($busca_mes == 10)
				$mes='Outubro';
			else if($busca_mes == 11)
				$mes_e='Novembro';
			else if($busca_mes == 12)
				$mes_e='Dezembro';
			
			$pdf->Cell(39,$l,utf8_decode("Mês de aniversário: ".$mes_e),0,0,'C',0);
		}
		if($busca_nome != "")
		{
			$pdf->Ln();
			$pdf->Cell(14,$l,utf8_decode('Nome: '.$busca_nome),0,0,'C',0);
		}
		if($busca_semana_1 != 0)
		{
			$pdf->Ln();
			$pdf->Cell(35,$l,utf8_decode('Semana de aniversário: '),0,0,'C',0);
			$pdf->Ln();
			$pdf->Cell(25,$l,utf8_decode('De: '.$busca_semana_1.'/'.$mes),0,0,'C',0);
			$pdf->Cell(35,$l,utf8_decode('Até: '.$busca_semana_2.'/'.$mes),0,0,'C',0);
		}
}

$pdf->Output(); 
Header('Pragma: public'); 
?>