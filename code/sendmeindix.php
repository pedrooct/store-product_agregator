<?php
session_start();
include 'send_email_geral.php';

if(!isset($_SESSION['email']))
{
  header('location: login.php');
}

$send= new Mail();
$email=$_SESSION['email'];
$nome=$_SESSION['nome'];
$id=$_SESSION['id'];


$offset=$_GET['offset'];
$id_product=$_GET['id'];

if(isset($_GET['produto']) && !isset($_GET['categoria']) && !isset($_GET['marca']))
{
  $produto=$_GET['produto'];
  $send->mail_send_product_indix($id_product,$produto,$email,$id,$nome,$offset);
  header('location: shopping.php');

}
else if(!isset($_GET['produto']) && isset($_GET['categoria']) && !isset($_GET['marca']))
{
  $categoria=$_GET['categoria'];
  $send->mail_send_category_indix($id_product,$categoria,$email,$id,$nome,$offset);
  header('location: shopping.php');

}
else if(!isset($_GET['produto']) && !isset($_GET['categoria']) && isset($_GET['marca']))
{
  $marca=$_GET['marca'];
  $send->mail_send_brand_indix($id_product,$marca,$email,$id,$nome,$offset);
  header('location: shopping.php');
}
else if(isset($_GET['produto']) && isset($_GET['categoria']) && !isset($_GET['marca']))
{
  $produto=$_GET['produto'];
  $categoria=$_GET['categoria'];
  $send->mail_send_product_category_indix($id_product,$produto,$categoria,$email,$id,$nome,$offset);
  header('location: shopping.php');

}
else if(isset($_GET['produto']) && !isset($_GET['categoria']) && isset($_GET['marca']))
{
  $produto=$_GET['produto'];
  $marca=$_GET['marca'];
  $send->mail_send_product_brand_indix($id_product,$produto,$marca,$email,$id,$nome,$offset);
  header('location: shopping.php');

}
else if(!isset($_GET['produto']) && isset($_GET['categoria']) && isset($_GET['marca']))
{
  $categoria=$_GET['categoria'];
  $marca=$_GET['marca'];
  $send->mail_send_category_brand_indix($id_product,$categoria,$marca,$email,$id,$nome,$offset);
  header('location: shopping.php');
}
else if(isset($_GET['produto']) && isset($_GET['categoria']) && isset($_GET['marca']))
{
  $produto=$_GET['produto'];
  $categoria=$_GET['categoria'];
  $marca=$_GET['marca'];
  $send->mail_send_product_category_brand_indix($id_product,$produto,$categoria,$marca,$email,$id,$nome,$offset);
  header('location: shopping.php');

}
else{
  $send->mail_send_default_indix($id_product,$email,$id,$nome,$offset);
  header('location: shopping.php');
}





?>
