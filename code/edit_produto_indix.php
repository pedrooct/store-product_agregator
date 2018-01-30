<?php

$offset=$_GET['offset'];
$id=$_GET['id'];

if(isset($_GET['produto']) && !isset($_GET['categoria']) && !isset($_GET['marca']))
{
	$produto=$_GET['produto'];
	$indix=file_get_contents('http://labpro.dev/indixproduct/'.$produto.'/'.$offset);
	$indix=json_decode($indix);
	$nome=explode(" ",$indix->result->products[$id]->title);
	$nome=implode("+",$nome);
	$categoria=explode(" ",$indix->result->products[$id]->categoryName);
	$categoria=implode("+",$categoria);
	$path='http://labpro.dev/editindixproduct/'.$produto.'/'.$offset;


}
else if(!isset($_GET['produto']) && isset($_GET['categoria']) && !isset($_GET['marca']))
{
	$categoria=$_GET['categoria'];
	$indix=file_get_contents('http://labpro.dev/indixproductcategory/'.$categoria."/".$offset);
	$indix=json_decode($indix);
	$nome=explode(" ",$indix->result->products[$id]->title);
	$nome=implode("+",$nome);
	$categoria=explode(" ",$indix->result->products[$id]->categoryName);
	$categoria=implode("+",$categoria);
	$path='http://labpro.dev/editindixcategoria/'.$categoria.'/'.$offset;;


}
else if(!isset($_GET['produto']) && !isset($_GET['categoria']) && isset($_GET['marca']))
{
	$marca=$_GET['marca'];
	$indix=file_get_contents('http://labpro.dev/indixproductbrand/'.$marca."/".$offset);
	$indix=json_decode($indix);
	$nome=explode(" ",$indix->result->products[$id]->title);
	$nome=implode("+",$nome);
	$categoria=explode(" ",$indix->result->products[$id]->categoryName);
	$categoria=implode("+",$categoria);
	$path='http://labpro.dev/editindixmarca/'.$marca.'/'.$offset;


}
else if(isset($_GET['produto']) && isset($_GET['categoria']) && !isset($_GET['marca']))
{
	$produto=$_GET['produto'];
	$categoria=$_GET['categoria'];
	$indix=file_get_contents('http://labpro.dev/indixsearch/product/category/'.$produto.'/'.$categoria.'/'.$offset);
	$indix=json_decode($indix);
	$nome=explode(" ",$indix->result->products[$id]->title);
	$nome=implode("+",$nome);
	$categoria=explode(" ",$indix->result->products[$id]->categoryName);
	$categoria=implode("+",$categoria);
	$path='http://labpro.dev/editindixprodutocategoria/'.$produto."/".$categoria."/".$offset;



}
else if(isset($_GET['produto']) && !isset($_GET['categoria']) && isset($_GET['marca']))
{
	$produto=$_GET['produto'];
	$marca=$_GET['marca'];
	$indix=file_get_contents('http://labpro.dev/indixsearch/product/brand/'.$produto.'/'.$marca.'/'.$offset);
	$indix=json_decode($indix);
	$nome=explode(" ",$indix->result->products[$id]->title);
	$nome=implode("+",$nome);
	$categoria=explode(" ",$indix->result->products[$id]->categoryName);
	$categoria=implode("+",$categoria);
	$path='http://labpro.dev/editindixprodutomarca/'.$produto.'/'.$marca.'/'.$offset;


}
else if(!isset($_GET['produto']) && isset($_GET['categoria']) && isset($_GET['marca']))
{
	$categoria=$_GET['categoria'];
	$marca=$_GET['marca'];
	$indix=file_get_contents('http://labpro.dev/indixsearch/brand/category/'.$marca.'/'.$categoria.'/'.$offset);
	$indix=json_decode($indix);
	$nome=explode(" ",$indix->result->products[$id]->title);
	$nome=implode("+",$nome);
	$categoria=explode(" ",$indix->result->products[$id]->categoryName);
	$categoria=implode("+",$categoria);
	$path='http://labpro.dev/editindixcategoriamarca/'.$categoria.'/'.$marca.'/'.$offset;

}
else if(isset($_GET['produto']) && isset($_GET['categoria']) && isset($_GET['marca']))
{
	$produto=$_GET['produto'];
	$categoria=$_GET['categoria'];
	$marca=$_GET['marca'];
	$indix=file_get_contents('http://labpro.dev/indixsearch/product/brand/category/'.$produto.'/'.$marca.'/'.$categoria.'/'.$offset);
	$indix=json_decode($indix);
	$nome=explode(" ",$indix->result->products[$id]->title);
	$nome=implode("+",$nome);
	$categoria=explode(" ",$indix->result->products[$id]->categoryName);
	$categoria=implode("+",$categoria);
	$path='http://labpro.dev/editindixcategoriamarcaproduto/'.$categoria."/".$marca.'/'.$produto.'/'.$offset;


}
else{
	$indix=file_get_contents('http://labpro.dev/indixdefault/'.$offset);
	$indix=json_decode($indix);
	$nome=explode(" ",$indix->result->products[$id]->title);
	$nome=implode("+",$nome);
	$categoria=explode(" ",$indix->result->products[$id]->categoryName);
	$categoria=implode("+",$categoria);
	$path='http://labpro.dev/editindixdefault/'.$offset;
}

