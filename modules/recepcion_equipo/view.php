<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=recepcion_equipo">Recepción de Equipos</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-folder icon-title"></i> Recepción de Equipos

        <a class="btn btn-primary btn-social pull-right" href="?module=form_recepcion_equipo&form=add">
           <i class="fa fa-plus"></i> Agregar
        </a>

        <a class="btn btn-warning btn-social pull-right" style="margin-right:10px;" href="?module=recepcion_archivados">
           <i class="fa fa-archive"></i> Archivados
        </a>
    </h1>
</section>


<section class="content">
<div class="row">
<div class="col-md-12">

<div class="box box-primary">
<div class="box-body">

<h2>Lista de Recepciones</h2>

<table id="dataTables1" class="table table-bordered table-striped table-hover">
<thead>
<tr>
    <th class="center">ID</th>
    <th class="center">Fecha</th>
    <th class="center">Cliente</th>
    <th class="center">Marca</th>
    <th class="center">Equipo</th>
    <th class="center">Modelo</th>
    <th class="center">Descripción</th>
    <th class="center">Estado</th>
    <th class="center">Acciones</th>
</tr>
</thead>

<tbody>
<?php
include "config/database.php";

$q = mysqli_query($mysqli,"
    SELECT re.*, cl.cli_razon_social, m.marca_descrip, te.tipo_descrip
    FROM recepcion_equipo re
    LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE re.estado <> 'archivado'
    ORDER BY re.id_recepcion_equipo DESC
");

while ($row = mysqli_fetch_assoc($q)) {

    // ==== COLORES IGUAL QUE DIAGNÓSTICO ====
    $estado = $row["estado"];
    $badgeColor = [
        "recepcionado"       => "primary",
        "en_diagnostico"     => "info",
        "esperando_repuestos"=> "warning",
        "pendiente_cliente"  => "danger",
        "listo"              => "success",
        "entregado"          => "success"
    ];
    $color = $badgeColor[$estado] ?? "default";
    $badge = "<span class='label label-$color'>$estado</span>";

    echo "
    <tr>
        <td class='center'>{$row['id_recepcion_equipo']}</td>
        <td class='center'>{$row['fecha_recepcion']}</td>
        <td class='center'>{$row['cli_razon_social']}</td>
        <td class='center'>{$row['marca_descrip']}</td>
        <td class='center'>{$row['tipo_descrip']}</td>
        <td class='center'>{$row['equipo_modelo']}</td>
        <td class='center'>{$row['equipo_descripcion']}</td>
        <td class='center'>$badge</td>

        <td class='center' width='160'>
            <a class='btn btn-primary btn-sm'
               href='?module=form_recepcion_equipo&form=edit&id={$row['id_recepcion_equipo']}'>
               <i class='glyphicon glyphicon-edit'></i>
            </a>

            <button class='btn btn-warning btn-sm archivar'
                    data-id='{$row['id_recepcion_equipo']}'>
                <i class='fa fa-archive'></i>
            </button>

            <a class='btn btn-danger btn-sm'
               onclick=\"return confirm('¿Eliminar registro?')\"
               href='modules/recepcion_equipo/proses.php?accion=eliminar&id_recepcion_equipo={$row['id_recepcion_equipo']}'>
               <i class='glyphicon glyphicon-trash'></i>
            </a>
        </td>
    </tr>";
}
?>
</tbody>
</table>

</div></div></div></div></section>

<script>
// ==== ARCHIVAR (IGUAL QUE DIAGNÓSTICO) ====
$(".archivar").click(function(){
    if(!confirm("¿Archivar esta recepción?")) return;

    let id = $(this).data("id");

    $.post("modules/recepcion_equipo/proses.php?accion=archivar",
        { id_recepcion_equipo: id },
        function(r){
            if(r.status === "ok"){
                alert("Archivado correctamente");
                location.reload();
            } else {
                alert("Error al archivar");
            }
        }, "json"
    );
});
</script>
