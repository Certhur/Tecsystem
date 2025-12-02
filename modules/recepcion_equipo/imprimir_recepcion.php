<?php
require_once '../../librerias/dompdf/autoload.inc.php';
require_once "../../config/database.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = intval($_GET['id']);

// Obtener datos
$query = mysqli_query($mysqli, "
    SELECT re.*,
           cl.cli_razon_social,
           cl.cli_direccion,
           cl.cli_telefono,
           cl.ci_ruc,
           m.marca_descrip,
           te.tipo_descrip
    FROM recepcion_equipo re
    LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE re.id_recepcion_equipo = $id
") or die(mysqli_error($mysqli));

$data = mysqli_fetch_assoc($query);
if (!$data) { die("Recepción no encontrada."); }

// Config DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Logo
$logo_path = __DIR__ . "/assets/logo.png";
$logo_base64 = base64_encode(file_get_contents($logo_path));
$logo_src = "data:image/png;base64," . $logo_base64;

// ===============================================
// HTML DEL DOCUMENTO
// ===============================================
$html = '
<style>
body { font-family: DejaVu Sans, sans-serif; }
h2 { text-align:center; margin-bottom: 15px; }
table { width:100%; border-collapse: collapse; font-size:13px; }
td, th { padding:6px; border:1px solid #000; }
.section-title { margin-top:20px; font-weight:bold; font-size:14px; }
.center { text-align:center; }
.firma-box { margin-top:50px; width:100%; }
.firma-col { width:50%; float:left; text-align:center; }
.firma-line { margin-top:60px; border-top:1px solid #000; width:80%; margin-left:auto; margin-right:auto; }
</style>

<div style="text-align:center;">
    <img src="'.$logo_src.'" style="width:140px; margin-bottom:10px;">
</div>

<h2>RECEPCIÓN DE EQUIPO</h2>

<p class="section-title">Datos de la Recepción</p>
<table>
    <tr>
        <th>ID Recepción</th><td>'.$data['id_recepcion_equipo'].'</td>
        <th>Fecha</th><td>'.$data['fecha_recepcion'].'</td>
    </tr>
    <tr>
        <th>Estado</th><td>'.ucfirst($data['estado']).'</td>
        <th>Cliente</th><td>'.$data['cli_razon_social'].'</td>
    </tr>
</table>

<p class="section-title">Datos del Cliente</p>
<table>
    <tr>
        <th>Documento (CI/RUC)</th><td>'.$data['ci_ruc'].'</td>
        <th>Teléfono</th><td>'.$data['cli_telefono'].'</td>
    </tr>
    <tr>
        <th>Dirección</th><td colspan="3">'.$data['cli_direccion'].'</td>
    </tr>
</table>

<p class="section-title">Datos del Equipo</p>
<table>
    <tr>
        <th>Marca</th><td>'.$data['marca_descrip'].'</td>
        <th>Tipo</th><td>'.$data['tipo_descrip'].'</td>
    </tr>
    <tr>
        <th>Modelo</th><td>'.$data['equipo_modelo'].'</td>
        <th>Descripción</th><td>'.$data['equipo_descripcion'].'</td>
    </tr>
</table>

<div class="firma-box">
    <div class="firma-col">
        <div class="firma-line"></div>
        <p>Firma del Técnico</p>
    </div>

    <div class="firma-col">
        <div class="firma-line"></div>
        <p>Firma del Cliente</p>
    </div>
</div>
';

// Cargar y Renderizar
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Abrir en navegador sin descargar automáticamente
$dompdf->stream("recepcion_$id.pdf", ["Attachment" => false]);

exit;
?>
