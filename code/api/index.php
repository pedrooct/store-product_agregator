<?php
session_start();
if(isset($_SESSION['username']))
{
  $username =$_SESSION['username'];
}
if(isset($_SESSION['nome']))
{
  $nome= $_SESSION['nome'];
}
if(isset($_SESSION['email']))
{
  $email=$_SESSION['email'];
}
if(!isset($_SESSION['id']))
{
  $_SESSION['id']=1;
  $uid=$_SESSION['id'];
}
require_once __DIR__ . '/../vendor/autoload.php';

// namespaces
use Silex\Application;
use Silex\Provider\SerializerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


// create the app
$app = new Silex\Application();

// using for serialize data for xml and json format
$app->register(new SerializerServiceProvider());


// connect to mysql atabase
try {
  //$dbh = new PDO($dsn, 'root', 'root');
  $dbh = new \PDO("mysql:host=localhost;dbname=labpro;charset=utf8", "root", "root", [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);



} catch (PDOException $e) {
  die('Connection failed: ');
}

// defining routes
// first route - this is an example - you can here define the API
$app->match('/', function () use ($app, $dbh) {
  return new Response('LaPro API!', 200);
})
->method('GET|POST');


$app->match('/wishlist/{id}/{offset}', function (request $resquest,$id,$offset) use ($app, $dbh) {

  $sth = $dbh->prepare('SELECT id, nome,marca,TO_BASE64(imagem) as imagem , imagetype, preco, portes, link, rating FROM produto WHERE id IN (SELECT product_id FROM lista_desejo WHERE user_id = :id) LIMIT 5 OFFSET :offset');
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->bindValue(":id", (int) $id , PDO::PARAM_INT);
  $sth->execute();

  $produtos = $sth->fetchAll(PDO::FETCH_ASSOC);
  if(empty($produtos)) {
    return $app->json(array("result"=>"Sem produtos na lista de desejos"));
  }

  return $app->json($produtos);
})
->method('GET|POST') // you can use get or post for this route
->assert('id', '\d+'); // verify that id is a digit

$app->match('/addwishlist/{pid}', function (request $resquet,$pid) use ($app, $dbh,$uid) {

  $sth = $dbh->prepare('INSERT INTO lista_desejo (user_id, product_id) VALUES('.$uid.','.$pid.')');
  $sth->execute();
  return new response("ok", 200);

})
->method('GET|POST') // you can use get or post for this route
->assert('pid', '\d+')
->assert('uid', '\d+'); // verify that id is a digit


$app->delete('/delwishlist/{pid}', function ($pid) use ($app, $dbh,$uid) {

  $sth = $dbh->prepare('DELETE FROM lista_desejo WHERE user_id=:uid AND product_id=:pid ');
  $sth->bindValue(":uid", (int) $uid , PDO::PARAM_INT);
  $sth->bindValue(":pid", (int) $pid , PDO::PARAM_INT);
  $sth->execute();
  return new response("ok", 200);

})
->assert('pid', '\d+')
->assert('uid', '\d+'); // verify that id is a digit


$app->match('/count', function (request $resquet) use ($app, $dbh) {

  $sth = $dbh->prepare('SELECT count(*) FROM produto');
  $sth->execute();
  $count = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($count[0]['count(*)']);

})
->method('GET|POST'); // you can use get or post for this route




// INSERT
// e.g., curl -X POST -H "Content-Type: application/json" -d '{"title":"My New Book","author":"Douglas","isbn":"111-11-1111-111-1"}' -i http://api.dev/book
$app->post('/store', function(Request $request) use ($app, $dbh) {
  $data = json_decode($request->getContent(), true); // load the received json data

  $sth = $dbh->prepare('INSERT INTO store (NIdentificacao,nome, password, morada,email,telefone,cidade,website,imagem)
  VALUES(:Nident,:nome, :password, :morada, :email, :telefone, :cidade, :website, :imagem)');

  $sth->execute($data);
  $id = $dbh->lastInsertId();
  // response, 201 created
  $response = new Response('Ok', 201);
  //$response->headers->set('Location', "/store/$id");
  return $response;
})->method('GET|POST');

$app->post('/user', function(Request $request) use ($app, $dbh) {
  $data = json_decode($request->getContent(), true); // load the received json data

  $sth = $dbh->prepare('INSERT INTO utilizador (nome, email, password,username)
  VALUES(:nome, :email, :password, :username)');

  $sth->execute($data);
  $id = $dbh->lastInsertId();
  // response, 201 created
  $response = new Response('Ok', 201);
  //$response->headers->set('Location', "/user/$id");
  return $response;

})->method('GET|POST');


$app->put('/edit/user', function(Request $request) use ($app, $dbh) {

  $data = json_decode($request->getContent(), true); // load the received json data
  $sth = $dbh->prepare('UPDATE utilizador SET nome=:nome ,email=:email, password=:password WHERE id=:pid');
  $sth->bindValue(":pid", (int) $data['pid'] , PDO::PARAM_INT);
  $sth->bindValue(":nome", (string) $data['nome'] , PDO::PARAM_STR);
  $sth->bindValue(":email", (string) $data['email'] , PDO::PARAM_STR);
  $sth->bindValue(":password", (string) $data['password'] , PDO::PARAM_STR);
  $sth->execute();
  $response = new Response("ok", 201);
  return $response;

});



// obtem informação de um produto especifico com base no ID
$app->match('/obtainproduct/{id}', function ($id) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT id,store_id,marca,nome,TO_BASE64(imagem) as imagem,imagetype,categoria,preco,link,rating,portes,especificacao,referencia from produto where id=?');

  $sth->execute(array($id));
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($produto);
})
->method('GET|POST');

$app->match('/obtainproductstore/{id}/{offset}', function ($id,$offset) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT id,nome,marca,TO_BASE64(imagem) as imagem,imagetype,categoria,preco,link,rating,portes,especificacao,referencia from produto where store_id=:id LIMIT 4 OFFSET :offset ');
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->bindValue(":id", (int) $id , PDO::PARAM_INT);
  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);
  if(empty($produto)) {
    return $app->json(array("result"=>"loja sem produtos"));
  }
  return $app->json($produto);
})
->method('GET|POST');


