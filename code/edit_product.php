<?php
session_start();
if (!isset($_SESSION['id'])) {
	$_SESSION['id']='';
	header("Location: login_loja.php");
}
$id=$_GET['id'];


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
			<a class="pure-menu-heading" href="">Bem-vindo! Edite o seu produto </a>
			<li class="pure-menu-item"><a href="dashboard.php" class="pure-button"> DASHBOARD </a></li>
		</div>
	</div>

	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-2 pure-u-md-2-5">
				<form enctype="multipart/form-data" class="pure-form pure-form-stacked" method="POST">
					<?php
					if(isset($_GET['id']))
					{
						$json=file_get_contents("http://labpro.dev/obtainproduct/".$id);
						$data=json_decode($json);
						$nome=explode(" ",$data[0]->nome);
						$nome=implode("+",$nome);
						$especificacao=explode(" ",$data[0]->especificacao);
						$especificacao=implode("+",$especificacao);
					}
					?>
					<fieldset>
						<label for="produto" style="margin-top: 20px" >Adicionar produto:</label>
						<input name="nome" type="text" class="pure-input-rounded" value=<?php echo $nome;?> <?php echo !isset($_POST['nome']) ? "value='".$data[0]->nome."'":""; ?>  >
						<div class="pure-u-1 pure-u-md-1-3">
							<label for="categoria">Categoria</label>
							<select name="categoria" class="pure-input-1-2">
								<option value=<?php echo $data[0]->categoria;?> >Escolha uma opção:</option>
								<option value="">Categorias:</option>
								<option value="Laptops">Portateis</option>
								<option value="Computers & Accessories">Torres</option>
								<option value="Computer Components">Hardware</option>
								<option value="Electronics" >Eletrónica</option>
								<option value="Cell Phones & Accessories">Tablets</option>
								<option value="Cell Phones & Accessories">Telemovéis</option>
							</select>
							<select name="marca">
								<option value=<?php echo $data[0]->marca;?> >Marcas:</option>
								<option value="apple">Apple</option>
								<option value="microsoft">Microsoft</option>
								<option value="xiaomi">xiaomi</option>
								<option value="asus">asus</option>
								<option value="raspberry">raspberry</option>
								<option value="arduino">arduino</option>
							</select>
						</div>

						<input name="preco" type="text" class="pure-input-rounded" value=<?php echo $data[0]->preco;?> placeholder="preco"  >

						<input name="link" type="text" class="pure-input-rounded" value=<?php echo $data[0]->link;?> placeholder="link"  >

						<input name="portes" type="text" class="pure-input-rounded" value=<?php echo $data[0]->portes;?> placeholder="portes"  >

						<input name="especificacao" type="text" class="pure-input-rounded" value=<?php echo $especificacao;?> placeholder="especificacao"  >

						<input name="referencia" type="text" class="pure-input-rounded" value=<?php echo $data[0]->referencia;?> placeholder="referencia"  >

						<label for="imagem" >Imagem da marca:</label>
						<input name="imagem" type="file" class="pure-input-rounded" placeholder="Imagem da loja" >
					</fieldset>
					<button type="submit" name="registo" class="pure-button pure-button-primary">Editar</button>

					<?php

					if(isset($_POST['registo']))
					{

						$nome=$_POST['nome'];
						$marca=$_POST['marca'];

						$nome=explode("+",$nome);
						$nome=implode(" ",$nome);

						$categoria=$_POST['categoria'];

						if($_POST['preco']<=0)
						{
							$preco=$data[0]->preco;
						}
						else
						{
							$preco=$_POST['preco'];
						}

						$link=$_POST['link'];


						if($_POST['portes']<=0)
						{
							$portes=$data[0]->portes;
						}
						else
						{
							$portes=$_POST['portes'];
						}

						$especificacao=$_POST['especificacao'];
						$especificacao=explode("+",$especificacao);
						$especificacao=implode(" ",$especificacao);

						$referencia=$_POST['referencia'];

						$target_file_tmp = $_FILES["imagem"]["tmp_name"];
						if(empty($_FILES["imagem"]["tmp_name"]))
						{
							/*$target_file_tmp=BASE64_decode($data[0]->imagem);
							$target_file_tmp= imagecreatefromstring($target_file_tmp);
							//$target_file_tmp= imagecreatefromstring($data[0]->imagem);
							$info=$data[0]->imagetype;*/
							$data=array(
								"nome" => $nome,
								"marca" => $marca,
								"categoria" => $categoria,
								"preco" => $preco,
								"portes" => $portes,
								"especificacao" => $especificacao,
								"link" => $link,
								"referencia" => $referencia
							);
							$data=json_encode($data);
							$curl=curl_init("http://labpro.dev/producteditnoimage/".$id);
							curl_setopt($curl, CURLOPT_CUSTOMREQUEST,'PUT');
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
							$response=curl_exec($curl);
							if(strcmp($response,"Ok")==0){
								header("Location: dashboard.php");
							}
							return $response;
						}
						else
						{
							$info= $_FILES["imagem"]["type"];
						}
						$data=array(
							"nome" => $nome,
							"marca" => $marca,
							"categoria" => $categoria,
							"preco" => $preco,
							"portes" => $portes,
							"especificacao" => $especificacao,
							"link" => $link,
							"referencia" => $referencia,
							"imagem" => $target_file_tmp,
							"imagetype"=> $info
						);
						$data=json_encode($data);
						$curl=curl_init("http://labpro.dev/productedit/".$id);
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST,'PUT');
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
						$response=curl_exec($curl);
						if(strcmp($response,"Ok")==0){
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
