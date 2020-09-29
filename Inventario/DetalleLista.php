<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'inventario.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$reser = new Inventario($db);
		try{
		 
		 $depto= isset($_GET['id'])?$_GET['id']:''; 
		 $area= isset($_GET['are'])?$_GET['are']:''; 
		 
        // $consulta = "select * from ".$reser->table_name." where fkdepto='".$depto."' and fkarea='".$area."'";
        
       $consulta = "SELECT caracteristicas.nombre, detalledepto.* FROM detalledepto
INNER JOIN caracteristicas
ON detalledepto.fkcaracteristicas = caracteristicas.id WHERE detalledepto.fkdepto='".$depto."' and detalledepto.fkarea='".$area."'";

//  $consulta = "SELECT d.*,p.costo,p.temporada,p.personas FROM propiedades as d INNER JOIN precioper as p on d.id=p.fkdepto WHERE p.personas=".$huesped." and p.temporada='Mes'"; 
		
	    $stmt = $reser->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				
				$add_item=array(
					"id"=>$id,
				    "caracteristicas"=>$fkcaracteristicas,
				    "depto"=>$fkdepto,
				    "usuario"=>$usuario,
				    "cantidad"=>$cantidad,
				    "estado"=>$estado,
				    "observaciones"=>$observaciones,
				    "unidad"=>$unidad,
				    "fkarea"=>$fkarea,
				    "img"=>$img,
				    "nombre"=>$nombre,
				);
				array_push($depas,$add_item);
			}
			echo json_encode($depas);
		}
		else {
			echo json_encode(array("Mensaje"=>"No se han encontrado registroOs"));
		}
	}catch(Exeption $e){
		echo json_encode(array("Error"=>"Error ".$e));
	}

?>