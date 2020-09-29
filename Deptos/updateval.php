<?php 
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: access");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Allow-Credentials: true");
		header("Content-Type: application/json; charset=UTF-8");
		
include_once '../Database.php';
include_once 'deptos.php';

$database = new DataBase();
$db = $database->getConnection();

$depto = new Depto($db);
try{
	$deptoid = isset($_GET['id'])?$_GET['id']:0;
	$campo = isset($_GET['campo'])?$_GET['campo']:die();
	$valor = isset($_GET['valor'])?$_GET['valor']:die();

	if($deptoid==0){
	$query = "INSERT INTO ".$depto->table_name." SET ".$campo."=:valor";
	$stm = $depto->conn->prepare($query);
	$stm->bindParam(":valor",$valor);
	$stmt = $depto->Insert($stm);
	if($stmt !== false){
	    echo json_encode(array("Id"=>$stmt));
	} else {
	    echo json_encode(array("Error"=>true));
	}

	}
	else {
	$query = "UPDATE ".$depto->table_name." SET ".$campo."=:valor WHERE id=:id";
	$stm = $depto->conn->prepare($query);
	$stm->bindParam(":id",$deptoid);
	$stm->bindParam(":valor",$valor);
	$stmt = $depto->Update($stm);
		//$imprimir="hola";
			if($stmt !==false){
		echo json_encode(array("Mensaje"=>"El campo ".$campo." se ha Actualizado"));
	}else{
		echo json_encode(array("Error"=>"No se ha podido actualizar el campo"));
	}
	}
	

}catch(Exception $ex){
	echo json_encode(array("Error"=>"No se ha podido procesar la solicitud"));
}

?>