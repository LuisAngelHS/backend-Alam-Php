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
	
	$query = "INSERT INTO ".$precio->table_name." SET fkdepto=:depto,temporada=:temporada,personas=:personas,costo=:costo";
	$stm = $precio->conn->prepare($query);
	$stm->bindParam(":depto",$data->fkdepto);
	$stm->bindParam(":temporada",$data->temporada);
	$stm->bindParam(":personas",$data->personas);
	$stm->bindParam(":costo",$data->costo);
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