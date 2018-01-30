<?php
session_start();
if(!isset($_SESSION['id_edit'])){
	$_SESSION['id_edit']=1;
}
// conexão a DB e sessão
$conn = mysqli_connect("localhost","root","root","labpro");
if(!$conn)
	die('Error: ' . mysqli_connect_error());
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
			<a class="pure-menu-heading" href="">Editar </a>
		</div>
	</div>
	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-2 pure-u-md-2-5">
				<form class="pure-form pure-form-stacked" action="edit.php" method="POST">

					<fieldset>
						<label for="username" style="margin-top: 20px" >Registar:</label>

						<input name="nome" type="text" class="pure-input-rounded" placeholder="nome" <?php echo isset($_POST['nome']) ? "value='".$_POST['nome']."'":""; ?> required >

						<input name="username" type="text" class="pure-input-rounded" placeholder="username" <?php echo isset($_POST['username']) ? "value='".$_POST['username']."'":""; ?> required >

						<input name="password" type="password" class="pure-input-rounded" placeholder="Password">

						<input name="newPassword" type="password" class="pure-input-rounded" placeholder="Password">

						<input name="email" type="text" class="pure-input-rounded" placeholder="email" <?php echo isset($_POST['email']) ? "value='".$_POST['email']."'":""; ?> required >
					</fieldset>

					<button type="submit" name="editar" class="pure-button pure-button-primary">Editar</button>

					<button type="submit" name="delete" class="pure-button pure-button-primary">DELETE</button>

					<?php
					if(isset($_POST['editar'])){

						$nome=mysqli_real_escape_string($conn,$_POST['nome']);
						$username=mysqli_real_escape_string($conn,$_POST['username']);
						$password=mysqli_real_escape_string($conn,$_POST['password']);
						$password1=mysqli_real_escape_string($conn,$_POST['password']);
						$email=mysqli_real_escape_string($conn,$_POST['email']);

						$id=$_SESSION['id_edit'];

						$id=3; // para teste

						if(strcmp($password,$password1)==0){
							$password=crypt($_POST['password']);
						}else{
							echo "Passwords dont match!<br>";
							exit();
						}

						$sql="UPDATE utilizador SET nome='$nome',email='$email',password='$password',username='$username' WHERE id=".$id;

						if(!mysqli_query($conn,$sql))
							die('Error: ' . mysqli_error($conn));

						echo $username." has been edited";
						//mysqli_close($conn);
						//header("Location: login.php");
					}

					if (isset($_POST['delete'])) {
						$sql="DELETE utilizador FROM utilizador WHERE id=".$id;

						if(!mysqli_query($conn,$sql))
							die('Error: ' . mysqli_error($conn));
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
</html>