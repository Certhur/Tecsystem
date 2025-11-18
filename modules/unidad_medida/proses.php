<?php 
session_start();
require_once "../../config/database.php";
if(empty($_SESSION["username"]) && empty($_SESSION["password"])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else {
if($_GET["act"]=="insert"){
    if(isset($_POST["Guardar"])){

        $id_producto   = $_POST["id_producto"];
        $id_marca      = $_POST["id_marca"];
        $cod_proveedor = $_POST["cod_proveedor"];
        $id_u_medida   = $_POST["id_u_medida"];
        $p_descrip     = $_POST["p_descrip"];
        $precio        = $_POST["precio"];
        $precio_final  = $_POST["precio_final"];

        $query = mysqli_query($mysqli, "
            INSERT INTO productos
            (id_producto, id_marca, cod_proveedor, id_u_medida, p_descrip, p_costo_actual, p_precio_servicio)
            VALUES
            ($id_producto, $id_marca, $cod_proveedor, $id_u_medida, '$p_descrip', $precio, $precio_final)
        ");

        if($query){
            header("Location: ../../main.php?module=producto&alert=1");
        } else {
            header("Location: ../../main.php?module=producto&alert=4");
        }
    }
}
elseif($_GET["act"]=="update"){
    if(isset($_POST["Guardar"])){

        $id_producto = $_POST["id_producto"];
        $descripcion = $_POST["descripcion"];
        $id_proveedor = $_POST["id_proveedor"];
        $id_marca = $_POST["id_marca"];
        $id_u_medida = $_POST["id_u_medida"];

        $query = mysqli_query($mysqli, "
            UPDATE productos 
            SET descripcion  = '$descripcion',
                id_proveedor = $id_proveedor,
                id_marca     = $id_marca,
                id_u_medida  = $id_u_medida
            WHERE id_producto = $id_producto;
        ") or die('Error'.mysqli_error($mysqli));

        if($query){
            header("Location: ../../main.php?module=producto&alert=2");
        } else {
            header("Location: ../../main.php?module=producto&alert=4");
        }
    }
}
    elseif($_GET["act"]=="delete"){
        if(isset($_GET["id_producto"])){
            $id_producto = $_GET["id_producto"];
            $query = mysqli_query($mysqli, "DELETE FROM id_producto WHERE productos = $id_producto;")
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=id_producto&alert=3");
            } else {
                header("Location: ../../main.php?module=id_producto&alert=4");
            }
        }
    }
}
?>