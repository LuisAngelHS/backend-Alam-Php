<?php
session_start();
if($_SERVER['REQUEST_METHOD']=='POST'){
	$usuario=filter_var(strtolower($_POST['user']),FILTER_SANITIZE_STRING);
	$password=$_POST['password'];
	 try
	 {
	 	$conexion = new PDO('mysql:host=promozonea.com.mx;dbname=promozon_aca','promozon_aca','castro-38');
	 } catch(PDOException $e){
	 	echo "Error de conexion";
	 }
	 $statement = $conexion->prepare("SELECT * FROM acceso WHERE correo= :usuario and password=SHA(:password)");
	 $statement->execute(array(':usuario'=>$usuario,':password'=>$password));
	 $resultado=$statement->fetch();
	 if ($resultado!==false) {
	 	$_SESSION['accesoacadepas']=$usuario;
	 	$_SESSION['accesoacadepasfoto']=$resultado['imagen'];
	 	$_SESSION['accesoacadepasid']=$resultado['id'];
	 	$_SESSION['accesoacadepasnombre']=$resultado['nombre'];
	 	echo "Bienvenid@ ".$resultado['nombre'];
	 	

	 	# code...
	 }else{
	 	echo "<script>alert('Datos no validos')</script>";
	 }
}
?>