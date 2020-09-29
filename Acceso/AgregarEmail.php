<?php 
	
header("Access-Control-Allow-Origin: * ");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
//header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");	
		

include_once '../Database.php';
include_once 'Acceso.php';

$database = new DataBase();
$db = $database->getConnection();

$acceaa = new Acceso($db);
try{
    
    $data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	$nombre=isset($data->nombre)?$data->nombre:'';
    $correo=isset($data->correo)?$data->correo:'';
    $imagen=isset($data->imagen)?$data->imagen:'';
    $tipo=isset($data->tipo)?$data->tipo:'';
    $uid=isset($data->uid)?$data->uid:'';
     
	$query = "INSERT INTO ".$acceaa->table_name." SET nombre=:nombre,correo=:correo,imagen=:imagen,tipo=:tipo,idgoogle=:uid";
	
	$stm = $acceaa->conn->prepare($query);
	
	$stm->bindParam(":nombre",$nombre);
	$stm->bindParam(":correo",$correo);
	$stm->bindParam(":imagen",$imagen);
	$stm->bindParam(":tipo",$tipo);
	$stm->bindParam(":uid",$uid);
	
	$stmt = $acceaa->Insert($stm);
	if($stmt !==false){
		echo json_encode(array("Error"=>false,"id"=>$stmt));
	}else{
		echo json_encode(array("Error"=>true,"Mensaje"=>"No se ha podido registrar"));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
}

?>