<?php 
	
header("Access-Control-Allow-Origin: * ");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
//header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");	
		

include_once '../Database.php';
include_once 'Caracteristicas.php';

$database = new DataBase();
$db = $database->getConnection();

$acce = new caracterist($db);
try{
    
    $data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	
	$nombre=isset($data->nombre)?$data->nombre:null;
	$Descripcion=isset($data->descripcion)?$data->descripcion:'No definido';
   
  
	$query = "INSERT INTO ".$acce->table_name." SET nombre=:nombre,descripcion=:descripcion";
	
	$stm = $acce->conn->prepare($query);
	
	$stm->bindParam(":nombre",$nombre);
	$stm->bindParam(":descripcion",$Descripcion);
         
	$stmt = $acce->Insert($stm);
	if($stmt !==false){
		echo json_encode(array("Error"=>false,"id"=>$stmt));
		
	}else{
		echo json_encode(array("Error"=>true,"Mensaje"=>"No se ha podido registrar"));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
}
?>