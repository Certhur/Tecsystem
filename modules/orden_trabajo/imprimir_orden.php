<?php
require_once "../../librerias/dompdf/autoload.inc.php";
require_once "../../config/database.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. VALIDAR ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID de orden no proporcionado.");
}
$id_orden = intval($_GET['id']);

// 2. CONSULTA CABECERA (ORDEN + PRESUPUESTO + CLIENTE + EQUIPO + TECNICO)
$query = mysqli_query($mysqli, "
    SELECT ot.*,
           p.id_presupuesto,
           p.mano_obra,
           p.subtotal,
           p.total,
           d.id_diagnostico,
           cl.cli_razon_social, cl.ci_ruc, cl.cli_direccion, cl.cli_telefono,
           re.equipo_modelo,
           m.marca_descrip,
           te.tipo_descrip,
           u.name_user as tecnico_nombre
    FROM orden_trabajo ot
    INNER JOIN presupuesto p       ON ot.id_presupuesto = p.id_presupuesto
    LEFT JOIN diagnostico d        ON p.id_diagnostico = d.id_diagnostico
    LEFT JOIN recepcion_equipo re  ON d.id_recepcion_equipo = re.id_recepcion_equipo
    LEFT JOIN clientes cl          ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m             ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te       ON re.id_tipo_equipo = te.id_tipo_equipo
    LEFT JOIN usuarios u           ON ot.id_user = u.id_user
    WHERE ot.id_orden = $id_orden
");

$data = mysqli_fetch_assoc($query);
if (!$data) die("Error: La orden de trabajo #$id_orden no existe.");

// 3. CONSULTA DETALLES (Usamos los del Presupuesto vinculado)
$id_presupuesto = $data['id_presupuesto'];
$qdet = mysqli_query($mysqli, "SELECT * FROM presupuesto_detalle WHERE id_presupuesto = $id_presupuesto");

// 4. CONFIGURAR DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);

// 5. CONSTRUIR HTML
$html = '
<!DOCTYPE html>
<html>
<head>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    th { background-color: #f2f2f2; font-weight: bold; text-align: left; }
    th, td { border: 1px solid #ccc; padding: 6px; }
    .center { text-align: center; }
    .right { text-align: right; }
    h2 { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
    .section-title { font-size: 12px; font-weight: bold; background: #333; color: #fff; padding: 5px; margin-top: 15px; }
    .firma-box { margin-top: 60px; width: 100%; }
    .firma-col { width: 45%; float: left; text-align: center; margin: 0 2.5%; }
    .firma-line { border-top: 1px solid #000; margin-bottom: 5px; }
    .info-box { width: 100%; margin-bottom: 10px; }
</style>
</head>
<body>

<h2>Orden de Trabajo #'.$data['id_orden'].'</h2>

<table style="border:none;">
    <tr>
        <td style="border:none; width:50%;">
            <strong>Fecha Inicio:</strong> '.date('d/m/Y H:i', strtotime($data['fecha_inicio'])).'<br>
            <strong>Entrega Estimada:</strong> '.($data['fecha_entrega_estimada'] ? date('d/m/Y', strtotime($data['fecha_entrega_estimada'])) : 'A definir').'<br>
            <strong>Estado:</strong> '.$data['estado_ot'].'
        </td>
        <td style="border:none; width:50%; text-align:right;">
            <strong>Ref. Presupuesto:</strong> #'.$data['id_presupuesto'].'<br>
            <strong>Técnico Asignado:</strong> '.$data['tecnico_nombre'].'
        </td>
    </tr>
</table>

<div class="section-title">DATOS DEL CLIENTE</div>
<table>
    <tr><th width="20%">Cliente:</th><td>'.$data['cli_razon_social'].'</td></tr>
    <tr><th>RUC/CI:</th><td>'.$data['ci_ruc'].'</td></tr>
    <tr><th>Dirección:</th><td>'.$data['cli_direccion'].'</td></tr>
    <tr><th>Teléfono:</th><td>'.$data['cli_telefono'].'</td></tr>
</table>

<div class="section-title">DATOS DEL EQUIPO</div>
<table>
    <tr><th width="20%">Equipo:</th><td>'.$data['tipo_descrip'].'</td></tr>
    <tr><th>Marca:</th><td>'.$data['marca_descrip'].'</td></tr>
    <tr><th>Modelo:</th><td>'.$data['equipo_modelo'].'</td></tr>
    <tr><th>Diagnóstico Ref:</th><td>#'.$data['id_diagnostico'].'</td></tr>
</table>

<div class="section-title">TAREAS A REALIZAR / REPUESTOS</div>
<table>
    <thead>
        <tr>
            <th width="70%">Descripción</th>
            <th width="10%" class="center">Cant.</th>
            <th width="20%" class="center">Control / Check</th>
        </tr>
    </thead>
    <tbody>';

// --- BUCLE DE DETALLES ---
if (mysqli_num_rows($qdet) > 0) {
    while($d = mysqli_fetch_assoc($qdet)) {
        $html .= "
        <tr>
            <td>{$d['descripcion']}</td>
            <td class='center'>{$d['cantidad']}</td>
            <td class='center' style='color:#ccc;'>[ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ]</td> 
        </tr>";
    }
} else {
    $html .= "<tr><td colspan='3' class='center'>No se encontraron detalles.</td></tr>";
}

// Totales (Informativos para cobro)
$mano_obra = number_format($data['mano_obra'], 0, ',', '.');
$total     = number_format($data['total'], 0, ',', '.');

$html .= '
    </tbody>
</table>

<table style="width: 40%; margin-left: auto;">
    <tr style="font-size:13px;"><th>TOTAL A COBRAR</th><td class="right"><b>'.$total.' Gs.</b></td></tr>
</table>

<div class="section-title">OBSERVACIONES / INSTRUCCIONES</div>
<div style="border: 1px solid #ccc; padding: 10px; min-height: 40px;">
    '.($data['observaciones_ot'] ?: 'Sin observaciones adicionales.').'
</div>

<div class="firma-box">
    <div class="firma-col">
        <div class="firma-line"></div>
        <span>Firma del Técnico ('.$data['tecnico_nombre'].')</span>
    </div>
    <div class="firma-col">
        <div class="firma-line"></div>
        <span>Conformidad del Cliente</span>
    </div>
</div>

</body>
</html>';

// 6. RENDERIZAR
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("OrdenTrabajo_".$id_orden.".pdf", ["Attachment" => false]);
?>