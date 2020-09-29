<?php
	header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");


		include_once '../Database.php';
		include_once '../Fotos/foto.php';
		include_once 'preper.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$prec = new PrecioPer($db);
		try{
		    
		 $consulta = "select * from ".$prec->table_name." order by id asc";
		
		
	    $stmt = $prec->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $pre=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				$add_item=array(
					"id"=>$id,
					"temporada"=>$temporada,
					"personas"=>$personas,
					"costo"=>$costo,
					"usuario"=>$usuario,
	                "fecha"=>$fecha,
				);
				array_push($pre,$add_item);
			}
			echo json_encode($pre);
		}
		else {
			echo json_encode(array("Mensaje"=>"No se han encontrado registros"));
		}
	}catch(Exeption $e){
		echo json_encode(array("Error"=>"Error ".$e));
	}
?>