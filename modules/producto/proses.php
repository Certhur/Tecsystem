<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "../../config/database.php";

if (empty($_SESSION["username"]) && empty($_SESSION["password"])) {
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
    exit;
}

$act = $_GET["act"] ?? '';

// =====================================================
// INSERTAR PRODUCTO
// =====================================================
if ($act == "insert") {

    if (isset($_POST["Guardar"])) {

        $id_producto         = $_POST["id_producto"];
        $id_marca            = $_POST["id_marca"];
        $id_u_medida         = $_POST["id_u_medida"];
        $p_descrip           = $_POST["p_descrip"];
        $p_precio_servicio   = $_POST["p_precio_servicio"];
        $p_costo_actual      = $_POST["p_costo_actual"];
        $cod_proveedor       = $_POST["cod_proveedor"];
        $tipo_producto       = $_POST["tipo_producto"];
        $estado              = $_POST["estado"];

        $query = mysqli_query($mysqli, "
            INSERT INTO productos (
                id_producto, id_marca, id_u_medida, p_descrip,
                p_precio_servicio, p_costo_actual, cod_proveedor,
                tipo_producto, estado
            ) VALUES (
                '$id_producto', '$id_marca', '$id_u_medida', '$p_descrip',
                '$p_precio_servicio', '$p_costo_actual', '$cod_proveedor',
                '$tipo_producto', '$estado'
            );
        ");

        if ($query) {
            header("Location: ../../main.php?module=producto&alert=1");
        } else {
            die("ERROR SQL INSERT: " . mysqli_error($mysqli));
        }
    }
}

// =====================================================
// ACTUALIZAR PRODUCTO
// =====================================================
elseif ($act == "update") {

    if (isset($_POST["Guardar"])) {

        $id_producto         = $_POST["id_producto"];
        $id_marca            = $_POST["id_marca"];
        $id_u_medida         = $_POST["id_u_medida"];
        $p_descrip           = $_POST["p_descrip"];
        $p_precio_servicio   = $_POST["p_precio_servicio"];
        $p_costo_actual      = $_POST["p_costo_actual"];
        $cod_proveedor       = $_POST["cod_proveedor"];
        $tipo_producto       = $_POST["tipo_producto"];
        $estado              = $_POST["estado"];

        $query = mysqli_query($mysqli, "
            UPDATE productos SET
                id_marca = '$id_marca',
                id_u_medida = '$id_u_medida',
                p_descrip = '$p_descrip',
                p_precio_servicio = '$p_precio_servicio',
                p_costo_actual = '$p_costo_actual',
                cod_proveedor = '$cod_proveedor',
                tipo_producto = '$tipo_producto',
                estado = '$estado'
            WHERE id_producto = '$id_producto';
        ");

        if ($query) {
            header("Location: ../../main.php?module=producto&alert=2");
        } else {
            die("ERROR SQL UPDATE: " . mysqli_error($mysqli));
        }
    }
}

// =====================================================
// ELIMINAR PRODUCTO
// =====================================================
elseif ($act == "delete") {

    if (isset($_GET["id_producto"])) {

        $id_producto = $_GET["id_producto"];

        $query = mysqli_query($mysqli, "
            DELETE FROM productos
            WHERE id_producto = '$id_producto';
        ");

        if ($query) {
            header("Location: ../../main.php?module=producto&alert=3");
        } else {
            die("ERROR SQL DELETE: " . mysqli_error($mysqli));
        }
    }
}
?>
