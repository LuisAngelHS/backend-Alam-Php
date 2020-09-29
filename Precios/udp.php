<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../Database.php';
include_once 'preper.php';

$database = new DataBase();
$db = $database->getConnection();

$precio = new PrecioPer($db);
try{
	$data = json_decode(file_get_contents("php://input"));
	
	$query = "UPDATE ".$precio->table_name." SET temporada=:temporada,personas=:personas,costo=:costo where id=:id";
	$stm = $precio->conn->prepare($query);
	$stm->bindParam(":temporada",$data->temporada);
	$stm->bindParam(":personas",$data->personas);
	$stm->bindParam(":costo",$data->costo);
	$stm->bindParam(":id",$data->id);
	$stmt = $precio->Update($stm);
	if($stmt !==false){
		echo json_encode(array("Mensaje"=>"Datos Actualizados"));
	}else{
		echo json_encode(array("Error"=>"No se ha creado el registro"));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>"No se ha podido procesar la solicitud"));
}

?>