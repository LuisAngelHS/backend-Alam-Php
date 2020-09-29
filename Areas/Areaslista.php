<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'Areas.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$area = new Areas($db);
		try{
		$depto= isset($_GET['id'])?$_GET['id']:''; 
        $consulta = "select * from ".$area->table_name." where  fkdepto='".$depto."'";
		
	    $stmt = $area->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				
				$add_item=array(
					"id"=>$id,
				    "nombre"=>$nombre,
				    "tipo"=>$tipo,
				    "fkdepto"=>$fkdepto,
				    "fecha"=>$fecha,
				    "fkuser"=>$fkuser,
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