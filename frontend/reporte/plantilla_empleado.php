<?php
require('../../backend/fpdf/fpdf.php');
date_default_timezone_set('America/Lima');
class PDF extends FPDF
{
  
function Header()
{
  $this->Image('../../backend/img/logo.png',-1,-1,85);
  $this->Image('../../backend/img/ico.png',150,15,25);
  
  $this->SetY(40);
  $this->SetX(145);
  $this->SetFont('Arial','B',12);
  
  $this->SetTextColor(30,10,32);
  $this->Cell(89, 8, 'Sistema de asistencia',0,1);
  $this->SetY(45);
  $this->SetX(147);
  $this->SetFont('Arial','',8);
  $this->Cell(40, 8, 'Reporte de empleados');
  
  $this->Ln(30);
  

}

function Footer()
{
     $this->SetFont('helvetica', 'B', 8);
        $this->SetY(-15);
        $this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(95,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->Line(10,287,200,287);
        $this->Cell(0,5,utf8_decode("Sistema de asistencia © Todos los derechos reservados."),0,0,"C");
        
}


}



$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);


$pdf->SetX(15);
$pdf->SetFillColor(40, 61, 199);
$pdf->SetDrawColor(255, 255, 255);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30, 12, utf8_decode('Documento'),1,0,'C',1);
$pdf->Cell(60, 12, utf8_decode('Nombre y apellidos'),1,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Cargo'),1,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Nacionalidad'),1,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Celular'),1,1,'C',1);


require('../../backend/bd/ctconex.php');

//$consulta = "SELECT * FROM period";
//$resultado = mysqli_query($conexion,$consulta);
$stmt = $connect->prepare("SELECT empleado.idemp, empleado.dniem, empleado.nomem, empleado.apeem, cargo.idcar, cargo.nomcar, empleado.naci, empleado.celu, empleado.estado, empleado.fere FROM empleado INNER JOIN cargo ON empleado.idcar = cargo.idcar ORDER BY empleado.idemp DESC");
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute();

while($row = $stmt->fetch()){

$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(65, 61, 61);
$pdf->Cell(30, 8, utf8_decode($row['dniem']),'B',0,'C',1);
$pdf->Cell(60, 8, utf8_decode($row['nomem'] .$row['nomem']),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($row['nomcar']),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($row['naci']),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($row['celu']),'B',1,'C',1);
$pdf->Ln(0.5);

}

$pdf->Output('empleados.pdf', 'D');
?>