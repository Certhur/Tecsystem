<?php
ob_start();
require "../../config/database.php";

$query = mysqli_query($mysqli,"
    SELECT re.*, cl.cli_razon_social, m.marca_descrip, te.tipo_descrip
    FROM recepcion_equipo re
    LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE re.estado <> 'archivado'
    ORDER BY re.id_recepcion_equipo DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Recepciones</title>
    <style>
        body { font-family: Arial; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top:10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background: #eee; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>REPORTE GENERAL DE RECEPCIONES</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Marca</th>
            <th>Equipo</th>
            <th>Modelo</th>
            <th>Descripción</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= $row['id_recepcion_equipo'] ?></td>
            <td><?= $row['fecha_recepcion'] ?></td>
            <td><?= $row['cli_razon_social'] ?></td>
            <td><?= $row['marca_descrip'] ?></td>
            <td><?= $row['tipo_descrip'] ?></td>
            <td><?= $row['equipo_modelo'] ?></td>
            <td><?= $row['equipo_descripcion'] ?></td>
            <td><?= $row['estado'] ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</body>
</html>

<?php
$html = ob_get_clean();

require "../../librerias/dompdf/autoload.inc.php";
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "landscape");
$dompdf->render();
$dompdf->stream("reporte_recepciones.pdf", ["Attachment" => false]);
