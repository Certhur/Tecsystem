<?php
session_start();
require_once "../../config/database.php";

$accion = $_REQUEST['accion'] ?? '';

/* ============================================================
   ACCIONES QUE RESPONDEN JSON (AJAX)
============================================================ */
$acciones_json = [
    "consultarCliente","consultarMarca","consultarTipo_Equipo","consultarCiudad",
    "guardarMarca","guardarTipoEquipo","guardarCliente",
    "archivar","desarchivar",
    "cambiar_estado" // 👈 AGREGADO
];

if (in_array($accion, $acciones_json)) {
    header("Content-Type: application/json; charset=utf-8");
}

function json_out($data){
    echo json_encode($data);
    exit;
}

/* ============================================================
   CONSULTAS PARA SELECT (AJAX)
============================================================ */

if ($accion == "consultarCliente") {
    $q = mysqli_query($mysqli, "SELECT id_cliente, cli_razon_social FROM clientes ORDER BY cli_razon_social ASC");
    $data = [];
    while($r = mysqli_fetch_assoc($q)) $data[] = $r;
    json_out($data);
}

if ($accion == "consultarMarca") {
    $q = mysqli_query($mysqli, "SELECT id_marca, marca_descrip FROM marcas ORDER BY marca_descrip ASC");
    $data = [];
    while($r = mysqli_fetch_assoc($q)) $data[] = $r;
    json_out($data);
}

if ($accion == "consultarTipo_Equipo") {
    $q = mysqli_query($mysqli, "SELECT id_tipo_equipo, tipo_descrip FROM tipo_equipo ORDER BY tipo_descrip ASC");
    $data = [];
    while($r = mysqli_fetch_assoc($q)) $data[] = $r;
    json_out($data);
}

if ($accion == "consultarCiudad") {
    $q = mysqli_query($mysqli, "SELECT cod_ciudad, descrip_ciudad FROM ciudad ORDER BY descrip_ciudad ASC");
    $data = [];
    while($r = mysqli_fetch_assoc($q)) $data[] = $r;
    json_out($data);
}

/* ============================================================
   GUARDAR DESDE MODALES
============================================================ */

if ($accion == "guardarMarca") {
    $nombre = trim($_POST['nuevaMarca']);
    mysqli_query($mysqli, "INSERT INTO marcas(marca_descrip) VALUES('$nombre')");
    json_out([
        "success"=>true,
        "id_marca"=>mysqli_insert_id($mysqli),
        "marca_descrip"=>$nombre
    ]);
}

if ($accion == "guardarTipoEquipo") {
    $nombre = trim($_POST['nuevoTipoEquipo']);
    mysqli_query($mysqli, "INSERT INTO tipo_equipo(tipo_descrip) VALUES('$nombre')");
    json_out([
        "success"=>true,
        "id_tipo_equipo"=>mysqli_insert_id($mysqli),
        "tipo_descrip"=>$nombre
    ]);
}

if ($accion == "guardarCliente") {

    $sql = "
    INSERT INTO clientes(cod_ciudad, ci_ruc, cli_razon_social, cli_direccion, cli_telefono, cli_email)
    VALUES (
        '".intval($_POST['ciudad'])."',
        '".mysqli_real_escape_string($mysqli, $_POST['ruc_ci'])."',
        '".mysqli_real_escape_string($mysqli, $_POST['razon_social'])."',
        '".mysqli_real_escape_string($mysqli, $_POST['direccion'])."',
        '".mysqli_real_escape_string($mysqli, $_POST['telefono'])."',
        '".mysqli_real_escape_string($mysqli, $_POST['email'])."'
    )";

    mysqli_query($mysqli, $sql);

    json_out([
        "success"=>true,
        "id_cliente"=>mysqli_insert_id($mysqli),
        "razon_social"=>$_POST['razon_social']
    ]);
}

/* ============================================================
   CAMBIAR ESTADO (AJAX)  👈 NUEVO BLOQUE
============================================================ */

