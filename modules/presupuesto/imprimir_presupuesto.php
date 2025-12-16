<?php
require_once "../../librerias/dompdf/autoload.inc.php";
require_once "../../config/database.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. VALIDAR ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID de presupuesto no proporcionado.");
}
$id = intval($_GET['id']);

// 2. CONSULTA CABECERA
$query = mysqli_query($mysqli, "
    SELECT p.*, 
           d.id_diagnostico,
           cl.cli_razon_social, cl.ci_ruc, cl.cli_direccion, cl.cli_telefono,
           re.equipo_modelo,
           m.marca_descrip,
           te.tipo_descrip
    FROM presupuesto p
    LEFT JOIN diagnostico d ON p.id_diagnostico = d.id_diagnostico
    LEFT JOIN recepcion_equipo re ON d.id_recepcion_equipo = re.id_recepcion_equipo
    LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE p.id_presupuesto = $id
");

$data = mysqli_fetch_assoc($query);
if (!$data) die("Error: El presupuesto #$id no existe.");

// 3. CONSULTA DETALLES
$qdet = mysqli_query($mysqli, "SELECT * FROM presupuesto_detalle WHERE id_presupuesto = $id");

// 4. CONFIGURAR DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans'); // Mejor soporte para tildes/ñ
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
</style>
</head>
<body>

<h2>Presupuesto de Servicio Técnico #'.$data['id_presupuesto'].'</h2>

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
    <tr><th>Diagnóstico:</th><td>#'.$data['id_diagnostico'].'</td></tr>
</table>

<div class="section-title">DETALLES DEL PRESUPUESTO</div>
<table>
    <thead>
        <tr>
            <th width="50%">Descripción</th>
            <th width="10%" class="center">Cant.</th>
            <th width="20%" class="right">P. Unitario</th>
            <th width="20%" class="right">Subtotal</th>
        </tr>
    </thead>
    <tbody>';

// --- BUCLE DE DETALLES ---
if (mysqli_num_rows($qdet) > 0) {
    while($d = mysqli_fetch_assoc($qdet)) {
        // Formatear números (separador de miles)
        $precio = number_format($d['precio_unitario'], 0, ',', '.');
        $sub    = number_format($d['subtotal'], 0, ',', '.');
        
        $html .= "
        <tr>
            <td>{$d['descripcion']}</td>
            <td class='center'>{$d['cantidad']}</td>
            <td class='right'>$precio</td>
            <td class='right'>$sub</td>
        </tr>";
    }
} else {
    // Si no hay datos, mostrar aviso
    $html .= "<tr><td colspan='4' class='center' style='color:red;'>No se encontraron ítems cargados en este presupuesto.</td></tr>";
}

// Formatear totales generales
$mano_obra = number_format($data['mano_obra'], 0, ',', '.');
$subtotal  = number_format($data['subtotal'], 0, ',', '.');
$total     = number_format($data['total'], 0, ',', '.');

$html .= '
    </tbody>
</table>

<table style="width: 40%; margin-left: auto;">
    <tr><th>Mano de Obra</th><td class="right">'.$mano_obra.'</td></tr>
    <tr><th>Subtotal</th><td class="right">'.$subtotal.'</td></tr>
    <tr style="font-size:13px;"><th>TOTAL A PAGAR</th><td class="right"><b>'.$total.' Gs.</b></td></tr>
</table>

<div class="section-title">OBSERVACIONES</div>
<div style="border: 1px solid #ccc; padding: 10px; min-height: 40px;">
    '.$data['observaciones'].'
</div>

<div class="firma-box">
    <div class="firma-col">
        <div class="firma-line"></div>
        <span>Firma del Técnico</span>
    </div>
    <div class="firma-col">
        <div class="firma-line"></div>
        <span>Aceptación del Cliente</span>
    </div>
</div>

</body>
</html>';

// 6. RENDERIZAR
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("Presupuesto_".$id.".pdf", ["Attachment" => false]);
?>