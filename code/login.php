<?php
session_start();
include 'send_email_geral.php';

if(!isset($_SESSION['id'])){
	$_SESSION['id']=1;
}
// conexão a DB e sessão
$conn = mysqli_connect("localhost","root","root","labpro");
if(!$conn)
{
	die('Error: ' . mysqli_connect_error());
}
//$user=$_POST['username'];
if(isset($_POST['login']))
{
	$stuff=mysqli_query($conn,"SELECT id,nome,username,password,email FROM utilizador");
	while($row = mysqli_fetch_array($stuff))
	{
		if(strcmp($row['username'],$_POST['username'])==0)
		{
			if(password_verify($_POST['password'],$row['password']))
			{
				$_SESSION["username"]=$_POST['username'];
				$_SESSION["nome"]= $row['nome'];
				$_SESSION["id"]=$row['id'];
				$_SESSION["email"]=$row['email'];
				//echo '<div class="success">Login efetuado com sucesso!</div>';
				mysqli_close($conn);
				header('location: shopping.php');
			}
		}
	}
	echo '<div class="error">Oooops algo correu mal !</div>';
	echo "<br>";
}
if(isset($_POST['registo']))
{
	header('location: register.php');
}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="login">
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
			<a class="pure-menu-heading" href="">Bem-vindo! faça o seu login</a>
		</div>
	</div>
	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-2 pure-u-md-2-5">
				<form class="pure-form pure-form-stacked" action="login.php" method="POST">
					<fieldset>
						<label for="username" style="margin-top: 20px" >Login:</label>
						<input name="username" type="text" class="pure-input-rounded" placeholder="Usermane" <?php echo isset($_POST['username']) ? "value='" .$_POST['username']."'":""; ?> required >
						<input name="password" type="password" class="pure-input-rounded" placeholder="Password">
						<button type="submit" name="login" class="pure-button pure-button-primary">login</button>
						<button type="submit" name="lost" class="pure-button pure-button-primary">Recuperar dados</button>
					</fieldset>
				</form>
				<form class="pure-form pure-form-stacked" method="POST">
					<button type="submit" name="registo" class="pure-button pure-button-primary">Registar</button>
				</form>
			</div>
		</div>
	</div>
	<?php
	if(isset($_POST['lost']))
	{
		$pass= new Mail();
		$pass->mail_Send_recover($_POST['username']);
		echo '<div class="success">Verifique o seu email para mais informações!</div>';
	}
	?>
	<div class="footer">
		© 2017! Projecto Laboratório Pedro Costa Nº: 31179 & Paulo Bento Nº:33959 .
	</div>
</div>
</body>
</html>
