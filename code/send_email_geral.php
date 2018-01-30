<?php

require_once "../vendor/autoload.php";

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
if(!isset($_SESSION['username'])){
  $_SESSION['username']='';
}
if(!isset($_SESSION['id_edit'])){
  $_SESSION['id_edit']=1;
}

class Mail{

  public function mail_Send_recover($username)
  {
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    $email="null";
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      // TCP port to connect to
      $conn= mysqli_connect("localhost","root","root","labpro") or die ("Unable to connect to the database");
      $stuff=mysqli_query($conn,"SELECT id,username,email FROM utilizador");
      while($row = mysqli_fetch_array($stuff))
      {
        if(strcmp($row['username'],$username)==0)
        {
          $email=$row['email'];
          $id=$row['id'];
        }
      }

      //Recipients
      $mail->setFrom('labproaulas@gmail.com','Recover');
      $mail->addAddress($email,$username);     // Add recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'recover your password';
      $mail->Body    = '<td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/password_forget.php?id='. $id .'> recover </a> </td>';
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
      //echo 'Message has been sent';
    } catch (Exception $e) {
      echo $email.'Mailer Error:'. $mail->ErrorInfo;
    }
  }
  public function mail_Send_recover_store($nident)
  {
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    $email="null";
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      // TCP port to connect to
      $conn= mysqli_connect("localhost","root","root","labpro") or die ("Unable to connect to the database");
      $stuff=mysqli_query($conn,"SELECT id,nome,NIdentificacao,email FROM store");
      while($row = mysqli_fetch_array($stuff))
      {
        if(strcmp($row['NIdentificacao'],$nident)==0)
        {
          $email=$row['email'];
          $id=$row['id'];
          $nome=$row['nome'];
        }
      }

      //Recipients
      $mail->setFrom('labproaulas@gmail.com','Recover');
      $mail->addAddress($email,$nome);     // Add recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'recover your password';
      $mail->Body    = '<td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/password_forget_store.php?id='. $id .'> recover </a> </td>';
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
      //echo 'Message has been sent';
    } catch (Exception $e) {
      echo $email.'Mailer Error:'. $mail->ErrorInfo;
    }
  }
  public function mail_send_newsletter()
  {
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    $winner=array();
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      // TCP port to connect to
      $conn= mysqli_connect("localhost","root","root","labpro") or die ("Unable to connect to the database");
      $stuff=mysqli_query($conn,"SELECT id,nome,email FROM utilizador order by rand() limit 5");
      while($row = mysqli_fetch_array($stuff))
      {
        $mail->addAddress($row['email'],$row['username']);
        $mail->setFrom('labproaulas@gmail.com','WINNER');
        $mail->isHTML(false);                                  // Set email format to HTML
        $mail->Subject = 'PARABENS';
        $mail->Body    = 'PARABENS !!! ganhou o premio de este test ';
        $mail->send();
      }
    } catch (Exception $e) {
      echo $email.'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_product($product_id,$user_mail,$user_id,$nome)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents("http://labpro.dev/obtainproduct/".$product_id);
    $data=json_decode($data);
    $loja=file_get_contents("http://labpro.dev/obtainstore/".$data[0]->store_id);
    $loja=json_decode($loja);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">'.$data[0]->nome.'</li>
      <li class="grey"> '.$loja[0]->nome.'</li>
      <li>preco:'.$data[0]->preco.'</li>
      <li>portes:'.$data[0]->portes.'</li>
      <li>especificao:'.$data[0]->especificacao.'</li>';


      $encoding = "base64";
      $type = "image/".$data[0]->imagetype;
      $mail->AddStringAttachment(base64_decode($data[0]->imagem), $data[0]->nome, $encoding, $type);
      $mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_product_pdf($product_id,$user_mail,$user_id,$nome,$pdf)
  {
    $mail = new PHPMailer(true);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    = 'Produto';
      $mail->addAttachment($pdf);
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;

    }
  }
  public function mail_send_default_indix($product_id,$user_mail,$user_id,$nome,$offset)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents("http://labpro.dev/indixdefault/".$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>';


      //$mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_product_indix($product_id,$product,$user_mail,$user_id,$nome,$offset)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixproduct/'.$product.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header"> Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>';


      //$mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_category_indix($product_id,$categoria,$user_mail,$user_id,$nome,$offset)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixproductcategory/'.$categoria.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header"> Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>';


      //$mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_brand_indix($product_id,$marca,$user_mail,$user_id,$nome,$offset)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixproductbrand/'.$marca."/".$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header"> Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>';


      //$mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_product_category_indix($product_id,$produto,$categoria,$user_mail,$user_id,$nome,$offset)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixsearch/product/category/'.$produto.'/'.$categoria.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header"> Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>';


      //$mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_product_brand_indix($product_id,$produto,$marca,$user_mail,$user_id,$nome,$offset)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixsearch/product/brand/'.$produto.'/'.$marca.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header"> Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>';


      //$mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_category_brand_indix($product_id,$categoria,$marca,$user_mail,$user_id,$nome,$offset)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixsearch/brand/category/'.$marca.'/'.$categoria.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header"> Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>';


      //$mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_send_product_category_brand_indix($product_id,$produto,$categoria,$marca,$user_mail,$user_id,$nome,$offset)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixsearch/product/brand/category/'.$produto.'/'.$marca.'/'.$categoria.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header"> Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>';


      //$mail->addAttachment();
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_report_product($product_id,$user_mail,$user_id,$nome)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents("http://labpro.dev/obtainproduct/".$product_id);
    $data=json_decode($data);
    $loja=file_get_contents("http://labpro.dev/obtainstore/".$data[0]->store_id);
    $loja=json_decode($loja);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto';
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">'.$data[0]->nome.'</li>
      <li class="grey"> '.$loja[0]->nome.'</li>
      <li>preco:'.$data[0]->preco.'</li>
      <li>portes:'.$data[0]->portes.'</li>
      <li>especificao:'.$data[0]->especificacao.'</li>';


      $encoding = "base64";
      $type = "image/".$data[0]->imagetype;
      $mail->AddStringAttachment(base64_decode($data[0]->imagem), $data[0]->nome, $encoding, $type);
      //$mail->addAttachment($img);
      $mail->send();
    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_report_default_indix($product_id,$user_mail,$user_id,$nome,$offset,$messagem)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents("http://labpro.dev/indixdefault/".$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado com sucesso';
      $mail->Body    ='Produto reportado com sucesso - '.$messagem;
      $mail->send();

      $mail->addAddress("31179@ufp.edu.pt",$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado por: '.$user_mail;
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Messagem do utilizador: '.$messagem.'</li>
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>
      <td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/edit_produto_indix.php?id='.$product_id.'&offset='.$offset.'> Editar produto </a> </td>';
      $mail->send();


    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;
    }
  }


  public function mail_report_product_indix($product_id,$product,$user_mail,$user_id,$nome,$offset,$messagem)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixproduct/'.$product.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado com sucesso';
      $mail->Body    ='Produto reportado com sucesso - '.$messagem;
      $mail->send();

      $mail->addAddress("31179@ufp.edu.pt",$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado por: '.$user_mail;
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Messagem do utilizador: '.$messagem.'</li>
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>
      <td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/edit_produto_indix.php?id='.$product_id.'&produto='.$product.'&offset='.$offset.'> Editar produto </a> </td>';
      $mail->send();

    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_report_category_indix($product_id,$categoria,$user_mail,$user_id,$nome,$offset,$messagem)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixproductcategory/'.$categoria.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado com sucesso';
      $mail->Body    ='Produto reportado com sucesso - '.$messagem;
      $mail->send();

      $mail->addAddress("31179@ufp.edu.pt",$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado por: '.$user_mail;
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Messagem do utilizador: '.$messagem.'</li>
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>
      <td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/edit_produto_indix.php?id='.$product_id.'&categoria='.$categoria.'&offset='.$offset.'> Editar produto </a> </td>';


      $mail->send();

    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_report_brand_indix($product_id,$marca,$user_mail,$user_id,$nome,$offset,$messagem)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixproductbrand/'.$marca."/".$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado com sucesso';
      $mail->Body    ='Produto reportado com sucesso - '.$messagem;
      $mail->send();

      $mail->addAddress("31179@ufp.edu.pt",$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado por: '.$user_mail;
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Messagem do utilizador: '.$messagem.'</li>
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>
      <td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/edit_produto_indix.php?id='.$product_id.'&marca='.$marca.'&offset='.$offset.'> Editar produto </a> </td>';

      $mail->send();

    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_report_product_category_indix($product_id,$produto,$categoria,$user_mail,$user_id,$nome,$offset,$messagem)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixsearch/product/category/'.$produto.'/'.$categoria.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado com sucesso';
      $mail->Body    ='Produto reportado com sucesso - '.$messagem;
      $mail->send();

      $mail->addAddress("31179@ufp.edu.pt",$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado por: '.$user_mail;
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Messagem do utilizador: '.$messagem.'</li>
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>
      <td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/edit_produto_indix.php?id='.$product_id.'&produto='.$produto.'&categoria='.$categoria.'&offset='.$offset.'> Editar produto </a> </td>';

      $mail->send();

    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_report_product_brand_indix($product_id,$produto,$marca,$user_mail,$user_id,$nome,$offset,$messagem)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixsearch/product/brand/'.$produto.'/'.$marca.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado com sucesso';
      $mail->Body    ='Produto reportado com sucesso - '.$messagem;
      $mail->send();

      $mail->addAddress("31179@ufp.edu.pt",$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado por: '.$user_mail;
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Messagem do utilizador: '.$messagem.'</li>
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>
      <td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/edit_produto_indix.php?id='.$product_id.'&produto='.$produto.'&marca='.$marca.'&offset='.$offset.'> Editar produto </a> </td>';

      $mail->send();

    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_report_category_brand_indix($product_id,$categoria,$marca,$user_mail,$user_id,$nome,$offset,$messagem)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixsearch/brand/category/'.$marca.'/'.$categoria.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado com sucesso';
      $mail->Body    ='Produto reportado com sucesso - '.$messagem;
      $mail->send();

      $mail->addAddress("31179@ufp.edu.pt",$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado por: '.$user_mail;
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Messagem do utilizador: '.$messagem.'</li>
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>
      <td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/edit_produto_indix.php?id='.$product_id.'&marca='.$marca.'&categoria='.$categoria.'&offset='.$offset.'> Editar produto </a> </td>';


      $mail->send();

    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
  public function mail_report_product_category_brand_indix($product_id,$produto,$categoria,$marca,$user_mail,$user_id,$nome,$offset,$messagem)
  {
    $mail = new PHPMailer(true);

    $data=file_get_contents('http://labpro.dev/indixsearch/product/brand/category/'.$produto.'/'.$marca.'/'.$categoria.'/'.$offset);
    $data=json_decode($data);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'labproaulas@gmail.com';                 // SMTP username
      $mail->Password = 'Labproaulas10';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;

      $maxprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->maxSalePrice);
      $minprice=file_get_contents('http://labpro.dev/currency/'.$data->result->products[$product_id]->minSalePrice);

      // TCP port to connect to
      $mail->addAddress($user_mail,$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado com sucesso';
      $mail->Body    ='Produto reportado com sucesso - '.$messagem;
      $mail->send();

      $mail->addAddress("31179@ufp.edu.pt",$nome);
      $mail->setFrom('labproaulas@gmail.com','Produto pedido de indix');
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Produto reportado por: '.$user_mail;
      $mail->Body    =
      '<div class="columns">
      <ul class="price">
      <li class="header">Messagem do utilizador: '.$messagem.'</li>
      <li class="header">Produto: '.$data->result->products[$product_id]->title.'</li>
      <li class="grey"> Marca: '.$data->result->products[$product_id]->brandName.'</li>
      <li class="grey"> Preço maximo: '.$maxprice.'</li>
      <li class="grey"> Preço minimo: '.$minprice.'</li>
      <li class="grey"> <img src= '.$data->result->products[$product_id]->imageUrl.' "height="200" width="200"></li>
      <td>'.'<a '.'class="pure-button"'.' href=http://app.dev/lab_project/lab_prog/public/edit_produto_indix.php?id='.$product_id.'&produto='.$produto.'&categoria='.$categoria.'&marca='.$marca.'&offset='.$offset.'> Editar produto </a> </td>';

      $mail->send();

    } catch (Exception $e) {
      echo 'Mailer Error:'. $mail->ErrorInfo;


      // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment(__DIR__."/".'recover.pdf', 'users&id');    // Optional name

      //Content

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      //echo 'Message has been sent';

    }
  }
}
?>
