<?php 
include "config/database.php";
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=reparacion">Reparaciones</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-cogs icon-title"></i> Gestión de Reparaciones

        <a class="btn btn-primary btn-social pull-right" 
           href="?module=form_reparacion&form=add"
           title="Registrar Nueva Reparación" data-toggle="tooltip">
           <i class="fa fa-plus"></i> Registrar
        </a>

        <a class="btn btn-warning btn-social pull-right" 
           style="margin-right:10px;" 
           href="?module=reparacion_archivados"
           title="Ver Historial Archivado" data-toggle="tooltip">
           <i class="fa fa-archive"></i> Archivados
        </a>

        <a class="btn btn-warning btn-social pull-right" 
            style="margin-right:10px;" 
            href="modules/reparacion/reporte_reparacion.php" 
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
            <h4><i class='icon fa fa-check'></i> Éxito!</h4> Reparación registrada y stock descontado.
          </div>";
} elseif ($_GET['alert'] == 2) {
    echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-check'></i> Éxito!</h4> Estado actualizado correctamente.
          </div>";
} elseif ($_GET['alert'] == 3) {
    echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-check'></i> Éxito!</h4> Reparación archivada.
          </div>";
} elseif ($_GET['alert'] == 4) {
    echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Error!</h4> No se pudo realizar la operación.
          </div>";
}
?>

<div class="box box-primary"><div class="box-body">

<h2>Historial de Reparaciones Activas</h2>

<table id="dataTables1" class="table table-bordered table-striped table-hover">
<thead>
<tr>
    <th class="center" width="50">ID</th>
    <th class="center" width="100">Fecha</th>
    <th class="center" width="100">Orden Trabajo</th>
    <th class="center">Cliente</th>
    <th class="center">Equipo / Modelo</th>
    <th class="center">Técnico</th>
    <th class="center" width="100">Estado</th>
    <th class="center" width="120">Acciones</th>
</tr>
</thead>

<tbody>
<?php
// CONSULTA PRINCIPAL
// Unimos Reparacion -> OT -> Presupuesto -> Diagnostico -> Recepcion -> Cliente/Equipo
$query = mysqli_query($mysqli,"
    SELECT r.id_reparacion,
           r.fecha_reparacion,
           r.id_orden,
           r.estado,
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
    WHERE r.estado <> 'Archivado'
    ORDER BY r.id_reparacion DESC
");

while ($row = mysqli_fetch_assoc($query)) {
    $fecha  = date('d/m/Y', strtotime($row['fecha_reparacion']));
    $tecnico = !empty($row['name_user']) ? $row['name_user'] : 'Sin asignar';
    $estado = $row['estado'];

    // LÓGICA DE COLORES PARA LOS ESTADOS
    $badgeColor = "default";
    if($estado == "En Proceso")         $badgeColor = "primary"; // Azul
    if($estado == "Esperando Repuesto") $badgeColor = "warning"; // Naranja
    if($estado == "Finalizada")         $badgeColor = "success"; // Verde
    if($estado == "Entregada")          $badgeColor = "info";    // Celeste
    if($estado == "Anulado")            $badgeColor = "danger";  // Rojo

    // Botón Badge (Click para cambiar estado)
    $badge = "
        <button 
            class='btn btn-$badgeColor btn-xs btn-estado'
            data-id='{$row['id_reparacion']}'
            data-estado='$estado'
            data-toggle='modal'
            data-target='#modalEstado'
            title='Actualizar Estado'
        >
            $estado <i class='fa fa-pencil'></i>
        </button>
    ";

    echo "
    <tr>
        <td class='center'>{$row['id_reparacion']}</td>
        <td class='center'>$fecha</td>
        <td class='center'>#{$row['id_orden']}</td>
        <td>{$row['cli_razon_social']}</td>
        <td>{$row['tipo_descrip']} - {$row['equipo_modelo']}</td>
        <td class='center'>{$tecnico}</td>
        <td class='center'>$badge</td>

        <td class='center'>
            <a class='btn btn-default btn-sm'
                href='modules/reparacion/imprimir_reparacion.php?id={$row['id_reparacion']}'
                target='_blank'
                title='Imprimir Constancia'>
                <i class='fa fa-print'></i>
            </a>

            <button class='btn btn-danger btn-sm archivar'
                    data-id='{$row['id_reparacion']}' 
                    title='Archivar / Ocultar'>
                <i class='fa fa-archive'></i>
            </button>
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
          <h4 class="modal-title">Actualizar Estado de Reparación</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="estado_id" name="id_reparacion">
          <input type="hidden" name="accion" value="cambiar_estado"> 

          <label>Nuevo Estado:</label>
          <select id="estado_valor" name="estado" class="form-control" required>
              <option value="">-- Seleccionar --</option>
              <option value="En Proceso">En Proceso</option>
              <option value="Esperando Repuesto">Esperando Repuesto</option>
              <option value="Finalizada">Finalizada</option>
              <option value="Entregada">Entregada</option>
              <option value="Anulado">Anulado (Devuelve Stock)</option>
          </select>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>

      </div>
    </form>
  </div>
</div>

<script>
// 1. CARGAR DATOS AL MODAL
$(document).on("click", ".btn-estado", function() {
    $("#estado_id").val($(this).data("id"));
    $("#estado_valor").val($(this).data("estado"));
});

// 2. GUARDAR CAMBIO DE ESTADO
$("#formCambiarEstado").submit(function(e){
    e.preventDefault();
    $.ajax({
        url: "modules/reparacion/proses.php",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(r){
            if(r.status === "ok"){
                // Recargar página para ver cambios
                window.location.href = "?module=reparacion&alert=2";
            } else {
                alert("Error: " + (r.msg || "No se pudo actualizar"));
            }
        },
        error: function(e){
            alert("Error de conexión");
        }
    });
});

// 3. BOTÓN ARCHIVAR
$(".archivar").click(function(){
    if(!confirm("¿Desea archivar esta reparación? \nDesaparecerá de la lista principal.")) return;

    let id = $(this).data("id");

    $.post("modules/reparacion/proses.php", 
        { accion: "archivar", id_reparacion: id },
        function(r){
            if(r.status === "ok"){
                window.location.href = "?module=reparacion&alert=3";
            } else {
                alert("Error al archivar");
            }
        }, "json"
    );
});
</script>