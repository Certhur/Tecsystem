<?php 
include "config/database.php";

$form = $_GET['form'] ?? 'add';

/* ======================================================
   OBTENER LISTAS REFERENCIALES
====================================================== */

// Diagnósticos finalizados (para ADD)
$lista_diagnosticos = mysqli_query($mysqli,"
    SELECT dg.id_diagnostico,
           re.id_recepcion_equipo,
           re.equipo_modelo,
           cl.cli_razon_social
    FROM diagnostico dg
    INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
    INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
    WHERE dg.estado_diagnostico = 'Finalizado'
    ORDER BY dg.id_diagnostico DESC
");

// Productos (repuestos)
$lista_productos = mysqli_query($mysqli,"
    SELECT id_producto, p_descrip, p_precio_servicio
    FROM productos
    WHERE tipo_producto = 'repuesto' AND estado = 1
    ORDER BY p_descrip ASC
");

// Servicios
$lista_servicios = mysqli_query($mysqli,"
    SELECT id_tipo_servicio, tipo_servicio_descrip, tipo_servicio_monto
    FROM tipo_servicio
    WHERE tipo_servicio_estado = 1
    ORDER BY tipo_servicio_descrip ASC
");

?>

<?php if ($form == 'add') { ?>

<!-- ==========================================
     AGREGAR PRESUPUESTO
========================================== -->
<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Agregar Presupuesto
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=presupuesto">Presupuestos</a></li>
        <li class="active">Agregar</li>
    </ol>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<form role="form" class="form-horizontal"
      action="modules/presupuesto/proses.php?accion=insertar"
      method="POST" id="formPresupuesto">

    <!-- ================= DATOS CABECERA ================ -->
    <h4><strong>Datos del Diagnóstico / Cliente</strong></h4>
    <hr>

    <!-- FECHA -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha Presupuesto :</label>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="fecha_presupuesto"
                   value="<?= date('Y-m-d'); ?>" readonly>
        </div>
    </div>

    <!-- DIAGNÓSTICO -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Diagnóstico :</label>
        <div class="col-sm-6">
            <select class="form-control" name="id_diagnostico" id="id_diagnostico" required>
                <option value="" disabled selected>Seleccione un diagnóstico finalizado</option>
                <?php while($dg = mysqli_fetch_assoc($lista_diagnosticos)): ?>
                    <option value="<?= $dg['id_diagnostico']; ?>">
                        #<?= $dg['id_diagnostico']; ?> - <?= $dg['cli_razon_social']; ?> - <?= $dg['equipo_modelo']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- CLIENTE -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Cliente :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="cliente" readonly>
        </div>
    </div>

    <!-- EQUIPO / MODELO -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Equipo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="equipo" readonly>
        </div>

        <label class="col-sm-1 control-label">Modelo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="modelo" readonly>
        </div>
    </div>

    <!-- ================= DETALLES ================ -->
    <h4><strong>Detalles del Presupuesto</strong></h4>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered" id="tablaDetalles">
            <thead>
                <tr class="bg-info">
                    <th style="width: 35%;">Descripción</th>
                    <th style="width: 10%;">Cantidad</th>
                    <th style="width: 15%;">Precio Unitario</th>
                    <th style="width: 15%;">Subtotal</th>
                    <th style="width: 25%;">Ítem Ref.</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
                <!-- Fila inicial -->
                <tr>
                    <td>
                        <input type="text" name="detalle_descripcion[]" class="form-control desc">
                    </td>
                    <td>
                        <input type="number" min="1" value="1" 
                               name="detalle_cantidad[]" class="form-control cant">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0"
                               name="detalle_precio[]" class="form-control precio">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0"
                               name="detalle_subtotal[]" class="form-control subtotal" readonly>
                    </td>
                    <td>
                        <select class="form-control selItem">
                            <option value="" data-precio="0" data-desc="">-- Seleccionar referencia --</option>
                            <optgroup label="Servicios">
                                <?php mysqli_data_seek($lista_servicios, 0); ?>
                                <?php while($s = mysqli_fetch_assoc($lista_servicios)): ?>
                                    <option 
                                        value="S<?= $s['id_tipo_servicio']; ?>"
                                        data-precio="<?= $s['tipo_servicio_monto']; ?>"
                                        data-desc="<?= htmlspecialchars($s['tipo_servicio_descrip'], ENT_QUOTES, 'UTF-8'); ?>"
                                    >
                                        <?= $s['tipo_servicio_descrip']; ?> (Serv.)
                                    </option>
                                <?php endwhile; ?>
                            </optgroup>
                            <optgroup label="Productos / Repuestos">
                                <?php mysqli_data_seek($lista_productos, 0); ?>
                                <?php while($p = mysqli_fetch_assoc($lista_productos)): ?>
                                    <option 
                                        value="P<?= $p['id_producto']; ?>"
                                        data-precio="<?= $p['p_precio_servicio']; ?>"
                                        data-desc="<?= htmlspecialchars($p['p_descrip'], ENT_QUOTES, 'UTF-8'); ?>"
                                    >
                                        <?= $p['p_descrip']; ?> (Rep.)
                                    </option>
                                <?php endwhile; ?>
                            </optgroup>
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnQuitarFila">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <button type="button" id="btnAgregarFila" class="btn btn-default">
        <i class="fa fa-plus"></i> Agregar línea
    </button>

    <hr>

    <!-- ================= TOTALES ================ -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Mano de Obra :</label>
        <div class="col-sm-2">
            <input type="number" step="0.01" min="0"
                   name="mano_obra" id="mano_obra" class="form-control" value="0.00">
        </div>

        <label class="col-sm-2 control-label">Subtotal :</label>
        <div class="col-sm-2">
            <input type="number" step="0.01" min="0"
                   name="subtotal" id="subtotal" class="form-control" readonly>
        </div>

        <label class="col-sm-1 control-label">Total :</label>
        <div class="col-sm-2">
            <input type="number" step="0.01" min="0"
                   name="total" id="total" class="form-control" readonly>
        </div>
    </div>

    <!-- OBSERVACIONES -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Observaciones :</label>
        <div class="col-sm-6">
            <textarea name="observaciones" class="form-control" rows="3"></textarea>
        </div>
    </div>

    <!-- BOTONES -->
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary"
                    onclick="return confirm('¿Guardar presupuesto?')">
                Guardar
            </button>
            <a href="?module=presupuesto" class="btn btn-default">Cancelar</a>
        </div>
    </div>

</form>

</div></div></div></div>
</section>

<script>
// ===============================
// CARGAR DATOS DEL DIAGNÓSTICO
// ===============================
$("#id_diagnostico").change(function(){
    let id = $(this).val();
    if(!id) return;

    $.getJSON("modules/presupuesto/proses.php?accion=datos_diagnostico&id="+id,
        function(d){
            if(d){
                $("#cliente").val(d.cli_razon_social);
                $("#equipo").val(d.tipo_descrip);
                $("#modelo").val(d.equipo_modelo);
            }
        }
    );
});

// ===============================
// FUNCIONES DETALLES
// ===============================
function recalcularFila($tr){
    let cant   = parseFloat($tr.find(".cant").val())   || 0;
    let precio = parseFloat($tr.find(".precio").val()) || 0;
    let sub    = cant * precio;
    $tr.find(".subtotal").val(sub.toFixed(2));
    recalcularTotales();
}

function recalcularTotales(){
    let subtotal = 0;
    $("#tablaDetalles tbody tr").each(function(){
        let s = parseFloat($(this).find(".subtotal").val()) || 0;
        subtotal += s;
    });
    $("#subtotal").val(subtotal.toFixed(2));

    let mano = parseFloat($("#mano_obra").val()) || 0;
    let total = subtotal + mano;
    $("#total").val(total.toFixed(2));
}

// Cambio en cantidad / precio
$(document).on("input", ".cant, .precio", function(){
    let $tr = $(this).closest("tr");
    recalcularFila($tr);
});

// Selección de item (servicio / producto)
$(document).on("change", ".selItem", function(){
    let opt = $(this).find("option:selected");
    let desc = opt.data("desc") || "";
    let precio = parseFloat(opt.data("precio")) || 0;
    let $tr = $(this).closest("tr");

    if(desc){
        $tr.find(".desc").val(desc);
    }
    if(precio > 0){
        $tr.find(".precio").val(precio.toFixed(2));
    }
    if(!$tr.find(".cant").val()){
        $tr.find(".cant").val(1);
    }
    recalcularFila($tr);
});

// Agregar fila
$("#btnAgregarFila").click(function(){
    let $fila = $("#tablaDetalles tbody tr:first").clone();

    $fila.find("input").val("");
    $fila.find(".cant").val(1);
    $fila.find(".selItem").val("");

    $("#tablaDetalles tbody").append($fila);
});

// Quitar fila
$(document).on("click", ".btnQuitarFila", function(){
    let filas = $("#tablaDetalles tbody tr").length;
    if(filas <= 1){
        // limpiar en vez de eliminar
        let $tr = $("#tablaDetalles tbody tr:first");
        $tr.find("input").val("");
        $tr.find(".cant").val(1);
        $tr.find(".selItem").val("");
        recalcularFila($tr);
    } else {
        $(this).closest("tr").remove();
        recalcularTotales();
    }
});

// Cambio en mano de obra
$("#mano_obra").on("input", function(){
    recalcularTotales();
});
</script>

<?php } elseif ($form == 'edit') { 

    // ================== EDITAR =======================
    $id = intval($_GET['id'] ?? 0);
    if($id <= 0){ echo "ID inválido"; exit; }

    $qCab = mysqli_query($mysqli,"
        SELECT p.*, 
               dg.id_recepcion_equipo,
               re.equipo_modelo,
               re.equipo_descripcion,
               cl.cli_razon_social,
               te.tipo_descrip
        FROM presupuesto p
        LEFT JOIN diagnostico dg       ON p.id_diagnostico = dg.id_diagnostico
        LEFT JOIN recepcion_equipo re  ON dg.id_recepcion_equipo = re.id_recepcion_equipo
        LEFT JOIN clientes cl          ON re.id_cliente = cl.id_cliente
        LEFT JOIN tipo_equipo te       ON re.id_tipo_equipo = te.id_tipo_equipo
        WHERE p.id_presupuesto = $id
    ");
    $cab = mysqli_fetch_assoc($qCab);

    $qDet = mysqli_query($mysqli,"
        SELECT * FROM presupuesto_detalle
        WHERE id_presupuesto = $id
    ");
?>

<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Editar Presupuesto
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=presupuesto">Presupuestos</a></li>
        <li class="active">Editar</li>
    </ol>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<form role="form" class="form-horizontal"
      action="modules/presupuesto/proses.php?accion=actualizar"
      method="POST" id="formPresupuesto">

    <input type="hidden" name="id_presupuesto" value="<?= $cab['id_presupuesto']; ?>">

    <h4><strong>Datos del Diagnóstico / Cliente</strong></h4>
    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha Presupuesto :</label>
        <div class="col-sm-3">
            <input type="date" class="form-control" 
                   value="<?= substr($cab['fecha_presupuesto'],0,10); ?>" readonly>
        </div>
    </div>

    <!-- Diagnóstico (solo lectura, pero guardamos id oculto) -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Diagnóstico :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control"
                   value="#<?= $cab['id_diagnostico']; ?> - <?= $cab['cli_razon_social']; ?> - <?= $cab['equipo_modelo']; ?>" readonly>
            <input type="hidden" name="id_diagnostico" value="<?= $cab['id_diagnostico']; ?>">
        </div>
    </div>

    <!-- Cliente -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Cliente :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" 
                   value="<?= $cab['cli_razon_social']; ?>" readonly>
        </div>
    </div>

    <!-- Equipo / Modelo -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Equipo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" 
                   value="<?= $cab['tipo_descrip']; ?>" readonly>
        </div>
        <label class="col-sm-1 control-label">Modelo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" 
                   value="<?= $cab['equipo_modelo']; ?>" readonly>
        </div>
    </div>

    <!-- DETALLES -->
    <h4><strong>Detalles del Presupuesto</strong></h4>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered" id="tablaDetalles">
            <thead>
                <tr class="bg-info">
                    <th style="width: 40%;">Descripción</th>
                    <th style="width: 10%;">Cantidad</th>
                    <th style="width: 15%;">Precio Unitario</th>
                    <th style="width: 15%;">Subtotal</th>
                    <th style="width: 15%;">(Ref. libre)</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $hay_det = false;
            while($det = mysqli_fetch_assoc($qDet)): 
                $hay_det = true;
            ?>
                <tr>
                    <td>
                        <input type="text" name="detalle_descripcion[]" 
                               class="form-control desc"
                               value="<?= htmlspecialchars($det['descripcion'], ENT_QUOTES, 'UTF-8'); ?>">
                    </td>
                    <td>
                        <input type="number" min="1"
                               name="detalle_cantidad[]" 
                               class="form-control cant"
                               value="<?= $det['cantidad']; ?>">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0"
                               name="detalle_precio[]" 
                               class="form-control precio"
                               value="<?= $det['precio_unitario']; ?>">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0"
                               name="detalle_subtotal[]" 
                               class="form-control subtotal"
                               value="<?= $det['subtotal']; ?>"
                               readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="(opcional)">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnQuitarFila">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>

            <?php if(!$hay_det): ?>
                <tr>
                    <td><input type="text" name="detalle_descripcion[]" class="form-control desc"></td>
                    <td><input type="number" min="1" name="detalle_cantidad[]" class="form-control cant" value="1"></td>
                    <td><input type="number" step="0.01" min="0" name="detalle_precio[]" class="form-control precio"></td>
                    <td><input type="number" step="0.01" min="0" name="detalle_subtotal[]" class="form-control subtotal" readonly></td>
                    <td><input type="text" class="form-control" placeholder="(opcional)"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnQuitarFila">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <button type="button" id="btnAgregarFila" class="btn btn-default">
        <i class="fa fa-plus"></i> Agregar línea
    </button>

    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Mano de Obra :</label>
        <div class="col-sm-2">
            <input type="number" step="0.01" min="0"
                   name="mano_obra" id="mano_obra" class="form-control"
                   value="<?= $cab['mano_obra']; ?>">
        </div>

        <label class="col-sm-2 control-label">Subtotal :</label>
        <div class="col-sm-2">
            <input type="number" step="0.01" min="0"
                   name="subtotal" id="subtotal" class="form-control"
                   value="<?= $cab['subtotal']; ?>" readonly>
        </div>

        <label class="col-sm-1 control-label">Total :</label>
        <div class="col-sm-2">
            <input type="number" step="0.01" min="0"
                   name="total" id="total" class="form-control"
                   value="<?= $cab['total']; ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Observaciones :</label>
        <div class="col-sm-6">
            <textarea name="observaciones" class="form-control" rows="3"><?= htmlspecialchars($cab['observaciones'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
    </div>

    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary"
                    onclick="return confirm('¿Guardar cambios del presupuesto?')">
                Guardar
            </button>
            <a href="?module=presupuesto" class="btn btn-default">Cancelar</a>
        </div>
    </div>

</form>

</div></div></div></div>
</section>

<script>
function recalcularFila($tr){
    let cant   = parseFloat($tr.find(".cant").val())   || 0;
    let precio = parseFloat($tr.find(".precio").val()) || 0;
    let sub    = cant * precio;
    $tr.find(".subtotal").val(sub.toFixed(2));
    recalcularTotales();
}

function recalcularTotales(){
    let subtotal = 0;
    $("#tablaDetalles tbody tr").each(function(){
        let s = parseFloat($(this).find(".subtotal").val()) || 0;
        subtotal += s;
    });
    $("#subtotal").val(subtotal.toFixed(2));

    let mano = parseFloat($("#mano_obra").val()) || 0;
    let total = subtotal + mano;
    $("#total").val(total.toFixed(2));
}

$(document).on("input", ".cant, .precio", function(){
    let $tr = $(this).closest("tr");
    recalcularFila($tr);
});

$("#btnAgregarFila").click(function(){
    let $fila = $("#tablaDetalles tbody tr:first").clone();

    $fila.find("input").val("");
    $fila.find(".cant").val(1);

    $("#tablaDetalles tbody").append($fila);
});

$(document).on("click", ".btnQuitarFila", function(){
    let filas = $("#tablaDetalles tbody tr").length;
    if(filas <= 1){
        let $tr = $("#tablaDetalles tbody tr:first");
        $tr.find("input").val("");
        $tr.find(".cant").val(1);
        recalcularFila($tr);
    } else {
        $(this).closest("tr").remove();
        recalcularTotales();
    }
});

$("#mano_obra").on("input", function(){
    recalcularTotales();
});

// Recalcular al cargar
$(document).ready(function(){
    $("#tablaDetalles tbody tr").each(function(){
        recalcularFila($(this));
    });
});
</script>

<?php } ?>
