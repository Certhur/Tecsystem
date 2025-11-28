<?php
session_start();
require_once "../../config/database.php";

if (empty($_SESSION["username"]) && empty($_SESSION["password"])) {
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
    exit;
}

$act = $_GET['act'] ?? '';

/* ============================================================
   INSERTAR
   ============================================================ */
if ($act == "insert") {

    if (isset($_POST["Guardar"])) {

        $u_descrip = mysqli_real_escape_string($mysqli, $_POST["u_descrip"]);

        $query = mysqli_query($mysqli, "
            INSERT INTO u_medida (u_descrip)
            VALUES ('$u_descrip')
        ");

        if ($query) {
            header("Location: ../../main.php?module=unidad_medida&alert=1");
        } else {
            header("Location: ../../main.php?module=unidad_medida&alert=4");
        }
    }
}

/* ============================================================
   UPDATE
   ============================================================ */
elseif ($act == "update") {

    if (isset($_POST["Guardar"])) {

        $id_u_medida = $_POST["id_u_medida"];
        $u_descrip   = mysqli_real_escape_string($mysqli, $_POST["u_descrip"]);

        $query = mysqli_query($mysqli, "
            UPDATE u_medida
            SET u_descrip = '$u_descrip'
            WHERE id_u_medida = '$id_u_medida'
        ");

        if ($query) {
            header("Location: ../../main.php?module=unidad_medida&alert=2");
        } else {
            header("Location: ../../main.php?module=unidad_medida&alert=4");
        }
    }
}

/* ============================================================
   DELETE — con protección
   ============================================================ */
elseif ($_GET["act"] == "delete") {

    if (isset($_GET["id_u_medida"])) {

        $id_u_medida = $_GET["id_u_medida"];

        // 1) Verificar si la unidad se usa en productos
        $check = mysqli_query($mysqli, "
            SELECT COUNT(*) AS total 
            FROM productos 
            WHERE id_u_medida = '$id_u_medida'
        ") or die("Error verificando uso: " . mysqli_error($mysqli));

        $data = mysqli_fetch_assoc($check);

        if ($data["total"] > 0) {
            // Está en uso → no se puede borrar
            header("Location: ../../main.php?module=unidad_medida&alert=5");
            exit;
        }

        // 2) Si no está en uso, borrar
        $query = mysqli_query($mysqli, "
            DELETE FROM u_medida 
            WHERE id_u_medida = '$id_u_medida'
        ") or die("Error SQL: " . mysqli_error($mysqli));

        if ($query) {
            header("Location: ../../main.php?module=unidad_medida&alert=3");
            exit;
        } else {
            header("Location: ../../main.php?module=unidad_medida&alert=4");
            exit;
        }
    }
}

?>
