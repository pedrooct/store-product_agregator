<?php
session_start();
include 'send_email_geral.php';
include 'create_pdf_absolute.php';



$id_product=$_GET['id'];
$email=$_SESSION['email'];
$nome=$_SESSION['nome'];
$id=$_SESSION['id'];

if(!isset($_SESSION['email']))
{
  header('location: login.php');
}

$pdf= new pdf();
$path=$pdf->pdf_send_product($id_product);
$send= new Mail();
$send->mail_send_product_pdf($id_product,$email,$id,$nome,$path);

header('location: shopping.php');



?>
