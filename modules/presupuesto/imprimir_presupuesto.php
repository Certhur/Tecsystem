<?php
require_once "../../librerias/dompdf/autoload.inc.php";
require_once "../../config/database.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// VALIDAR ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido");
}

$id = intval($_GET['id']);

// DATOS PRINCIPALES
$query = mysqli_query($mysqli, "
    SELECT p.*, 
           d.id_diagnostico,
           cl.cli_razon_social,
           cl.ci_ruc,
           cl.cli_direccion,
           cl.cli_telefono,
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
if (!$data) die("Presupuesto no encontrado");

// DETALLES
$qdet = mysqli_query($mysqli, "
    SELECT *
    FROM presupuesto_detalle
    WHERE id_presupuesto = $id
");

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$html = '
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #000; padding: 6px; }
h2 { text-align:center; margin-bottom: 10px; }
.section-title { font-size: 14px; margin-top:15px; font-weight:bold; }
.center { text-align:center; }
.firma-box { margin-top:40px; width:100%; }
.firma-col { width:50%; float:left; text-align:center; }
.firma-line { margin-top:40px; border-top:1px solid #000; width:80%; margin:auto; }
</style>

<h2>PRESUPUESTO DE SERVICIO TÉCNICO</h2>

<p class="section-title">Datos del Cliente</p>
<table>
<tr><th>Cliente</th><td>'.$data['cli_razon_social'].'</td></tr>
<tr><th>RUC/CI</th><td>'.$data['ci_ruc'].'</td></tr>
<tr><th>Dirección</th><td>'.$data['cli_direccion'].'</td></tr>
<tr><th>Teléfono</th><td>'.$data['cli_telefono'].'</td></tr>
</table>

<p class="section-title">Datos del Equipo</p>
<table>
<tr><th>Marca</th><td>'.$data['marca_descrip'].'</td></tr>
<tr><th>Tipo</th><td>'.$data['tipo_descrip'].'</td></tr>
<tr><th>Modelo</th><td>'.$data['equipo_modelo'].'</td></tr>
</table>

<p class="section-title">Detalles del Presupuesto</p>

<table>
<thead>
<tr>
    <th>Descripción</th>
    <th>Cant.</th>
    <th>P. Unit.</th>
    <th>Subtotal</th>
</tr>
</thead>
<tbody>
';

while($d = mysqli_fetch_assoc($qdet)) {
    $html .= "
    <tr>
        <td>{$d['descripcion']}</td>
        <td class='center'>{$d['cantidad']}</td>
        <td>{$d['precio_unitario']}</td>
        <td>{$d['subtotal']}</td>
    </tr>";
}

$html .= "
</tbody>
</table>

<p class='section-title'>Totales</p>
<table>
<tr><th>Mano de Obra</th><td>{$data['mano_obra']}</td></tr>
<tr><th>Subtotal</th><td>{$data['subtotal']}</td></tr>
<tr><th>Total</th><td>{$data['total']}</td></tr>
</table>

<p class='section-title'>Observaciones</p>
<p>{$data['observaciones']}</p>

<div class='firma-box'>
    <div class='firma-col'>
        <div class='firma-line'></div>
        <p>Firma del Técnico</p>
    </div>

    <div class='firma-col'>
        <div class='firma-line'></div>
        <p>Firma del Cliente</p>
    </div>
</div>
";

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("presupuesto_$id.pdf", ["Attachment" => false]);
?>
<?php
require_once "../../librerias/dompdf/autoload.inc.php";
require_once "../../config/database.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// VALIDAR ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido");
}

$id = intval($_GET['id']);

// DATOS PRINCIPALES
$query = mysqli_query($mysqli, "
    SELECT p.*, 
           d.id_diagnostico,
           cl.cli_razon_social,
           cl.ci_ruc,
           cl.cli_direccion,
           cl.cli_telefono,
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
if (!$data) die("Presupuesto no encontrado");

// DETALLES
$qdet = mysqli_query($mysqli, "
    SELECT *
    FROM presupuesto_detalle
    WHERE id_presupuesto = $id
");

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$html = '
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #000; padding: 6px; }
h2 { text-align:center; margin-bottom: 10px; }
.section-title { font-size: 14px; margin-top:15px; font-weight:bold; }
.center { text-align:center; }
.firma-box { margin-top:40px; width:100%; }
.firma-col { width:50%; float:left; text-align:center; }
.firma-line { margin-top:40px; border-top:1px solid #000; width:80%; margin:auto; }
</style>

<h2>PRESUPUESTO DE SERVICIO TÉCNICO</h2>

<p class="section-title">Datos del Cliente</p>
<table>
<tr><th>Cliente</th><td>'.$data['cli_razon_social'].'</td></tr>
<tr><th>RUC/CI</th><td>'.$data['ci_ruc'].'</td></tr>
<tr><th>Dirección</th><td>'.$data['cli_direccion'].'</td></tr>
<tr><th>Teléfono</th><td>'.$data['cli_telefono'].'</td></tr>
</table>

<p class="section-title">Datos del Equipo</p>
<table>
<tr><th>Marca</th><td>'.$data['marca_descrip'].'</td></tr>
<tr><th>Tipo</th><td>'.$data['tipo_descrip'].'</td></tr>
<tr><th>Modelo</th><td>'.$data['equipo_modelo'].'</td></tr>
</table>

<p class="section-title">Detalles del Presupuesto</p>

<table>
<thead>
<tr>
    <th>Descripción</th>
    <th>Cant.</th>
    <th>P. Unit.</th>
    <th>Subtotal</th>
</tr>
</thead>
<tbody>
';

while($d = mysqli_fetch_assoc($qdet)) {
    $html .= "
    <tr>
        <td>{$d['descripcion']}</td>
        <td class='center'>{$d['cantidad']}</td>
        <td>{$d['precio_unitario']}</td>
        <td>{$d['subtotal']}</td>
    </tr>";
}

$html .= "
</tbody>
</table>

<p class='section-title'>Totales</p>
<table>
<tr><th>Mano de Obra</th><td>{$data['mano_obra']}</td></tr>
<tr><th>Subtotal</th><td>{$data['subtotal']}</td></tr>
<tr><th>Total</th><td>{$data['total']}</td></tr>
</table>

<p class='section-title'>Observaciones</p>
<p>{$data['observaciones']}</p>

<div class='firma-box'>
    <div class='firma-col'>
        <div class='firma-line'></div>
        <p>Firma del Técnico</p>
    </div>

    <div class='firma-col'>
        <div class='firma-line'></div>
        <p>Firma del Cliente</p>
    </div>
</div>
";

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("presupuesto_$id.pdf", ["Attachment" => false]);
?>
