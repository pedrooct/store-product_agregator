<?php
session_start();
if(!isset($_SESSION['id'])){
	$_SESSION['id']=1;
}
//$uid=$_GET['uid'];
$pid=$_GET['pid'];

/*if($uid=="")
{
  header('location: login.php');
}*/

//$curl = curl_init("http://labpro.dev/addwishlist/".$pid."/".$uid);
$curl = curl_init("http://labpro.dev/addwishlist/".$pid);

curl_setopt($curl, CURLOPT_POST, 1);
$response=curl_exec($curl);

header('location: shopping.php');


?>
