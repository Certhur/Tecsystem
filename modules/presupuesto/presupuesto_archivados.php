<?php 
include "config/database.php";
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=presupuesto_archivados">Presupuestos Archivados</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-archive icon-title"></i> Presupuestos Archivados
        <a href="?module=presupuesto" class="btn btn-default pull-right">
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
    <th>Diagnóstico</th>
    <th>Cliente</th>
    <th>Equipo</th>
    <th>Modelo</th>
    <th>Total</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
<?php

$q = mysqli_query($mysqli, "
    SELECT p.*, 
           d.id_diagnostico, 
           re.equipo_modelo, 
           cl.cli_razon_social,
           m.marca_descrip,
           te.tipo_descrip
    FROM presupuesto p
    LEFT JOIN diagnostico d ON p.id_diagnostico = d.id_diagnostico
    LEFT JOIN recepcion_equipo re ON d.id_recepcion_equipo = re.id_recepcion_equipo
    LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE p.estado = 'Archivado'
    ORDER BY p.id_presupuesto DESC
");

while($row = mysqli_fetch_assoc($q)){
    echo "
    <tr>
        <td>{$row['id_presupuesto']}</td>
        <td>{$row['fecha_presupuesto']}</td>
        <td>{$row['id_diagnostico']}</td>
        <td>{$row['cli_razon_social']}</td>
        <td>{$row['tipo_descrip']}</td>
        <td>{$row['equipo_modelo']}</td>
        <td>₲ ".number_format($row['total'], 0, ',', '.')."</td>

        <td width='130'>
            <button class='btn btn-success btn-sm desarchivar'
                    data-id='{$row['id_presupuesto']}'>
                <i class='fa fa-undo'></i> Restaurar
            </button>

            <a class='btn btn-default btn-sm'
               href='modules/presupuesto/imprimir_presupuesto.php?id={$row['id_presupuesto']}'
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
// DESARCHIVAR PRESUPUESTO
// =============================
$(".desarchivar").click(function(){
    if(!confirm("¿Restaurar este presupuesto?")) return;

    let id = $(this).data("id");

    $.post("modules/presupuesto/proses.php?accion=desarchivar",
        { id_presupuesto: id },
        function(r){
            if(r.status === "ok"){
                alert("Presupuesto restaurado correctamente");
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
            emptyTable: "No hay presupuestos archivados"
        },
        responsive: true,
        autoWidth: false
    });
});
</script>
