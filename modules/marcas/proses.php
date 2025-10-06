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

            $marca_descrip =  mb_strtoupper($_POST['descripcion'],'UTF-8');
            $marca_estado= true;


            $query = mysqli_query($mysqli, "INSERT INTO marcas(marca_descrip, marca_estado)
            VALUES ('$marca_descrip',$marca_estado);") 
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=marcas&alert=1");
            } else {
                header("Location: ../../main.php?module=marcas&alert=4");
            }
        }
    }
    // *********************************************update************************************************
    elseif($_GET['act']=='update'){
        if(isset($_POST['Guardar'])){
            if(isset($_POST['id_marca'])){
                $id_marca = $_POST['id_marca'];
                $marca_descrip = mb_strtoupper($_POST['descripcion'],'UTF-8');
                
              
                $query = mysqli_query($mysqli, "UPDATE marcas SET marca_descrip = '$marca_descrip'
                WHERE id_marca = $id_marca;")or die('Error'.mysqli_error($mysqli));
                if($query){
                header("Location: ../../main.php?module=marcas&alert=2");
                } else {
                header("Location: ../../main.php?module=marcas&alert=4");
                }                                                    
            }
        }
    }
    // *******************************************update_estado*****************************************************
    elseif($_GET['act']=='update_estado'){
        if(isset($_GET['id_marca'])){
            $id_marca = $_GET['id_marca'];
            $marca_estado = $_GET['marca_estado'];
            
            if($marca_estado == true){
                $estado = false;
            }else{
                $estado = true;
            }
            $query = mysqli_query($mysqli, "UPDATE marcas SET marca_estado = '$estado'
            WHERE id_marca = $id_marca;")or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=marcas&alert=3");
            } else {
                header("Location: ../../main.php?module=marcas&alert=4");
            }
        }
    }
}
?>