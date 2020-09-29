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

$acce = new Acceso($db);
try{
    
    $data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	
	$nombre=isset($data->nombre)?$data->nombre:null;
	$apellidop=isset($data->apellidop)?$data->apellidop:'No definido';
    $apellidom=isset($data->apellidom)?$data->apellidom:'No definido';
    $telefono=isset($data->telefono)?$data->telefono:null;
    $tipo=isset($data->tipo)?$data->tipo:'correo';
    $correo=isset($data->correo)?$data->correo:'No definido';
    $politicas=isset($data->politicas)?$data->politicas:false;
   
    $mensaje = "Gracias por registrarse, para terminar el proceso de clic en el siguiente link \r\n";
    $mensaje .="http://departamentosaca.promozonea.com.mx/verificar.php";
    $cabeceras = "From: Departamentos Acapulco <acapulcodepartamentos@gmail.com>";
    mail($correo,'Verificar Correo',$mensaje,$cabeceras);

	$opciones = [
        'cost' => 12
    ];
     
    $pass= password_hash($data->password, PASSWORD_BCRYPT, $opciones);
	$query = "INSERT INTO ".$acce->table_name." SET nombre=:nombre,apellidop=:apellidop,apellidom=:apellidom,telefono=:telefono,correo=:correo,tipo=:tipo,password=:password,politicas=:politicas";
	
	$stm = $acce->conn->prepare($query);
	
	$stm->bindParam(":nombre",$nombre);
	$stm->bindParam(":apellidop",$apellidop);
    $stm->bindParam(":apellidom",$apellidom);
	$stm->bindParam(":telefono",$telefono);
	$stm->bindParam(":tipo",$tipo);
	$stm->bindParam(":correo",$correo);
	$stm->bindParam(":password",$pass);
	$stm->bindParam(":politicas",$politicas);
         
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