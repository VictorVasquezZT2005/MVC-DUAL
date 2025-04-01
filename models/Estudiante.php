<?php
class Estudiante {
    private $conn;
    private $table_name = "estudiantes";

    public $id;
    public $nombre;
    public $carnet;
    public $carrera;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha_registro DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre=:nombre, carnet=:carnet, carrera=:carrera, fecha_registro=:fecha_registro";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->carnet = htmlspecialchars(strip_tags($this->carnet));
        $this->carrera = htmlspecialchars(strip_tags($this->carrera));
        $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":carnet", $this->carnet);
        $stmt->bindParam(":carrera", $this->carrera);
        $stmt->bindParam(":fecha_registro", $this->fecha_registro);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->nombre = $row['nombre'];
        $this->carnet = $row['carnet'];
        $this->carrera = $row['carrera'];
        $this->fecha_registro = $row['fecha_registro'];
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre=:nombre, carnet=:carnet, carrera=:carrera 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->carnet = htmlspecialchars(strip_tags($this->carnet));
        $this->carrera = htmlspecialchars(strip_tags($this->carrera));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":carnet", $this->carnet);
        $stmt->bindParam(":carrera", $this->carrera);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>