<?php
session_start();
require_once "../../config/database.php";

$accion = $_REQUEST['accion'] ?? '';

/* ============================================================
   ACCIONES QUE RESPONDEN JSON (AJAX)
============================================================ */
$acciones_json = [
    "consultarDiagnosticosFinalizados",
    "datosDiagnostico"
];

if (in_array($accion, $acciones_json)) {
    header("Content-Type: application/json; charset=utf-8");
}

function json_out($data){
    echo json_encode($data);
    exit;
}

/* ============================================================
   CONSULTAR DIAGNÓSTICOS FINALIZADOS (PARA SELECT)
============================================================ */

if ($accion == "consultarDiagnosticosFinalizados") {

    $sql = mysqli_query($mysqli, "
        SELECT dg.id_diagnostico,
               re.equipo_modelo,
               cl.cli_razon_social
        FROM diagnostico dg
        LEFT JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
        LEFT JOIN clientes cl         ON re.id_cliente         = cl.id_cliente
        WHERE dg.estado_diagnostico = 'Finalizado'
        ORDER BY dg.id_diagnostico DESC
    ");

    $data = [];
    while($r = mysqli_fetch_assoc($sql)){
        $texto = "ID #".$r['id_diagnostico']." - ".
                 $r['cli_razon_social']." - ".
                 $r['equipo_modelo'];

        $data[] = [
            "id_diagnostico" => $r['id_diagnostico'],
            "texto"          => $texto
        ];
    }

    json_out($data);
}

/* ============================================================
   OBTENER DATOS COMPLETOS DEL DIAGNÓSTICO SELECCIONADO
   (CLIENTE + EQUIPO)
============================================================ */

if ($accion == "datosDiagnostico") {

    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) json_out(null);

    $sql = mysqli_query($mysqli, "
        SELECT dg.id_diagnostico,
               re.equipo_modelo,
               re.equipo_descripcion,
               m.marca_descrip,
               te.tipo_descrip,
               cl.cli_razon_social,
               cl.ci_ruc,
               cl.cli_telefono,
               cl.cli_direccion
        FROM diagnostico dg
        LEFT JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
        LEFT JOIN clientes cl         ON re.id_cliente         = cl.id_cliente
        LEFT JOIN marcas m            ON re.id_marca           = m.id_marca
        LEFT JOIN tipo_equipo te      ON re.id_tipo_equipo     = te.id_tipo_equipo
        WHERE dg.id_diagnostico = $id
        LIMIT 1
    ");

    $data = mysqli_fetch_assoc($sql);
    json_out($data ?: null);
}

/* ============================================================
   INSERTAR PRESUPUESTO
============================================================ */

if ($accion == "insertar") {

    $id_diagnostico = intval($_POST['id_diagnostico'] ?? 0);
    $mano_obra      = floatval($_POST['mano_obra'] ?? 0);

    $desc_arr = $_POST['detalle_descripcion'] ?? [];
    $cant_arr = $_POST['detalle_cantidad']    ?? [];
    $prec_arr = $_POST['detalle_precio']      ?? [];

    if ($id_diagnostico <= 0 || count($desc_arr) == 0) {
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }

    // Calcular subtotal (lado servidor)
    $subtotal = 0;
    $lineas   = [];

    for($i=0; $i<count($desc_arr); $i++){
        $desc = trim($desc_arr[$i]);
        if($desc === '') continue;

        $cant = isset($cant_arr[$i]) ? intval($cant_arr[$i]) : 1;
        if($cant <= 0) $cant = 1;

        $precio = isset($prec_arr[$i]) ? floatval($prec_arr[$i]) : 0;
        if($precio < 0) $precio = 0;

        $sub = $cant * $precio;
        $subtotal += $sub;

        $lineas[] = [
            'descripcion'    => $desc,
            'cantidad'       => $cant,
            'precio_unitario'=> $precio,
            'subtotal'       => $sub
        ];
    }

    if (count($lineas) == 0) {
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }

    $total = $subtotal + $mano_obra;

    $obs = mysqli_real_escape_string($mysqli, $_POST['observaciones'] ?? '');
    $usuario = $_SESSION['username'] ?? null;

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
            ".($usuario ? "'".mysqli_real_escape_string($mysqli,$usuario)."'" : "NULL").",
            NOW()
        )
    ";

    if (!mysqli_query($mysqli, $sql)) {
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }

    $id_presupuesto = mysqli_insert_id($mysqli);

    // Insertar detalles
    foreach($lineas as $ln){
        $desc_sql = mysqli_real_escape_string($mysqli, $ln['descripcion']);
        $cant     = $ln['cantidad'];
        $precio   = $ln['precio_unitario'];
        $sub      = $ln['subtotal'];

        mysqli_query($mysqli, "
            INSERT INTO presupuesto_detalle(
                id_presupuesto,
                descripcion,
                cantidad,
                precio_unitario,
                subtotal
            ) VALUES (
                $id_presupuesto,
                '$desc_sql',
                $cant,
                $precio,
                $sub
            )
        ");
    }

    header("Location: ../../main.php?module=presupuesto&alert=1");
    exit;
}

/* ============================================================
   ACTUALIZAR PRESUPUESTO
============================================================ */

if ($accion == "actualizar") {

    $id_presupuesto = intval($_POST['id_presupuesto'] ?? 0);
    $id_diagnostico = intval($_POST['id_diagnostico'] ?? 0);

    if($id_presupuesto <= 0 || $id_diagnostico <= 0){
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }

    $mano_obra = floatval($_POST['mano_obra'] ?? 0);
    $estado    = $_POST['estado'] ?? 'Pendiente';
    $estado_validos = ['Pendiente','Enviado','Aprobado','Rechazado'];
    if(!in_array($estado, $estado_validos)) $estado = 'Pendiente';

    $desc_arr = $_POST['detalle_descripcion'] ?? [];
    $cant_arr = $_POST['detalle_cantidad']    ?? [];
    $prec_arr = $_POST['detalle_precio']      ?? [];

    // Calcular nuevo subtotal
    $subtotal = 0;
    $lineas   = [];

    for($i=0; $i<count($desc_arr); $i++){
        $desc = trim($desc_arr[$i]);
        if($desc === '') continue;

        $cant = isset($cant_arr[$i]) ? intval($cant_arr[$i]) : 1;
        if($cant <= 0) $cant = 1;

        $precio = isset($prec_arr[$i]) ? floatval($prec_arr[$i]) : 0;
        if($precio < 0) $precio = 0;

        $sub = $cant * $precio;
        $subtotal += $sub;

        $lineas[] = [
            'descripcion'    => $desc,
            'cantidad'       => $cant,
            'precio_unitario'=> $precio,
            'subtotal'       => $sub
        ];
    }

    if (count($lineas) == 0) {
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }

    $total = $subtotal + $mano_obra;
    $obs = mysqli_real_escape_string($mysqli, $_POST['observaciones'] ?? '');

    // Actualizar cabecera
    $sql = "
        UPDATE presupuesto SET
            mano_obra    = $mano_obra,
            subtotal     = $subtotal,
            total        = $total,
            observaciones= '$obs',
            estado       = '$estado'
        WHERE id_presupuesto = $id_presupuesto
    ";

    if (!mysqli_query($mysqli, $sql)) {
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }

    // Borrar detalles anteriores y volver a insertar
    mysqli_query($mysqli, "DELETE FROM presupuesto_detalle WHERE id_presupuesto = $id_presupuesto");

    foreach($lineas as $ln){
        $desc_sql = mysqli_real_escape_string($mysqli, $ln['descripcion']);
        $cant     = $ln['cantidad'];
        $precio   = $ln['precio_unitario'];
        $sub      = $ln['subtotal'];

        mysqli_query($mysqli, "
            INSERT INTO presupuesto_detalle(
                id_presupuesto,
                descripcion,
                cantidad,
                precio_unitario,
                subtotal
            ) VALUES (
                $id_presupuesto,
                '$desc_sql',
                $cant,
                $precio,
                $sub
            )
        ");
    }

    header("Location: ../../main.php?module=presupuesto&alert=2");
    exit;
}

/* ============================================================
   ELIMINAR PRESUPUESTO (opcional)
============================================================ */

if ($accion == "eliminar") {
    $id_presupuesto = intval($_GET['id_presupuesto'] ?? 0);
    if($id_presupuesto > 0){
        mysqli_query($mysqli, "DELETE FROM presupuesto_detalle WHERE id_presupuesto = $id_presupuesto");
        mysqli_query($mysqli, "DELETE FROM presupuesto WHERE id_presupuesto = $id_presupuesto");
        header("Location: ../../main.php?module=presupuesto&alert=3");
        exit;
    } else {
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }
}

header("Location: ../../main.php?module=presupuesto");
exit;
