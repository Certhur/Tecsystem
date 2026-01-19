<?php 
include "config/database.php";
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="?module=reparacion">Reparaciones</a></li>
        <li class="active">Archivados</li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-archive icon-title"></i> Historial de Reparaciones Archivadas
        <a href="?module=reparacion" class="btn btn-default pull-right">
            <i class="fa fa-arrow-left"></i> Volver a la Lista
        </a>
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<table id="archivadosTable" class="table table-bordered table-striped table-hover">
<thead>
<tr>
    <th class="center" width="50">ID</th>
    <th class="center" width="100">Fecha</th>
    <th class="center">Ref. OT</th>
    <th class="center">Cliente</th>
    <th class="center">Equipo / Modelo</th>
    <th class="center">Técnico</th>
    <th class="center" width="120">Acciones</th>
</tr>
</thead>

<tbody>
<?php
// Consulta: TRAER SOLO LAS ARCHIVADAS
$query = mysqli_query($mysqli,"
    SELECT r.id_reparacion,
           r.fecha_reparacion,
           r.id_orden,
           cl.cli_razon_social,
           re.equipo_modelo,
           te.tipo_descrip,
           u.name_user
    FROM reparacion r
    INNER JOIN orden_trabajo ot    ON r.id_orden = ot.id_orden
    INNER JOIN presupuesto p       ON ot.id_presupuesto = p.id_presupuesto
    INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
    INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
    INNER JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
    LEFT JOIN usuarios u           ON r.id_user = u.id_user
    WHERE r.estado = 'Archivado'
    ORDER BY r.id_reparacion DESC
");

while ($row = mysqli_fetch_assoc($query)) {
    $fecha   = date('d/m/Y', strtotime($row['fecha_reparacion']));
    $tecnico = !empty($row['name_user']) ? $row['name_user'] : 'Sin asignar';

    echo "
    <tr>
        <td class='center'>{$row['id_reparacion']}</td>
        <td class='center'>$fecha</td>
        <td class='center'>OT #{$row['id_orden']}</td>
        <td>{$row['cli_razon_social']}</td>
        <td>{$row['tipo_descrip']} - {$row['equipo_modelo']}</td>
        <td class='center'>{$tecnico}</td>

        <td class='center'>
            <button class='btn btn-success btn-sm desarchivar'
                    data-id='{$row['id_reparacion']}' 
                    title='Restaurar a la lista principal'>
                <i class='fa fa-undo'></i> Restaurar
            </button>

            <a class='btn btn-default btn-sm'
                href='modules/reparacion/imprimir_reparacion.php?id={$row['id_reparacion']}'
                target='_blank'
                title='Imprimir Constancia'>
                <i class='fa fa-print'></i>
            </a>
        </td>
    </tr>";
}
?>
</tbody>
</table>

</div></div></div></div></section>

<script>
// 1. ACTIVAR DATATABLES
$(document).ready(function(){
    $('#archivadosTable').DataTable({
        language: {
            url: "assets/plugins/datatables/es_es.json",
            emptyTable: "No hay reparaciones en el archivo."
        },
        responsive: true,
        autoWidth: false
    });
});

// 2. FUNCIÓN DESARCHIVAR (RESTAURAR)
$(".desarchivar").click(function(){
    if(!confirm("¿Desea restaurar esta reparación a la lista de Activos?")) return;

    let id = $(this).data("id");

    $.post("modules/reparacion/proses.php", 
        { 
            accion: "desarchivar", 
            id_reparacion: id 
        },
        function(r){
            if(r.status === "ok"){
                alert("Reparación restaurada correctamente.");
                window.location.reload();
            } else {
                alert("Error al restaurar.");
            }
        }, "json"
    );
});
</script>