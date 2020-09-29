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

	    $correo=isset($data->correo)?$data->correo:null;
	    $pass=isset($data->password)?$data->password:null;
        
    	$consulta = "select * from ".$acce->table_name." WHERE correo='".$correo."'";
            $stmt = $acce->select($consulta);
            
		$num = $stmt->rowCount();
		if($num>0){
		 $row=$stmt->fetch(PDO::FETCH_ASSOC);
    	      extract($row);
    	        if(password_verify($pass,$password)){
    	            	echo json_encode(array("Error"=>false, "nombre"=>$nombre,"id"=>$id,"Mensaje"=>"Bienvenido"));
    	        }
    	        else {
    	            	echo json_encode(array("Error"=>true, "Mensaje"=>"La contraseña no coincide"));
    	        }   
		}else {
		    echo json_encode(array("Error"=>true,"Mensaje"=>"Correo no registrado","correo"=>$correo));
		}
    	        
    	        
    	    
}catch(Exception $ex){
	echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
}

?>