<?php

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();
$db = new mysqli ('localhost','root','root','cuej');

//ESCUELA 
$app->get('/escuela', function () use ($app, $db)
{
  $sql = "select
  avg(d.respuesta) promedio,
  (6 - avg(d.respuesta)) *100/5 calificacion,
  -- IF(d.respuesta = 1, p.opcion_1, IF(d.respuesta = 2, p.opcion_2, IF(d.respuesta = 3, p.opcion_3, '') ) ) respuesta
count(*)
from evaluaciones_docentes d
join evaluaciones_preguntas p on d.id_evaluacion_pregunta = p.id_evaluacion_pregunta
join horarios h on d.id_horario = h.id_horario
where 1=1
and d.id_evaluacion_pregunta = 1
-- and h.id_profesor = 44
-- and h.id_materia in (61,  155)
order by count(*) desc, calificacion desc;";
  $query = $db->query($sql);
  //$result = array();
  $usuarios= array();
  //var_dump($query -> fetch_all());
  while ($usuario = $query-> fetch_assoc())
  {
    $usuarios[] = $usuario;
  }
  $result = array
  (
    'status' => 'success',
    'code' => 200,
    'data' => $usuarios
  );
  echo json_encode($result);
});

//CARRERA

$app->get('/carreras', function () use ($app, $db)
{
  $sql = "select  c.id_carrera
  carrera,
         avg(d.respuesta) promedio,
         (6 - avg(d.respuesta)) *100/5 calificacion,
         -- IF(d.respuesta = 1, p.opcion_1, IF(d.respuesta = 2, p.opcion_2, IF(d.respuesta = 3, p.opcion_3, '') ) ) respuesta
   count(*)
  from evaluaciones_docentes d
      join evaluaciones_preguntas p on d.id_evaluacion_pregunta = p.id_evaluacion_pregunta
      join horarios h on d.id_horario = h.id_horario
      join grupos g on h.id_grupo = g.id_grupo
      join materias m on h.id_materia = m.id_materia
      join planes_estudio pe on g.id_plan_estudio = pe.id_plan_estudio
  join carreras c on pe.id_carrera = c.id_carrera
  where 1=1
  and d.id_evaluacion_pregunta = 1
  -- and h.id_profesor = 44
  -- and h.id_materia in (61,  155)
  group by carrera, c.id_carrera
  order by count(*) desc, calificacion desc;";
  $query = $db->query($sql);
  //$result = array();
  $usuarios= array();
  //var_dump($query -> fetch_all());
  while ($usuario = $query-> fetch_assoc())
  {
    $usuarios[] = $usuario;
  }
  $result = array
  (
    'status' => 'success',
    'code' => 200,
    'data' => $usuarios
  );
  echo json_encode($result);
});


//MATERIA

$app->get('/materias', function () use ($app, $db)
{
  $sql = "select
  m.materia,
         -- avg(d.respuesta) promedio,
         (6 - avg(d.respuesta)) *100/5 calificacion,
         -- IF(d.respuesta = 1, p.opcion_1, IF(d.respuesta = 2, p.opcion_2, IF(d.respuesta = 3, p.opcion_3, '') ) ) respuesta
          count(*)
  from evaluaciones_docentes d
      join evaluaciones_preguntas p on d.id_evaluacion_pregunta = p.id_evaluacion_pregunta
      join horarios h on d.id_horario = h.id_horario
          join grupos g on h.id_grupo = g.id_grupo
  join materias m on h.id_materia = m.id_materia
  join planes_estudio pe on g.id_plan_estudio = pe.id_plan_estudio
  join carreras c on pe.id_carrera = c.id_carrera
  where 1=1
  and d.id_evaluacion_pregunta = 1
  -- and h.id_profesor = 44
  -- and h.id_materia in (61,  155)
  and c.id_carrera =1
  group by m.materia
  order by count(*) desc, calificacion desc;";
  $query = $db->query($sql);
  //$result = array();
  $usuarios= array();
  //var_dump($query -> fetch_all());
  while ($usuario = $query-> fetch_assoc())
  {
    $usuarios[] = $usuario;
  }
  $result = array
  (
    'status' => 'success',
    'code' => 200,
    'data' => $usuarios
  );
  echo json_encode($result);
});


//Profesores

$app->get('/profesores', function () use ($app, $db)
{
  $sql = "select
  avg(d.respuesta) promedio,
  (6 - avg(d.respuesta)) *100/5 calificacion,
  -- IF(d.respuesta = 1, p.opcion_1, IF(d.respuesta = 2, p.opcion_2, IF(d.respuesta = 3, p.opcion_3, '') ) ) respuesta
  m.materia, count(*),
  p2.nombre,  p2.apellido_paterno,  p2.apellido_materno
from evaluaciones_docentes d
join evaluaciones_preguntas p on d.id_evaluacion_pregunta = p.id_evaluacion_pregunta
join horarios h on d.id_horario = h.id_horario
join profesores p2 on h.id_profesor = p2.id_profesor
join materias m on h.id_materia = m.id_materia
where 1=1
and d.id_evaluacion_pregunta = 1
-- and h.id_profesor = 44
-- and h.id_materia in (61,  155)
group by m.materia, p2.nombre,  p2.apellido_paterno,  p2.apellido_materno
order by count(*) desc, calificacion desc;";
  $query = $db->query($sql);
  //$result = array();
  $usuarios= array();
  //var_dump($query -> fetch_all());
  while ($usuario = $query-> fetch_assoc())
  {
    $usuarios[] = $usuario;
  }
  $result = array
  (
    'status' => 'success',
    'code' => 200,
    'data' => $usuarios
  );
  echo json_encode($result);
});

//Profesor 
$app->get('/profesor', function () use ($app, $db)
{
  $sql = "select -- eo.*, h.*, g.*, m.*, pe.*, c.*
  c.carrera, c.abreviatura, m.semestre, m.clave_materia, m.materia, g.grupo,
         eo.observaciones, h.id_profesor
  from evaluaciones_observaciones eo
      join horarios h on eo.id_horario = h.id_horario
      join grupos g on h.id_grupo = g.id_grupo
      join materias m on h.id_materia = m.id_materia
      join planes_estudio pe on g.id_plan_estudio = pe.id_plan_estudio
  join carreras c on pe.id_carrera = c.id_carrera
      where  eo.id_horario in
              (select d.id_horario from evaluaciones_docentes d
              join horarios h on d.id_horario = h.id_horario
  where h.id_profesor = 44);";
  $query = $db->query($sql);
  //$result = array();
  $usuarios= array();
  //var_dump($query -> fetch_all());
  while ($usuario = $query-> fetch_assoc())
  {
    $usuarios[] = $usuario;
  }
  $result = array
  (
    'status' => 'success',
    'code' => 200,
    'data' => $usuarios
  );
  echo json_encode($result);
});

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
$app->get('/usuarios', function () use ($app, $db)
{
  $sql = "SELECT * FROM usuarios;";
  $query = $db->query($sql);
  //$result = array();
  $usuarios= array();
  //var_dump($query -> fetch_all());
  while ($usuario = $query-> fetch_assoc())
  {
    $usuarios[] = $usuario;
  }
  $result = array
  (
    'status' => 'success',
    'code' => 200,
    'data' => $usuarios
  );
  echo json_encode($result);
});
//DEVOLVER
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
