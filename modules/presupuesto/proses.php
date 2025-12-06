<?php
session_start();
require_once "../../config/database.php";

$accion = $_REQUEST['accion'] ?? '';

$acciones_json = [
    "datos_diagnostico",
    "cambiar_estado",
    "archivar",
    "desarchivar"
];

if (in_array($accion, $acciones_json)) {
    header("Content-Type: application/json; charset=utf-8");
}

function json_out($data){
    echo json_encode($data);
    exit;
}

/* =========================================================
   OBTENER DATOS DEL DIAGNÓSTICO (AJAX)
========================================================= */

if ($accion == 'datos_diagnostico') {
    $id = intval($_GET['id'] ?? 0);
    if($id <= 0) json_out(null);

    $q = mysqli_query($mysqli,"
        SELECT dg.id_diagnostico,
               re.equipo_modelo,
               re.equipo_descripcion,
               cl.cli_razon_social,
               te.tipo_descrip
        FROM diagnostico dg
        LEFT JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
        LEFT JOIN clientes cl         ON re.id_cliente = cl.id_cliente
        LEFT JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
        WHERE dg.id_diagnostico = $id
    ") or die(mysqli_error($mysqli));

    $data = mysqli_fetch_assoc($q);
    json_out($data ?: null);
}

/* =========================================================
   CAMBIAR ESTADO PRESUPUESTO (AJAX)
========================================================= */

if ($accion == 'cambiar_estado') {
    $id     = intval($_POST['id_presupuesto'] ?? 0);
    $estado = $_POST['estado'] ?? '';

    $validos = ["Pendiente","Enviado","Aprobado","Rechazado"];

    if($id <= 0 || !in_array($estado,$validos)){
        json_out(["status"=>"error","message"=>"Datos inválidos"]);
    }

    $sql = "UPDATE presupuesto
            SET estado = '".mysqli_real_escape_string($mysqli,$estado)."'
            WHERE id_presupuesto = $id";

    if(mysqli_query($mysqli,$sql)){
        json_out(["status"=>"ok"]);
    } else {
        json_out(["status"=>"error","message"=>"Error al guardar"]);
    }
}

/* =========================================================
   INSERTAR PRESUPUESTO + DETALLES
========================================================= */

if ($accion == 'insertar') {

    $id_diagnostico = intval($_POST['id_diagnostico'] ?? 0);
    $mano_obra      = floatval($_POST['mano_obra'] ?? 0);
    $subtotal       = floatval($_POST['subtotal'] ?? 0);
    $total          = floatval($_POST['total'] ?? 0);
    $obs            = mysqli_real_escape_string($mysqli, $_POST['observaciones'] ?? '');

    $usuario        = $_SESSION['username'] ?? 'sistema';

    $sql = "
        INSERT INTO presupuesto(
            id_diagnostico,
            fecha_presupuesto,
            mano_obra,
            subtotal,
            total,
            observaciones,
            estado,
            usuario_registro,
            fecha_registro
        ) VALUES (
            $id_diagnostico,
            NOW(),
            $mano_obra,
            $subtotal,
            $total,
            '$obs',
            'Pendiente',
            '$usuario',
            NOW()
        )";

    if(mysqli_query($mysqli,$sql)){
        $id_pres = mysqli_insert_id($mysqli);

        // Insertar detalles
        $desc  = $_POST['detalle_descripcion'] ?? [];
        $cant  = $_POST['detalle_cantidad'] ?? [];
        $precio= $_POST['detalle_precio'] ?? [];
        $sub   = $_POST['detalle_subtotal'] ?? [];

        $num = count($desc);

        for($i=0; $i<$num; $i++){
            $d = trim($desc[$i]);
            if($d === '') continue;

            $c  = intval($cant[$i] ?? 0);
            $pu = floatval($precio[$i] ?? 0);
            $st = floatval($sub[$i] ?? 0);

            $d_sql = mysqli_real_escape_string($mysqli, $d);

            mysqli_query($mysqli,"
                INSERT INTO presupuesto_detalles(
                    id_presupuesto, descripcion, cantidad, precio_unitario, subtotal
                ) VALUES (
                    $id_pres, '$d_sql', $c, $pu, $st
                )
            ");
        }

        header("Location: ../../main.php?module=presupuesto&alert=1");
    } else {
        header("Location: ../../main.php?module=presupuesto&alert=4");
    }
    exit;
}

/* =========================================================
   ACTUALIZAR PRESUPUESTO + DETALLES
========================================================= */

if ($accion == 'actualizar') {

    $id_presupuesto = intval($_POST['id_presupuesto'] ?? 0);
    if($id_presupuesto <= 0){
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }

    $id_diagnostico = intval($_POST['id_diagnostico'] ?? 0);
    $mano_obra      = floatval($_POST['mano_obra'] ?? 0);
    $subtotal       = floatval($_POST['subtotal'] ?? 0);
    $total          = floatval($_POST['total'] ?? 0);
    $obs            = mysqli_real_escape_string($mysqli, $_POST['observaciones'] ?? '');

    $sql = "
        UPDATE presupuesto SET
            id_diagnostico = $id_diagnostico,
            mano_obra      = $mano_obra,
            subtotal       = $subtotal,
            total          = $total,
            observaciones  = '$obs'
        WHERE id_presupuesto = $id_presupuesto
    ";

    if(mysqli_query($mysqli,$sql)){

        // Borrar detalles antiguos
        mysqli_query($mysqli,"DELETE FROM presupuesto_detalles WHERE id_presupuesto = $id_presupuesto");

        // Insertar nuevos detalles
        $desc  = $_POST['detalle_descripcion'] ?? [];
        $cant  = $_POST['detalle_cantidad'] ?? [];
        $precio= $_POST['detalle_precio'] ?? [];
        $sub   = $_POST['detalle_subtotal'] ?? [];

        $num = count($desc);

        for($i=0; $i<$num; $i++){
            $d = trim($desc[$i]);
            if($d === '') continue;

            $c  = intval($cant[$i] ?? 0);
            $pu = floatval($precio[$i] ?? 0);
            $st = floatval($sub[$i] ?? 0);

            $d_sql = mysqli_real_escape_string($mysqli, $d);

            mysqli_query($mysqli,"
                INSERT INTO presupuesto_detalles(
                    id_presupuesto, descripcion, cantidad, precio_unitario, subtotal
                ) VALUES (
                    $id_presupuesto, '$d_sql', $c, $pu, $st
                )
            ");
        }

        header("Location: ../../main.php?module=presupuesto&alert=2");
    } else {
        header("Location: ../../main.php?module=presupuesto&alert=4");
    }
    exit;
}

/* =========================================================
   ELIMINAR PRESUPUESTO (opcional, si luego agregas botón)
========================================================= */

if ($accion == 'eliminar') {
    $id = intval($_GET['id_presupuesto'] ?? 0);
    if($id > 0){
        mysqli_query($mysqli,"DELETE FROM presupuesto_detalles WHERE id_presupuesto = $id");
        mysqli_query($mysqli,"DELETE FROM presupuesto WHERE id_presupuesto = $id");
        header("Location: ../../main.php?module=presupuesto&alert=3");
    } else {
        header("Location: ../../main.php?module=presupuesto&alert=4");
    }
    exit;
}

/* =========================================================
   ARCHIVAR / DESARCHIVAR (AJAX)
========================================================= */

if ($accion == 'archivar') {
    $id = intval($_POST['id_presupuesto'] ?? 0);
    if($id <= 0) json_out(["status"=>"error"]);

    mysqli_query($mysqli,"
        UPDATE presupuesto
        SET estado = 'Archivado'
        WHERE id_presupuesto = $id
    ");

    json_out(["status"=>"ok"]);
}

if ($accion == 'desarchivar') {
    $id = intval($_POST['id_presupuesto'] ?? 0);
    if($id <= 0) json_out(["status"=>"error"]);

    mysqli_query($mysqli,"
        UPDATE presupuesto
        SET estado = 'Pendiente'
        WHERE id_presupuesto = $id
    ");

    json_out(["status"=>"ok"]);
}

json_out(["error"=>"Acción no reconocida"]);
