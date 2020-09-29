<?php
header("Access-Control-Allow-Origin: * ");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../Database.php';
include_once 'galeria.php';

$database = new DataBase();

$db = $database->getConnection();

$img = new Img($db);
$id = isset($_GET['id'])?$_GET['id']:null;
$area = isset($_GET['area'])?$_GET['area']:0;
if($id == null){
    echo json_encode(array('status'=>false,'msg'=>'El departamento no ha sido validado'));
    return;
}
$rut='https://propiedadeslaureth.com/conexion/' .$id. '/';
$path="../../conexion/" . $id . "/";

 if(!file_exists($path)){
				mkdir($path,0777) or die("No se puede crear el directorio de extracci&oacute;n");	
}

if(isset($_FILES['file'])){
	$originalname=$_FILES['file']['name'];
	$filePath = $path.$originalname;
	$ruti=$rut.$originalname;
	if (!is_writable($path)) {
		echo json_encode(array('status'=>false,'msg'=>'El directorio no existe, o no tiene permisos de acceso'));
	}
	if(move_uploaded_file($_FILES['file']['tmp_name'], $filePath)){
	$query = "INSERT INTO ".$img->table_name." SET ruta=:rut, fkdepto=:depto, fktipoarea=:area";
	$stm = $img->conn->prepare($query);
	$stm->bindParam(":rut",$ruti);
	$stm->bindParam(":depto",$id);
	$stm->bindParam(":area",$area);
	
	$stmt = $img->Insert($stm);
	if($stmt !==false){
		echo json_encode(array('status'=>true,'ruta'=>$rut));
	}else{
	echo json_encode(array('status'=> false,'msg'=>'No se ha cargado el archivo'));    
	}
	}
}else{
	echo json_encode(array('status'=> false,'msg'=>'No se ha cargado el archivo'));
}
?>