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
            $codigo_ciudad = $_POST['codigo_ciudad'];
            $ci_ruc = $_POST['ci_ruc'];
            $razon_social = $_POST['razon_social'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
           

            $query = mysqli_query($mysqli, "INSERT INTO clientes 
            (cod_ciudad, ci_ruc,cli_razon_social, cli_direccion, cli_telefono, cli_email)
            VALUES ($codigo_ciudad, '$ci_ruc', '$razon_social', '$direccion', $telefono,'$email');") 
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=clientes&alert=1");
            } else {
                header("Location: ../../main.php?module=clientes&alert=4");
            }
        }
    }
    // *********************************************update************************************************
    elseif($_GET['act']=='update'){
        if(isset($_POST['Guardar'])){
            if(isset($_POST['id_cliente'])){
                $id_cliente = $_POST['id_cliente'];
                $codigo_ciudad = $_POST['codigo_ciudad'];
                $ci_ruc = $_POST['ci_ruc'];
                $razon_social = $_POST['razon_social'];
                $direccion = $_POST['direccion'];
                $email = $_POST['email'];
                $telefono = $_POST['telefono'];
                
    
               
              
                $query = mysqli_query($mysqli, "UPDATE clientes SET cod_ciudad = $codigo_ciudad,
                ci_ruc =  '$ci_ruc',
                cli_razon_social = '$razon_social',
                cli_direccion = '$direccion',
                cli_email = '$email',
                cli_telefono = $telefono
                WHERE id_cliente = $id_cliente;")or die('Error'.mysqli_error($mysqli));
                if($query){
                header("Location: ../../main.php?module=clientes&alert=2");
                } else {
                header("Location: ../../main.php?module=clientes&alert=4");
                }                                                    
            }
        }
    }
    // *******************************************delete*****************************************************
    elseif($_GET['act']=='delete'){
        if(isset($_GET['id_cliente'])){
            $codigo = $_GET['id_cliente'];
            $query = mysqli_query($mysqli, "DELETE FROM clientes WHERE id_cliente = $codigo;")
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=clientes&alert=3");
            } else {
                header("Location: ../../main.php?module=clientes&alert=4");
            }
        }
    }
}
?>