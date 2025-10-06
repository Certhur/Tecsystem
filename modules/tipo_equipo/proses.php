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

            $tipo_descrip = strtoupper($_POST['descripcion']);
            $tipo_estado= true;


            $query = mysqli_query($mysqli, "INSERT INTO tipo_equipo(tipo_descrip, tipo_estado)
            VALUES ('$tipo_descrip',$tipo_estado);") 
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=tipo_equipo&alert=1");
            } else {
                header("Location: ../../main.php?module=tipo_equipo&alert=4");
            }
        }
    }
    // *********************************************update************************************************
    elseif($_GET['act']=='update'){
        if(isset($_POST['Guardar'])){
            if(isset($_POST['id_tipo_equipo'])){
                $id_tipo_equipo = $_POST['id_tipo_equipo'];
                $tipo_descrip = strtoupper($_POST['descripcion']);
                
              
                $query = mysqli_query($mysqli, "UPDATE tipo_equipo SET tipo_descrip = '$tipo_descrip'
                WHERE id_tipo_equipo = $id_tipo_equipo;")or die('Error'.mysqli_error($mysqli));
                if($query){
                header("Location: ../../main.php?module=tipo_equipo&alert=2");
                } else {
                header("Location: ../../main.php?module=tipo_equipo&alert=4");
                }                                                    
            }
        }
    }
    // *******************************************update_estado*****************************************************
    elseif($_GET['act']=='update_estado'){
        if(isset($_GET['id_tipo_equipo'])){
            $id_tipo_equipo = $_GET['id_tipo_equipo'];
            $tipo_estado = $_GET['tipo_estado'];
            
            if($tipo_estado == true){
                $estado = false;
            }else{
                $estado = true;
            }
            $query = mysqli_query($mysqli, "UPDATE tipo_equipo SET tipo_estado = '$estado'
            WHERE id_tipo_equipo = $id_tipo_equipo;")or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=tipo_equipo&alert=3");
            } else {
                header("Location: ../../main.php?module=tipo_equipo&alert=4");
            }
        }
    }
}
?>