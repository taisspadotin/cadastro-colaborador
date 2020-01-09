<?php
define('FPDF_FONTPATH','fpdf/font/'); 
require('fpdf/fpdf.php'); 
include('conexao/conexao.php');	

$busca_dia = $_GET['busca_dia'];
$busca_mes = $_GET['busca_mes'];
$busca_nome = $_GET['busca_nome'];
$busca_semana_1 = $_GET['busca_semana_1'];
$busca_semana_2 = $_GET['busca_semana_2']; 

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
	$data_s = explode('/',$busca_semana_1);
	$busca_semana_1 = ($data_s[2].'-'.$data_s[1].'-'.$data_s[0]);
	$data_s = explode('/',$busca_semana_2);
	$busca_semana_2 = ($data_s[2].'-'.$data_s[1].'-'.$data_s[0]);
	
	$where .= " AND data_nasc BETWEEN($busca_semana_1) AND ($busca_semana_2)";
}


$sql =  ("SELECT id, nome, data_nasc, breve_desc from `clientes` WHERE 1=1 $where");
//die($sql);
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
        $this->Cell(190,$l, utf8_decode('Relatório de cadastro de clientes'),1,0,'C',1);
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
        $pdf->Cell(70,$l,'Nome do cliente',1,0,'L',1);
        $pdf->Cell(30,$l,'Data de nascimento',1,0,'l',1);
        $pdf->Cell(90,$l,utf8_decode('Breve Descrição'),1,0,'C',1);
        $pdf->Ln();
		
while($row = mysqli_fetch_array($result))
{
		$pdf->SetFillColor(250,250,250);
		$nome = utf8_decode($row["nome"]); 
		$pdf->SetFont('Arial','B',8);
        $pdf->Cell(70,$l,utf8_decode($nome),1,0,'L',1);
        $pdf->Cell(30,$l,$row["data_nasc"],1,0,'l',1);
        $pdf->Cell(90,$l,$row["breve_desc"],1,0,'C',1);
        $pdf->Ln();
}
if($busca_dia!='' || $busca_mes != 0|| $busca_semana_1 != '' || $busca_nome != '')
{
		$pdf->Ln();
		$pdf->SetFillColor(265,265,265);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(15,$l,"Filtros:",0,0,'C',0);
		$pdf->SetFont('Arial','',8);
		if($busca_dia!='')
		{
			$pdf->Ln();
			$pdf->Cell(30,$l,utf8_decode("Dia de aniversário: ").$busca_dia,0,0,'C',0);
		}
		if($busca_mes!=0)
		{
			$pdf->Ln();
			$mes = '';
			if($busca_mes == 1)
				$mes='Janeiro';
			else if($busca_mes == 2)
				$mes='Fevereiro';
			else if($busca_mes == 3)
				$mes='Março';
			else if($busca_mes == 4)
				$mes='Abril';
			else if($busca_mes == 5)
				$mes='Maio';
			else if($busca_mes == 6)
				$mes='Junho';
			else if($busca_mes == 7)
				$mes='Julho';
			else if($busca_mes == 8)
				$mes='Agosto';
			else if($busca_mes == 9)
				$mes='Setembro';
			else if($busca_mes == 10)
				$mes='Outubro';
			else if($busca_mes == 11)
				$mes='Novembro';
			else if($busca_mes == 12)
				$mes='Dezembro';
			
			$pdf->Cell(39,$l,utf8_decode("Mês de aniversário: ".$mes),0,0,'C',0);
		}
		if($busca_nome != "")
		{
			$pdf->Ln();
			$pdf->Cell(16,$l,utf8_decode('Nome: '.$busca_nome),0,0,'C',0);
		}
}

$pdf->Output(); 
Header('Pragma: public'); 
?>