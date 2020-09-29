<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../Database.php';
include_once 'Temporadas.php';

$database = new DataBase();
$db = $database->getConnection();

$precio = new Temporada($db);
try{
	$data = json_decode(file_get_contents("php://input"));
	
	 $inicio=isset($data->inicio)?$data->inicio:'inicio';
	 $fin=isset($data->fin)?$data->fin:'fin';
	 
	$entrada = strtotime($inicio); //Convierte el string a formato de fecha php
	$salida = strtotime($fin); //Convierte el string a formato de fecha en php
	
$entrada = date('Y-m-d',$entrada); //Lo comvierte a formato de fecha en MySQL
	$salida = date('Y-m-d',$salida); //Lo comvierte a formato de fecha en MySQL
	
	$query = "INSERT INTO ".$precio->table_name." SET nombre=:nombre,inicio=:inicio,fin=:fin,quien_crea=:quien_crea";
	$stm = $precio->conn->prepare($query);
	$stm->bindParam(":nombre",$data->nombre);
	$stm->bindParam(":inicio",$entrada);
	$stm->bindParam(":fin",$salida);
	$stm->bindParam(":quien_crea",$data->quien_crea);
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