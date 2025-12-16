<?php
session_start();
require_once "../../config/database.php";

$accion = $_REQUEST['accion'] ?? '';

// Definir cabecera JSON para acciones AJAX
$acciones_json = ["datos_diagnostico", "cambiar_estado", "archivar", "desarchivar"];
if (in_array($accion, $acciones_json)) {
    header("Content-Type: application/json; charset=utf-8");
}

function json_out($data){
    echo json_encode($data);
    exit;
}

/* =========================================================
   1. OBTENER DATOS DEL DIAGNÓSTICO (AJAX)
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
    ");

    $data = mysqli_fetch_assoc($q);
    json_out($data ?: null);
}

/* =========================================================
   2. INSERTAR PRESUPUESTO (GUARDAR)
========================================================= */
if ($accion == 'insertar') {

    // Recibir datos de cabecera
    $id_diagnostico = intval($_POST['id_diagnostico'] ?? 0);
    $mano_obra      = floatval($_POST['mano_obra'] ?? 0);
    $subtotal       = floatval($_POST['subtotal'] ?? 0);
    $total          = floatval($_POST['total'] ?? 0);
    $obs            = mysqli_real_escape_string($mysqli, $_POST['observaciones'] ?? '');
    $usuario        = $_SESSION['username'] ?? 'sistema';

    // Insertar Cabecera
    $sql = "INSERT INTO presupuesto(
                id_diagnostico, fecha_presupuesto, mano_obra, subtotal, total, 
                observaciones, estado, usuario_registro, fecha_registro
            ) VALUES (
                $id_diagnostico, NOW(), $mano_obra, $subtotal, $total, 
                '$obs', 'Pendiente', '$usuario', NOW()
            )";

    if(mysqli_query($mysqli, $sql)){
        $id_pres = mysqli_insert_id($mysqli); // Obtener el ID del presupuesto creado

        // --- PROCESAR DETALLES ---
        $items  = $_POST['detalle_item'] ?? []; 
        $desc   = $_POST['detalle_descripcion'] ?? [];
        $cant   = $_POST['detalle_cantidad'] ?? [];
        $precio = $_POST['detalle_precio'] ?? [];
        $sub    = $_POST['detalle_subtotal'] ?? [];

        // USAMOS el array de descripciones para contar el número de filas (más seguro)
        $num_filas = count($desc); 

        for($i=0; $i<$num_filas; $i++){ 
            
            $d = trim($desc[$i]);
            // Seguridad: Si la descripción está vacía, saltamos.
            if(empty($d)) continue; 
            
            // Si no hay item_code, asumimos que no es un producto/servicio vinculado (se guarda solo la descripción)
            $item_code = $items[$i] ?? ''; 

            // Lógica para separar ID (P=Producto, S=Servicio)
            $tipo = substr($item_code, 0, 1);       
            $id_real = intval(substr($item_code, 1)); 

            // Asignar al campo correcto y dejar el otro en NULL
            $id_producto = ($tipo == 'P') ? $id_real : "NULL";
            $id_tipo_servicio = ($tipo == 'S') ? $id_real : "NULL"; // <--- CORREGIDO 'id_tipo_servicio'

            // Datos visuales
            $d_sql  = mysqli_real_escape_string($mysqli, $d);
            $c  = intval($cant[$i] ?? 0);
            $pu = floatval($precio[$i] ?? 0);
            $st = floatval($sub[$i] ?? 0);

            // Insertar Detalle
            $sql_det = "INSERT INTO presupuesto_detalle (
                            id_presupuesto, id_producto, id_tipo_servicio, 
                            descripcion, cantidad, precio_unitario, subtotal
                        ) VALUES (
                            $id_pres, $id_producto, $id_tipo_servicio,
                            '$d_sql', $c, $pu, $st
                        )";
            mysqli_query($mysqli, $sql_det);
        }

        header("Location: ../../main.php?module=presupuesto&alert=1");
    } else {
        // Error al insertar cabecera
        header("Location: ../../main.php?module=presupuesto&alert=4");
    }
    exit;
}

