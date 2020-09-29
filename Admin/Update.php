<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../Database.php';
include_once 'User.php';

$database = new DataBase();
$db = $database->getConnection();

$precio = new Usuario($db);
try{
	$data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	$nombre=isset($data->nombre)?$data->nombre:null;
	$puesto=isset($data->puesto)?$data->puesto:'No definido';
    $usuario=isset($data->usuario)?$data->usuario:'No definido';
    $correo=isset($data->correo)?$data->correo:null;
    
    	$opciones = [
        'cost' => 12
    ];
     
    $pass= password_hash($data->contra, PASSWORD_BCRYPT, $opciones);
	
	$query = "UPDATE ".$precio->table_name." SET nombre=:nombre,puesto=:puesto,usuario=:usuario,correo=:correo,contra=:password where id=:id";
	$stm = $precio->conn->prepare($query);
	$stm->bindParam(":nombre",$nombre);
	$stm->bindParam(":puesto",$puesto);
    $stm->bindParam(":usuario",$usuario);
	$stm->bindParam(":correo",$correo);
	$stm->bindParam(":password",$pass);
	$stm->bindParam(":id",$data->id);
	$stmt = $precio->Update($stm);
	if($stmt !==false){
		echo json_encode(array("Mensaje"=>"Datos Actualizados", "nom"=>$nombre));
	}else{
		echo json_encode(array("Error"=>"No se ha creado el registro"));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>"No se ha podido procesar la solicitud"));
}

?>