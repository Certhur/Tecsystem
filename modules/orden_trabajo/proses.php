<?php
session_start();
require_once "../../config/database.php";

// Capturamos la acción (ya sea por GET o POST)
$accion = $_REQUEST['accion'] ?? '';

// Definir cabecera JSON para acciones AJAX
// Agregamos 'archivar' y 'desarchivar' a la lista permitida para JSON
$acciones_json = ["datos_presupuesto", "cambiar_estado", "archivar", "desarchivar"];
if (in_array($accion, $acciones_json)) {
    header("Content-Type: application/json; charset=utf-8");
}

// Función auxiliar para responder JSON y salir
function json_out($data){
    echo json_encode($data);
    exit;
}

/* =========================================================
   1. OBTENER DATOS DEL PRESUPUESTO (AJAX para Formulario)
========================================================= */
if ($accion == 'datos_presupuesto') {
    $id = intval($_GET['id'] ?? 0);
    
    if($id <= 0) {
        json_out(null);
    }

    $q = mysqli_query($mysqli, "
        SELECT p.id_presupuesto,
               p.total,  /* Incluimos el total para mostrarlo en el form */
               cl.cli_razon_social,
               re.equipo_modelo,
               te.tipo_descrip
        FROM presupuesto p
        INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
        INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
        INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
        INNER JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
        WHERE p.id_presupuesto = $id
    ");

    $data = mysqli_fetch_assoc($q);
    json_out($data ?: null);
}

/* =========================================================
   2. INSERTAR ORDEN DE TRABAJO (Formulario ADD)
========================================================= */
if ($accion == 'insertar') {

    // Recibir datos POST
    $id_presupuesto = intval($_POST['id_presupuesto'] ?? 0);
    $id_user        = intval($_POST['id_user'] ?? 0); // Usuario logueado
    $fecha_entrega  = $_POST['fecha_entrega_estimada'] ?? '';
    $obs            = mysqli_real_escape_string($mysqli, $_POST['observaciones_ot'] ?? '');
    
    // Validar fecha entrega (NULL si está vacía)
    $fecha_entrega_sql = empty($fecha_entrega) ? "NULL" : "'$fecha_entrega'";

    // Insertar Cabecera
    // Nota: El estado inicial siempre es 'Pendiente'
    $sql = "INSERT INTO orden_trabajo(
                id_presupuesto, 
                id_user, 
                fecha_inicio, 
                fecha_entrega_estimada, 
                estado_ot, 
                observaciones_ot
            ) VALUES (
                $id_presupuesto, 
                $id_user, 
                NOW(), 
                $fecha_entrega_sql, 
                'Pendiente', 
                '$obs'
            )";

    if(mysqli_query($mysqli, $sql)){
        // Éxito
        header("Location: ../../main.php?module=orden_trabajo&alert=1");
    } else {
        // Error
        header("Location: ../../main.php?module=orden_trabajo&alert=4");
    }
    exit;
}

/* =========================================================
   3. ACTUALIZAR ORDEN DE TRABAJO (Formulario EDIT)
========================================================= */
if ($accion == 'actualizar') {

    $id_orden = intval($_POST['id_orden'] ?? 0);
    
    if($id_orden <= 0){
        header("Location: ../../main.php?module=orden_trabajo&alert=4");
        exit;
    }

    // Recibir datos editables
    $estado_ot      = mysqli_real_escape_string($mysqli, $_POST['estado_ot'] ?? 'Pendiente');
    $fecha_entrega  = $_POST['fecha_entrega_estimada'] ?? '';
    $obs            = mysqli_real_escape_string($mysqli, $_POST['observaciones_ot'] ?? '');
    
    $fecha_entrega_sql = empty($fecha_entrega) ? "NULL" : "'$fecha_entrega'";

    $sql = "UPDATE orden_trabajo SET 
                estado_ot              = '$estado_ot', 
                fecha_entrega_estimada = $fecha_entrega_sql, 
                observaciones_ot       = '$obs'
            WHERE id_orden = $id_orden";

    if(mysqli_query($mysqli, $sql)){
        header("Location: ../../main.php?module=orden_trabajo&alert=2");
    } else {
        header("Location: ../../main.php?module=orden_trabajo&alert=4");
    }
    exit;
}

/* =========================================================
   4. CAMBIAR ESTADO (AJAX desde Modal)
========================================================= */
if ($accion == 'cambiar_estado') {
    $id     = intval($_POST['id_orden'] ?? 0);
    $estado = $_POST['estado'] ?? '';
    
    // Lista de estados permitidos (incluyendo Archivado por seguridad)
    $validos = ["Pendiente", "En Proceso", "Finalizada", "Entregada", "Cancelada", "Archivado"];

    if($id > 0 && in_array($estado, $validos)){
        $estado_sql = mysqli_real_escape_string($mysqli, $estado);
        
        $sql = "UPDATE orden_trabajo SET estado_ot = '$estado_sql' WHERE id_orden = $id";
        
        if(mysqli_query($mysqli, $sql)){
            json_out(["status"=>"ok"]);
        } else {
            json_out(["status"=>"error", "msg"=>mysqli_error($mysqli)]);
        }
    } else {
        json_out(["status"=>"error", "msg"=>"Datos inválidos"]);
    }
}

/* =========================================================
   5. ARCHIVAR (AJAX - Botón Archivar/Cancelar)
========================================================= */
if ($accion == 'archivar') {
    $id = intval($_POST['id_orden'] ?? 0);
    
    if($id > 0) {
        // Establecemos el estado 'Archivado'
        if(mysqli_query($mysqli, "UPDATE orden_trabajo SET estado_ot = 'Archivado' WHERE id_orden = $id")){
            json_out(["status"=>"ok"]);
        } else {
            json_out(["status"=>"error"]);
        }
    } else {
        json_out(["status"=>"error"]);
    }
}

/* =========================================================
   6. DESARCHIVAR (AJAX - Botón Restaurar)
========================================================= */
if ($accion == 'desarchivar') {
    $id = intval($_POST['id_orden'] ?? 0);
    
    if($id > 0) {
        // Al restaurar, vuelve a estado 'Pendiente' por defecto
        if(mysqli_query($mysqli, "UPDATE orden_trabajo SET estado_ot = 'Pendiente' WHERE id_orden = $id")){
            json_out(["status"=>"ok"]);
        } else {
            json_out(["status"=>"error"]);
        }
    } else {
        json_out(["status"=>"error"]);
    }
}

// Si llega aquí sin entrar en nignún if
json_out(["error"=>"Acción no reconocida"]);
?>