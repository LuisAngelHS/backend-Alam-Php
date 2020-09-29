<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'deptos.php';
		include_once '../Fotos/foto.php';
		include_once '../Servicios/servicios.php';
		include_once '../Inventario/inventario.php';
		include_once '../Precios/preper.php';
		include_once '../Ubicacion/Ubicacion.php';
		 include_once '../Reservacion/Reservacion.php';
		include_once '../Areas/Areas.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$depto = new Depto($db);
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
					"numero"=>$numero,
					"nombre"=>$nombre,
					"capacidad"=>$capacidad,
					"habitaciones"=>$habitaciones,
					"camas"=>$camas,
					"tipo"=>$tipo,
					"zona"=>$zona,
					"banios"=>$banios,
					"disponibilidad"=>$disponibilidad,
					"descripcion"=>$descripcion,
					"costopp"=>$costopp,
					"apartir"=>$apartir,
					"cuota"=>$cuota,
					"limpieza"=>$limpieza,
					"precio"=>$precio,
					"titulo"=>$titulo,
					"medida"=>$medida,
					"inmobiliaria"=>$inmobiliaria,
					"propietario"=>$propietario,
					"fotos"=>getFotos($db,$id),
					"servicios"=>getServicios($db,$id),
					"preper"=>getPreper($db,$id),
					"Ubicacion"=>getUbicacion($db,$id),
					"Reservacion"=>getReserva($db,$id),
					"areas"=>getAreas($db,$id),
					"Detalle"=>getDetalle($db,$id),
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
	    return array();
	}
}
	
function getServicios($db,$id){
    $serv = new Servicio($db);
    $stmt = $serv->select("select * from ".$serv->table_name." where fkdepto=".$id);
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

function getDetalle($db,$id){
    $serv = new Inventario($db);
    $stmt = $serv->select("select * from ".$serv->table_name." where fkdepto=".$id);
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


function getInventario($db,$id,$area){
    $inv = new Inventario($db);
    $stmt = $inv->select("select d.*,c.nombre from ".$inv->table_name." as d, caracteristicas as c where c.id=d.fkcaracteristicas and d.fkarea=".$area." and d.fkdepto=".$id);
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

function getPreper($db,$id){
    $inv = new PrecioPer($db);
    $stmt = $inv->select("select * from ".$inv->table_name." where fkdepto=".$id);
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

function getUbicacion($db,$id){
    $inv = new Ubicacion($db);
    $stmt = $inv->select("select * from ".$inv->table_name." where propiedad=".$id);
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

function getAreas($db,$id){
    $area = new Areas($db);
    $stmt = $area->select("select * from ".$area->table_name." where fkdepto=".$id);
	$num = $stmt->rowCount();
	if($num>0){
	    $areas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
			    $row['detalle']=getInventario($db,$id,$row['id']);
				array_push($areas,$row);
			}
			return $areas;
	}else{
	    return 0;
	}
}
		
?>