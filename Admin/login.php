<?php
header("Access-Control-Allow-Origin: * ");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
//header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");	
		
		include_once '../Database.php';
        include_once 'Usuarios.php';

        $database = new DataBase();
        $db = $database->getConnection();
        $acces = new Usuario($db);
		try{
		    	$data = json_decode(file_get_contents("php://input"));
        		
        		if(!isset($data)){
        		    echo json_encode(array("Mensaje"=>"Error al obtener los datos"));
        		    return;
        		}
        	$uid=isset($data->password)?$data->password:'null';
        	$corre=isset($data->correo)?$data->correo:'null';
        
		    $consulta = "select * from ".$acces->table_name." WHERE correo='".$corre."'";
		    
			$stmt = $acces->select($consulta);
		    $num = $stmt->rowCount();

		if($num>0){
			$row =$stmt->fetch(PDO::FETCH_ASSOC);
				extract($row);
				
                    	if($stmt){
                    	      if(password_verify($uid,$contra)){
    	            	echo json_encode(array("Error"=>false,"id"=>$id,"uid"=>$uid,"correo"=>$correo,"puesto"=>$puesto));
    	        }
    	        else {
    	            	echo json_encode(array("Error"=>true, "Mensaje"=>"La contrase単a no coincide"));
    	        }   
                    	}else{
                    		echo json_encode(array("Error"=>true,"Mensaje"=>"No se ha podido procesar la peticion "));
                    	    }
				        
				    
				}
	}catch(Exeption $e){
			echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
	}
?>