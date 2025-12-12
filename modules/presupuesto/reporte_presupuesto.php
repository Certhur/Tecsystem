<?php
require_once "../../config/database.php";
require_once "../../librerias/dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Consulta
$q = mysqli_query($mysqli, "
    SELECT p.*, d.id_diagnostico, cl.cli_razon_social
    FROM presupuesto p
    LEFT JOIN diagnostico d ON p.id_diagnostico = d.id_diagnostico
    LEFT JOIN recepcion_equipo re ON d.id_recepcion_equipo = re.id_recepcion_equipo
    LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
    ORDER BY p.id_presupuesto DESC
");

$html = "
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #000; padding: 5px; text-align: left; }
h2 { text-align: center; }
</style>

<h2>REPORTE GENERAL DE PRESUPUESTOS</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Diagnóstico</th>
    <th>Cliente</th>
    <th>Fecha</th>
    <th>Total</th>
    <th>Estado</th>
</tr>
</thead>
<tbody>
";

while ($row = mysqli_fetch_assoc($q)) {
    $html .= "
    <tr>
        <td>{$row['id_presupuesto']}</td>
        <td>{$row['id_diagnostico']}</td>
        <td>{$row['cli_razon_social']}</td>
        <td>{$row['fecha_presupuesto']}</td>
        <td>{$row['total']}</td>
        <td>{$row['estado']}</td>
    </tr>";
}

$html .= "
</tbody>
</table>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_presupuesto.pdf", ["Attachment" => false]);
exit;
?>
