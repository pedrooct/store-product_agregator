<?php



session_start();

error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL & ~WARNING);



$page=$_GET['page'];
if(!isset($page))
{
	header('location: shopping.php?page=1');
}

if(isset($_SESSION['username']))
{
	$username =$_SESSION['username'];
	$nome= $_SESSION['nome'];
	$email=$_SESSION['email'];
	$id=$_SESSION['id'];
}

if(isset($_POST['searchbt']))
{
	header('location: shopping.php?page='.$page.'&searchbt=&produto='.$_POST['produto'].'&categories='.$_POST['categories'].'&brands='.$_POST['brands']);
}

if(isset($_POST['next_page']) && isset($_GET['searchbt']))
{
	$page++;
	header('location: shopping.php?page='.$page.'&searchbt=&produto='.$_GET['produto'].'&categories='.$_GET['categories'].'&brands='.$_GET['brands']);
}
if(isset($_POST['back_page']) && isset($_GET['searchbt']))
{
	if($page==1)
	{
		header('location: shopping.php?page='.$page.'&searchbt=&produto='.$_GET['produto'].'&categories='.$_GET['categories'].'&brands='.$_GET['brands']);

	}
	else{
		$page--;
		header('location: shopping.php?page='.$page.'&searchbt=&produto='.$_GET['produto'].'&categories='.$_GET['categories'].'&brands='.$_GET['brands']);

	}
}
if(isset($_POST['next_page']) && !isset($_GET['searchbt']))
{
	$page++;
	header('location: shopping.php?page='.$page);
}
if(isset($_POST['back_page'])  && !isset($_GET['searchbt']) )
{
	if($page==1)
	{
		header('location: shopping.php?page=1');
	}
	else{
		$page--;
		header('location: shopping.php?page='.$page);
	}
}



?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="Shooping">
	<title>web page of &ndash; Pedro Costa and Paulo Bento &ndash; Pure</title>

	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script
	src="https://code.jquery.com/jquery-3.2.1.min.js"
	integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	crossorigin="anonymous"></script>
	<title></title>

</head>