$app->match('/searchproduct/{nome}/{offset}', function (request $resquest, $nome, $offset) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT id,store_id,nome,TO_BASE64(imagem) as imagem,preco,link,rating,portes from produto where nome like :nome LIMIT 4 OFFSET :offset');
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->bindValue(":nome", (string) "%".$nome."%" , PDO::PARAM_STR);

  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);


  return $app->json($produto);
})
->method('GET|POST');

$app->match('/searchcategorie/{categoria}/{offset}', function (request $resquest, $categoria, $offset) use ($app, $dbh) {

  $categoria=explode("+",$categoria);
  $categoria=implode(" ",$categoria);
  $categoria=explode("%26",$categoria);
  $categoria=implode("&",$categoria);
  $sth = $dbh->prepare('SELECT id,store_id,nome,TO_BASE64(imagem) as imagem,preco,link,rating,portes from produto where categoria=:categoria LIMIT 4 OFFSET :offset');
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->bindValue(":categoria", (string) $categoria , PDO::PARAM_STR);

  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($produto);
})
->method('GET|POST');

$app->match('/searchproductcategorie/{nome}/{categoria}/{offset}', function (request $resquest, $nome, $categoria,$offset) use ($app, $dbh) {
  $categoria=explode("+",$categoria);
  $categoria=implode(" ",$categoria);
  $categoria=explode("%26",$categoria);
  $categoria=implode("&",$categoria);
  $sth = $dbh->prepare('SELECT id,store_id,nome,TO_BASE64(imagem) as imagem,categoria,preco,link,rating,portes from produto where categoria= :categoria AND nome like :nome LIMIT 4 OFFSET :offset ');
  $sth->bindValue(":categoria", (string) $categoria , PDO::PARAM_STR);
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->bindValue(":nome", (string) "%".$nome."%" , PDO::PARAM_STR);

  $sth->execute();

  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($produto);
})
->method('GET|POST');

$app->match('/searchproductmarca/{nome}/{marca}/{offset}', function (request $resquest, $nome, $marca,$offset) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT id,store_id,nome,TO_BASE64(imagem) as imagem,categoria,preco,link,rating,portes from produto where marca= :marca AND nome like :nome LIMIT 4 OFFSET :offset ');
  $sth->bindValue(":marca", (string) $marca , PDO::PARAM_STR);
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->bindValue(":nome", (string) "%".$nome."%" , PDO::PARAM_STR);

  $sth->execute();

  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($produto);
})
->method('GET|POST');


$app->match('/obtainimage/{id}', function ($id) use ($app, $dbh) {
  //$sth = $dbh->prepare('SELECT imagem from produto where id=?');
  $sth = $dbh->prepare('SELECT TO_BASE64(imagem) as imagem from produto where store_id=?');
  $sth->execute(array($id));
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);
  if(empty($produto)) {
    return $app->json(array("result"=>"loja sem imagem"));
  }
  return $app->json($produto);

})
->method('GET|POST');


