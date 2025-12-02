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

        <a class="btn btn-warning btn-social pull-right" style="margin-right:10px;" href="modules/recepcion_equipo/reporte_recepciones.php" target="_blank">
            <i class="fa fa-print"></i> IMPRIMIR RECEPCIONES
        </a>
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

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

    // ==== COLORES ====
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

    // botón editable
    $badge = "
        <button 
            class='btn btn-$color btn-xs btn-estado'
            data-id='{$row['id_recepcion_equipo']}'
            data-estado='$estado'
            data-toggle='modal'
            data-target='#modalEstado'
        >
            $estado <i class='fa fa-pencil'></i>
        </button>
    ";

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

            <a class='btn btn-default btn-sm'
                href='modules/recepcion_equipo/imprimir_recepcion.php?id={$row['id_recepcion_equipo']}'
                target='_blank'
                title='Imprimir recepción'>
                <i class='fa fa-print'></i>
            </a>
        </td>
    </tr>";
}
?>
</tbody>
</table>

</div></div></div></div></section>

<!-- ==============================
     MODAL CAMBIAR ESTADO
=================================-->
<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="formCambiarEstado">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Cambiar Estado</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="estado_id" name="id_recepcion_equipo">

          <label>Seleccione el estado:</label>
          <select id="estado_valor" name="estado" class="form-control" required>
              <option value="">--Elegir estado--</option>
              <option value="recepcionado">Recepcionado</option>
              <option value="en_diagnostico">En diagnóstico</option>
              <option value="esperando_repuestos">Esperando repuestos</option>
              <option value="pendiente_cliente">Pendiente cliente</option>
              <option value="listo">Listo</option>
              <option value="entregado">Entregado</option>
          </select>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>

      </div>
    </form>
  </div>
</div>

<script>
// ====================================
//   ABRIR MODAL ESTADO
// ====================================
$(document).on("click", ".btn-estado", function() {
    $("#estado_id").val($(this).data("id"));
    $("#estado_valor").val($(this).data("estado"));
});

// ====================================
//     GUARDAR CAMBIO ESTADO (AJAX)
// ====================================
$("#formCambiarEstado").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "modules/recepcion_equipo/proses.php?accion=cambiar_estado",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(r){
            if(r.status === "ok"){
                $("#modalEstado").modal("hide");
                location.reload();
            } else {
                alert("Error al actualizar estado");
            }
        }
    });
});

// ==== ARCHIVAR ====
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
