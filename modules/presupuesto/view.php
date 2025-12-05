<?php
include "config/database.php";
?>
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=presupuesto">Presupuestos</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-file-text-o icon-title"></i> Presupuestos

        <a class="btn btn-primary btn-social pull-right" 
           href="?module=form_presupuesto&form=add">
           <i class="fa fa-plus"></i> Agregar
        </a>

        <!-- Si luego quieres reporte general, aquí iría el botón:
        <a class="btn btn-warning btn-social pull-right" style="margin-right:10px;"
           href="modules/presupuesto/reporte_presupuestos.php" target="_blank">
            <i class="fa fa-print"></i> IMPRIMIR PRESUPUESTOS
        </a>
        -->
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<?php
// Mensajes tipo recepción / diagnóstico
if (!empty($_GET['alert'])) {
    if ($_GET['alert'] == 1) {
        echo "<div class='alert alert-success'>Presupuesto registrado correctamente</div>";
    } elseif ($_GET['alert'] == 2) {
        echo "<div class='alert alert-success'>Presupuesto actualizado correctamente</div>";
    } elseif ($_GET['alert'] == 3) {
        echo "<div class='alert alert-success'>Presupuesto eliminado correctamente</div>";
    } elseif ($_GET['alert'] == 4) {
        echo "<div class='alert alert-danger'>Error al realizar la operación</div>";
    }
}
?>

<h2>Lista de Presupuestos</h2>

<table id="dataTables1" class="table table-bordered table-striped table-hover">
<thead>
<tr>
    <th class="center">ID</th>
    <th class="center">Fecha</th>
    <th class="center">Cliente</th>
    <th class="center">Equipo</th>
    <th class="center">Modelo</th>
    <th class="center">Estado</th>
    <th class="center">Total</th>
    <th class="center">Acciones</th>
</tr>
</thead>

<tbody>
<?php
$q = mysqli_query($mysqli, "
    SELECT p.*,
           dg.id_diagnostico,
           re.equipo_modelo,
           re.equipo_descripcion,
           m.marca_descrip,
           te.tipo_descrip,
           cl.cli_razon_social
    FROM presupuesto p
    LEFT JOIN diagnostico dg      ON p.id_diagnostico      = dg.id_diagnostico
    LEFT JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    LEFT JOIN clientes cl         ON re.id_cliente         = cl.id_cliente
    LEFT JOIN marcas m            ON re.id_marca           = m.id_marca
    LEFT JOIN tipo_equipo te      ON re.id_tipo_equipo     = te.id_tipo_equipo
    ORDER BY p.id_presupuesto DESC
");

while ($row = mysqli_fetch_assoc($q)) {

    // Colores para estado del presupuesto
    $estado = $row['estado'];
    $badgeColor = [
        'Pendiente' => 'warning',
        'Enviado'   => 'info',
        'Aprobado'  => 'success',
        'Rechazado' => 'danger'
    ];
    $color = $badgeColor[$estado] ?? 'default';
    $badge = "<span class='label label-$color'>$estado</span>";

    // Texto de equipo: Tipo + Marca
    $equipo_txt = trim($row['tipo_descrip'] . ' ' . $row['marca_descrip']);

    echo "
    <tr>
        <td class='center'>{$row['id_presupuesto']}</td>
        <td class='center'>{$row['fecha_presupuesto']}</td>
        <td class='center'>{$row['cli_razon_social']}</td>
        <td class='center'>{$equipo_txt}</td>
        <td class='center'>{$row['equipo_modelo']}</td>
        <td class='center'>$badge</td>
        <td class='center'>" . number_format($row['total'], 2, ',', '.') . "</td>
        <td class='center' width='160'>
            <a class='btn btn-primary btn-sm'
               href='?module=form_presupuesto&form=edit&id={$row['id_presupuesto']}'>
               <i class='glyphicon glyphicon-edit'></i>
            </a>

            <!-- Si quieres borrar:
            <a class='btn btn-danger btn-sm'
               onclick=\"return confirm('¿Eliminar presupuesto?')\"
               href='modules/presupuesto/proses.php?accion=eliminar&id_presupuesto={$row['id_presupuesto']}'>
               <i class='glyphicon glyphicon-trash'></i>
            </a>
            -->

            <!-- Botón imprimir presupuesto (cuando lo tengas) -->
            <!--
            <a class='btn btn-default btn-sm'
                href='modules/presupuesto/imprimir_presupuesto.php?id={$row['id_presupuesto']}'
                target='_blank'
                title='Imprimir Presupuesto'>
                <i class='fa fa-print'></i>
            </a>
            -->
        </td>
    </tr>";
}
?>
</tbody>
</table>

</div></div></div></div></section>
