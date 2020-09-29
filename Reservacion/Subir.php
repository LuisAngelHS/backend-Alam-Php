<?php 
header("Access-Control-Allow-Origin: * ");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");	

include_once '../Database.php';
include_once 'Reservacion.php';

$database = new DataBase();
$db = $database->getConnection();

$acce = new Reserva($db);
try{
    
    $data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	$id=isset($data->id)?$data->id:null;
	$nombre=isset($data->nombre)?$data->nombre:null;
$nombreArchivo=isset($data->nombreArchivo)?$data->nombreArchivo:'No definido';
    $archivo=isset($data->archivo)?$data->archivo:'No definido';
    
    $filePath = "hola/".$id;
	mkdir($filePath,0777);
    	
    $destino = $filePath."/".$nombreArchivo;
			                move_uploaded_file($nombreArchivo, $destino);
			                file_put_contents($destino, $archivo);
							$dirBase ='http://propiedadeslaureth.com/Api/Reservacion/'.$destino;
							
							
    
     $query = "UPDATE ".$acce->table_name." SET comprobante=:tipo WHERE id=:id";
     $stm = $acce->conn->prepare($query);
     $stm->bindParam(":tipo",$dirBase);
     $stm->bindParam(":id",$id);
     $stmt = $acce->Update($stm);
     
    if($stmt){
                    	       class Result {}
    // GENERA LOS DATOS DE RESPUESTA
    $response = new Result();
    
    $response->resultado = 'OK';
    $response->mensaje = 'SE SUBIO EXITOSAMENTE';
    $response->dire =$nombreArchivo;
    $response->id =$id;
    $response->linkh =$dirBase;
    
    
    header('Content-Type: application/json');
    echo json_encode($response); // MUESTRA EL JSON GENERADO */
	}

}catch(Exception $ex){
	echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
}
?>