$app->match('/obtainbest/{offset}', function (Request $request,$offset) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT id,nome,marca,store_id, TO_BASE64(imagem) as imagem ,imagetype,link,preco,portes,especificacao FROM produto ORDER BY rating DESC LIMIT 4 OFFSET :offset');
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($produto);

})
->method('GET|POST');


$app->match('/searchbrand/{marca}/{offset}', function (Request $request,$marca,$offset) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT id,nome,marca,store_id, TO_BASE64(imagem) as imagem ,imagetype,link,preco,portes,especificacao FROM produto where marca=:marca ORDER BY rating DESC LIMIT 4 OFFSET :offset');
  $sth->bindValue(":marca", (string) $marca , PDO::PARAM_STR);
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($produto);

})
->method('GET|POST');

$app->match('/search/product/category/brand/{produto}/{categoria}/{marca}/{offset}', function (Request $request,$produto,$categoria,$marca,$offset) use ($app, $dbh) {

  $categoria=explode("+",$categoria);
  $categoria=implode(" ",$categoria);
  $categoria=explode("%26",$categoria);
  $categoria=implode("&",$categoria);
  $sth = $dbh->prepare('SELECT id,nome,marca,store_id, TO_BASE64(imagem) as imagem ,imagetype,link,preco,portes,especificacao FROM produto where nome like :nome  AND categoria=:categoria AND marca=:marca ORDER BY rating DESC LIMIT 4 OFFSET :offset');
  $sth->bindValue(":marca", (string) $marca , PDO::PARAM_STR);
  $sth->bindValue(":categoria", (string) $categoria , PDO::PARAM_STR);
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->bindValue(":nome", (string) "%".$produto."%" , PDO::PARAM_STR);
  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($produto);

})
->method('GET|POST');

$app->match('/search/category/brand/{marca}/{categoria}/{offset}', function (Request $request,$categoria,$marca,$offset) use ($app, $dbh) {

  $categoria=explode("+",$categoria);
  $categoria=implode(" ",$categoria);
  $categoria=explode("%26",$categoria);
  $categoria=implode("&",$categoria);

  $sth = $dbh->prepare('SELECT id,nome,marca,store_id, TO_BASE64(imagem) as imagem ,imagetype,link,preco,portes,especificacao FROM produto where categoria=:categoria AND marca=:marca ORDER BY rating DESC LIMIT 4 OFFSET :offset');
  $sth->bindValue(":marca", (string) $marca , PDO::PARAM_STR);
  $sth->bindValue(":categoria", (string) $categoria , PDO::PARAM_STR);
  $sth->bindValue(":offset", (int) $offset , PDO::PARAM_INT);
  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  return $app->json($produto);

})
->method('GET|POST');



$app->match('/obtainstore/{id}', function ($id) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT * from store where id=?');
  $sth->execute(array($id));
  $store = $sth->fetchAll(PDO::FETCH_ASSOC);
  if(empty($store)) {
    return $app->json(array("result"=>"oops loja não encontrada"));
  }
  return $app->json($store);

})
->method('GET|POST');

$app->match('/obtainlowprice', function (Request $request) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT * from produto ORDER BY preco ASC LIMIT 4');
  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);
  if(empty($produto)) {
    return $app->json(array("result"=>"oops SEM PRODUTOS na base de dados"));
  }
  return $app->json($produto);

})
->method('GET|POST');


$app->post('/product', function(Request $request) use ($app, $dbh) {
  $data = json_decode($request->getContent(), true); // load the received json data

  $sth = $dbh->prepare('INSERT INTO produto (store_id,nome,marca,categoria, imagem, imagetype,preco,link,portes,especificacao,referencia) VALUES(:store_id, :nome,:marca,:categoria, :imagem,:imagetype, :preco, :link, :portes, :especificacao, :referencia)');


  $sth->bindValue(":store_id", (int) $data['store_id'] , PDO::PARAM_INT); //
  $sth->bindValue(":nome", (string) $data['nome'] , PDO::PARAM_STR);
  $sth->bindValue(":marca", (string) $data['marca'] , PDO::PARAM_STR);
  $sth->bindValue(":categoria", (string) $data['categoria'] , PDO::PARAM_STR);
  $sth->bindValue(":preco", (float) $data['preco'] , PDO::PARAM_STR);
  $sth->bindValue(":link", (string) $data['link'] , PDO::PARAM_STR);
  $sth->bindValue(":portes", (float) $data['portes'] , PDO::PARAM_STR);
  $sth->bindValue(":especificacao", (string) $data['especificacao'] , PDO::PARAM_STR);
  $sth->bindValue(":referencia", (string) $data['referencia'] , PDO::PARAM_STR);
  $sth->bindValue(":imagetype", (string) $data['imagetype'] , PDO::PARAM_STR);
  $sth->bindValue(":imagem", (string) file_get_contents($data['imagem']) , PDO::PARAM_STR);
  $sth->execute();
  return new response("ok", 200);
});


