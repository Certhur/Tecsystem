<?php 
session_start();
require_once "../../config/database.php";
if(empty($_SESSION["username"]) && empty($_SESSION["password"])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else {
    if($_GET["act"]=="insert"){
        if(isset($_POST["Guardar"])){
            $cod_proveedor = $_POST["cod_proveedor"];
            $razon_social = $_POST["razon_social"];
            $ruc = $_POST["ruc"];
            $direccion = $_POST["direccion"];
            $telefono = $_POST["telefono"];
            $query = mysqli_query($mysqli, "INSERT INTO proveedor(cod_proveedor,razon_social,ruc,direccion,telefono)
            VALUES ($cod_proveedor,'$razon_social','$ruc','$direccion','$telefono');") or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=proveedor&alert=1");
            } else {
                header("Location: ../../main.php?module=proveedor&alert=4");
            }
        }
    }
    elseif($_GET["act"]=="update"){
        if(isset($_POST["Guardar"])){
            if(isset($_POST["cod_proveedor"])){
                $cod_proveedor = $_POST["cod_proveedor"];
                $razon_social = $_POST["razon_social"];
                $ruc = $_POST["ruc"];
                $direccion = $_POST["direccion"];
                $telefono = $_POST["telefono"];
                $query = mysqli_query($mysqli, "UPDATE proveedor SET 
                razon_social = '$razon_social',
                ruc = '$ruc',
                direccion = '$direccion',
                telefono = '$telefono' 
                WHERE cod_proveedor = $cod_proveedor;")
                or die('Error'.mysqli_error($mysqli));
                if($query){
                header("Location: ../../main.php?module=proveedor&alert=2");
                } else {
                header("Location: ../../main.php?module=proveedor&alert=4");
                }                                                    
            }
        }
    }
    elseif($_GET["act"]=="delete"){
        if(isset($_GET["cod_proveedor"])){
            $cod_proveedor = $_GET["cod_proveedor"];
            $query = mysqli_query($mysqli, "DELETE FROM proveedor WHERE cod_proveedor = $cod_proveedor;")
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=proveedor&alert=3");
            } else {
                header("Location: ../../main.php?module=proveedor&alert=4");
            }
        }
    }
}
?>