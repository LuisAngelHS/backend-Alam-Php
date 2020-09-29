<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'Reservacion.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$reser = new Reserva($db);
		try{
		 
// 		 $clave = isset($_GET['id'])?$_GET['id']:''; 
		 $email = isset($_GET['correo'])?$_GET['correo']:''; 
		 $consulta = "select * from ".$reser->table_name." where correo='".$email."'";
		
	    $stmt = $reser->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				
				$add_item=array(
					"id"=>$id,
				    "nombre"=>$nombre,
	                "correo"=>$correo,
	                "entrada"=>$entrada,
	                "salida"=>$salida,
	                "fkdepto"=>$fkdepto,
	                "estado"=>$estado,
	                "personas"=>$personas,
	                "costo"=>$costo,
	                "anticipo"=>$anticipo,
	                "comprobante"=>$comprobante,
	                "identificacion"=>$identificacion,
	                "acceso"=>$acceso,
	                "origen"=>$origen,
	                "fecha"=>$fecha,
				);
				array_push($depas,$add_item);
			}
			echo json_encode($depas);
		}
		else {
			echo json_encode(array("Mensaje"=>"No se han encontrado registros",$email));
		}
	}catch(Exeption $e){
		echo json_encode(array("Error"=>"Error ".$e));
	}

?>