// UPDATE
// e.g., curl -X PUT -H "Content-Type: application/json" -d '{"title":"PHP2","author":"Douglas","isbn":"111-11-1111-111-1"}' -i http://api.dev/bookedit/6
$app->put('/productedit/{id}', function(Request $request, $id) use ($app, $dbh) {
  $data = json_decode($request->getContent(), true);

  $sth = $dbh->prepare('UPDATE produto SET nome=:nome,marca=:marca ,categoria=:categoria , imagem=:imagem ,imagetype=:imagetype, preco=:preco , portes=:portes , especificacao=:especificacao , link=:link , referencia=:referencia WHERE id=:id');
  $sth->bindValue(":id", (int) $id , PDO::PARAM_INT);
  $sth->bindValue(":nome", (string) $data['nome'] , PDO::PARAM_STR);
  $sth->bindValue(":marca", (string) $data['marca'] , PDO::PARAM_STR);
  $sth->bindValue(":categoria", (string) $data['categoria'] , PDO::PARAM_STR);
  $sth->bindValue(":preco", (float) $data['preco'] , PDO::PARAM_STR);
  $sth->bindValue(":portes", (float) $data['portes'] , PDO::PARAM_STR);
  $sth->bindValue(":especificacao", (string) $data['especificacao'] , PDO::PARAM_STR);
  $sth->bindValue(":link", (string) $data['link'] , PDO::PARAM_STR);
  $sth->bindValue(":referencia", (string) $data['referencia'] , PDO::PARAM_STR);
  $sth->bindValue(":imagetype", (string) $data['imagetype'] , PDO::PARAM_STR);
  $sth->bindValue(":imagem", (string) file_get_contents($data['imagem']) , PDO::PARAM_STR);
  $sth->execute(); //$data
  //$id = $dbh->lastInsertId();

  $response = new Response('Ok', 201);
  //$response->headers->set('Location', "/book/$id");
  return $response;
})
->assert('id', '\d+'); // verify that id is a digit

//edita um produto mas sem inserir a imagem
$app->put('/producteditnoimage/{id}', function(Request $request, $id) use ($app, $dbh) {
  $data = json_decode($request->getContent(), true);

  $sth = $dbh->prepare('UPDATE produto SET nome=:nome,marca=:marca ,categoria=:categoria, preco=:preco , portes=:portes , especificacao=:especificacao , link=:link , referencia=:referencia WHERE id=:id');
  $sth->bindValue(":id", (int) $id , PDO::PARAM_INT);
  $sth->bindValue(":nome", (string) $data['nome'] , PDO::PARAM_STR);
  $sth->bindValue(":marca", (string) $data['marca'] , PDO::PARAM_STR);
  $sth->bindValue(":categoria", (string) $data['categoria'] , PDO::PARAM_STR);
  $sth->bindValue(":preco", (float) $data['preco'] , PDO::PARAM_STR);
  $sth->bindValue(":portes", (float) $data['portes'] , PDO::PARAM_STR);
  $sth->bindValue(":especificacao", (string) $data['especificacao'] , PDO::PARAM_STR);
  $sth->bindValue(":link", (string) $data['link'] , PDO::PARAM_STR);
  $sth->bindValue(":referencia", (string) $data['referencia'] , PDO::PARAM_STR);
  $sth->execute(); //$data
  //$id = $dbh->lastInsertId();

  $response = new Response('Ok', 201);
  //$response->headers->set('Location', "/book/$id");
  return $response;
})
->assert('id', '\d+'); // verify that id is a digit

// DELETE
$app->delete('/deletewishlist/{id}', function($id) use ($app, $dbh) {

  $sth = $dbh->prepare('DELETE FROM lista_desejo WHERE product_id=:id');
  $sth->bindValue(":id", (int) $id , PDO::PARAM_INT);
  $sth->execute();

  return new Response(null, 204);
})
->assert('id', '\d+');

