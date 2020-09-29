<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../Database.php';
include_once 'inventario.php';

$database = new DataBase();
$db = $database->getConnection();

$precio = new Inventario($db);
try{
	$data = json_decode(file_get_contents("php://input"));
	
	$query = "INSERT INTO ".$precio->table_name." SET fkcaracteristicas=:caracteristicas,fkdepto=:depto,cantidad=:cantidad,estado=:estado,observaciones=:observaciones,unidad=:unidad,fkarea=:area";
	$stm = $precio->conn->prepare($query);
	$stm->bindParam(":caracteristicas",$data->fkcaracteristicas);
	$stm->bindParam(":depto",$data->fkdepto);
	$stm->bindParam(":cantidad",$data->cantidad);
	$stm->bindParam(":estado",$data->estado);
	$stm->bindParam(":observaciones",$data->observaciones);
	$stm->bindParam(":unidad",$data->unidad);
	$stm->bindParam(":area",$data->fkarea);
	
	$stmt = $precio->Insert($stm);
	if($stmt !==false){
		echo json_encode(array("Id"=>$stmt));
	}else{
		echo json_encode(array("Error"=>"No se ha creado el registro"));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>"No se ha podido procesar la solicitud"));
}

?>