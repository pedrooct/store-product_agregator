<?php
//$uid=$_GET['uid'];
$pid=$_GET['pid'];

/*if($uid=="")
{
  header('location: login.php');
}*/

//$curl = curl_init("http://labpro.dev/delwishlist/".$pid."/".$uid);
$curl = curl_init("http://labpro.dev/delwishlist/".$pid);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST,'DELETE');
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response=curl_exec($curl);

header('location: profile.php');


?>