// e.g., curl -X DELETE -i http://api.dev/bookdel/6
$app->delete('/productdel/{id}', function($id) use ($app, $dbh) {
  $sth = $dbh->prepare('SELECT id FROM lista_desejo WHERE product_id=:id');
  $sth->bindValue(":id", (int) $id , PDO::PARAM_INT);
  $sth->execute();
  $lista = $sth->fetchAll(PDO::FETCH_ASSOC);
  if($lista > 1)
  {
    $sth = $dbh->prepare('DELETE FROM lista_desejo WHERE product_id=:id');
    $sth->bindValue(":id", (int) $id , PDO::PARAM_INT);
    $sth->execute();
  }

  $sth = $dbh->prepare('DELETE FROM produtos WHERE id=:id');
  $sth->bindValue(":id", (int) $id , PDO::PARAM_INT);
  $sth->execute();
  $produto = $sth->fetchAll(PDO::FETCH_ASSOC);

  if($produto < 1) {
    // this books id does not exists, return 404 with Inexistant book id - $id
    return new Response("produto não existe id - $id", 404);
  }
  // this books has been removed, return 204 with no content
  return new Response(null, 204);
})
->assert('id', '\d+'); // verify that id is a digit


//external API

//WOLFGRAM
$app->match('/currency/{val}', function($val) use ($app, $dbh)
{
  $convert="";
  $convert=@file_get_contents("http://api.wolframalpha.com/v1/result?appid=R8VYRY-EAT444YL4E&i=convert+".$val."+USD+in+EUR");
  return $convert;
})
->method('GET|POST');

//external

$app->match('/externalproduct/{produto}/{offset}', function(request $request,$produto,$offset) use ($app, $dbh)
{
  $data="";
  $client= new MongoDB\Driver\Manager(); // connect
  $filter = ['produto' => $produto, 'offset'=> $offset]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  { // o foreach é a unica maneira que conheço para extrair o json armazenado
    $data=$r->search;
    break;
  }
  if($data=="")
  {
    $write = new MongoDB\Driver\BulkWrite;
    $data=file_get_contents("");
    $document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'produto' => $produto, 'offset'=> $offset];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
  }
  return $data;
})
->method('GET|POST');

//procura pela marca para auxiliar na busca de produtos
$app->match('/externalsearchbrand/{marca}', function(request $request,$marca) use ($app, $dbh)
{
  $client= new MongoDB\Driver\Manager();

  $filter = ['id' => 3, 'brand'=>$marca]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  {
    $brand=json_decode($r->search);
    if(!empty($brand->result))
    {
      $size=sizeof($brand->result->brands);
      for($i=0;$i<$size;$i++)
      {
        if(strcmp($marca,$brand->result->brands[$i]->name)==0)
        {
          return $brand->result->brands[$i]->id;
        }
      }
    }
    break;
  }
  $write = new MongoDB\Driver\BulkWrite;
  $data=file_get_contents('');
  $check=json_decode($data);
  if($check->result!="")
  {
    $document = ['id'=>3,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'brand' => $marca];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
    $size=sizeof($check->result->brands);
    for($i=0;$i<$size;$i++)
    {
      if(strcmp($marca,$check->result->brands[$i]->name)==0)
      {
        return $check->result->brands[$i]->id;
      }
    }
  }
  return new Response("0", 204);

})->method('GET|POST');


//procura categorias e retorna ID
$app->match('/externalsearchcategory/{categoria}', function(request $request,$categoria) use ($app, $dbh)
{
  $categoria=explode("+",$categoria);
  $categoria=implode(" ",$categoria);
  $categoria=explode("%26",$categoria);
  $categoria=implode("&",$categoria);
  $client= new MongoDB\Driver\Manager();
  $filter = ['id' => 5]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);

  foreach($rows as $r)
  {

    $category=json_decode($r->search);
    $size=sizeof($category->result->categories);
    for($i=0;$i<$size;$i++)
    {

      if(strcmp($categoria,$category->result->categories[$i]->name)==0)
      {

        return $category->result->categories[$i]->id;
      }
    }
    break;
  }
  $response = new Response("categoria", 400);
  return $response;
})
->method('GET|POST');

