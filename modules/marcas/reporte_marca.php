<?php
// Cargar Dompdf desde la carpeta 'librerias'
require_once '../../librerias/dompdf/autoload.inc.php';
require_once "../../config/database.php";

use Dompdf\Dompdf;
use Dompdf\Options;



// Verificar conexión
if ($mysqli->connect_error) {
    die("Error en la conexión: " . $mysqli->connect_error);
}

// Obtener los datos de las marcas
$query = $mysqli->query("SELECT id_marca, marca_descrip, marca_estado FROM marcas");

// Generar el contenido HTML para el PDF
$html = '<h2 style="text-align: center;">Lista de Marcas</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: center;">Nº</th>
                    <th style="text-align: center;">ID</th>
                    <th style="text-align: center;">Descripción</th>
                    <th style="text-align: center;">Estado</th>
                </tr>
            </thead>
            <tbody>';

$nro = 1; // Inicializamos el contador para la columna Nº
while ($row = $query->fetch_assoc()) {
    $estado = $row['marca_estado'] == 1 ? "Activo" : "Inactivo";
    $html .= '<tr>
                <td style="text-align: center;">' . $nro++ . '</td>
                <td style="text-align: center;">' . htmlspecialchars($row['id_marca']) . '</td>
                <td>' . htmlspecialchars($row['marca_descrip']) . '</td>
                <td style="text-align: center;">' . htmlspecialchars($estado) . '</td>
              </tr>';
}
$html .= '</tbody></table>';

// Configurar Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Para cargar recursos externos como imágenes
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait'); // Tamaño y orientación
$dompdf->render();

// Enviar el PDF al navegador
$dompdf->stream("reporte_marcas.pdf", ["Attachment" => false]); // Cambia a true para descargar automáticamente

exit;
?>
