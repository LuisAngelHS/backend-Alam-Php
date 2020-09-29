<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'Acceso.php';
		

		$database = new DataBase();
		$db = $database->getConnection();

		$acce = new Acceso($db);
		try{
		    
		 $consulta = "select * from ".$acce->table_name." order by id desc";
		
		
	    $stmt = $acce->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				array_push($depas,$row);
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