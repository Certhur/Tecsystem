<?php
include "config/database.php";
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=recepcion_archivados">Archivados</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-archive icon-title"></i> Recepciones Archivadas
        <a href="?module=recepcion_equipo" class="btn btn-default pull-right">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<table id="dataTables1" class="table table-bordered table-striped">
<thead>
<tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Cliente</th>
    <th>Marca</th>
    <th>Equipo</th>
    <th>Modelo</th>
    <th>Descripción</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
<?php
$q = mysqli_query($mysqli,"
    SELECT re.*, cl.cli_razon_social, m.marca_descrip, te.tipo_descrip
    FROM recepcion_equipo re
    LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE re.estado = 'archivado'
    ORDER BY re.id_recepcion_equipo DESC
");

while($row = mysqli_fetch_assoc($q)){
    echo "
    <tr>
        <td>{$row['id_recepcion_equipo']}</td>
        <td>{$row['fecha_recepcion']}</td>
        <td>{$row['cli_razon_social']}</td>
        <td>{$row['marca_descrip']}</td>
        <td>{$row['tipo_descrip']}</td>
        <td>{$row['equipo_modelo']}</td>
        <td>{$row['equipo_descripcion']}</td>

        <td width='80'>
            <button class='btn btn-success btn-sm desarchivar'
                data-id='{$row['id_recepcion_equipo']}'>
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
$(".desarchivar").click(function(){
    if(!confirm("¿Desarchivar recepción?")) return;

    let id = $(this).data("id");

    $.post("modules/recepcion_equipo/proses.php?accion=desarchivar",
        { id_recepcion_equipo: id },
        function(r){
            if(r.status === "ok"){
                alert("Restaurado correctamente");
                location.reload();
            } else {
                alert("Error al restaurar");
            }
        }, "json"
    );
});
</script>
