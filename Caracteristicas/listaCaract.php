<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'Caracteristicas.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$reser = new caracterist($db);
		try{
		 
        $consulta = "select * from ".$reser->table_name." order by nombre asc";
		
	    $stmt = $reser->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				
				$add_item=array(
					"id"=>$id,
				    "nombre"=>$nombre,
				    "descripcion"=>$descripcion,
				    "fecha"=>$fecha,
				    "tipoarea"=>$fktipoarea,
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