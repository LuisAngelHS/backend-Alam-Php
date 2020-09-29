<?php
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");

		include_once '../Database.php';
		include_once 'deptos.php';
		include_once '../Fotos/foto.php';
		include_once '../Precios/preper.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$depto = new Depto($db);
		try{
		    
		$desde = isset($_GET['inicio'])?$_GET['inicio']:'';
		$hasta = isset($_GET['fin'])?$_GET['fin']:'';
		$huesped = isset($_GET['huesped'])?$_GET['huesped']:'';
		$id= isset($_GET['id'])?$_GET['id']:'';
		
		$dif=dias_pasados($desde,$hasta);
         if($dif>=18)
		    {
		    $consulta = "SELECT d.*,p.costo,p.temporada,p.personas FROM propiedades as d INNER JOIN precioper as p on d.id=p.fkdepto WHERE p.personas=".$huesped." and p.temporada='Mes'"; 
		    }
		    else{
		    $consulta = "SELECT d.*,p.costo,p.temporada,p.personas FROM propiedades as d INNER JOIN precioper as p on d.id=p.fkdepto WHERE p.temporada=(SELECT nombre from temporadas as t WHERE t.inicio<='$desde' AND t.fin>='$hasta') and p.personas=".$huesped." and NOT d.id in (Select fkdepto from reservacion as r where r.entrada>='$desde' and r.salida<='$hasta') and d.id=".$id.""; 
		    }
		
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
					"Preciofinal"=>$costo,
					"temporada"=>$temporada,
					"personas"=>$personas,
					"fotos"=>getFotos($db,$id),
					"precios"=>getPrecios($db,$id),
				);
				array_push($depas,$add_item);
			}
			echo json_encode($depas);
		
		}
		else {
			echo json_encode(array("Error"=>true,"Mensaje"=>"No se han encontrado registros"));
		}
	}catch(Exeption $e){
		echo json_encode(array("Error"=>"Error ".$e));
	}
	
function dias_pasados($fecha_inicial,$fecha_final){
    $dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
    $dias = abs($dias); $dias = floor($dias);
    return $dias;
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


function getPrecios($db,$id){
    $precio = new PrecioPer($db);
    $stmt = $precio->select("select * from ".$precio->table_name." where fkdepto=".$id);
	$num = $stmt->rowCount();
	if($num>0){
	    $precios=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				array_push($precios,$row);
			}
			return $precios;
	}else{
	    return 0;
	}
}
	
		
?>