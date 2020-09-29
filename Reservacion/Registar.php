<?php 

header("Access-Control-Allow-Origin: * ");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
//header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");	
		
include_once '../Database.php';
include_once 'Reservacion.php';

$database = new DataBase();
$db = $database->getConnection();

$reserv = new Reserva($db);
try{
    
    $data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	$nombre=isset($data->nombre)?$data->nombre:'null';
	$correo=isset($data->correo)?$data->correo:'null';
    $entrada=isset($data->entrada)?$data->entrada:'entrada';
    $estado=isset($data->estado)?$data->estado:'Pendiente';
    $salida=isset($data->salida)?$data->salida:'salida';
    $fkdepto=isset($data->fkdepto)?$data->fkdepto:'null';
    $personas=isset($data->personas)?$data->personas:'null';
    $costo=isset($data->costo)?$data->costo:'null';
    $acceso=isset($data->acceso)?$data->acceso:'null';
    
    $inicio = strtotime($entrada); //Convierte el string a formato de fecha php
	$fin = strtotime($salida); //Convierte el string a formato de fecha en php
	
	$inicio = date('Y-m-d',$inicio); //Lo comvierte a formato de fecha en MySQL
	$fin = date('Y-m-d',$fin); //Lo comvierte a formato de fecha en MySQL
	
	//Revisar que no haya ocupaciones para esa fecha de entrada.
    $result = "SELECT id from reservacion where fkdepto='$fkdepto' and estado='Reservado' AND '$entrada' BETWEEN entrada and salida ORDER BY id desc limit 1";
    
    $stm = $reserv->select($result);
    
    $row = $stm->fetch(PDO::FETCH_ASSOC);
    
	if($row == null){
	    
    $consulta = "INSERT INTO ".$reserv->table_name." SET nombre=:nombre,correo=:correo,entrada=:entrada,salida=:salida,fkdepto=:fkdepto,estado=:estado,personas=:personas,costo=:costo,acceso=:acceso";
	
	$stm = $reserv->conn->prepare($consulta);
	
	$stm->bindParam(":nombre",$data->nombre);
	$stm->bindParam(":correo",$data->correo);
    $stm->bindParam(":entrada",$inicio);
	$stm->bindParam(":salida",$fin);
	$stm->bindParam(":fkdepto",$data->fkdepto);
	$stm->bindParam(":estado",$estado);
	$stm->bindParam(":personas",$data->personas);
	$stm->bindParam(":costo",$data->costo);
	$stm->bindParam(":acceso",$data->acceso);
	
	$stmt = $reserv->Insert($stm);
	if($stmt !==false){
		echo json_encode(array("Error"=>false,"id"=>$stmt,"row"=>$row,$entrada));
		
	}else{
		echo json_encode(array("Error"=>true,"Mensaje"=>"No se ha podido registrar","ident"=>$nombre,$correo,$inicio,$fin,$fkdepto,$personas,$costo,$acceso));
	    }	    
	    
	}
	else {
	    	echo json_encode(array("id"=>$row));
	}
    
}catch(Exception $ex){
	echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
}
?>