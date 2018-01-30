<?php
session_start();
if(!isset($_SESSION['id_edit'])){
	$_SESSION['id_edit']=1;
	$conn = mysqli_connect("localhost","root","root","labpro");
	if(!$conn)
		die('Error: ' . mysqli_connect_error());
}
//$id=$_GET['id'];
$id=$_SESSION['id_edit'];
?>


html>
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
			<a class="pure-menu-heading" href="">Bem-vindo! Escreva uma nova palavra passe</a>
			<li class="pure-menu-item"><a href="" class="pure-button"><?php echo $id; ?> </a></li>
		</div>
	</div>
	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-2 pure-u-md-2-5">
				<form class="pure-form pure-form-stacked" action="password_forget.php" method="POST">
					<fieldset>
						<label for="password" style="margin-top: 20px" >Reescreva a sua palavra passe:</label>

						<input name="password" type="password" class="pure-input-rounded" placeholder="Password">

						<input name="n_password" type="password" class="pure-input-rounded" placeholder="Confirm Password">
					</fieldset>

					<button type="submit" name="registo" class="pure-button pure-button-primary">Registar</button>

					<?php

					$conn = mysqli_connect("localhost","root","root","labpro");
					if(!$conn)
						die('Error: ' . mysqli_connect_error());

		/*$id=$_GET['id'];//$id=$_SESSION['id_edit'];
		$_SESSION['id_edit']=$id;*/
		$password=null;

		$password1=null;
		$newPassword=null;
		if(isset($_POST['enviar'])){
			$password1=mysqli_real_escape_string($conn,$_POST['password']);
			$newPassword=mysqli_real_escape_string($conn,$_POST['n_password']);


			if(strcmp($password1,$newPassword)==0)
				$password1=crypt($_POST['password']);
			else{
				echo "Passwords dont match!<br>";
				exit();
			}
			$sql="UPDATE utilizador SET password='$password1' WHERE id=$id;";

			echo '<span>Password1='.$password1.'</span><br>';
			echo '<span>Password new='.$newPassword.'</span><br>';
			echo '<span>sql='.$sql.'</span><br>';

			if(!mysqli_query($conn,$sql))
				die('Error: ' . mysqli_error($conn));

			echo 'Password has been successfully updated<br>';

			//mysqli_close($conn);
			//header("Location: verify_user.php");
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
<?php
mysqli_close($conn);
?>
