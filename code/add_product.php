<?php
session_start();
if (!isset($_SESSION['id'])) {
	$_SESSION['id']='';
	header("Location: login_loja.php");
}
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
			<a class="pure-menu-heading" href="">Bem-vindo! Adicione o seu produto </a>
		</div>
	</div>
	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-2 pure-u-md-2-5">
				<form class="pure-form pure-form-stacked" enctype="multipart/form-data" method="POST">
					<fieldset>
						<label for="produto" style="margin-top: 20px" >Adicionar produto:</label>

						<input name="nome" type="text" class="pure-input-rounded" placeholder="nome" <?php echo isset($_POST['nome']) ? "value='".$_POST['nome']."'":""; ?> required >
						<div class="pure-u-1 pure-u-md-1-3">
							<label for="categoria">Categoria</label>
							<select name="categoria" class="pure-input-1-2">
								<option value="">Categorias:</option>
								<option value="Laptops">Portateis</option>
								<option value="Computers & Accessories">Torres</option>
								<option value="Computer Components">Hardware</option>
								<option value="Electronics">Eletrónica</option>
								<option value="Cell Phones & Accessories">Tablets</option>
								<option value="Cell Phones & Accessories">Telemovéis</option>
							</select>
							<select name="marca">
								<option value="">Marcas:</option>
								<option value="apple">Apple</option>
								<option value="microsoft">Microsoft</option>
								<option value="xiaomi">xiaomi</option>
								<option value="asus">asus</option>
								<option value="raspberry">raspberry</option>
								<option value="arduino">arduino</option>
							</select>
						</div>

						<input name="preco" type="text" class="pure-input-rounded" placeholder="preco" <?php echo isset($_POST['preco']) ? "value='".$_POST['preco']."'":""; ?> required >

						<input name="link" type="text" class="pure-input-rounded" placeholder="link" <?php echo isset($_POST['link']) ? "value='".$_POST['link']."'":""; ?> required >

						<input name="portes" type="text" class="pure-input-rounded" placeholder="portes" <?php echo isset($_POST['portes']) ? "value='".$_POST['portes']."'":""; ?> required >

						<input name="especificacao" type="text" class="pure-input-rounded" placeholder="especificacao" <?php echo isset($_POST['especificacao']) ? "value='".$_POST['especificacao']."'":""; ?> required >

						<input name="referencia" type="text" class="pure-input-rounded" placeholder="referencia" <?php echo isset($_POST['referencia']) ? "value='".$_POST['referencia']."'":""; ?> required >

						<label for="imagem" >Imagem do produto:</label>
						<input name="imagem" type="file" class="pure-input-rounded" placeholder="Imagem do produto" required >

					</fieldset>

					<button type="submit" name="registo" class="pure-button pure-button-primary">Adicionar</button>

					<?php
					if(isset($_POST['registo'])){
						$store_id=$_SESSION['id'];

						$nome=$_POST['nome'];
						$marca=$_POST['marca'];
						$categoria=$_POST['categoria'];

						if($_POST['preco']<=0)
						{
							$preco=1;
						}
						else
						{
							$preco=$_POST['preco'];
						}


						$link=$_POST['link'];

						if($_POST['portes']<=0)
						{
							$portes=1;
						}
						else
						{
							$portes=$_POST['portes'];
						}
						$especificacao=$_POST['especificacao'];

						$referencia=$_POST['referencia'];

						$target_file_tmp = $_FILES["imagem"]["tmp_name"];
						$info=$_FILES["imagem"]["type"];
						$data=array(
							"store_id" => $store_id,
							"nome" => $nome,
							"marca" => $marca,
							"categoria" => $categoria,
							"preco" => $preco,
							"link" => $link,
							"portes" => $portes,
							"especificacao" => $especificacao,
							"referencia" => $referencia,
							"imagem" => $target_file_tmp,
							"imagetype"=> $info
						);
						$data=json_encode($data);
						$curl=curl_init("http://labpro.dev/product");
						curl_setopt($curl, CURLOPT_POST,1);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
						$response=curl_exec($curl);
						if(strcmp($response,"ok")==0)
						{
							header("Location: dashboard.php");
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
</div>
</body>
</html>
