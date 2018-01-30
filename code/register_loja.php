<?php
session_start();
// conexão a DB e sessão
/*$conn = mysqli_connect("localhost","root","root","labpro");
if(!$conn)
die('Error: ' . mysqli_connect_error());*/

if(isset($_POST['registo']))
{
  header("Location: login_loja.php");
}
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="Register store">
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
      <a class="pure-menu-heading" href="">Bem-vindo! faça o seu registo </a>
    </div>
  </div>
  <div class="content">
    <div class="pure-g">
      <div class="l-box-lrg pure-u-2 pure-u-md-2-5">
        <form class="pure-form pure-form-stacked" action="register_loja.php" method="POST" enctype="multipart/form-data" >
          <fieldset>
            <label for="Nident" style="margin-top: 20px" >Registar:</label>
            <input name="Nident" type="text" class="pure-input-rounded" placeholder="Numero de identificação" <?php echo isset($_POST['Nident']) ? "value='".$_POST['Nident']."'":""; ?> required >
            <input name="nome" type="text" class="pure-input-rounded" placeholder="nome da loja" <?php echo isset($_POST['nome']) ? "value='".$_POST['nome']."'":""; ?> required >
            <input name="password" type="password" class="pure-input-rounded" placeholder="Password" required>
            <input name="morada" type="text" class="pure-input-rounded" placeholder="Morada da loja" <?php echo isset($_POST['morada']) ? "value='".$_POST['morada']."'":""; ?> required >
            <input name="cidade" type="text" class="pure-input-rounded" placeholder="Cidade" <?php echo isset($_POST['cidade']) ? "value='".$_POST['cidade']."'":""; ?> required >
            <input name="email" type="text" class="pure-input-rounded" placeholder="email" <?php echo isset($_POST['email']) ? "value='".$_POST['email']."'":""; ?> required >
            <input name="telefone" type="text" class="pure-input-rounded" placeholder="telefone da loja" <?php echo isset($_POST['telefone']) ? "value='".$_POST['telefone']."'":""; ?> required >
            <input name="website" type="text" class="pure-input-rounded" placeholder="Website" <?php echo isset($_POST['website']) ? "value='".$_POST['website']."'":""; ?> required >
            <label for="imagem" >Imagem da marca:</label>
            <input name="imagem" type="file" class="pure-input-rounded" placeholder="Imagem da loja" <?php echo isset($_POST['imagem']) ? "value='".$_POST['image']."'":""; ?> required >

          </fieldset>

          <button type="submit" name="registo" class="pure-button pure-button-primary">Registar</button>

          <?php
          if(isset($_POST['registo']))
          {
            $Nident=$_POST['Nident'];
            $nome=$_POST['nome'];
            $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
            $morada=$_POST['morada'];
            $cidade=$_POST['cidade'];
            $email=$_POST['email'];
            $telefone=$_POST['telefone'];
            $website=$_POST['website'];

            $target_dir = "img_store/";
            $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $check = getimagesize($_FILES["imagem"]["tmp_name"]);
            if($check !== false) {
              //echo "File is an image - " . $check["mime"] . ".";
              $uploadOk = 1;
            } else {
              echo '<div class="error"> Erro na imagem...</div>';
              $uploadOk = 0;
            }
            if ($uploadOk == 0) {
              echo '<div class="error"> Error...</div>';
              exit();
            }
            else
            {
              if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
                //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
              }
              else
              {
              echo '<div class="error"> Error ao fazer upload da imagem...</div>';
                exit();
              }
            }
            $imagem=$target_file;
            $data = array(
          		'Nident' => $Nident,
          		'nome'=> $nome,
          		'password' => $password,
              'morada' => $morada,
              'email' => $email,
              'telefone' => $telefone,
              'cidade' => $cidade,
              'website' => $website,
              'imagem' => $imagem,
          	);
          	$data=json_encode($data);
          	$curl = curl_init("http://labpro.dev/store");
          	//curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
          	curl_setopt($curl, CURLOPT_POST, 1);
          	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          	$response=curl_exec($curl);

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
<?php
//mysqli_close($conn);
?>
</html>
