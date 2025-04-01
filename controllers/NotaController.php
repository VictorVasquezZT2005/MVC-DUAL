<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../config/database.php';
require_once '../models/nota.php';
require_once '../models/estudiante.php';

$database = new Database();
$db = $database->getConnection();

$nota = new Nota($db);
$estudiante = new Estudiante($db);

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'GET':
        if(!empty($_GET['id'])) {
            $nota->id = $_GET['id'];
            $nota->readOne();
            
            if($nota->modulo != null) {
                $estudiante->id = $nota->id_estudiante;
                $estudiante->readOne();
                
                $nota_arr = array(
                    "id" => $nota->id,
                    "modulo" => $nota->modulo,
                    "nota1" => $nota->nota1,
                    "nota2" => $nota->nota2,
                    "tarea" => $nota->tarea,
                    "promedio" => $nota->promedio,
                    "id_estudiante" => $nota->id_estudiante,
                    "nombre_estudiante" => $estudiante->nombre
                );
                http_response_code(200);
                echo json_encode($nota_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nota no encontrada."));
            }
        } else if(!empty($_GET['id_estudiante'])) {
            $stmt = $nota->readByEstudiante($_GET['id_estudiante']);
            $num = $stmt->rowCount();
            
            if($num > 0) {
                $notas_arr = array();
                $notas_arr["records"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $nota_item = array(
                        "id" => $id,
                        "modulo" => $modulo,
                        "nota1" => $nota1,
                        "nota2" => $nota2,
                        "tarea" => $tarea,
                        "promedio" => $promedio,
                        "id_estudiante" => $id_estudiante
                    );
                    array_push($notas_arr["records"], $nota_item);
                }
                http_response_code(200);
                echo json_encode($notas_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se encontraron notas para este estudiante."));
            }
        } else {
            $stmt = $nota->read();
            $num = $stmt->rowCount();
            
            if($num > 0) {
                $notas_arr = array();
                $notas_arr["records"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $nota_item = array(
                        "id" => $id,
                        "modulo" => $modulo,
                        "nota1" => $nota1,
                        "nota2" => $nota2,
                        "tarea" => $tarea,
                        "promedio" => $promedio,
                        "id_estudiante" => $id_estudiante,
                        "nombre_estudiante" => $nombre_estudiante
                    );
                    array_push($notas_arr["records"], $nota_item);
                }
                http_response_code(200);
                echo json_encode($notas_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se encontraron notas."));
            }
        }
        break;
    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->modulo) && !empty($data->nota1) && !empty($data->nota2) && !empty($data->tarea) && !empty($data->id_estudiante)) {
            $nota->modulo = $data->modulo;
            $nota->nota1 = $data->nota1;
            $nota->nota2 = $data->nota2;
            $nota->tarea = $data->tarea;
            $nota->id_estudiante = $data->id_estudiante;
            
            if($nota->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Nota creada correctamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear la nota."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se pudo crear la nota. Datos incompletos."));
        }
        break;
    
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        $nota->id = $data->id;
        $nota->modulo = $data->modulo;
        $nota->nota1 = $data->nota1;
        $nota->nota2 = $data->nota2;
        $nota->tarea = $data->tarea;
        $nota->id_estudiante = $data->id_estudiante;
        
        if($nota->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Nota actualizada correctamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "No se pudo actualizar la nota."));
        }
        break;
    
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        $nota->id = $data->id;
        
        if($nota->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Nota eliminada correctamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "No se pudo eliminar la nota."));
        }
        break;
}
?>