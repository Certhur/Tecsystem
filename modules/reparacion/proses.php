<?php
session_start();
require_once "../../config/database.php";

// Evitar que warnings de PHP rompan el JSON
error_reporting(0); 
$accion = $_REQUEST['accion'] ?? '';

// Definir cabecera JSON para acciones AJAX
$acciones_json = ["datos_ot_detalles", "cambiar_estado", "archivar", "anular", "desarchivar"];
if (in_array($accion, $acciones_json)) {
    header("Content-Type: application/json; charset=utf-8");
}

function json_out($data){
    echo json_encode($data);
    exit;
}

/* =========================================================
   1. AJAX: TRAER DATOS OT Y DETALLES (Para el Formulario)
========================================================= */
if ($accion == 'datos_ot_detalles') {
    $id = intval($_GET['id'] ?? 0);
    if($id <= 0) json_out(null);

    // 1. Cabecera (Incluye JOIN con tipo_equipo)
    $sqlCab = "
        SELECT ot.id_orden,
               cl.cli_razon_social,
               re.equipo_modelo,
               te.tipo_descrip
        FROM orden_trabajo ot
        INNER JOIN presupuesto p       ON ot.id_presupuesto = p.id_presupuesto
        INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
        INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
        INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
        INNER JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
        WHERE ot.id_orden = $id
    ";

    $qCab = mysqli_query($mysqli, $sqlCab);
    $cab = mysqli_fetch_assoc($qCab);

    if(!$cab) json_out(null);

    // 2. Detalles (Productos del Presupuesto asociado)
    $qPres = mysqli_query($mysqli, "SELECT id_presupuesto FROM orden_trabajo WHERE id_orden = $id");
    $rPres = mysqli_fetch_assoc($qPres);
    $id_presupuesto = $rPres['id_presupuesto'];

    $qDet = mysqli_query($mysqli, "
        SELECT id_producto, cantidad
        FROM presupuesto_detalle
        WHERE id_presupuesto = $id_presupuesto
        AND id_producto IS NOT NULL
    ");

    $productos = [];
    while($d = mysqli_fetch_assoc($qDet)){
        $productos[] = $d;
    }

    json_out([
        "cliente"   => $cab['cli_razon_social'],
        "equipo"    => $cab['tipo_descrip'],
        "modelo"    => $cab['equipo_modelo'],
        "productos" => $productos
    ]);
}

/* =========================================================
   2. INSERTAR REPARACIÓN (Y DESCONTAR STOCK)
========================================================= */
if ($accion == 'insertar') {
    // Restaurar cabecera HTML para redirección
    header('Content-Type: text/html; charset=utf-8');

    $id_orden = intval($_POST['id_orden'] ?? 0);
    $id_user  = intval($_POST['id_user'] ?? 0);
    $obs      = mysqli_real_escape_string($mysqli, $_POST['observaciones'] ?? '');
    $estado   = mysqli_real_escape_string($mysqli, $_POST['estado_inicial'] ?? 'En Proceso');

    // 1. Insertar Cabecera
    $sql = "INSERT INTO reparacion (id_orden, id_user, fecha_reparacion, observaciones, estado) 
            VALUES ($id_orden, $id_user, NOW(), '$obs', '$estado')";

    if(mysqli_query($mysqli, $sql)){
        $id_reparacion = mysqli_insert_id($mysqli);

        // 2. Procesar Insumos
        $productos  = $_POST['id_producto'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];

        for($i=0; $i < count($productos); $i++){
            $id_prod = intval($productos[$i]);
            $cant    = intval($cantidades[$i]);

            if($id_prod > 0 && $cant > 0){
                // A. Guardar detalle
                $sql_det = "INSERT INTO reparacion_detalles (id_reparacion, id_producto, cantidad)
                            VALUES ($id_reparacion, $id_prod, $cant)";
                mysqli_query($mysqli, $sql_det);

                // B. DESCONTAR STOCK (Tabla 'stock' separada)
                // Restamos la cantidad usada al stock del producto
                $sql_stock = "UPDATE stock SET cantidad = cantidad - $cant WHERE id_producto = $id_prod";
                mysqli_query($mysqli, $sql_stock);
            }
        }

        // 3. Actualizar estado de la OT
        mysqli_query($mysqli, "UPDATE orden_trabajo SET estado_ot = '$estado' WHERE id_orden = $id_orden");

        header("Location: ../../main.php?module=reparacion&alert=1");
    } else {
        header("Location: ../../main.php?module=reparacion&alert=4");
    }
    exit;
}

/* =========================================================
   3. CAMBIAR ESTADO (Desde View - AJAX)
========================================================= */
if ($accion == 'cambiar_estado') {
    $id     = intval($_POST['id_reparacion'] ?? 0);
    $estado = $_POST['estado'] ?? '';
    $validos = ['En Proceso', 'Esperando Repuesto', 'Finalizada', 'Entregada', 'Anulado'];

    if($id > 0 && in_array($estado, $validos)){
        
        // Si se ANULA, devolver stock
        if($estado == 'Anulado'){
            $qItems = mysqli_query($mysqli, "SELECT id_producto, cantidad FROM reparacion_detalles WHERE id_reparacion = $id");
            while($item = mysqli_fetch_assoc($qItems)){
                $cant = $item['cantidad'];
                $prod = $item['id_producto'];
                // Sumar al stock (Devolución)
                mysqli_query($mysqli, "UPDATE stock SET cantidad = cantidad + $cant WHERE id_producto = $prod");
            }
        }

        // Actualizar reparación
        mysqli_query($mysqli, "UPDATE reparacion SET estado = '$estado' WHERE id_reparacion = $id");
        
        // Sincronizar OT
        $qOT = mysqli_query($mysqli, "SELECT id_orden FROM reparacion WHERE id_reparacion = $id");
        if($rOT = mysqli_fetch_assoc($qOT)){
            $id_ot_linked = $rOT['id_orden'];
            mysqli_query($mysqli, "UPDATE orden_trabajo SET estado_ot = '$estado' WHERE id_orden = $id_ot_linked");
        }

        json_out(["status"=>"ok"]);
    } else {
        json_out(["status"=>"error", "msg" => "Estado inválido"]);
    }
}

/* =========================================================
   4. ARCHIVAR
========================================================= */
if ($accion == 'archivar') {
    $id = intval($_POST['id_reparacion'] ?? 0);
    if($id > 0) {
        mysqli_query($mysqli, "UPDATE reparacion SET estado = 'Archivado' WHERE id_reparacion = $id");
        json_out(["status"=>"ok"]);
    } else {
        json_out(["status"=>"error"]);
    }
}

/* =========================================================
   5. DESARCHIVAR
========================================================= */
if ($accion == 'desarchivar') {
    $id = intval($_POST['id_reparacion'] ?? 0);
    if($id > 0) {
        // Al restaurar, vuelve a estado seguro 'En Proceso'
        mysqli_query($mysqli, "UPDATE reparacion SET estado = 'En Proceso' WHERE id_reparacion = $id");
        json_out(["status"=>"ok"]);
    } else {
        json_out(["status"=>"error"]);
    }
}

json_out(["error" => "Acción no reconocida"]);
?>