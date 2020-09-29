<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../../Database.php';
		include_once 'Propietarios.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$user = new Propietario($db);
		try{
		    
		 $consulta = "select * from ".$user->table_name." order by id desc";
		
		
	    $stmt = $user->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				$add_item=array(
					"id"=>$id,
					"nombre"=>$nombre,
					"direccion"=>$direccion,
					"telefono"=>$telefono,
					"correo"=>$correo,
					"fecha"=>$fecha,
					"usuario"=>$usuario,
				);
				array_push($depas,$add_item);
			}
			echo json_encode($depas);
		}
		else {
			echo json_encode(array("Mensaje"=>"No se han encontrado registros"));
		}
	}catch(Exeption $e){
		echo json_encode(array("Error"=>"Error ".$e));
	}
	?>