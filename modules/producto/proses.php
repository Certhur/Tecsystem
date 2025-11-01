<?php 
session_start();
require_once "../../config/database.php";
if(empty($_SESSION["username"]) && empty($_SESSION["password"])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else {
    if($_GET["act"]=="insert"){
        if(isset($_POST["Guardar"])){
            $id_producto = $_POST["id_producto"];
            $id_marca = $_POST["id_marca"];
            $id_u_medida = $_POST["id_u_medida"];
            $p_descrip = $_POST["p_descrip"];
            $p_precio_servicio = $_POST["p_precio_servicio"];
            $p_costo_actual = $_POST["p_costo_actual"];
            $cod_proveedor = $_POST["cod_proveedor"];
            $query = mysqli_query($mysqli, "INSERT INTO producto(id_producto,id_marca,id_u_medida,p_descrip,p_precio_servicio)
            VALUES ($id_producto,'$id_marca','$id_u_medida','$p_descrip','$p_precio_servicio');") or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=producto&alert=1");
            } else {
                header("Location: ../../main.php?module=producto&alert=4");
            }
        }
    }
    elseif($_GET["act"]=="update"){
        if(isset($_POST["Guardar"])){
            if(isset($_POST["id_producto"])){
                $id_producto = $_POST["id_producto"];
                $id_marca = $_POST["id_marca"];
                $id_u_medida = $_POST["id_u_medida"];
                $p_descrip = $_POST["p_descrip"];
                $p_precio_servicio = $_POST["p_precio_servicio"];
                $query = mysqli_query($mysqli, "UPDATE producto SET 
                id_marca = '$id_marca',
                id_u_medida = '$id_u_medida',
                p_descrip = '$p_descrip',
                p_precio_servicio = '$p_precio_servicio' 
                WHERE id_producto = $id_producto;")
                or die('Error'.mysqli_error($mysqli));
                if($query){
                header("Location: ../../main.php?module=producto&alert=2");
                } else {
                header("Location: ../../main.php?module=producto&alert=4");
                }                                                    
            }
        }
    }
    elseif($_GET["act"]=="delete"){
        if(isset($_GET["id_producto"])){
            $id_producto = $_GET["id_producto"];
            $query = mysqli_query($mysqli, "DELETE FROM producto WHERE id_producto = $id_producto;")
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=producto&alert=3");
            } else {
                header("Location: ../../main.php?module=producto&alert=4");
            }
        }
    }
}
?>