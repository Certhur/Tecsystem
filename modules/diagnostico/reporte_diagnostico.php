<?php
require_once '../../librerias/dompdf/autoload.inc.php';
require_once "../../config/database.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Consulta completa con joins (igual que tu vista)
$query = $mysqli->query("
    SELECT dg.*, 
           re.equipo_modelo, 
           cl.cli_razon_social, 
           m.marca_descrip, 
           te.tipo_descrip
    FROM diagnostico AS dg
    LEFT JOIN recepcion_equipo AS re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    LEFT JOIN clientes AS cl ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas AS m ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo AS te ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE dg.estado_diagnostico != 'Archivado'
");

$logo = __DIR__ . "/assets/logo.png";
$logo_base64 = base64_encode(file_get_contents($logo));
$logo_src = "data:image/png;base64," . $logo_base64;

// Armado del HTML
$html = '
<h2 style="text-align:center; margin-bottom:20px;">Lista de Diagnósticos</h2>
<table border="1" cellspacing="0" cellpadding="4" style="width:100%; border-collapse:collapse; font-size:12px;">
<thead>
<tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Cliente</th>
    <th>Marca</th>
    <th>Tipo</th>
    <th>Modelo</th>
    <th>Falla</th>
    <th>Causa</th>
    <th>Solución</th>
    <th>Observaciones</th>
    <th>Estado</th>
</tr>
</thead>
<tbody>
';

while ($d = $query->fetch_assoc()) {
    $html .= "
    <tr>
        <td>{$d['id_diagnostico']}</td>
        <td>{$d['fecha_diagnostico']}</td>
        <td>{$d['cli_razon_social']}</td>
        <td>{$d['marca_descrip']}</td>
        <td>{$d['tipo_descrip']}</td>
        <td>{$d['equipo_modelo']}</td>
        <td>{$d['falla_diagnostico']}</td>
        <td>{$d['causa_diagnostico']}</td>
        <td>{$d['solucion_diagnostico']}</td>
        <td>{$d['observaciones']}</td>
        <td>{$d['estado_diagnostico']}</td>
    </tr>";
}

$html .= "</tbody></table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_diagnosticos.pdf", ["Attachment" => false]);
exit;
