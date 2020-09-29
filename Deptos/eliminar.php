<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:DELETE");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../Database.php';
include_once 'deptos.php';

$database = new DataBase();
$db = $database->getConnection();

$depto = new Depto($db);
try{
	$data = $_GET['id']; //json_decode(file_get_contents("php://input"));
	$depto->id=$data;
	$valor = $depto->delete();
	if($valor !== false){
		echo json_encode(array("error"=>true,"mensaje"=>"Datos Borrados"));
	}else{
		echo json_encode(array("error"=>false,"mensaje"=>"No se ha creado el registro"));
	}
}catch(Exception $ex){
	echo json_encode(array("error"=>false,"mensaje"=>"No se ha podido procesar la solicitud"));
}

?>