/* =========================================================
   3. ACTUALIZAR PRESUPUESTO (EDITAR)
========================================================= */
if ($accion == 'actualizar') {

    $id_presupuesto = intval($_POST['id_presupuesto'] ?? 0);
    
    // Validación básica
    if($id_presupuesto <= 0){
        header("Location: ../../main.php?module=presupuesto&alert=4");
        exit;
    }

    $id_diagnostico = intval($_POST['id_diagnostico'] ?? 0);
    $mano_obra      = floatval($_POST['mano_obra'] ?? 0);
    $subtotal       = floatval($_POST['subtotal'] ?? 0);
    $total          = floatval($_POST['total'] ?? 0);
    $obs            = mysqli_real_escape_string($mysqli, $_POST['observaciones'] ?? '');

    // Actualizar Cabecera
    $sql = "UPDATE presupuesto SET 
                id_diagnostico = $id_diagnostico, 
                mano_obra      = $mano_obra, 
                subtotal       = $subtotal, 
                total          = $total, 
                observaciones  = '$obs'
            WHERE id_presupuesto = $id_presupuesto";

    if(mysqli_query($mysqli, $sql)){

        // 1. Borrar todos los detalles viejos de este presupuesto
        mysqli_query($mysqli, "DELETE FROM presupuesto_detalle WHERE id_presupuesto = $id_presupuesto");

        // 2. Insertar los nuevos detalles (Misma lógica que insertar)
        $items  = $_POST['detalle_item'] ?? [];
        $desc   = $_POST['detalle_descripcion'] ?? [];
        $cant   = $_POST['detalle_cantidad'] ?? [];
        $precio = $_POST['detalle_precio'] ?? [];
        $sub    = $_POST['detalle_subtotal'] ?? [];

        // USAMOS el array de descripciones para contar el número de filas
        $num_filas = count($desc); 

        for($i=0; $i<$num_filas; $i++){
            
            $d = trim($desc[$i]);
            if(empty($d)) continue; 

            $item_code = $items[$i] ?? '';

            $tipo = substr($item_code, 0, 1);
            $id_real = intval(substr($item_code, 1));

            $id_producto = ($tipo == 'P') ? $id_real : "NULL";
            $id_tipo_servicio = ($tipo == 'S') ? $id_real : "NULL"; // <--- CORREGIDO 'id_tipo_servicio'

            $d_sql  = mysqli_real_escape_string($mysqli, $d);
            $c  = intval($cant[$i] ?? 0);
            $pu = floatval($precio[$i] ?? 0);
            $st = floatval($sub[$i] ?? 0);

            $sql_det = "INSERT INTO presupuesto_detalle (
                            id_presupuesto, id_producto, id_tipo_servicio, 
                            descripcion, cantidad, precio_unitario, subtotal
                        ) VALUES (
                            $id_presupuesto, $id_producto, $id_tipo_servicio,
                            '$d_sql', $c, $pu, $st
                        )";
            mysqli_query($mysqli, $sql_det);
        }

        header("Location: ../../main.php?module=presupuesto&alert=2");
    } else {
        header("Location: ../../main.php?module=presupuesto&alert=4");
    }
    exit;
}

/* =========================================================
   4. CAMBIAR ESTADO
========================================================= */
if ($accion == 'cambiar_estado') {
    $id     = intval($_POST['id_presupuesto'] ?? 0);
    $estado = $_POST['estado'] ?? '';
    $validos = ["Pendiente","Enviado","Aprobado","Rechazado"];

    if($id > 0 && in_array($estado,$validos)){
        $estado_sql = mysqli_real_escape_string($mysqli, $estado);
        mysqli_query($mysqli, "UPDATE presupuesto SET estado = '$estado_sql' WHERE id_presupuesto = $id");
        json_out(["status"=>"ok"]);
    } else {
        json_out(["status"=>"error"]);
    }
}

/* =========================================================
   5. ARCHIVAR
========================================================= */
if ($accion == 'archivar') {
    $id = intval($_POST['id_presupuesto'] ?? 0);
    if($id > 0) {
        mysqli_query($mysqli, "UPDATE presupuesto SET estado = 'Archivado' WHERE id_presupuesto = $id");
        json_out(["status"=>"ok"]);
    } else {
        json_out(["status"=>"error"]);
    }
}

/* =========================================================
   6. DESARCHIVAR
========================================================= */
if ($accion == 'desarchivar') {
    $id = intval($_POST['id_presupuesto'] ?? 0);
    if($id > 0) {
        mysqli_query($mysqli, "UPDATE presupuesto SET estado = 'Pendiente' WHERE id_presupuesto = $id");
        json_out(["status"=>"ok"]);
    } else {
        json_out(["status"=>"error"]);
    }
}

// Si la acción no coincide con ninguna
json_out(["error"=>"Acción no reconocida"]);
?>