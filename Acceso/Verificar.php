<?php 
//session_start();
require 'Admin/funciones.php';
//if (isset($_SESSION['usuarioaca'])) {
	$conexion=conexionmysqli();
	if (!$conexion) {
		header('location: error.php');
		# code...
	}else{
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$usuario =$_POST['nombre'];
			$contra = $_POST['password'];
			$correo= $_POST['correo'];
			try{
				mysqli_query($conexion,"INSERT INTO acceso(nombre,correo,password)values('$usuario','$correo',SHA('$contra'))");
				$sql = mysqli_query($conexion,"SELECT id from acceso where correo='$correo' and nombre='$usuario'");
				$data = mysqli_fetch_assoc($sql);
			$mensaje = "Gracias por registrarse, para terminar el proceso de clic en el siguiente link \r\n";
            $mensaje .="http://departamentosaca.promozonea.com.mx/verificar.php?id=".$data['id'];
            $cabeceras = "From: Departamentos Acapulco <acapulcodepartamentos@gmail.com>";
            
            $bool = mail($correo,'Verificar Correo',$mensaje,$cabeceras);
            if($bool){
              
                echo "<h1>Gracias por Registrarse</h1><br>
					<p>Se le ha enviado un correo electronico para confirmacion.</p>
					";
            }else{
                echo "Registro fallido";
            }
					
			}catch(Exception $e){
				echo "Registro fallido";
				print $e->getMessage();
			}
		}
	}
	# code...
//}else{
//	header('Location: login.php');
//}
?>