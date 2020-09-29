<?php 
header("Access-Control-Allow-Origin: * ");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");	

include_once '../Database.php';
include_once '../Fotos/foto.php';

$database = new DataBase();
$db = $database->getConnection();

$acce = new Foto($db);
try{
    
    $data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	$depto=isset($data->depto)?$data->depto:null;
	$tipoarea=isset($data->tipoarea)?$data->tipoarea:null;
$nombreArchivo=isset($data->nombreArchivo)?$data->nombreArchivo:'No definido';
$file=$_FILES["nombreArchivo"]["tmp_name"];
    $archivo=isset($data->archivo)?$data->archivo:'No definido';
    
    // file_exists
    // is_writable()
    $filePath = "hola/".$depto;
    
    //Validamos si la ruta de destino existe, en caso de no existir la creamos
    if(!file_exists($filePath)){
				mkdir($filePath,0777) or die("No se puede crear el directorio de extracci&oacute;n");	
			}
			
    	$dir=opendir($filePath); //Abrimos el directorio de destino
    	
    $destino = $filePath."/".$nombreArchivo;
    $dirBase ='http://propiedadeslaureth.com/Api/CargaImagenes/'.$destino;
    
	if(move_uploaded_file($file, $dirBase)){
	    echo "El archivo  se ha almacenado en forma exitosa.<br>";
				} else {	
				echo "Ha ocurrido un error, por favor inténtelo de nuevo.<br>";
	}
							
		closedir($dir); //Cerramos el directorio de destino					
    
       $query = "INSERT INTO ".$acce->table_name." SET fkdepto=:depto,fktipoarea=:tipoarea,ruta=:ruta";
       
    $stm = $acce->conn->prepare($query);
    $stm->bindParam(":depto",$depto);
	$stm->bindParam(":tipoarea",$data->tipoarea);
    $stm->bindParam(":ruta",$dirBase);
    $stmt = $acce->Insert($stm);
	
	if($stmt !==false){
		echo json_encode(array("Error"=>false,"id"=>$stmt,"link"=>$dirBase,"archivo"=>$file));
		
	}else{
		echo json_encode(array("Error"=>true,"Mensaje"=>"No se ha podido registrar"));
	} 

}catch(Exception $ex){
	echo json_encode(array("Error"=>true, "Mensaje"=>"No se ha podido procesar la solicitud"));
}
?>