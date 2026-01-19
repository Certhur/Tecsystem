<?php
require_once "../../config/database.php";
require_once "../../librerias/dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Consulta para listar Reparaciones con sus relaciones (Marca, Tipo, Cliente)
$q = mysqli_query($mysqli, "
    SELECT r.id_reparacion,
           r.fecha_reparacion,
           r.estado,
           cl.cli_razon_social,
           cl.ci_ruc,
           te.tipo_descrip,
           re.equipo_modelo,
           m.marca_descrip,
           u.name_user
    FROM reparacion r
    INNER JOIN orden_trabajo ot    ON r.id_orden = ot.id_orden
    INNER JOIN presupuesto p       ON ot.id_presupuesto = p.id_presupuesto
    INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
    INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
    INNER JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
    INNER JOIN marcas m            ON re.id_marca = m.id_marca
    LEFT JOIN usuarios u           ON r.id_user = u.id_user
    WHERE r.estado <> 'Archivado'
    ORDER BY r.id_reparacion DESC
");

$html = "
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #000; padding: 5px; text-align: left; }
th { background-color: #f2f2f2; text-align: center; font-weight: bold; text-transform: uppercase; }
h2 { text-align: center; text-transform: uppercase; margin-bottom: 5px; }
.center { text-align: center; }
.sub-header { text-align: center; margin-bottom: 20px; font-size: 12px; }
</style>

<h2>REPORTE GENERAL DE REPARACIONES</h2>
<div class='sub-header'>Fecha de emisión: " . date('d/m/Y H:i') . "</div>

<table>
<thead>
<tr>
    <th width='40'>ID</th>
    <th width='150'>Cliente / RUC</th>
    <th>Equipo / Marca / Modelo</th>
    <th width='100'>Técnico</th>
    <th width='70'>Fecha</th>
    <th width='80'>Estado</th>
</tr>
</thead>
<tbody>
";

while ($row = mysqli_fetch_assoc($q)) {
    $fecha = date('d/m/Y', strtotime($row['fecha_reparacion']));
    $tecnico = !empty($row['name_user']) ? $row['name_user'] : 'Sin asignar';
    $equipo_detalle = $row['tipo_descrip'] . " " . $row['marca_descrip'] . " " . $row['equipo_modelo'];

    $html .= "
    <tr>
        <td class='center'>{$row['id_reparacion']}</td>
        <td>{$row['cli_razon_social']}<br><small>RUC: {$row['ci_ruc']}</small></td>
        <td>{$equipo_detalle}</td>
        <td class='center'>{$tecnico}</td>
        <td class='center'>{$fecha}</td>
        <td class='center'>" . strtoupper($row['estado']) . "</td>
    </tr>";
}

$html .= "
</tbody>
</table>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_reparaciones.pdf", ["Attachment" => false]);
exit;