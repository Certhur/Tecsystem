<?php 
include "config/database.php";



/* ======================================================
   CONSULTAS GENERALES (Para cargar los Selects)
====================================================== */

// 1. Diagnósticos Finalizados (Solo se usa en ADD)
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

// 2. Productos (Repuestos) - Para llenar el menú desplegable
$lista_productos = mysqli_query($mysqli,"
    SELECT id_producto, p_descrip, p_precio_servicio
    FROM productos
    WHERE tipo_producto = 'repuesto' AND estado = 1
    ORDER BY p_descrip ASC
");

// 3. Servicios - Para llenar el menú desplegable
$lista_servicios = mysqli_query($mysqli,"
    SELECT id_tipo_servicio, tipo_servicio_descrip, tipo_servicio_monto
    FROM tipo_servicio
    WHERE tipo_servicio_estado = 1
    ORDER BY tipo_servicio_descrip ASC
");
?>

<?php if ($form == 'add') { ?>

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

    <h4><strong>Datos del Diagnóstico / Cliente</strong></h4>
    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha Presupuesto :</label>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="fecha_presupuesto"
                   value="<?= date('Y-m-d'); ?>" readonly>
        </div>
    </div>

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

    <div class="form-group">
        <label class="col-sm-2 control-label">Cliente :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="cliente" readonly>
        </div>
    </div>

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

    <h4><strong>Detalles del Presupuesto</strong></h4>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered" id="tablaDetalles">
            <thead>
                <tr class="bg-info">
                    <th style="width: 30%;">Ítem Ref. (Seleccionar)</th>
                    <th style="width: 35%;">Descripción (Automático)</th>
                    <th style="width: 10%;">Cant.</th>
                    <th style="width: 15%;">Precio Unit. (Auto)</th>
                    <th style="width: 15%;">Subtotal</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="form-control selItem" name="detalle_item[]" required>
                            <option value="" data-precio="0" data-desc="">-- Seleccionar --</option>
                            
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

                            <optgroup label="Productos">
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
                    <td>
                        <input type="text" name="detalle_descripcion[]" class="form-control desc" readonly>
                    </td>
                    <td>
                        <input type="number" min="1" value="1" name="detalle_cantidad[]" class="form-control cant">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="detalle_precio[]" class="form-control precio" readonly>
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="detalle_subtotal[]" class="form-control subtotal" readonly>
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

    <div class="form-group">
        <label class="col-sm-2 control-label">Observaciones :</label>
        <div class="col-sm-6">
            <textarea name="observaciones" class="form-control" rows="3"></textarea>
        </div>
    </div>

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

<?php } elseif ($form == 'edit') { 

    /* ==========================================
       MODO: EDITAR PRESUPUESTO
    ========================================== */
    $id = intval($_GET['id'] ?? 0);
    if($id <= 0){ echo "ID inválido"; exit; }

    // 1. Consulta Cabecera
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

    // 2. Consulta Detalles
    $query_detalles = "SELECT * FROM presupuesto_detalle WHERE id_presupuesto = $id";
    $res_detalles = mysqli_query($mysqli, $query_detalles);

    // Guardamos en array para recorrerlo en el HTML
    $filas_a_mostrar = [];
    if (mysqli_num_rows($res_detalles) > 0) {
        while ($row = mysqli_fetch_assoc($res_detalles)) {
            $filas_a_mostrar[] = $row;
        }
    } else {
        // Fila vacía por defecto si no hay detalles
        $filas_a_mostrar[] = [
            'descripcion' => '', 'cantidad' => 1, 'precio_unitario' => 0, 
            'subtotal' => 0, 'id_tipo_servicio' => null, 'id_producto' => null
        ];
    }
?>

<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Editar Presupuesto #<?= $id; ?>
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

    <div class="form-group">
        <label class="col-sm-2 control-label">Diagnóstico :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control"
                   value="#<?= $cab['id_diagnostico']; ?> - <?= $cab['cli_razon_social']; ?>" readonly>
            <input type="hidden" name="id_diagnostico" value="<?= $cab['id_diagnostico']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Cliente :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" 
                   value="<?= $cab['cli_razon_social']; ?>" readonly>
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

    <h4><strong>Detalles del Presupuesto</strong></h4>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered" id="tablaDetalles">
            <thead>
                <tr class="bg-info">
                    <th style="width: 30%;">Ítem Ref. (Seleccionar)</th>
                    <th style="width: 35%;">Descripción (Automático)</th>
                    <th style="width: 10%;">Cant.</th>
                    <th style="width: 15%;">Precio Unit. (Auto)</th>
                    <th style="width: 15%;">Subtotal</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($filas_a_mostrar as $d): ?>
                    
                    <?php 
                        // LÓGICA DE RECONSTRUCCIÓN DEL VALOR ("P1" o "S5")
                        $valor_seleccionado = "";
                        
                        // OJO: Aquí usamos 'id_tipo_servicio' como acordamos
                        if (!empty($d['id_tipo_servicio'])) {
                            $valor_seleccionado = "S" . $d['id_tipo_servicio'];
                        } elseif (!empty($d['id_producto'])) {
                            $valor_seleccionado = "P" . $d['id_producto'];
                        }
                    ?>

                    <tr>
                        <td>
                            <select class="form-control selItem" name="detalle_item[]" required>
                                <option value="" data-precio="0">-- Seleccionar --</option>
                                
                                <optgroup label="Servicios">
                                    <?php mysqli_data_seek($lista_servicios, 0); // Rebobinar lista ?>
                                    <?php while($s = mysqli_fetch_assoc($lista_servicios)): ?>
                                        <?php 
                                            $val_serv = "S" . $s['id_tipo_servicio']; 
                                            $selected = ($val_serv == $valor_seleccionado) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $val_serv; ?>" 
                                                data-precio="<?= $s['tipo_servicio_monto']; ?>"
                                                data-desc="<?= htmlspecialchars($s['tipo_servicio_descrip'], ENT_QUOTES, 'UTF-8'); ?>"
                                                <?= $selected; ?> > 
                                            <?= $s['tipo_servicio_descrip']; ?> (Serv.)
                                        </option>
                                    <?php endwhile; ?>
                                </optgroup>

                                <optgroup label="Productos">
                                    <?php mysqli_data_seek($lista_productos, 0); // Rebobinar lista ?>
                                    <?php while($p = mysqli_fetch_assoc($lista_productos)): ?>
                                        <?php 
                                            $val_prod = "P" . $p['id_producto'];
                                            $selected = ($val_prod == $valor_seleccionado) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $val_prod; ?>" 
                                                data-precio="<?= $p['p_precio_servicio']; ?>"
                                                data-desc="<?= htmlspecialchars($p['p_descrip'], ENT_QUOTES, 'UTF-8'); ?>"
                                                <?= $selected; ?> >
                                            <?= $p['p_descrip']; ?> (Rep.)
                                        </option>
                                    <?php endwhile; ?>
                                </optgroup>
                            </select>
                        </td>

                        <td>
                            <input type="text" name="detalle_descripcion[]" class="form-control desc" 
                                   value="<?= isset($d['descripcion']) ? $d['descripcion'] : ''; ?>" readonly>
                        </td>

                        <td>
                            <input type="number" min="1" name="detalle_cantidad[]" class="form-control cant"
                                   value="<?= isset($d['cantidad']) ? $d['cantidad'] : 1; ?>">
                        </td>

                        <td>
                            <input type="number" step="0.01" min="0" name="detalle_precio[]" class="form-control precio"
                                   value="<?= isset($d['precio_unitario']) ? $d['precio_unitario'] : 0; ?>" readonly>
                        </td>

                        <td>
                            <input type="number" step="0.01" min="0" name="detalle_subtotal[]" class="form-control subtotal" readonly
                                   value="<?= isset($d['subtotal']) ? $d['subtotal'] : 0; ?>">
                        </td>

                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm btnQuitarFila">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
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

<?php } ?>

<script>
// 1. CARGAR DATOS DEL DIAGNÓSTICO (Solo ADD)
$("#id_diagnostico").change(function(){
    let id = $(this).val();
    if(!id) return;
    $.getJSON("modules/presupuesto/proses.php?accion=datos_diagnostico&id="+id, function(d){
        if(d){
            $("#cliente").val(d.cli_razon_social);
            $("#equipo").val(d.tipo_descrip);
            $("#modelo").val(d.equipo_modelo);
        }
    });
});

// 2. CAMBIO EN SELECT (Llena descripción y precio)
$(document).on("change", ".selItem", function(){
    let opt = $(this).find("option:selected");
    let desc = opt.data("desc") || "";
    let precio = parseFloat(opt.data("precio")) || 0;
    let $tr = $(this).closest("tr");

    // Llenar inputs automáticamente
    if(desc) $tr.find(".desc").val(desc);
    if(precio > 0) $tr.find(".precio").val(precio.toFixed(2));
    
    // Si no hay cantidad, poner 1
    if(!$tr.find(".cant").val()) $tr.find(".cant").val(1);

    // Recalcular
    recalcularFila($tr);
});

// 3. CÁLCULOS MATEMÁTICOS
function recalcularFila($tr){
    let cant   = parseFloat($tr.find(".cant").val())   || 0;
    let precio = parseFloat($tr.find(".precio").val()) || 0;
    let sub    = cant * precio;
    $tr.find(".subtotal").val(sub.toFixed(2));
    recalcularTotales();
}

function recalcularTotales(){
    let subtotal = 0;
    // Sumar todas las filas
    $("#tablaDetalles tbody tr").each(function(){
        let s = parseFloat($(this).find(".subtotal").val()) || 0;
        subtotal += s;
    });
    $("#subtotal").val(subtotal.toFixed(2));

    // Sumar Mano de Obra
    let mano = parseFloat($("#mano_obra").val()) || 0;
    let total = subtotal + mano;
    $("#total").val(total.toFixed(2));
}

// Evento al escribir cantidad o mano de obra
$(document).on("input", ".cant, #mano_obra", function(){
    if($(this).hasClass("cant")){
        recalcularFila($(this).closest("tr"));
    } else {
        recalcularTotales();
    }
});

// 4. AGREGAR FILA
$("#btnAgregarFila").click(function(){
    // Clonamos la primera fila
    let $fila = $("#tablaDetalles tbody tr:first").clone();
    
    // Limpiamos los valores
    $fila.find("input").val("");
    $fila.find(".cant").val(1);
    $fila.find(".selItem").val(""); 

    // Agregamos a la tabla
    $("#tablaDetalles tbody").append($fila);
});

// 5. QUITAR FILA
$(document).on("click", ".btnQuitarFila", function(){
    let filas = $("#tablaDetalles tbody tr").length;
    if(filas <= 1){
        // Si es la última, solo limpiamos
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

// 6. INICIALIZAR CÁLCULOS (Para Edit)
$(document).ready(function(){
    $("#tablaDetalles tbody tr").each(function(){
        recalcularFila($(this));
    });
});
</script>