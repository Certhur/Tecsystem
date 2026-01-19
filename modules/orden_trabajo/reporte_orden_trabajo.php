<?php
require_once "../../config/database.php";
require_once "../../librerias/dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Consulta para listar Órdenes de Trabajo con sus relaciones
$q = mysqli_query($mysqli, "
    SELECT ot.*, 
           p.id_presupuesto,
           cl.cli_razon_social,
           u.name_user
    FROM orden_trabajo ot
    INNER JOIN presupuesto p       ON ot.id_presupuesto = p.id_presupuesto
    INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
    INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
    LEFT JOIN usuarios u           ON ot.id_user = u.id_user
    ORDER BY ot.id_orden DESC
");

$html = "
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #000; padding: 5px; text-align: left; }
th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
h2 { text-align: center; text-transform: uppercase; margin-bottom: 20px; }
.center { text-align: center; }
</style>

<h2>REPORTE GENERAL DE ÓRDENES DE TRABAJO</h2>

<table>
<thead>
<tr>
    <th width='50'>ID OT</th>
    <th width='60'>Ref. Pres.</th>
    <th>Cliente</th>
    <th>Técnico Asignado</th>
    <th width='80'>Fecha Inicio</th>
    <th width='80'>Estado</th>
</tr>
</thead>
<tbody>
";

while ($row = mysqli_fetch_assoc($q)) {
    // Formatear fecha
    $fecha = date('d/m/Y', strtotime($row['fecha_inicio']));
    
    // Validar nombre de técnico
    $tecnico = !empty($row['name_user']) ? $row['name_user'] : 'Sin asignar';

    $html .= "
    <tr>
        <td class='center'>{$row['id_orden']}</td>
        <td class='center'>#{$row['id_presupuesto']}</td>
        <td>{$row['cli_razon_social']}</td>
        <td>{$tecnico}</td>
        <td class='center'>{$fecha}</td>
        <td class='center'>{$row['estado_ot']}</td>
    </tr>";
}

$html .= "
</tbody>
</table>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_ordenes_trabajo.pdf", ["Attachment" => false]);
exit;
?>