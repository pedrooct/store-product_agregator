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

?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="Adicionar Produto">
	<title>web page of &ndash; Pedro Costa and Paulo Bento &ndash; Pure</title>


	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title></title>

</head>

<style></style>

<body>
	<div class="header">
		<div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
			<a class="pure-menu-heading" href="">Bem-vindo! Reporte o produto </a>
		</div>
	</div>
	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-2 pure-u-md-2-5">
				<form class="pure-form pure-form-stacked" method="POST">
					<fieldset>
						<label for="produto" style="margin-top: 20px" >Adicionar produto:</label>
						<textarea name="registo" type="text" class="pure-input-rounded" placeholder="Explique o que está de errado" <?php echo isset($_POST['explicacao']) ? "value='".$_POST['explicacao']."'":""; ?> required ></textarea>

					</fieldset>

					<button type="submit" name="send" class="pure-button pure-button-primary">Reportar</button>
					<?php
					if(isset($_POST['send']))
					{
						if(isset($_GET['produto']) && !isset($_GET['categoria']) && !isset($_GET['marca']))
						{
						  $produto=$_GET['produto'];
						  $send->mail_report_product_indix($id_product,$produto,$email,$id,$nome,$offset,$_POST['registo']);
						  header('location: shopping.php');

						}
						else if(!isset($_GET['produto']) && isset($_GET['categoria']) && !isset($_GET['marca']))
						{
						  $categoria=$_GET['categoria'];
						  $send->mail_report_category_indix($id_product,$categoria,$email,$id,$nome,$offset,$_POST['registo']);
						  header('location: shopping.php');

						}
						else if(!isset($_GET['produto']) && !isset($_GET['categoria']) && isset($_GET['marca']))
						{
						  $marca=$_GET['marca'];
						  $send->mail_report_brand_indix($id_product,$marca,$email,$id,$nome,$offset,$_POST['registo']);
						  header('location: shopping.php');
						}
						else if(isset($_GET['produto']) && isset($_GET['categoria']) && !isset($_GET['marca']))
						{
						  $produto=$_GET['produto'];
						  $categoria=$_GET['categoria'];
						  $send->mail_report_product_category_indix($id_product,$produto,$categoria,$email,$id,$nome,$offset,$_POST['registo']);
						  header('location: shopping.php');

						}
						else if(isset($_GET['produto']) && !isset($_GET['categoria']) && isset($_GET['marca']))
						{
						  $produto=$_GET['produto'];
						  $marca=$_GET['marca'];
						  $send->mail_report_product_brand_indix($id_product,$produto,$marca,$email,$id,$nome,$offset,$_POST['registo']);
						  header('location: shopping.php');

						}
						else if(!isset($_GET['produto']) && isset($_GET['categoria']) && isset($_GET['marca']))
						{
						  $categoria=$_GET['categoria'];
						  $marca=$_GET['marca'];
						  $send->mail_report_category_brand_indix($id_product,$categoria,$marca,$email,$id,$nome,$offset,$_POST['registo']);
						  header('location: shopping.php');
						}
						else if(isset($_GET['produto']) && isset($_GET['categoria']) && isset($_GET['marca']))
						{
						  $produto=$_GET['produto'];
						  $categoria=$_GET['categoria'];
						  $marca=$_GET['marca'];
						  $send->mail_report_product_category_brand_indix($id_product,$produto,$categoria,$marca,$email,$id,$nome,$offset,$_POST['registo']);
						  header('location: shopping.php');

						}
						else{
						  $send->mail_report_default_indix($id_product,$email,$id,$nome,$offset,$_POST['registo']);
						  header('location: shopping.php');
						}
					}
					?>
				</form>
			</div>
		</div>
	</div>
	<div class="footer">
		© 2017! Projecto Laboratório Pedro Costa Nº: 31179 & Paulo Bento Nº:33959 .
	</div>
</div>
</body>
</html>
