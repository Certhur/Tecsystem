<?php 
include "config/database.php";
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=orden_trabajo_archivados">Órdenes Archivadas</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-archive icon-title"></i> Órdenes de Trabajo Archivadas
        <a href="?module=orden_trabajo" class="btn btn-default pull-right">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<table id="archivadosTable" class="table table-bordered table-striped table-hover">
<thead>
<tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Ref. Presup.</th>
    <th>Cliente</th>
    <th>Equipo</th>
    <th>Técnico</th>
    <th>Total</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
<?php

// CONSULTA CORREGIDA: Busca estado 'Archivado'
$q = mysqli_query($mysqli, "
    SELECT ot.*, 
           p.id_presupuesto,
           p.total,
           cl.cli_razon_social,
           re.equipo_modelo,
           te.tipo_descrip,
           u.name_user
    FROM orden_trabajo ot
    INNER JOIN presupuesto p       ON ot.id_presupuesto = p.id_presupuesto
    INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
    INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
    INNER JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
    LEFT JOIN usuarios u           ON ot.id_user = u.id_user
    WHERE ot.estado_ot = 'Archivado'  
    ORDER BY ot.id_orden DESC
");

while($row = mysqli_fetch_assoc($q)){
    $fecha = date('d/m/Y', strtotime($row['fecha_inicio']));
    $tecnico = !empty($row['name_user']) ? $row['name_user'] : 'Sin asignar';

    echo "
    <tr>
        <td>{$row['id_orden']}</td>
        <td>{$fecha}</td>
        <td>#{$row['id_presupuesto']}</td>
        <td>{$row['cli_razon_social']}</td>
        <td>{$row['tipo_descrip']} - {$row['equipo_modelo']}</td>
        <td>{$tecnico}</td>
        <td>₲ ".number_format($row['total'], 0, ',', '.')."</td>

        <td width='130'>
            <button class='btn btn-success btn-sm desarchivar'
                    data-id='{$row['id_orden']}'>
                <i class='fa fa-undo'></i> Restaurar
            </button>

            <a class='btn btn-default btn-sm'
               href='modules/orden_trabajo/imprimir_orden.php?id={$row['id_orden']}'
               target='_blank'>
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
// =============================
// DESARCHIVAR (RESTAURAR)
// =============================
$(".desarchivar").click(function(){
    if(!confirm("¿Restaurar esta Orden de Trabajo a estado Pendiente?")) return;

    let id = $(this).data("id");

    $.post("modules/orden_trabajo/proses.php",
        { 
            accion: "desarchivar", // Usamos la acción específica 'desarchivar'
            id_orden: id
        },
        function(r){
            if(r.status === "ok"){
                alert("Orden restaurada correctamente");
                window.location.reload();
            } else {
                alert("Error al restaurar");
            }
        }, "json"
    );
});
</script>

<script>
// =============================
// ACTIVAR DATATABLES
// =============================
$(document).ready(function(){
    $('#archivadosTable').DataTable({
        language: {
            url: "assets/plugins/datatables/es_es.json",
            emptyTable: "No hay órdenes archivadas"
        },
        responsive: true,
        autoWidth: false
    });
});
</script>