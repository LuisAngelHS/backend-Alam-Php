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
        $acces = new Acceso($db);
		try{
		    	$data = json_decode(file_get_contents("php://input"));
        		
        		if(!isset($data)){
        		    echo json_encode(array("Mensaje"=>"Error al obtener los datos"));
        		    return;
        		}
        	$uid=isset($data->uid)?$data->uid:'null';
        	$corre=isset($data->correo)?$data->correo:'null';
        		
		    if($data->tipo=='gmail'){
    	    $consulta = "select * from ".$acces->table_name." WHERE idgoogle='".$uid."' || correo='".$corre."'";
		    }else{
		    $consulta = "select * from ".$acces->table_name." WHERE idfacebook='".$uid."' || correo='".$corre."'";
		    }
			$stmt = $acces->select($consulta);
		    $num = $stmt->rowCount();

		if($num>0){
			$row =$stmt->fetch(PDO::FETCH_ASSOC);
				extract($row);
				if($data->uid===$idgoogle || $data->uid===$idfacebook){
				   	echo json_encode(array("Error"=>false,"id"=>$id,"nombre"=>$nombre,"Mensaje"=>"Bienvenido"));
				}else{
				    if($data->tipo!='correo'){
				  		 echo json_encode(array("Error"=>false, "Mensaje"=>"Desea cambiar el tipo"));
				  		 
				  		 if($data->tipo==='gmail'){
				  		     $query = "UPDATE ".$acces->table_name." SET idgoogle=:google, tipo=:tipo WHERE id=:id";
				  		 } else {
				  		        $query = "UPDATE ".$acces->table_name." SET idfacebook=:face, tipo=:tipo WHERE id=:id";
				  		 }
                    	$stm = $acces->conn->prepare($query);
                    	$stm->bindParam(":tipo",$data->tipo);
                    	$stm->bindParam(":id",$id);
                    	  	if($data->tipo=='gmail'){
                    	$stm->bindParam(":google",$data->uid);
                    	}else{
                    	$stm->bindParam(":face",$data->uid);    
                    	}
                    	$stmt = $acces->Update($stm);
                    	if($stmt){
                    		echo json_encode(array("Error"=>false,"id"=>$id));
                    	}else{
                    		echo json_encode(array("Error"=>true,"No se ha podido procesar la peticion "));
                    	    }
				        }
				    }
				}
		
		else {
        	if($data->tipo=='gmail'){
	        $query = "INSERT INTO ".$acces->table_name." SET nombre=:nombre,correo=:correo,imagen=:imagen,tipo=:tipo,idgoogle=:uid";
        	}else{
	        $query = "INSERT INTO ".$acces->table_name." SET nombre=:nombre,correo=:correo,imagen=:imagen,tipo=:tipo,idfacebook=:uid";
        	}
        	$stm = $acces->conn->prepare($query);
        	$stm->bindParam(":nombre",$data->nombre);
        	$stm->bindParam(":correo",$data->correo);
        	$stm->bindParam(":imagen",$data->imagen);
        	$stm->bindParam(":tipo",$data->tipo);
        	$stm->bindParam(":uid",$uid);
        	$stmt = $acces->Insert($stm);
        	if($stmt !==false){
	       	echo json_encode(array("Error"=>false, "id"=>$stmt,"Mensaje"=>"Bienvenido"));
        	}else{
	        	echo json_encode(array("Error"=>true,"Mensaje"=>" No se ha podido registrar","id"=>$stmt));
	}
		}
	}catch(Exeption $e){
			echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
	}
?>