<?php 
include "config/database.php";

// --- ESTA ES LA LÍNEA QUE FALTABA ---
$form = $_GET['form']; 
// ------------------------------------
?>

<?php if ($form == 'add') { 

    // 1. CONSULTA: Presupuestos Aprobados (Solo se usa en ADD)
    $lista_presupuestos = mysqli_query($mysqli,"
        SELECT p.id_presupuesto,
               cl.cli_razon_social,
               re.equipo_modelo,
               te.tipo_descrip
        FROM presupuesto p
        INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
        INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
        INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
        INNER JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
        WHERE p.estado = 'Aprobado'
        AND p.id_presupuesto NOT IN (SELECT id_presupuesto FROM orden_trabajo)
        ORDER BY p.id_presupuesto DESC
    ");
?>

<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Generar Orden de Trabajo
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=orden_trabajo">Órdenes de Trabajo</a></li>
        <li class="active">Agregar</li>
    </ol>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<form role="form" class="form-horizontal"
      action="modules/orden_trabajo/proses.php?accion=insertar"
      method="POST" id="formOrden">

    <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">

    <h4><strong>Datos del Presupuesto / Cliente</strong></h4>
    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha Inicio :</label>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="fecha_inicio"
                   value="<?= date('Y-m-d'); ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Presupuesto :</label>
        <div class="col-sm-6">
            <select class="form-control" name="id_presupuesto" id="id_presupuesto" required>
                <option value="" disabled selected>-- Seleccione un presupuesto aprobado --</option>
                <?php while($row = mysqli_fetch_assoc($lista_presupuestos)): ?>
                    <option value="<?= $row['id_presupuesto']; ?>">
                        #<?= $row['id_presupuesto']; ?> - <?= $row['cli_razon_social']; ?> - <?= $row['tipo_descrip']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Cliente :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="cliente" readonly placeholder="Se carga automáticamente...">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Equipo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="equipo" readonly placeholder="Se carga automáticamente...">
        </div>
        <label class="col-sm-1 control-label">Modelo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="modelo" readonly>
        </div>
    </div>

    <h4><strong>Detalles de la Orden</strong></h4>
    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha Entrega Est. :</label>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="fecha_entrega_estimada">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Observaciones :</label>
        <div class="col-sm-6">
            <textarea name="observaciones_ot" class="form-control" rows="3" placeholder="Instrucciones para el técnico..."></textarea>
        </div>
    </div>

    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary"
                    onclick="return confirm('¿Generar Orden de Trabajo?')">
                Guardar
            </button>
            <a href="?module=orden_trabajo" class="btn btn-default">Cancelar</a>
        </div>
    </div>

</form>
</div></div></div></div>
</section>

<?php } elseif ($form == 'edit') { 

    /* ==========================================
       MODO: EDITAR ORDEN
    ========================================== */
    $id = intval($_GET['id'] ?? 0);
    if($id <= 0){ echo "ID inválido"; exit; }

    // 1. Consulta Cabecera OT + Datos relacionados
    $qCab = mysqli_query($mysqli,"
        SELECT ot.*, 
               p.id_presupuesto,
               cl.cli_razon_social,
               re.equipo_modelo,
               te.tipo_descrip
        FROM orden_trabajo ot
        INNER JOIN presupuesto p       ON ot.id_presupuesto = p.id_presupuesto
        INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
        INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
        INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
        INNER JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
        WHERE ot.id_orden = $id
    ");
    $cab = mysqli_fetch_assoc($qCab);
?>

<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Editar Orden #<?= $id; ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=orden_trabajo">Órdenes de Trabajo</a></li>
        <li class="active">Editar</li>
    </ol>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<form role="form" class="form-horizontal"
      action="modules/orden_trabajo/proses.php?accion=actualizar"
      method="POST" id="formOrden">

    <input type="hidden" name="id_orden" value="<?= $cab['id_orden']; ?>">
    <input type="hidden" name="id_user" value="<?= $cab['id_user']; ?>">

    <h4><strong>Datos del Presupuesto / Cliente</strong></h4>
    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha Inicio :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" 
                   value="<?= date('d/m/Y H:i', strtotime($cab['fecha_inicio'])); ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Ref. Presupuesto :</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" value="#<?= $cab['id_presupuesto']; ?>" readonly>
        </div>
        <div class="col-sm-4">
            <input type="text" class="form-control" value="<?= $cab['cli_razon_social']; ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Equipo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" value="<?= $cab['tipo_descrip']; ?>" readonly>
        </div>
        <label class="col-sm-1 control-label">Modelo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" value="<?= $cab['equipo_modelo']; ?>" readonly>
        </div>
    </div>

    <h4><strong>Estado y Entrega</strong></h4>
    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Estado Orden :</label>
        <div class="col-sm-4">
             <select class="form-control" name="estado_ot">
                <option value="Pendiente"  <?= ($cab['estado_ot']=='Pendiente')?'selected':''; ?>>Pendiente</option>
                <option value="En Proceso" <?= ($cab['estado_ot']=='En Proceso')?'selected':''; ?>>En Proceso</option>
                <option value="Finalizada" <?= ($cab['estado_ot']=='Finalizada')?'selected':''; ?>>Finalizada</option>
                <option value="Entregada"  <?= ($cab['estado_ot']=='Entregada')?'selected':''; ?>>Entregada</option>
                <option value="Cancelada"  <?= ($cab['estado_ot']=='Cancelada')?'selected':''; ?>>Cancelada</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha Entrega Est. :</label>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="fecha_entrega_estimada" 
                   value="<?= $cab['fecha_entrega_estimada']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Observaciones :</label>
        <div class="col-sm-6">
            <textarea name="observaciones_ot" class="form-control" rows="3"><?= $cab['observaciones_ot']; ?></textarea>
        </div>
    </div>

    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary"
                    onclick="return confirm('¿Guardar cambios?')">
                Guardar
            </button>
            <a href="?module=orden_trabajo" class="btn btn-default">Cancelar</a>
        </div>
    </div>

</form>

</div></div></div></div>
</section>

<?php } ?>

<script>
// 1. CARGAR DATOS DEL PRESUPUESTO (Solo ADD)
// Reutilizamos la lógica de AJAX pero apuntando a 'datos_presupuesto'
$("#id_presupuesto").change(function(){
    let id = $(this).val();
    if(!id) return;
    
    // Llamada AJAX
    $.getJSON("modules/orden_trabajo/proses.php?accion=datos_presupuesto&id="+id, function(d){
        if(d){
            $("#cliente").val(d.cli_razon_social);
            $("#equipo").val(d.tipo_descrip);
            $("#modelo").val(d.equipo_modelo);
        } else {
            $("#cliente").val("");
            $("#equipo").val("");
            $("#modelo").val("");
        }
    });
});
</script>