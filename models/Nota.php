<?php
class Nota {
    private $conn;
    private $table_name = "notas";

    public $id;
    public $modulo;
    public $nota1;
    public $nota2;
    public $tarea;
    public $promedio;
    public $id_estudiante;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT n.*, e.nombre as nombre_estudiante 
                  FROM " . $this->table_name . " n
                  LEFT JOIN estudiantes e ON n.id_estudiante = e.id
                  ORDER BY n.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        // Calcular promedio
        $this->promedio = ($this->nota1 * 0.3) + ($this->nota2 * 0.3) + ($this->tarea * 0.4);
        
        $query = "INSERT INTO " . $this->table_name . " 
                  SET modulo=:modulo, nota1=:nota1, nota2=:nota2, tarea=:tarea, promedio=:promedio, id_estudiante=:id_estudiante";
        
        $stmt = $this->conn->prepare($query);
        
        $this->modulo = htmlspecialchars(strip_tags($this->modulo));
        $this->nota1 = htmlspecialchars(strip_tags($this->nota1));
        $this->nota2 = htmlspecialchars(strip_tags($this->nota2));
        $this->tarea = htmlspecialchars(strip_tags($this->tarea));
        $this->id_estudiante = htmlspecialchars(strip_tags($this->id_estudiante));
        
        $stmt->bindParam(":modulo", $this->modulo);
        $stmt->bindParam(":nota1", $this->nota1);
        $stmt->bindParam(":nota2", $this->nota2);
        $stmt->bindParam(":tarea", $this->tarea);
        $stmt->bindParam(":promedio", $this->promedio);
        $stmt->bindParam(":id_estudiante", $this->id_estudiante);
        
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
        
        $this->modulo = $row['modulo'];
        $this->nota1 = $row['nota1'];
        $this->nota2 = $row['nota2'];
        $this->tarea = $row['tarea'];
        $this->promedio = $row['promedio'];
        $this->id_estudiante = $row['id_estudiante'];
    }

    public function update() {
        // Calcular promedio
        $this->promedio = ($this->nota1 * 0.3) + ($this->nota2 * 0.3) + ($this->tarea * 0.4);
        
        $query = "UPDATE " . $this->table_name . " 
                  SET modulo=:modulo, nota1=:nota1, nota2=:nota2, tarea=:tarea, promedio=:promedio, id_estudiante=:id_estudiante
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->modulo = htmlspecialchars(strip_tags($this->modulo));
        $this->nota1 = htmlspecialchars(strip_tags($this->nota1));
        $this->nota2 = htmlspecialchars(strip_tags($this->nota2));
        $this->tarea = htmlspecialchars(strip_tags($this->tarea));
        $this->id_estudiante = htmlspecialchars(strip_tags($this->id_estudiante));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":modulo", $this->modulo);
        $stmt->bindParam(":nota1", $this->nota1);
        $stmt->bindParam(":nota2", $this->nota2);
        $stmt->bindParam(":tarea", $this->tarea);
        $stmt->bindParam(":promedio", $this->promedio);
        $stmt->bindParam(":id_estudiante", $this->id_estudiante);
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

    public function readByEstudiante($id_estudiante) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_estudiante = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_estudiante);
        $stmt->execute();
        return $stmt;
    }
}
?>