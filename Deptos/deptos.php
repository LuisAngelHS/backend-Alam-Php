<?php 
class Depto{
	public $conn;
	public $table_name="propiedades";

	public $id;
	public $numero;
	public $propietario;
	public $capacidad;
	public $habitaciones;
	public $camas;
	public $banios;
	public $disponibilidad;
	public $tipo;
	public $zona;
	public $medida;
	public $descripcion;
	public $estado;
	public $costopp;
	public $apartir;
	public $cuota;
	public $limpieza;
	public $precio;
	public $moneda;
	public $categoria;
	public $subcategoria;
	public $titulo;
	public $ejuridico;
	public $comision;
	public $inmobiliaria;
	public $catastral;
	public $llaves;
	public $comentario;
	public $publicar;
	public $propuesta;
	public $usuario;
	public $fecha;
	

	public function __construct($db){
		$this->conn=$db;
	}

	function select($consulta){
			$query=$consulta;
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;
		}

	function read(){
		 
		 $consulta="select * from ".$this->table_name." ORDER by id";
		 	
		    // prepare query statement
		    $stmt = $this->conn->prepare($consulta);
		    // execute query
		    $stmt->execute();
		 
		    return $stmt;

		}

	function Insert($stmt){
		try{
			$this->conn->beginTransaction();
			if($stmt->execute()){
				$id = $this->conn->lastInsertId("id");
				$this->conn->commit();
				return $id;
			}
		}catch(PDOExecption $e){
			$this->conn->rollback();
		}
		return false;
	}

	function Update($stmt){
		try{
			$this->conn->beginTransaction();
			if($stmt->execute()){
				
				$this->conn->commit();
				return true;
			}
		}catch(PDOExecption $e){
			$this->conn->rollback();
		}
		return false;
	}

    
	function delete(){
			$query="DELETE FROM ". $this->table_name." WHERE id=?";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(1,$this->id);
			if($stmt->execute()){
				return true;
			}
			return false;
		}
}
?>