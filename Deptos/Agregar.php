<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");
		

include_once '../Database.php';
include_once 'deptos.php';

$database = new DataBase();
$db = $database->getConnection();

$acce = new Depto($db);
try{
    
    $data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	
	$titulo=isset($data->titulo)?$data->titulo:null;
	$numero=isset($data->numero)?$data->numero:'No definido';
    $tipo=isset($data->tipo)?$data->tipo:'No definido';
    $zona=isset($data->zona)?$data->zona:null;
    $capacidad=isset($data->capacidad)?$data->capacidad:null;
    $medida=isset($data->medida)?$data->medida:'No definido';
    $habitaciones=isset($data->habitaciones)?$data->habitaciones:false;
    $camas=isset($data->camas)?$data->camas:false;
    $banios=isset($data->banios)?$data->banios:false;
    $descripcion=isset($data->descripcion)?$data->descripcion:false;
     
	$query = "INSERT INTO ".$acce->table_name." SET titulo=:titulo,numero=:numero,tipo=:tipo,zona=:zona,medida=:medida,capacidad=:capacidad,habitaciones=:habitaciones,camas=:camas,banios=:banios,descripcion=:descripcion";
	
	$stm = $acce->conn->prepare($query);
	
	$stm->bindParam(":titulo",$titulo);
	$stm->bindParam(":numero",$numero);
    $stm->bindParam(":tipo",$tipo);
	$stm->bindParam(":zona",$zona);
	$stm->bindParam(":medida",$medida);
	$stm->bindParam(":capacidad",$capacidad);
	$stm->bindParam(":habitaciones",$habitaciones);
	$stm->bindParam(":camas",$camas);
	$stm->bindParam(":banios",$banios);
	$stm->bindParam(":descripcion",$descripcion);
	
         
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