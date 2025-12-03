<?php 
include "config/database.php";
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=diagnostico_archivados">Diagnósticos Archivados</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-archive icon-title"></i> Diagnósticos Archivados
        <a href="?module=diagnostico" class="btn btn-default pull-right">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<table id="archivadosTable" class="table table-bordered table-striped">
<thead>
<tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Recepción</th>
    <th>Cliente</th>
    <th>Marca</th>
    <th>Tipo</th>
    <th>Modelo</th>
    <th>Descripcion</th>
    <th>Falla</th>
    <th>Causa</th>
    <th>Solución</th>
    <th>Observaciones</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
<?php
$q = mysqli_query($mysqli,"
    SELECT dg.*, 
           re.equipo_modelo,
           re.equipo_descripcion,
           cl.cli_razon_social,
           m.marca_descrip,
           te.tipo_descrip
    FROM diagnostico dg
    LEFT JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE dg.estado_diagnostico = 'Archivado'
    ORDER BY dg.id_diagnostico DESC
");

while ($row = mysqli_fetch_assoc($q)) {
    echo "
    <tr>
        <td>{$row['id_diagnostico']}</td>
        <td>{$row['fecha_diagnostico']}</td>
        <td>{$row['id_recepcion_equipo']}</td>
        <td>{$row['cli_razon_social']}</td>
        <td>{$row['marca_descrip']}</td>
        <td>{$row['tipo_descrip']}</td>
        <td>{$row['equipo_modelo']}</td>
        <td>{$row['equipo_descripcion']}</td>
        <td>{$row['falla_diagnostico']}</td>
        <td>{$row['causa_diagnostico']}</td>
        <td>{$row['solucion_diagnostico']}</td>
        <td>{$row['observaciones']}</td>

        <td width='80'>
            <button class='btn btn-success btn-sm desarchivar'
                data-id='{$row['id_diagnostico']}'>
                <i class='fa fa-undo'></i> Restaurar
            </button>
        </td>
    </tr>";
}
?>
</tbody>
</table>

</div></div></div></div></section>

<script>
// =============================
// RESTAURAR
// =============================
$(".desarchivar").click(function(){
    if(!confirm("¿Restaurar diagnóstico?")) return;

    let id = $(this).data("id");

    $.post("modules/diagnostico/proses.php?accion=desarchivar",
        { id_diagnostico: id },
        function(r){
            if(r.status === "ok"){
                alert("Restaurado correctamente");
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
$(document).ready(function() {
    $('#archivadosTable').DataTable({
        language: {
            url: "assets/plugins/datatables/es_es.json",
            emptyTable: "No hay diagnósticos archivados"
        },
        responsive: true,
        autoWidth: false
    });
});
</script>
