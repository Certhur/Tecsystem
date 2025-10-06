<?php 
session_start();
require_once "../../config/database.php";
// ****************************************insert****************************************************************
if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else {
    if($_GET['act']=='insert'){
        if(isset($_POST['Guardar'])){

            $tipo_servicio_descrip = mb_strtoupper($_POST['descripcion'],'UTF-8');
            $tipo_servicio_estado= true;


            $query = mysqli_query($mysqli, "INSERT INTO tipo_servicio(tipo_servicio_descrip, tipo_servicio_estado)
            VALUES ('$tipo_servicio_descrip',$tipo_servicio_estado);") 
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=tipo_servicio&alert=1");
            } else {
                header("Location: ../../main.php?module=tipo_servicio&alert=4");
            }
        }
    }
    // *********************************************update************************************************
    elseif($_GET['act']=='update'){
        if(isset($_POST['Guardar'])){
            if(isset($_POST['id_tipo_servicio'])){
                $id_tipo_servicio = $_POST['id_tipo_servicio'];
                $tipo_servicio_descrip = mb_strtoupper($_POST['descripcion'],'UTF-8');
                
              
                $query = mysqli_query($mysqli, "UPDATE tipo_servicio SET tipo_servicio_descrip = '$tipo_servicio_descrip'
                WHERE id_tipo_servicio = $id_tipo_servicio;")or die('Error'.mysqli_error($mysqli));
                if($query){
                header("Location: ../../main.php?module=tipo_servicio&alert=2");
                } else {
                header("Location: ../../main.php?module=tipo_servicio&alert=4");
                }                                                    
            }
        }
    }
    // *******************************************update_estado*****************************************************
    elseif($_GET['act']=='update_estado'){
        if(isset($_GET['id_tipo_servicio'])){
            $id_tipo_servicio = $_GET['id_tipo_servicio'];
            $tipo_servicio_estado = $_GET['tipo_servicio_estado'];
            
            if($tipo_servicio_estado == true){
                $estado = false;
            }else{
                $estado = true;
            }
            $query = mysqli_query($mysqli, "UPDATE tipo_servicio SET tipo_servicio_estado = '$estado'
            WHERE id_tipo_servicio = $id_tipo_servicio;")or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=tipo_servicio&alert=3");
            } else {
                header("Location: ../../main.php?module=tipo_servicio&alert=4");
            }
        }
    }
}
?>