<?php 
	
header("Access-Control-Allow-Origin: * ");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
//header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");	
		

include_once '../Database.php';
include_once 'Prueba.php';

$database = new DataBase();
$db = $database->getConnection();

$acce = new Prueb($db);
try{
    
    $data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	$nombre=isset($data->nombre)?$data->nombre:null;
	$apellidop=isset($data->apellidop)?$data->apellidop:'No definido';
    $apellidom=isset($data->apellidom)?$data->apellidom:'No definido';
    $telefono=isset($data->telefono)?$data->telefono:null;
    $correo=isset($data->correo)?$data->correo:'No definido';
	$sexo=isset($data->sexo)?$data->sexo:'No definido';
    $instituto=isset($data->instituto)?$data->instituto:'No definido';
	$pais=isset($data->pais)?$data->pais:'No definido';
   
	$opciones = [
        'cost' => 12
    ];
    
      $pass= password_hash($data->password, PASSWORD_BCRYPT, $opciones);
	$query = "INSERT INTO ".$acce->table_name." SET nombre=:nombre,apellidop=:apellidop,apellidom=:apellidom,telefono=:telefono,correo=:correo,sexo=:sexo,password=:password,instituto=:instituto,pais=:pais";

	$stm = $acce->conn->prepare($query);
	
	$stm->bindParam(":nombre",$nombre);
	$stm->bindParam(":apellidop",$apellidop);
    $stm->bindParam(":apellidom",$apellidom);
	$stm->bindParam(":telefono",$telefono);
	$stm->bindParam(":correo",$correo);
	$stm->bindParam(":sexo",$sexo);
	$stm->bindParam(":password",$pass);
	$stm->bindParam(":pais",$pais);
    $stm->bindParam(":instituto",$instituto);
    	
	$stmt = $acce->Insert($stm);
	if($stmt !==false){
		echo json_encode(array("Error"=>false,"id"=>$stmt));
		
	}else{
		echo json_encode(array("Error"=>true,"Mensaje"=>"No se ha podido registrar",$nombre));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
}
?>