//rota para procurar com categoria e produto
$app->match('/externalsearch/product/category/{searchbar}/{categoria}/{offset}', function(request $request,$searchbar,$categoria,$offset) use ($app, $dbh)
{
  $rest=file_get_contents('http://labpro.dev/externalsearchcategory/'.$categoria);
  if(strcmp($rest,"categoria")==0)
  {
    $response = new Response("Categoria sem produtos", 400);
    return $response;
  }
  $data="";
  $client= new MongoDB\Driver\Manager(); // connect
  $filter = ['searchbar' => $searchbar,'categoria'=> $rest,  'offset'=> $offset]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  { // o foreach é a unica maneira que conheço para extrair o json armazenado
    $data=$r->search;
    break;
  }
  if($data=="")
  {
    $write = new MongoDB\Driver\BulkWrite;
    $data=file_get_contents('');
    $document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'searchbar' => $searchbar, 'categoria'=> $rest ,'offset'=> $offset];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
  }
  return $data;
})
->method('GET|POST');


$app->match('/externalsearch/product/brand/{searchbar}/{brand}/{offset}', function(request $request,$searchbar,$brand,$offset) use ($app, $dbh)
{
  $idbrand=file_get_contents('http://labpro.dev/externalsearchbrand/'.$brand);
  $data="";
  $client= new MongoDB\Driver\Manager(); // connect
  $filter = ['searchbar' => $searchbar,'brand'=> $idbrand,  'offset'=> $offset]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  { // o foreach é a unica maneira que conheço para extrair o json armazenado
    $data=$r->search;
    break;
  }
  if($data=="")
  {
    $write = new MongoDB\Driver\BulkWrite;
    $data=file_get_contents('');
    $document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'searchbar' => $searchbar, 'brand'=> $idbrand ,'offset'=> $offset];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
  }
  return $data;
})
->method('GET|POST');



$app->match('/externalsearch/product/brand/category/{searchbar}/{brand}/{category}/{offset}', function(request $request,$searchbar,$brand,$category,$offset) use ($app, $dbh)
{
  $idbrand=file_get_contents('http://labpro.dev/externalsearchbrand/'.$brand);
  $idcat=file_get_contents('http://labpro.dev/externalsearchcategory/'.$category);
  $data="";
  $client= new MongoDB\Driver\Manager(); // connect
  $filter = ['searchbar' => $searchbar,'brand'=> $idbrand,'categoria'=> $idcat, 'offset'=> $offset]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  { // o foreach é a unica maneira que conheço para extrair o json armazenado
    $data=$r->search;
    break;
  }
  if($data=="")
  {
    $write = new MongoDB\Driver\BulkWrite;
    $data=file_get_contents('');
    $document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'searchbar' => $searchbar, 'brand'=> $idbrand, 'categoria'=>$idcat ,'offset'=> $offset];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
  }
  return $data;
})
->method('GET|POST');


$app->match('/externalsearch/brand/category/{brand}/{category}/{offset}', function(request $request,$brand,$category,$offset) use ($app, $dbh)
{
  $idbrand=file_get_contents('http://labpro.dev/externalsearchbrand/'.$brand);
  $idcat=file_get_contents('http://labpro.dev/externalsearchcategory/'.$category);
  $data="";
  $client= new MongoDB\Driver\Manager(); // connect
  $filter = ['brand'=> $idbrand,'categoria'=> $idcat, 'offset'=> $offset]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  { // o foreach é a unica maneira que conheço para extrair o json armazenado
    $data=$r->search;
    break;
  }
  if($data=="")
  {
    $write = new MongoDB\Driver\BulkWrite;
    $data=file_get_contents('');
    $document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'brand'=> $idbrand, 'categoria'=>$idcat ,'offset'=> $offset];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
  }
  return $data;
})
->method('GET|POST');


//devolve ou categoria ou produtos
$app->match('/externalcategoriaprodutct/{categoria}', function(request $request,$categoria) use ($app, $dbh)
{
  $categoria=explode("+",$categoria);
  $categoria=implode(" ",$categoria);

  $categoria=explode("%26",$categoria);
  $categoria=implode("&",$categoria);

  //var_dump($categoria);

  $client= new MongoDB\Driver\Manager();
  $filter = ['id' => 5]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  {
    $category=json_decode($r->search);
    $size=sizeof($category->result->categories);
    for($i=0;$i<$size;$i++)
    {
      if(strcmp($categoria,$category->result->categories[$i]->name)==0)
      {
        return $category->result->categories[$i]->id;
      }
    }
    break;
  }
  $write = new MongoDB\Driver\BulkWrite;
  $data=file_get_contents('');
  $document = ['id'=>5,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data ];
  $write->insert($document);
  $result=$client->executeBulkWrite('externalapi.products', $write);
  $response = new Response("sem produtos", 400);
  return $response;
})
->method('GET|POST');

