<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'deptos.php';
		include_once '../Fotos/foto.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$depto = new Depto($db);
		try{
		$hoy = date('y-m-d');
		$consulta="select * from ".$depto->table_name." where publicar=1 and tipo='Renta' and NOT id in (Select fkdepto from reservacion where '$hoy' BETWEEN entrada and salida) limit 3";
		
		
	    $stmt = $depto->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				$add_item=array(
					"id"=>$id,
					"numero"=>$numero,
					"capacidad"=>$capacidad,
					"habitaciones"=>$habitaciones,
					"camas"=>$camas,
					"banios"=>$banios,
					"disponibilidad"=>$disponibilidad,
					"descripcion"=>$descripcion,
					"costopp"=>$costopp,
					"apartir"=>$apartir,
					"cuota"=>$cuota,
					"limpieza"=>$limpieza,
					"precio"=>$precio,
					"titulo"=>$titulo,
					"fotos"=>getFotos($db,$id),
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
	
function getFotos($db,$id){
    $foto = new Foto($db);
    $stmt = $foto->select("select * from ".$foto->table_name." where fkdepto=".$id);
	$num = $stmt->rowCount();
	if($num>0){
	    $image=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				array_push($image,$row);
			}
			return $image;
	}else{
	    return 0;
	}
}
	
		
?>