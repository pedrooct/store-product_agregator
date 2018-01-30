<?php
session_start();
if(!isset($_SESSION['id'])){
	header('location:login.php');
}
$id=$_SESSION['id'];
$nome=$_SESSION['nome'];
$email=$_SESSION['email'];

$page=$_GET['page'];

if(!isset($_GET['page']))
{
	header('location: profile.php?page=1');
}
$lines=file_get_contents("http://labpro.dev/count");
$lines=json_decode($lines);
$au=$lines/4;
$last=ceil($au);
if(isset($_POST['next_page']))
{
	if($page==$last)
	{
		header('location: profile.php?page='.$page);
	}
	else{
		$page++;
		header('location: profile.php?page='.$page);
	}

}
if(isset($_POST['back_page']))
{
	if($page==1)
	{
		header('location: profile.php?page=1');
	}
	else{
		$page--;
		header('location: profile.php?page='.$page);
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="description" content="login">
	<title>web page of &ndash; Pedro Costa and Paulo Bento &ndash; Pure</title>


	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="header">
		<div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
			<a class="pure-menu-heading" href="">Bem-vindo ao seu perfil <?php echo $nome; ?> </a>
			<li class="pure-menu-item"><a href="shopping.php" class="pure-button"> BACK </a></li>
		</div>
	</div>
	<div class="content" style="margin-top:100px" >
		<div class="pure-g">
			<div class="pure-u-1-2 pure-u-md-1-2 pure-u-lg-1-4">
				<?php
				$offset=$page-1;
				$noproducts="Sem produtos na lista de desejos";
				$json=file_get_contents("http://labpro.dev/wishlist/".$id."/".$offset);
				$produto=json_decode($json);
				$size=sizeof($produto);
				if(isset($produto->result))
				{
					echo '<div class="info">Sem produtos!</div>';
				}
				else {
					for($i=0;$i<$size;$i++)
					{?>
						<div class="columns">
							<ul class="price">
								<li class="header"> <?php echo $produto[$i]->nome ;?></li>
								<li class="grey"> <?php echo $produto[$i]->marca ;?></li>
								<li><?php echo '<img src="data:image/'.$produto[$i]->imagetype.';base64,'.$produto[$i]->imagem.'" height="200" width="200" />';?></li>
								<li><?php echo "preço: ",$produto[$i]->preco, " euros"; ?></li>
								<li><?php echo "portes: ",$produto[$i]->portes," euros"; ?></li>
								<li class="grey"> <a href=<?php echo $produto[$i]->link ;?> class="pure-button">Ver produto</a></li>
								<li class="grey"> <a href=<?php echo "sendme.php?id=",$produto[$i]->id ;?> name="send" class="pure-button">Envia-me</a></li>
								<li class="grey"> <a href=<?php echo "del_wishlist.php?pid=".$produto[$i]->id."&uid=".$id ;?> name="del" class="pure-button">Remover da lista de desejos</a></li>
							</ul>
						</div>
						<?php
					}
				}
				?>
				<form class="pure-form pure-form-stacked"  method="post">
					<button type="submit"   name="back_page" class="pure-button pure-button-primary">Pagina anterior</button>
					<button type="submit"	 name="next_page" class="pure-button pure-button-primary">Ver mais</button>
				</form>
			</div>
			<div class="pure-u-1-2 pure-u-md-1-2 pure-u-lg-1-4">
				<form class="pure-form pure-form-stacked" method="POST">
					<input name="nome" type="text" class="pure-input-rounded" placeholder="nome" value=<?php echo $nome ?>>
					<input name="email" type="text" class="pure-input-rounded" placeholder="email" value=<?php echo $email ?> >
					<input name="password" type="password" class="pure-input-rounded" placeholder="PASSWORD ! Insira a sua ou outra a sua escolha" required>
					<button type="submit" name="editar" class="pure-button pure-button-primary">Editar</button>
					<?php
					if(isset($_POST['editar']))
					{
						$data=array(
							'pid'=>$id,
							'nome'=>$_POST['nome'],
							'email'=> $_POST['email'],
							'password'=> password_hash($_POST['password'],PASSWORD_DEFAULT)
						);
						$data=json_encode($data);
						$curl = curl_init("http://labpro.dev/edit/user");
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST,'PUT');
						curl_setopt($curl, CURLOPT_HEADER, false);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
						$response=curl_exec($curl);
						if(strcmp($response,"ok")==0)
						{
							header('location: login.php');
						}
						echo $response;
					}
					?>
				</form>
			</div>
		</div>
	</div>
	<div class="footer">
		© 2017! Projecto Laboratório Pedro Costa Nº: 31179 & Paulo Bento Nº:33959 .
	</div>
</body>
</html>
