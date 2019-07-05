<?php

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();
$db = new mysqli ('localhost','root','','curso_angular');
$app->get("/pruebas",function() use($app, $db)
{
  echo "hola mundo desde PHP";
  var_dump($db);
});
$app->get("/hola",function() use($app)
{
  echo "hola HP";
});

//LISTAR
$app->get('/producto', function () use ($app, $db)
{
  $sql = "SELECT * FROM productos ORDER BY idProducto DESC;";
  $query = $db->query($sql);
  //$result = array();
  $productos= array();
  //var_dump($query -> fetch_all());
  while ($producto = $query-> fetch_assoc())
  {
    $productos[] = $producto;
  }
  $result = array
  (
    'status' => 'success',
    'code' => 200,
    'data' => $productos
  );
  echo json_encode($result);
});
//DEVOLVER
$app->get('/producto/:idProducto', function () use ($idProducto,$app, $db)
{
  $sql = 'SELECT * FROM productos WHERE idProducto = '.$idProducto;
  $query = $db->query($sql);
  //$result = array();
  $result = array
  (
    'status' => 'success',
    'code' => 404,
    'data' => 'Producto no Encontrado'
  );
  if ($query->num_rows == 1)
  {
    $producto = $query->fetch_assoc();
  }
  echo
});
//ELIMINAR
//ACTUALIZAR
//SUBIR UN FICHERO O IMAGEN

//GUARDAR
$app->post('/producto', function() use ($app, $db)
{
  $json = $app->request->post('json');
  $data = json_decode($json, true);

  if (!isset ($data['imagen']))
  {
    $data['imagen']=null;
  }
  if (!isset ($data['descripcion']))
  {
    $data['descripcion']=null;
  }
  if (!isset ($data['precio']))
  {
    $data['precio']=null;
  }
  if (!isset ($data['nombre']))
  {
    $data['nombre']=null;
  }

  $query = "INSERT INTO productos VALUES (NULL,".
    "'{$data['nombre']}',".
    "'{$data['descripcion']}',".
    "'{$data['precio']}',".
    "'{$data['imagen']}'".
  ")";

  $insert = $db->query($query);

  $result = array
  (
    'status' => 'error',
    'code' => 404,
    'message' => 'Producto no se ha creado correctamente'
  );

  if ($insert)
  {
    $result = array(
      'status' => 'success',
      'code' => 200,
      'message' => 'Producto creado correctamente'
    );
  }
  echo json_encode($result);
});
$app->run();