$app->match('/externalproductbrand/{brand}/{offset}', function(request $request,$brand,$offset) use ($app, $dbh)
{
  $id= file_get_contents('http://labpro.dev/externalsearchbrand/'.$brand);
  $data="";
  $client= new MongoDB\Driver\Manager(); // connect
  $filter = ['brand' => $brand, 'offset'=> $offset]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  { // o foreach é a unica maneira que conheço para extrair o json armazenado
    $data=$r->search;
    break;
  }
  if($data=="")
  {
    $write = new MongoDB\Driver\BulkWrite;
    $data=file_get_contents('');
    $document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'brand' => $id, 'offset'=> $offset];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
  }
  return $data;
})
->method('GET|POST');

$app->match('/externalproductcategory/{categoria}/{offset}', function(request $request,$categoria,$offset) use ($app, $dbh)
{

  $data="";
  $idcat= file_get_contents('http://labpro.dev/externalcategoriaprodutct/'.$categoria);
  $client= new MongoDB\Driver\Manager(); // connect
  $filter = ['categoria' => $idcat, 'offset'=> $offset]; // O 0 foi usado para testes
  $options = [
    'projection' => ['_id' => 0],
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  { // o foreach é a unica maneira que conheço para extrair o json armazenado
    $data=$r->search;
    break;
  }
  if($data=="")
  {
    $write = new MongoDB\Driver\BulkWrite;
    $data=file_get_contents('');
    $document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'categoria' => $idcat, 'offset'=> $offset];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
  }
  return $data;
})
->method('GET|POST');



$app->match('/externaldefault/{offset}', function(request $resquest,$offset) use ($app, $dbh)
{
  $data="";
  $client= new MongoDB\Driver\Manager(); // connect
  $filter = ['id' => 1, 'offset'=> $offset]; // O 0 foi usado para testes
  $options = ['projection' => ['_id' => 0],];
  $query = new MongoDB\Driver\Query($filter, $options);
  $rows = $client->executeQuery('externalapi.products', $query);
  foreach($rows as $r)
  { // o foreach é a unica maneira que conheço para extrair o json armazenado
    $data=$r->default;
    break;
  }
  if($data=="")
  {
    $write = new MongoDB\Driver\BulkWrite;
    $data = file_get_contents("");
    $document = ['id'=>1,'_id' => new MongoDB\BSON\ObjectId, 'default' => $data, 'offset'=> $offset];
    $write->insert($document);
    $result=$client->executeBulkWrite('externalapi.products', $write);
  }
  return $data;
})
->method('GET|POST');


$app->put('/editexternaldefault/{offset}', function(request $request,$offset) use ($app, $dbh)
{
  $data = $request->getContent();
  $client= new MongoDB\Driver\Manager();
  $write = new MongoDB\Driver\BulkWrite;
  $write->update(
    ['id' => 1, 'offset'=> $offset], //filtro
    ['$set' => ['default' => $data]], //o que vai alterar
    ['multi' => true , 'upsert' => false] //multi serve para escrever em mais que um pacote se existir com o mesmo ID e offset e o upsert serve para criar uma entrada na se não existir a entrada em questão
  );

  $result=$client->executeBulkWrite('externalapi.products', $write);

  return new response("ok",200);
});

$app->put('/editexternalproduct/{produto}/{offset}', function(request $request,$produto,$offset) use ($app, $dbh)
{
  $data = $request->getContent();
  $client= new MongoDB\Driver\Manager();
  $write = new MongoDB\Driver\BulkWrite;
  $write->update(
    ['id' => 2, 'produto'=>$produto,'offset'=> $offset],
    ['$set' => ['search' => $data]],
    ['multi' => true , 'upsert' => false]
  );
  //$document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'produto' => $produto, 'offset'=> $offset];
  $result=$client->executeBulkWrite('externalapi.products', $write);

  return new response("ok",200);
});

$app->put('/editexternalcategoria/{categoria}/{offset}', function(request $request,$categoria,$offset) use ($app, $dbh)
{
  $categoria=file_get_contents('http://labpro.dev/externalsearchcategory/'.$categoria);
  $data = $request->getContent();
  $client= new MongoDB\Driver\Manager();
  $write = new MongoDB\Driver\BulkWrite;
  $write->update(
    ['id' => 2, 'categoria'=>$categoria,'offset'=> $offset],
    ['$set' => ['search' => $data]],
    ['multi' => true , 'upsert' => false]
  );
  //$document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'produto' => $produto, 'offset'=> $offset];
  $result=$client->executeBulkWrite('externalapi.products', $write);

  return new response("ok",200);
});