<body>
	<div class="header">
		<div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
			<a class="pure-menu-heading" href="shopping.php?page=1">Bem-vindo <?php echo $nome; ?> ! ;) </a>
			<?php
			if($username=="")
			{
				echo '<li class="pure-menu-item"><a href="login.php" class="pure-button"> LOGIN </a></li>';
				echo '<li class="pure-menu-item"><a href="register.php" class="pure-button"> Registar </a></li>';
			}
			else
			{
				echo '<li class="pure-menu-item"><a href="logout.php" class="pure-button"> Logout </a></li>';
				echo '<li class="pure-menu-item"><a href="profile.php" class="pure-button"> Perfil </a></li>';
			}

			?>
		</div>
	</div>
	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-1 pure-u-md-1-5">
				<form class="pure-form pure-form-stacked" method="POST">
					<fieldset>
						<label for="produto"> Pesquisa</label>
						<input name="produto" type="text" class="pure-input-rounded" placeholder="EX: macbook">
						<select name="categories">
							<option value="">Categorias:</option>
							<option value="Laptops">Portateis</option>
							<option value=<?php echo "Computers+%26+Accessories"?> >Torres</option>
							<option value=<?php echo "Computer+Components"?> >Hardware</option>
							<option value="Electronics">Eletrónica</option>
							<option value=<?php echo "Cell+Phones+%26+Accessories"?> >Tablets</option>
							<option value=<?php echo "Cell+Phones+%26+Accessories"?> >Telemovéis</option>
						</select>
						<select name="brands">
							<option value="">Marcas:</option>
							<option value="apple">Apple</option>
							<option value="microsoft">Microsoft</option>
							<option value="xiaomi">xiaomi</option>
							<option value="asus">asus</option>
							<option value="raspberry">raspberry</option>
							<option value="arduino">arduino</option>
						</select>
						<button type="submit" name="searchbt" class="pure-button pure-button-primary">Procura</button>
					</fieldset>
				</form>
				<?php
				if(isset($_GET['searchbt']))
				{
					$produtos_raw=$_GET['produto'];
					$produtos=explode(" ",$produtos_raw);
					$produtos=implode("%20",$produtos);
					$categories=$_GET['categories'];
					$categories=explode(" ",$categories);
					$categories=implode("+",$categories);
					$categories=explode("&",$categories);
					$categories=implode("%26",$categories);
					$brands=$_GET['brands'];
					if(strcmp($produtos,"")==0 && strcmp($categories,"")==0 && strcmp($brands,"")==0 )
					{
						echo '<div id="erro" class="error">Insira um produto na barra de pesquisa ou escolha uma categoria !</div>';
						$offset=$page-1;
						$local=file_get_contents("http://labpro.dev/obtainbest/".$offset);
						$obtain=json_decode($local);
						$offsetindix=$offset+1;
						$pathindix='http://labpro.dev/indixdefault/'.$offsetindix;
						$sendmeindix='sendmeindix.php?offset='.$offset.'&id=';
						$reportme='reportmeindix.php?offset='.$offsetindix.'&id=';


					}
					else if(strcmp($produtos,"")!=0 && strcmp($categories,"")==0 && strcmp($brands,"")==0)
					{

						$offset=$page-1;
						$local=file_get_contents("http://labpro.dev/searchproduct/".$produtos."/".$offset);
						$obtain=json_decode($local);
						$offsetindix=$offset+1;
						$pathindix='http://labpro.dev/indixproduct/'.$produtos.'/'.$offsetindix;
						$sendmeindix='sendmeindix.php?offset='.$offsetindix.'&produto='.$produtos.'&id=';
						$reportme='reportmeindix.php?offset='.$offsetindix.'&produto='.$produtos.'&id=';
					}
					else if(strcmp($produtos,"")==0 && strcmp($categories,"")!=0 && strcmp($brands,"")==0)
					{
						$offset=$page-1;
						$local=file_get_contents("http://labpro.dev/searchcategorie/".$categories."/".$offset);
						$obtain=json_decode($local);
						$offsetindix=$offset+1;
						$pathindix='http://labpro.dev/indixproductcategory/'.$categories."/".$offsetindix;
						$sendmeindix='sendmeindix.php?offset='.$offsetindix.'&categoria='.$categories.'&id=';
						$reportme='reportmeindix.php?offset='.$offsetindix.'&categoria='.$categories.'&id=';
					}
					else if(strcmp($produtos,"")==0 && strcmp($categories,"")==0 && strcmp($brands,"")!=0)
					{
						$offset=$page-1;
						$local=file_get_contents("http://labpro.dev/searchbrand/".$brands."/".$offset);
						$obtain=json_decode($local);
						$offsetindix=$offset+1;
						$pathindix='http://labpro.dev/indixproductbrand/'.$brands."/".$offsetindix;
						$sendmeindix='sendmeindix.php?offset='.$offsetindix.'&marca='.$brands.'&id=';
						$reportme='reportmeindix.php?offset='.$offsetindix.'&marca='.$brands.'&id=';
					}
					else if(strcmp($produtos,"")!=0 && strcmp($categories,"")!=0 && strcmp($brands,"")==0)
					{
						$offset=$page-1;
						$local=file_get_contents('http://labpro.dev/searchproductcategorie/'.$produtos.'/'.$categories.'/'.$offset);
						$obtain=json_decode($local);
						$offsetindix=$offset+1;
						$pathindix='http://labpro.dev/indixsearch/product/category/'.$produtos.'/'.$categories.'/'.$offsetindix;
						$sendmeindix='sendmeindix.php?offset='.$offsetindix.'&produto='.$produtos.'&categoria='.$categories.'&id=';
						$reportme='reportmeindix.php?offset='.$offsetindix.'&produto='.$produtos.'&categoria='.$categories.'&id=';
					}
					else if(strcmp($produtos,"")!=0 && strcmp($categories,"")==0 && strcmp($brands,"")!=0)
					{
						$offset=$page-1;
						$local=file_get_contents("http://labpro.dev/searchproductmarca/".$produtos."/".$brands."/".$offset);
						$obtain=json_decode($local);
						$offsetindix=$offset+1;
						$pathindix='http://labpro.dev/indixsearch/product/brand/'.$produtos.'/'.$brands.'/'.$offsetindix;
						$sendmeindix='sendmeindix.php?offset='.$offsetindix.'&produto='.$produtos.'&marca='.$brands.'&id=';
						$reportme='reportmeindix.php?offset='.$offsetindix.'&produto='.$produtos.'&marca='.$brands.'&id=';
					}
					else if(strcmp($produtos,"")!=0 && strcmp($categories,"")!=0 && strcmp($brands,"")!=0)
					{
						$offset=$page-1;
						$local=file_get_contents("http://labpro.dev/search/product/category/brand/".$produtos."/".$categories."/".$brands."/".$offset);
						$obtain=json_decode($local);
						$offsetindix=$offset+1;
						$pathindix='http://labpro.dev/indixsearch/product/brand/category/'.$produtos.'/'.$brands.'/'.$categories.'/'.$offsetindix;
						$sendmeindix='sendmeindix.php?offset='.$offsetindix.'&produto='.$produtos.'&categoria='.$categories.'&marca='.$brands.'&id=';
						$reportme='reportmeindix.php?offset='.$offsetindix.'&produto='.$produtos.'&categoria='.$categories.'&marca='.$brands.'&id=';
					}
					else if(strcmp($produtos,"")==0 && strcmp($categories,"")!=0 && strcmp($brands,"")!=0)
					{
						$offset=$page-1;
						$local=file_get_contents("http://labpro.dev/search/category/brand/".$brands."/".$categories."/".$offset);
						$obtain=json_decode($local);
						$offsetindix=$offset+1;
						$pathindix='http://labpro.dev/indixsearch/brand/category/'.$brands.'/'.$categories.'/'.$offsetindix;
						$sendmeindix='sendmeindix.php?offset='.$offsetindix.'&categoria='.$categories.'&marca='.$brands.'&id=';
						$reportme='reportmeindix.php?offset='.$offsetindix.'&categoria='.$categories.'&marca='.$brands.'&id=';
					}
				}
				else
				{
					$offset=$page-1;
					$local=file_get_contents("http://labpro.dev/obtainbest/".$offset);
					$obtain=json_decode($local);
					$offsetindix=$offset+1;
					$pathindix='http://labpro.dev/indixdefault/'.$offsetindix;
					$sendmeindix='sendmeindix.php?offset='.$offsetindix.'&id=';
					$reportme='reportmeindix.php?offset='.$offsetindix.'&id=';
				}
				?>
				<script>
				$(function (){
					var $api = $('#api');
					var $r = $('#erro');
					var $text= $('#text');
					var $loading = $('#loading').hide();
					var html='';
					var conversion='';
					var $bt = $('#bt');
					var sendme = '<?php echo $sendmeindix; ?>';
					var reportme= '<?php echo $reportme; ?>';
					var offset = '<?php echo $offsetindix; ?>';
					$(document)
					.ajaxStart(function () {
						$loading.show();
						$r.show();
						$bt.hide();
						$text.hide();
					})
					.ajaxStop(function () {
						$loading.hide();
						$r.hide();
						$bt.show();
						$text.show();
					});
					$.ajax({
						async: true,
						dataType: "json",
						contentType: "application/json",
						type: 'GET',
						url: '<?php echo $pathindix; ?>',
					})
					.done(function table($data){
						for (i = 0; i < 8; i++) {
							html="";
							html+=('<div class="pure-u-1-2 pure-u-md-1-2 pure-u-lg-1-4">');
							html+=('<div class="columns">');
							html+=('<ul  class="price">');
							html+=('<li class="header">'+ $data.result.products[i].title +'</li>');
							html+=('<li class="grey">'+ $data.result.products[i].brandName +'</li>');
							html+=('<li id="wolf'+i+'" > <img src= '+ $data.result.products[i].imageUrl + ' "height="200" width="200"> </li>');
							html+=('<li class="grey"><a href='+sendme+i+' class="pure-button">Envia-me</a></li>');
							html+=('<li class="grey"><a href='+reportme+i+' class="pure-button">Reportar produto </a></li>');
							html+=('</ul>');
							html+=('</div>');
							html+=('</div>');
							$api.append(html);
						};
					})
					.done(convert());
				});
				function convert(){
					$.ajax({
						async: true,
						dataType: "json",
						contentType: "application/json",
						type: 'GET',
						url: '<?php echo $pathindix; ?>',
						success: function ($data){
							for (j = 0; j < 8; j++) {
								$.ajax({
									forj: j ,
									async: true,
									type: 'GET',
									url: 'http://labpro.dev/currency/'+$data.result.products[j].maxSalePrice,
									success: function max($convert){
										appendj($convert,this.forj);

										function appendj($convert,forj) {
											$('#wolf'+forj).append('<li class="grey"> Preço maximo='+ $convert +'</li>');
										}
									}
								});
							}
							for (k = 0; k < 8; k++) {
								$.ajax({
									fork: k ,
									async: true,
									type: 'GET',
									url: 'http://labpro.dev/currency/'+$data.result.products[k].minSalePrice,

									success: function min($convert){
										appendk($convert,this.fork);
										function appendk($convert,fork) {
											$('#wolf'+fork).append('<li class="grey"> Preço minimo='+ $convert +'</li>');
										}
									}
								});
							}
						}
					});
				}
				</script>
				<?php
				if(sizeof($obtain)==0)
				{
					echo '<div id="info" class="info"> Ooops o produto procurado não foi encontrado nas nossas lojas ou não temos mais pordutos !</div>';
				}
				else {
					for($i=0; $i<sizeof($obtain); $i++)
					{
						$json=file_get_contents("http://labpro.dev/obtainstore/".$obtain[$i]->store_id);
						$loja=json_decode($json);
						?>
						<div class="columns">
							<ul class="price">
								<li class="header"> <?php echo $obtain[$i]->nome ;?></li>
								<li class="grey"> <?php echo $loja[0]->nome ;?> </li>
								<li class="grey"> <?php echo $obtain[$i]->marca ;?> </li>
								<li><?php echo '<img src="data:image/'.$obtain[$i]->imagetype.';base64,'.$obtain[$i]->imagem.'" height="200" width="200" />';?></li>
								<li><?php echo "preço: ",$obtain[$i]->preco, " euros"; ?></li>
								<li><?php echo "portes: ",$obtain[$i]->portes," euros"; ?></li>
								<div class="box">
									<a class="pure-button" href=<?php echo "#popup".$i ?>>Especificações</a>
								</div>
								<div id=<?php echo "popup".$i; ?> class="overlay">
									<div class="popup">
										<h2>Especificação do produto</h2>
										<a class="close" href="http://app.dev/lab_project/lab_prog/public/shopping.php?page=1">&times;</a>
										<div class="content">
											<?php echo $obtain[$i]->especificacao;?>
										</div>
									</div>
								</div>
								<li class="grey"> <a href=<?php echo $obtain[$i]->link ;?> class="pure-button">Ver produto</a></li>
								<li class="grey"> <a href=<?php echo "sendme.php?id=",$obtain[$i]->id ;?> name="send" class="pure-button">Envia-me</a></li>
								<!--<li class="grey"> <a href=<?php //echo "whislist.php?pid=",$obtain[$i]->id,"&uid=".$id ;?> name="whis" class="pure-button">Lista de desejo</a></li>-->
								<li class="grey"> <a href=<?php echo "whislist.php?pid=",$obtain[$i]->id ;?> name="whis" class="pure-button">Lista de desejo</a></li>
							</ul>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>

	<div class="container">
		<div id="loading" class="loader" style="margin-bottom:50px"></div>
		<p id="text" >*preços resultantes de buscas externas com base indix.com API</p>
		<div id="api" class="pure-g">


		</div>

		<form id="bt" method="post" style="margin-bottom:50px">
			<button type="submit" style="postion:relative"  name="back_page" class="pure-button pure-button-primary">Pagina anterior</button>
			<button type="submit" style="postion:relative"  name="next_page" class="pure-button pure-button-primary">Ver mais</button>
		</form>
	</div>
	<div class="footer">
		<a href="register_loja.php" > Resgistar loja / </a>
		<a href="login_loja.php">Login loja </a>
		© 2017! Projecto de Laboratório de Programacao Pedro Costa Nº: 31179 & Paulo Bento Nº: 33595.
	</div>
</body>
</html>
