<?php
session_start();


$page=$_GET['page'];
$ident=$_SESSION['ident'];
$id=$_SESSION['id'];

if(!isset($page))
{
	header('location: dashboard.php?page=1');
}

?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="Dashboard">
	<title>web page of &ndash; Pedro Costa &ndash; Pure</title>

	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title></title>

</head>

<body>
	<div class="header">
		<div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
			<a class="pure-menu-heading" href="">Bem-vindo! </a>
			<li class="pure-menu-item"><a href="logout.php" class="pure-button"> Logout </a></li>
			<li class="pure-menu-item"><a href="add_product.php" class="pure-button"> Adiconar produto </a></li>
		</div>
	</div>
	<div class="content">
		<div class="pure-g">
			<div class="l-box-lrg pure-u-1 pure-u-md-2-5">
				<form class="pure-form pure-form-stacked" method="POST">
					<fieldset>
						<table class="table-title" style="margin:30px" >
							<thead>
								<tr>
									<th>ID</th>
									<th>nome</th>
									<th>marca</th>
									<th>categoria</th>
									<th>imagem</th>
									<th>preço </th>
									<th>link</th>
									<!--<th>rating</th>-->
									<th>portes</th>
									<th>especificação</th>
									<th>referencia</th>
									<th>Apagar Produto</th>
									<th>Editar produto</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$offset=$page-1;
								$json=file_get_contents("http://labpro.dev/obtainproductstore/".$id."/".$offset);
								$json=json_decode($json);
								$size=sizeof($json);
								if(isset($json->result))
								{
									echo '<div class="info">Sem produtos!</div>';
								}
								else {
									for($i=0;$i<$size;$i++)
									{
										echo "<tr>";
										echo "<td>",$json[$i]->id,"</td>";
										echo "<td>",$json[$i]->nome,"</td>";
										echo "<td>",$json[$i]->marca,"</td>";
										echo "<td>",$json[$i]->categoria,"</td>";
										echo '<td> <img src="data:image/'.$json[$i]->imagetype.';base64,'.$json[$i]->imagem.'"  width="100" height="100"></td>';
										echo "<td>",$json[$i]->preco," euros </td>";
										echo "<td>",$json[$i]->link,"</td>";
										//echo "<td>",$json[$i]->rating,"</td>";
										echo "<td>",$json[$i]->portes," euros </td>";
										echo "<td>";
										echo '<textarea class="scrollabletextbox" name="specs" readonly>';
										echo $json[$i]->especificacao;
										echo '</textarea>';
										echo "</td>";
										echo "<td>",$json[$i]->referencia,"</td>";
										echo "<td>","<a ",'class="pure-button"'," href=delete_produto.php?id=",$json[$i]->id,"> X </a></td>";
										echo "<td>","<a ",'class="pure-button"'," href=edit_product.php?id=",$json[$i]->id,">  Editar </a> </td>";
										echo "</tr>";
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="footer">
				© 2017! Projecto de Laboratório de Programacao Pedro Costa Nº: 31179 & Paulo Bento Nº: 33595.
			</div>
		</div>
	</body>
	</html>
