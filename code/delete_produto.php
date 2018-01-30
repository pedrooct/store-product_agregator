<?php

$id=$_GET['id'];

$json=file_get_contents("http://labpro.dev/obtainimage/".$id);
$file=json_decode($json);
unlink($file[0]->imagem);

$curl=curl_init("http://labpro.dev/productdel/".$id);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response=curl_exec($curl);



header('location: dashboard.php?page=1');

?>
