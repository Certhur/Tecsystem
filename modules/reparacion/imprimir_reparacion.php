<?php
require_once "../../config/database.php";

$id_reparacion = $_GET['id'] ?? 0;

// Consulta con los nombres de campos que confirmaste
$query = mysqli_query($mysqli, "
    SELECT r.id_reparacion,
           r.fecha_reparacion,
           r.observaciones,
           r.estado,
           ot.id_orden,
           cl.cli_razon_social,
           cl.ci_ruc,        
           cl.cli_telefono,
           cl.cli_direccion,
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
    WHERE r.id_reparacion = $id_reparacion
");

$data = mysqli_fetch_assoc($query);

if (!$data) { die("Error: No se encontraron datos para esta reparación."); }

$query_detalles = mysqli_query($mysqli, "
    SELECT p.p_descrip, rd.cantidad
    FROM reparacion_detalles rd
    INNER JOIN productos p ON rd.id_producto = p.id_producto
    WHERE rd.id_reparacion = $id_reparacion
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia #<?php echo $data['id_reparacion']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; color: #333; }
        .wrapper { width: 100%; max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 20px; }
        .header { border-bottom: 2px solid #3c8dbc; margin-bottom: 20px; padding-bottom: 10px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 6px; border: 1px solid #eee; }
        .label { font-weight: bold; background: #f4f4f4; width: 18%; }
        .val { width: 32%; }
        .table-items { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-items th { background: #eee; padding: 8px; border: 1px solid #ddd; text-align: left; }
        .table-items td { padding: 8px; border: 1px solid #ddd; }
        .footer { margin-top: 60px; text-align: center; }
        .linea { border-top: 1px solid #000; width: 200px; margin: 0 auto 5px auto; }
        @media print { .no-print { display: none; } .wrapper { border: none; } }
    </style>
</head>
<body>

<div class="no-print" style="text-align:center; margin-bottom:10px;">
    <button onclick="window.print()" style="padding:10px 20px; background:#3c8dbc; color:white; border:none; border-radius:4px; cursor:pointer;">Imprimir Constancia</button>
    <button onclick="window.close()" style="padding:10px 20px; background:#dd4b39; color:white; border:none; border-radius:4px; margin-left:10px; cursor:pointer;">Cerrar</button>
</div>

<div class="wrapper">
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <strong style="font-size:18px;">SERVICIO TÉCNICO INFORMÁTICO</strong><br>
                    San Lorenzo, Paraguay | Tel: <?php echo $data['cli_telefono']; ?>
                </td>
                <td align="right">
                    <strong style="color: #dd4b39; font-size: 14px;">CONSTANCIA DE REPARACIÓN</strong><br>
                    <strong>Nº <?php echo str_pad($data['id_reparacion'], 6, "0", STR_PAD_LEFT); ?></strong><br>
                    Fecha: <?php echo date('d/m/Y', strtotime($data['fecha_reparacion'])); ?>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Cliente:</td>
            <td class="val"><?php echo $data['cli_razon_social']; ?></td>
            <td class="label">C.I./RUC:</td>
            <td class="val"><?php echo $data['ci_ruc']; ?></td> </tr>
        <tr>
            <td class="label">Equipo:</td>
            <td class="val"><?php echo $data['tipo_descrip'] . " " . $data['equipo_modelo']; ?></td>
            <td class="label">Marca:</td>
            <td class="val"><?php echo $data['marca_descrip']; ?></td> </tr>
        <tr>
            <td class="label">Ref. Orden:</td>
            <td class="val">OT #<?php echo $data['id_orden']; ?></td>
            <td class="label">Técnico:</td>
            <td class="val"><?php echo $data['name_user']; ?></td>
        </tr>
    </table>

    <div style="background:#f9f9f9; padding:15px; border:1px solid #ddd; border-radius: 4px;">
        <strong>INFORME TÉCNICO / TRABAJO REALIZADO:</strong><br><br>
        <?php echo nl2br($data['observaciones']); ?>
    </div>

    <table class="table-items">
        <thead>
            <tr>
                <th>Insumos / Repuestos Utilizados</th>
                <th width="80" style="text-align: center;">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $hay = false;
            while($row = mysqli_fetch_assoc($query_detalles)){ 
                $hay = true;
            ?>
            <tr>
                <td><?php echo $row['p_descrip']; ?></td>
                <td align="center"><?php echo $row['cantidad']; ?></td>
            </tr>
            <?php } if(!$hay) echo "<tr><td colspan='2' align='center'>Sin repuestos registrados.</td></tr>"; ?>
        </tbody>
    </table>

    <p align="right" style="font-size: 14px; margin-top: 20px;">
        <strong>Estado de Reparación:</strong> 
        <span style="border: 1px solid #333; padding: 2px 8px;"><?php echo strtoupper($data['estado']); ?></span>
    </p>

    <table width="100%" class="footer">
        <tr>
            <td width="50%">
                <div class="linea"></div>
                Firma del Técnico
            </td>
            <td width="50%">
                <div class="linea"></div>
                Firma del Cliente (Conforme)
            </td>
        </tr>
    </table>
</div>

</body>
</html>