$app->put('/editexternalmarca/{marca}/{offset}', function(request $request,$marca,$offset) use ($app, $dbh)
{
  $marca=file_get_contents('http://labpro.dev/externalsearchbrand/'.$marca);
  $data = $request->getContent();
  $client= new MongoDB\Driver\Manager();
  $write = new MongoDB\Driver\BulkWrite;
  $write->update(
    ['id' => 2, 'brand'=>$marca,'offset'=> $offset],
    ['$set' => ['search' => $data]],
    ['multi' => true , 'upsert' => false]
  );
  //$document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'produto' => $produto, 'offset'=> $offset];
  $result=$client->executeBulkWrite('externalapi.products', $write);

  return new response("ok",200);
});

$app->put('/editexternalprodutocategoria/{produto}/{categoria}/{offset}', function(request $request,$produto,$categoria,$offset) use ($app, $dbh)
{
  $categoria=file_get_contents('http://labpro.dev/externalsearchcategory/'.$categoria);
  $data = $request->getContent();
  $client= new MongoDB\Driver\Manager();
  $write = new MongoDB\Driver\BulkWrite;
  $write->update(
    ['id' => 2, 'searchbar' => $produto, 'categoria'=> $categoria ,'offset'=> $offset],
    ['$set' => ['search' => $data]],
    ['multi' => true , 'upsert' => false]
  );
  //$document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'produto' => $produto, 'offset'=> $offset];
  $result=$client->executeBulkWrite('externalapi.products', $write);

  return new response("ok",200);
});

$app->put('/editexternalprodutomarca/{produto}/{marca}/{offset}', function(request $request,$produto,$marca,$offset) use ($app, $dbh)
{
  $marca=file_get_contents('http://labpro.dev/externalsearchbrand/'.$marca);
  $data = $request->getContent();
  $client= new MongoDB\Driver\Manager();
  $write = new MongoDB\Driver\BulkWrite;
  $write->update(
    ['id' => 2, 'searchbar' => $produto, 'brand'=> $marca ,'offset'=> $offset],
    ['$set' => ['search' => $data]],
    ['multi' => true , 'upsert' => false]
  );
  //$document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'produto' => $produto, 'offset'=> $offset];
  $result=$client->executeBulkWrite('externalapi.products', $write);

  return new response("ok",200);
});

$app->put('/editexternalcategoriamarca/{categoria}/{marca}/{offset}', function(request $request,$categoria,$marca,$offset) use ($app, $dbh)
{
  $categoria=file_get_contents('http://labpro.dev/externalsearchcategory/'.$categoria);
  $marca=file_get_contents('http://labpro.dev/externalsearchbrand/'.$marca);
  $data = $request->getContent();
  $client= new MongoDB\Driver\Manager();
  $write = new MongoDB\Driver\BulkWrite;
  $write->update(
    ['id' => 2, 'categoria' => $categoria, 'brand'=> $marca ,'offset'=> $offset],
    ['$set' => ['search' => $data]],
    ['multi' => true , 'upsert' => false]
  );
  //$document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'produto' => $produto, 'offset'=> $offset];
  $result=$client->executeBulkWrite('externalapi.products', $write);

  return new response("ok",200);
});

$app->put('/editexternalcategoriamarcaproduto/{categoria}/{marca}/{produto}/{offset}', function(request $request,$categoria,$marca,$produto,$offset) use ($app, $dbh)
{
  $categoria=file_get_contents('http://labpro.dev/externalsearchcategory/'.$categoria);
  $marca=file_get_contents('http://labpro.dev/externalsearchbrand/'.$marca);
  $data = $request->getContent();
  $client= new MongoDB\Driver\Manager();
  $write = new MongoDB\Driver\BulkWrite;
  $write->update(
    ['id' => 2, 'searchbar'=>$produto ,'categoria' => $categoria, 'brand'=> $marca ,'offset'=> $offset],
    ['$set' => ['search' => $data]],
    ['multi' => true , 'upsert' => false]
  );
  //$document = ['id'=>2,'_id' => new MongoDB\BSON\ObjectId, 'search' => $data, 'produto' => $produto, 'offset'=> $offset];
  $result=$client->executeBulkWrite('externalapi.products', $write);

  return new response("ok",200);
});





// enable debug mode - optional this could be commented
$app['debug'] = true;
// execute the app
$app->run();
