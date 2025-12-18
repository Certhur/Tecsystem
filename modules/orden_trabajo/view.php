<?php 
include "config/database.php";
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=orden_trabajo">Órdenes de Trabajo</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-wrench icon-title"></i> Órdenes de Trabajo

        <a class="btn btn-primary btn-social pull-right" 
           href="?module=form_orden_trabajo&form=add"
           title="Agregar" data-toggle="tooltip">
           <i class="fa fa-plus"></i> Generar Orden
        </a>

        <a class="btn btn-warning btn-social pull-right" 
           style="margin-right:10px;" 
           href="?module=orden_trabajo_archivados"
           title="Ver Archivados" data-toggle="tooltip">
           <i class="fa fa-archive"></i> Archivados
        </a>

         <a class="btn btn-warning btn-social pull-right" 
            style="margin-right:10px;" 
            href="modules/orden_trabajo/reporte_orden_trabajo.php" 
            target="_blank"
            title="Imprimir Reporte" data-toggle="tooltip">
            <i class="fa fa-print"></i> IMPRIMIR LISTA
        </a>
    </h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12">

<?php 
if (empty($_GET['alert'])) {
    echo "";
} elseif ($_GET['alert'] == 1) {
    echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-check'></i> Exito!</h4> Orden generada correctamente.
          </div>";
} elseif ($_GET['alert'] == 2) {
    echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-check'></i> Exito!</h4> Orden actualizada correctamente.
          </div>";
} elseif ($_GET['alert'] == 4) {
    echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Error!</h4> No se pudo realizar la operación.
          </div>";
}
?>

<div class="box box-primary"><div class="box-body">

<h2>Lista de Órdenes de Trabajo</h2>

<table id="dataTables1" class="table table-bordered table-striped table-hover">
<thead>
<tr>
    <th class="center">ID</th>
    <th class="center">Ref. Presup.</th>
    <th class="center">Fecha Inicio</th>
    <th class="center">Cliente</th>
    <th class="center">Equipo</th>
    <th class="center">Técnico</th>
    <th class="center">Estado</th>
    <th class="center">Acciones</th>
</tr>
</thead>

<tbody>
<?php
// Consulta Principal: OCULTAMOS LOS ARCHIVADOS (estado_ot <> 'Archivado')
$query = mysqli_query($mysqli,"
    SELECT ot.*, 
           p.id_presupuesto,
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
    WHERE ot.estado_ot <> 'Archivado' 
    ORDER BY ot.id_orden DESC
");

while ($row = mysqli_fetch_assoc($query)) {

    $estado = $row['estado_ot'];
    $fecha  = date('d/m/Y', strtotime($row['fecha_inicio']));
    $tecnico = !empty($row['name_user']) ? $row['name_user'] : '<span class="text-muted">--</span>';

    // Colores según estado de la Orden
    $badgeColor = [
        "Pendiente"  => "warning",
        "En Proceso" => "primary",
        "Finalizada" => "success",
        "Entregada"  => "info",
        "Cancelada"  => "danger"
    ];
    $color = $badgeColor[$estado] ?? "default";

    // Botón estilo Badge para abrir Modal
    $badge = "
        <button 
            class='btn btn-$color btn-xs btn-estado'
            data-id='{$row['id_orden']}'
            data-estado='$estado'
            data-toggle='modal'
            data-target='#modalEstado'
            title='Cambiar Estado'
        >
            $estado <i class='fa fa-pencil'></i>
        </button>
    ";

    echo "
    <tr>
        <td class='center'>{$row['id_orden']}</td>
        <td class='center'>#{$row['id_presupuesto']}</td>
        <td class='center'>$fecha</td>
        <td class='center'>{$row['cli_razon_social']}</td>
        <td class='center'>{$row['tipo_descrip']} - {$row['equipo_modelo']}</td>
        <td class='center'>{$tecnico}</td>
        <td class='center'>$badge</td>

        <td class='center' width='150'>
            <a class='btn btn-primary btn-sm'
               href='?module=form_orden_trabajo&form=edit&id={$row['id_orden']}'
               title='Editar'>
               <i class='glyphicon glyphicon-edit'></i>
            </a>

            <button class='btn btn-danger btn-sm archivar'
                    data-id='{$row['id_orden']}' title='Archivar / Eliminar de la lista'>
                <i class='fa fa-archive'></i>
            </button>

            <a class='btn btn-default btn-sm'
                href='modules/orden_trabajo/imprimir_orden.php?id={$row['id_orden']}'
                target='_blank'
                title='Imprimir Orden'>
                <i class='fa fa-print'></i>
            </a>
        </td>
    </tr>";
}
?>
</tbody>
</table>

</div></div></div></div></section>

<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="formCambiarEstado">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Cambiar Estado de Orden</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="estado_id" name="id_orden">
          <input type="hidden" name="accion" value="cambiar_estado"> 

          <label>Seleccione el estado:</label>
          <select id="estado_valor" name="estado" class="form-control" required>
              <option value="">--Elegir estado--</option>
              <option value="Pendiente">Pendiente</option>
              <option value="En Proceso">En Proceso</option>
              <option value="Finalizada">Finalizada</option>
              <option value="Entregada">Entregada</option>
              <option value="Cancelada">Cancelada</option>
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
// 1. ABRIR MODAL ESTADO
// ===============================
$(document).on("click", ".btn-estado", function() {
    $("#estado_id").val($(this).data("id"));
    $("#estado_valor").val($(this).data("estado"));
});

// ===============================
// 2. GUARDAR CAMBIO ESTADO (AJAX)
// ===============================
$("#formCambiarEstado").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "modules/orden_trabajo/proses.php",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(r){
            if(r.status === "ok"){
                $("#modalEstado").modal("hide");
                location.reload();
            } else {
                alert("Error al actualizar estado: " + (r.msg || "Desconocido"));
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("Error de conexión con el servidor");
        }
    });
});

// ===============================
// 3. ARCHIVAR (Acción en proses.php)
// ===============================
$(".archivar").click(function(){
    if(!confirm("¿Desea ARCHIVAR esta orden de trabajo? \nDesaparecerá de esta lista.")) return;

    let id = $(this).data("id");

    $.post("modules/orden_trabajo/proses.php",
        { 
            accion: "archivar", 
            id_orden: id 
        },
        function(r){
            if(r.status === "ok"){
                alert("Orden archivada correctamente");
                location.reload();
            } else {
                alert("Error al archivar");
            }
        }, "json"
    );
});
</script>