<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../Database.php';
include_once 'Areas.php';

$database = new DataBase();
$db = $database->getConnection();

$precio = new Areas($db);
try{
	$data = json_decode(file_get_contents("php://input"));
	
	$query = "INSERT INTO ".$precio->table_name." SET nombre=:nombre,tipo=:tipo,fkdepto=:fkdepto";
	$stm = $precio->conn->prepare($query);
	$stm->bindParam(":nombre",$data->nombre);
	$stm->bindParam(":tipo",$data->tipo);
	$stm->bindParam(":fkdepto",$data->fkdepto);
	
	$stmt = $precio->Insert($stm);
	if($stmt !==false){
		echo json_encode(array("id"=>$stmt));
	}else{
		echo json_encode(array("Error"=>"No se ha creado el registro"));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>"No se ha podido procesar la solicitud"));
}

?>