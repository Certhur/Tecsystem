<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=diagnostico">Diagnóstico de Equipos</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-stethoscope icon-title"></i> Diagnóstico de Equipos

        <a class="btn btn-primary btn-social pull-right" 
           href="?module=form_diagnostico&form=add">
           <i class="fa fa-plus"></i> Agregar
        </a>

        <a class="btn btn-warning btn-social pull-right" 
           style="margin-right:10px;" 
           href="?module=diagnostico_archivados">
           <i class="fa fa-archive"></i> Archivados
        </a>

        <a class="btn btn-warning btn-social pull-right" 
           style="margin-right:10px;"
           href="modules/diagnostico/reporte_diagnosticos.php"
           target="_blank">
           <i class="fa fa-print"></i> IMPRIMIR DIAGNÓSTICOS
        </a>
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<h2>Lista de Diagnosticos</h2>

<table id="dataTables1" class="table table-bordered table-striped table-hover">
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
    <th>Estado</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
<?php
include "config/database.php";

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
    WHERE dg.estado_diagnostico <> 'Archivado'
    ORDER BY dg.id_diagnostico DESC
");

while ($row = mysqli_fetch_assoc($q)) {

    // ==== COLORES ====
    $estado = $row["estado_diagnostico"];
    $colores = [
        "Pendiente" => "default",
        "En Proceso" => "warning",
        "Finalizado" => "success",
        "Cancelado" => "danger"
    ];
    $color = $colores[$estado] ?? "default";

    // botón editable del estado
    $badge = "
        <button 
            class='btn btn-$color btn-xs btn-estado'
            data-id='{$row['id_diagnostico']}'
            data-estado='$estado'
            data-toggle='modal'
            data-target='#modalEstado'
        >
            $estado <i class='fa fa-pencil'></i>
        </button>
    ";

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
        <td class='center'>$badge</td>

        <td class='center' width='150'>
            <a class='btn btn-default btn-sm'
               href='modules/diagnostico/imprimir_diagnostico.php?id={$row['id_diagnostico']}'
               target='_blank'>
                <i class='fa fa-print'></i>
            </a>

            <button class='btn btn-warning btn-sm archivar'
                    data-id='{$row['id_diagnostico']}'>
                <i class='fa fa-archive'></i>
            </button>
        </td>
    </tr>";
}
?>
</tbody>
</table>

</div></div></div></div></section>

<!-- ======================
   MODAL CAMBIAR ESTADO
========================= -->
<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="formCambiarEstado">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Cambiar Estado</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="estado_id" name="id_diagnostico">

          <label>Seleccione el estado:</label>
          <select id="estado_valor" name="estado_diagnostico" class="form-control" required>
              <option value="">--Elegir estado--</option>
              <option value="Pendiente">Pendiente</option>
              <option value="En Proceso">En Proceso</option>
              <option value="Finalizado">Finalizado</option>
              <option value="Cancelado">Cancelado</option>
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
// Abrir modal estado
$(document).on("click", ".btn-estado", function() {
    $("#estado_id").val($(this).data("id"));
    $("#estado_valor").val($(this).data("estado"));
});

// Guardar estado
$("#formCambiarEstado").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "modules/diagnostico/proses.php?accion=cambiar_estado",
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

// ARCHIVAR
$(".archivar").click(function(){
    if(!confirm("¿Archivar este diagnóstico?")) return;

    let id = $(this).data("id");

    $.post("modules/diagnostico/proses.php?accion=archivar",
        { id_diagnostico: id },
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
