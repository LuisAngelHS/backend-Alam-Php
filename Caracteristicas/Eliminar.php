<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../Database.php';
include_once 'Caracteristicas.php';

$database = new DataBase();
$db = $database->getConnection();

$precio = new  caracterist($db);
try{
	$data = json_decode(file_get_contents("php://input"));
	$precio->id=$data->id;
	$precio->delete();
	if($precio!==false){
		echo json_encode(array("Mensaje"=>"Datos Borrados"));
	}else{
		echo json_encode(array("Error"=>"No se ha creado el registro"));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>"No se ha podido procesar la solicitud"));
}

?>