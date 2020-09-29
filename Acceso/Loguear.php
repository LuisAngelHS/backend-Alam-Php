<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'Acceso.php';
	    include_once '../Reservacion/Reservacion.php';
		

		$database = new DataBase();
		$db = $database->getConnection();

		$depto = new Acceso($db);
		try{
		    
		 $consulta = "select * from ".$depto->table_name." order by id desc";
		
		
	    $stmt = $depto->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				$add_item=array(
					"id"=>$id,
				    "idfacebook"=>$idfacebook,
	                "idgoogle"=>$idgoogle,
	                "correo"=>$correo,
	                "nombre"=>$nombre,
	                "apellidop"=>$apellidop,
	                "apellidom"=>$apellidom,
	                "politicas"=>$politicas,
	                "password"=>$password,
	                "role"=>$role,
	                "fecha"=>$fecha,
	                "imagen"=>$imagen,
	                "verificacorreo"=>$verificacorreo,
	                "telefono"=>$telefono,
	                "Reservacion"=>getReserva($db,$id),

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
	
	function getReserva($db,$id){
    $reserv = new Reserva($db);
    $stmt = $reserv->select("select * from ".$reserv->table_name." where fkdepto=".$id);
	$num = $stmt->rowCount();
	if($num>0){
	    $image=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				array_push($image,$row);
			}
			return $image;
	}else{
	    return array();
	}
}
?>