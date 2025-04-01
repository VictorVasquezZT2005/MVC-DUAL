<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../config/database.php';
require_once '../models/estudiante.php';

$database = new Database();
$db = $database->getConnection();

$estudiante = new Estudiante($db);

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'GET':
        if(!empty($_GET['id'])) {
            $estudiante->id = $_GET['id'];
            $estudiante->readOne();
            
            if($estudiante->nombre != null) {
                $est_arr = array(
                    "id" => $estudiante->id,
                    "nombre" => $estudiante->nombre,
                    "carnet" => $estudiante->carnet,
                    "carrera" => $estudiante->carrera,
                    "fecha_registro" => $estudiante->fecha_registro
                );
                http_response_code(200);
                echo json_encode($est_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Estudiante no encontrado."));
            }
        } else {
            $stmt = $estudiante->read();
            $num = $stmt->rowCount();
            
            if($num > 0) {
                $est_arr = array();
                $est_arr["records"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $est_item = array(
                        "id" => $id,
                        "nombre" => $nombre,
                        "carnet" => $carnet,
                        "carrera" => $carrera,
                        "fecha_registro" => $fecha_registro
                    );
                    array_push($est_arr["records"], $est_item);
                }
                http_response_code(200);
                echo json_encode($est_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se encontraron estudiantes."));
            }
        }
        break;
    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->nombre) && !empty($data->carnet) && !empty($data->carrera)) {
            $estudiante->nombre = $data->nombre;
            $estudiante->carnet = $data->carnet;
            $estudiante->carrera = $data->carrera;
            $estudiante->fecha_registro = date('Y-m-d');
            
            if($estudiante->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Estudiante creado correctamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear el estudiante."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se pudo crear el estudiante. Datos incompletos."));
        }
        break;
    
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        $estudiante->id = $data->id;
        $estudiante->nombre = $data->nombre;
        $estudiante->carnet = $data->carnet;
        $estudiante->carrera = $data->carrera;
        
        if($estudiante->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Estudiante actualizado correctamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "No se pudo actualizar el estudiante."));
        }
        break;
    
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        $estudiante->id = $data->id;
        
        if($estudiante->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Estudiante eliminado correctamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "No se pudo eliminar el estudiante."));
        }
        break;
}
?>