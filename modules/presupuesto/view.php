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

        <a class="btn btn-warning btn-social pull-right" 
           style="margin-right:10px;" 
           href="?module=presupuesto_archivados">
           <i class="fa fa-archive"></i> Archivados
        </a>
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<h2>Lista de Presupuestos</h2>

<table id="dataTables1" class="table table-bordered table-striped table-hover">
<thead>
<tr>
    <th class="center">ID</th>
    <th class="center">Fecha</th>
    <th class="center">Cliente</th>
    <th class="center">Equipo</th>
    <th class="center">Modelo</th>
    <th class="center">Total</th>
    <th class="center">Estado</th>
    <th class="center">Acciones</th>
</tr>
</thead>

<tbody>
<?php
$query = mysqli_query($mysqli,"
    SELECT p.*,
           dg.id_diagnostico,
           re.equipo_modelo,
           re.equipo_descripcion,
           cl.cli_razon_social,
           m.marca_descrip,
           te.tipo_descrip
    FROM presupuesto p
    LEFT JOIN diagnostico dg       ON p.id_diagnostico = dg.id_diagnostico
    LEFT JOIN recepcion_equipo re  ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    LEFT JOIN clientes cl          ON re.id_cliente = cl.id_cliente
    LEFT JOIN marcas m             ON re.id_marca = m.id_marca
    LEFT JOIN tipo_equipo te       ON re.id_tipo_equipo = te.id_tipo_equipo
    WHERE p.estado <> 'Archivado'
    ORDER BY p.id_presupuesto DESC
");

while ($row = mysqli_fetch_assoc($query)) {

    $estado = $row['estado'];

    $badgeColor = [
        "Pendiente" => "default",
        "Enviado"   => "info",
        "Aprobado"  => "success",
        "Rechazado" => "danger",
    ];
    $color = $badgeColor[$estado] ?? "default";

    $badge = "
        <button 
            class='btn btn-$color btn-xs btn-estado'
            data-id='{$row['id_presupuesto']}'
            data-estado='$estado'
            data-toggle='modal'
            data-target='#modalEstado'
        >
            $estado <i class='fa fa-pencil'></i>
        </button>
    ";

    echo "
    <tr>
        <td class='center'>{$row['id_presupuesto']}</td>
        <td class='center'>{$row['fecha_presupuesto']}</td>
        <td class='center'>{$row['cli_razon_social']}</td>
        <td class='center'>{$row['tipo_descrip']}</td>
        <td class='center'>{$row['equipo_modelo']}</td>
        <td class='center'>".number_format($row['total'], 2, ',', '.')."</td>
        <td class='center'>$badge</td>

        <td class='center' width='180'>
            <a class='btn btn-primary btn-sm'
               href='?module=form_presupuesto&form=edit&id={$row['id_presupuesto']}'>
               <i class='glyphicon glyphicon-edit'></i>
            </a>

            <button class='btn btn-warning btn-sm archivar'
                    data-id='{$row['id_presupuesto']}'>
                <i class='fa fa-archive'></i>
            </button>

            <a class='btn btn-default btn-sm'
                href='modules/presupuesto/imprimir_presupuesto.php?id={$row['id_presupuesto']}'
                target='_blank'
                title='Imprimir presupuesto'>
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
          <h4 class="modal-title">Cambiar Estado del Presupuesto</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="estado_id" name="id_presupuesto">

          <label>Seleccione el estado:</label>
          <select id="estado_valor" name="estado" class="form-control" required>
              <option value="">--Elegir estado--</option>
              <option value="Pendiente">Pendiente</option>
              <option value="Enviado">Enviado</option>
              <option value="Aprobado">Aprobado</option>
              <option value="Rechazado">Rechazado</option>
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
// ===============================
// ABRIR MODAL ESTADO
// ===============================
$(document).on("click", ".btn-estado", function() {
    $("#estado_id").val($(this).data("id"));
    $("#estado_valor").val($(this).data("estado"));
});

// ===============================
// GUARDAR CAMBIO ESTADO (AJAX)
// ===============================
$("#formCambiarEstado").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "modules/presupuesto/proses.php?accion=cambiar_estado",
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

// ===============================
// ARCHIVAR
// ===============================
$(".archivar").click(function(){
    if(!confirm("¿Archivar este presupuesto?")) return;

    let id = $(this).data("id");

    $.post("modules/presupuesto/proses.php?accion=archivar",
        { id_presupuesto: id },
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