if(isset($_POST['registo']))
{

	$nome=$_POST['nome'];
	$nome=explode("+",$nome);
	$nome=implode(" ",$nome);

	$categoria=$_POST['categoria'];
	$categoria=explode("+",$categoria);
	$categoria=implode(" ",$categoria);

	$indix->result->products[$id]->title=$nome;
	$indix->result->products[$id]->brandName=$_POST['marca'];
	$indix->result->products[$id]->categoryName=$categoria;
	$indix->result->products[$id]->imageUrl=$_POST['imagem'];
	$indix->result->products[$id]->minSalePrice=$_POST['minprice'];
	$indix->result->products[$id]->maxSalePrice=$_POST['maxprice'];
	$indix=json_encode($indix);
	$curl=curl_init($path);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST,'PUT');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS,$indix);
	$response=curl_exec($curl);
	if(strcmp($response,"ok")==0){
		header("Location: shopping.php");
	}
	echo $response;
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
			<a class="pure-menu-heading" href="">Bem-vindo! Edite o produto INDIX </a>
			<li class="pure-menu-item"><a href="shopping.php" class="pure-button"> BACK </a></li>
		</div>
	</div>

	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-2 pure-u-md-2-5">
				<form  class="pure-form pure-form-stacked" method="POST">
					<fieldset>
						<label for="produto" style="margin-top: 20px" >Edite produto:</label>
						<input name="nome" type="text" class="pure-input-rounded" value=<?php echo $nome;?>>
						<label for="imagem" style="margin-top: 20px" >Link Imagem:</label>
						<input name="imagem" type="text" class="pure-input-rounded" value=<?php echo $indix->result->products[$id]->imageUrl;?>   >
						<div class="pure-u-1 pure-u-md-1-3">
							<label for="categoria">Categoria</label>
							<select name="categoria" class="pure-input-1-2">
								<option value=<?php echo $categoria ;?> >Escolha uma opção:</option>
								<option value="">Categorias:</option>
								<option value="Laptops">Portateis</option>
								<option value="Computers & Accessories">Torres</option>
								<option value="Computer Components">Hardware</option>
								<option value="Electronics" >Eletrónica</option>
								<option value="Cell Phones & Accessories">Tablets</option>
								<option value="Cell Phones & Accessories">Telemovéis</option>
							</select>
							<select name="marca">
								<option value=<?php echo $indix->result->products[$id]->brandName;?> >Marcas:</option>
								<option value="apple">Apple</option>
								<option value="microsoft">Microsoft</option>
								<option value="xiaomi">xiaomi</option>
								<option value="asus">asus</option>
								<option value="raspberry">raspberry</option>
								<option value="arduino">arduino</option>
							</select>
							<label for="maxprice" style="margin-top: 20px" >Preço maximo(dollar):</label>
							<input name="maxprice" type="float" class="pure-input-rounded" value=<?php echo $indix->result->products[$id]->maxSalePrice ;?>   >
							<label for="minprice" style="margin-top: 20px" >Preço minimo(dollar):</label>
							<input name="minprice" type="float" class="pure-input-rounded" value=<?php echo $indix->result->products[$id]->minSalePrice ;?>   >
						</div>
					</fieldset>
					<button type="submit" name="registo" class="pure-button pure-button-primary">Editar</button>

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
