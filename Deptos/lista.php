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
		include_once '../Areas/Areas.php';
		include_once '../Inventario/inventario.php';

		$database = new DataBase();
		$db = $database->getConnection();

		$depto = new Depto($db);
		try{
		    
		$zona = isset($_GET['zona'])?$_GET['zona']:null;    
		$tipo = isset($_GET['tipo'])?$_GET['tipo']:'Renta';
		$desde = isset($_GET['desde'])?$_GET['desde']:'';
		$hasta = isset($_GET['hasta'])?$_GET['hasta']:'';
		$huesped = isset($_GET['huesped'])?$_GET['huesped']:'';
		$mes = isset($_GET['mes'])?$_GET['mes']:'';
		
	
	    $dif=dias_pasados($desde,$hasta);
	    if($mes){
	        
	         $consulta = "SELECT d.*,p.costo,p.temporada,p.personas FROM propiedades as d INNER JOIN precioper as p on d.id=p.fkdepto WHERE p.temporada='Mes'"; 
	        
	    } else {
	        	if($desde != ''){
		    if($dif>=18)
		    {
		       $consulta = "SELECT d.*,p.costo,p.temporada,p.personas FROM propiedades as d INNER JOIN precioper as p on d.id=p.fkdepto WHERE p.personas=".$huesped." and p.temporada='Mes'"; 
		    }
		    else {
		    $consulta="select * from ".$depto->table_name." where publicar=1 and tipo='".$tipo."' and capacidad>=".$huesped." and NOT id in (Select fkdepto from reservacion where '$desde' BETWEEN entrada and salida)";
		    }
		}else{
		    if($tipo=='Venta'){
		        $consulta="select * from ".$depto->table_name." where publicar=1 and tipo='".$tipo."' and zona='".$zona."'";
		    }else{
		    $consulta="select * from ".$depto->table_name." where publicar=1 and tipo='".$tipo."'";       
		    }
		}
	    }
		
	    $stmt = $depto->select($consulta);
		$num = $stmt->rowCount();

		if($num>0){
		    $depas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);
				if($dif>=18 || $mes){
				    	$add_item=array(
					"id"=>$id,
					"numero"=>$numero,
				// 	"capacidad"=>$capacidad,
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
					"temporada"=>$temporada,
					"PrecioFinal"=>$costo,
					"Personas"=>$personas,
					"fotos"=>getFotos($db,$id),
					"precios"=>getPrecios($db,$id),
					"areas"=>getAreas($db,$id),
				);
				}
				else {
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
					"precios"=>getPrecios($db,$id),
					"areas"=>getAreas($db,$id),
				);
				    
				}
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

function getAreas($db,$id){
    $area = new Areas($db);
    $stmt = $area->select("select * from ".$area->table_name." where fkdepto=".$id);
	$num = $stmt->rowCount();
	if($num>0){
	    $areas=array();
			while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
			    $row['detalle'] = getInventario($db,$id,$row['id']);
				array_push($areas,$row);
			}
			return $areas;
	}else{
	    return 0;
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
	
		
?>