if ($accion == "cambiar_estado") {

    $id     = intval($_POST['id_recepcion_equipo'] ?? 0);
    $estado = $_POST['estado'] ?? '';

    // estados válidos (sin 'archivado', porque eso lo hace el botón archivar)
    $validos = [
        "recepcionado",
        "en_diagnostico",
        "esperando_repuestos",
        "pendiente_cliente",
        "listo",
        "entregado"
    ];

    if ($id <= 0 || !in_array($estado, $validos)) {
        json_out(["status"=>"error","message"=>"Datos inválidos"]);
    }

    $sql = "
        UPDATE recepcion_equipo
        SET estado = '".mysqli_real_escape_string($mysqli, $estado)."'
        WHERE id_recepcion_equipo = $id
    ";

    if (mysqli_query($mysqli, $sql)) {
        json_out(["status"=>"ok"]);
    } else {
        json_out(["status"=>"error","message"=>"Error al actualizar"]);
    }
}

/* ============================================================
   INSERTAR RECEPCIÓN
============================================================ */

if ($accion == "insertar") {

    $sql = "
    INSERT INTO recepcion_equipo(
        id_tipo_equipo, id_marca, equipo_modelo, equipo_descripcion,
        fecha_recepcion, id_cliente, estado
    ) VALUES (
        '".intval($_POST['tipo_equipo'])."',
        '".intval($_POST['marca'])."',
        '".mysqli_real_escape_string($mysqli, $_POST['modelo'])."',
        '".mysqli_real_escape_string($mysqli, $_POST['descripcion'])."',
        NOW(),
        '".intval($_POST['cliente'])."',
        'recepcionado'
    )";

    if (mysqli_query($mysqli, $sql)) {
        header("Location: ../../main.php?module=recepcion_equipo&alert=1");
    } else {
        header("Location: ../../main.php?module=recepcion_equipo&alert=4");
    }
    exit;
}

/* ============================================================
   ACTUALIZAR RECEPCIÓN
============================================================ */

if ($accion == "actualizar") {

    $sql = "
    UPDATE recepcion_equipo SET
        id_cliente        = '".intval($_POST['cliente'])."',
        id_marca          = '".intval($_POST['marca'])."',
        id_tipo_equipo    = '".intval($_POST['tipo_equipo'])."',
        equipo_modelo     = '".mysqli_real_escape_string($mysqli, $_POST['modelo'])."',
        equipo_descripcion= '".mysqli_real_escape_string($mysqli, $_POST['descripcion'])."'
    WHERE id_recepcion_equipo = '".intval($_POST['id_recepcion_equipo'])."'";

    if (mysqli_query($mysqli, $sql)) {
        header("Location: ../../main.php?module=recepcion_equipo&alert=2");
    } else {
        header("Location: ../../main.php?module=recepcion_equipo&alert=4");
    }
    exit;
}

/* ============================================================
   ELIMINAR RECEPCIÓN
============================================================ */

if ($accion == "eliminar") {
    mysqli_query($mysqli, "DELETE FROM recepcion_equipo WHERE id_recepcion_equipo = ".intval($_GET['id_recepcion_equipo']));
    header("Location: ../../main.php?module=recepcion_equipo&alert=3");
    exit;
}

/* ============================================================
   ARCHIVAR (AJAX)
============================================================ */

if ($accion == "archivar") {

    $id = intval($_POST['id_recepcion_equipo']);

    mysqli_query($mysqli, "
        UPDATE recepcion_equipo
        SET estado = 'archivado'
        WHERE id_recepcion_equipo = $id
    ");

    json_out(["status"=>"ok"]);
}

/* ============================================================
   DESARCHIVAR (AJAX)
============================================================ */

if ($accion == "desarchivar") {

    $id = intval($_POST['id_recepcion_equipo']);

    mysqli_query($mysqli, "
        UPDATE recepcion_equipo
        SET estado = 'recepcionado'
        WHERE id_recepcion_equipo = $id
    ");

    json_out(["status"=>"ok"]);
}

json_out(["error"=>"Acción no reconocida"]);
