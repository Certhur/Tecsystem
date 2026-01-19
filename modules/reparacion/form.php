<?php 
include "config/database.php";

$form = $_GET['form'] ?? 'add';

/* ======================================================
   CONSULTAS PARA CARGAR COMBOS
====================================================== */

// 1. PRODUCTOS / REPUESTOS (Buscando cantidad en tabla STOCK)
$query_productos = mysqli_query($mysqli, "
    SELECT p.id_producto, 
           p.p_descrip, 
           COALESCE(SUM(s.cantidad), 0) as stock
    FROM productos p
    LEFT JOIN stock s ON p.id_producto = s.id_producto
    WHERE p.tipo_producto IN ('producto', 'repuesto') 
    GROUP BY p.id_producto
    HAVING stock > 0  
    ORDER BY p.p_descrip ASC
");

// 2. ÓRDENES DE TRABAJO (Disponibles)
// Filtramos OTs pendientes/en proceso que NO tengan ya reparación activa
$query_ots = mysqli_query($mysqli, "
   SELECT ot.id_orden, 
       ot.id_presupuesto, 
       cl.cli_razon_social, 
       re.equipo_modelo, 
       ot.fecha_inicio,
       te.tipo_descrip
FROM orden_trabajo ot
INNER JOIN presupuesto p       ON ot.id_presupuesto = p.id_presupuesto
INNER JOIN diagnostico dg      ON p.id_diagnostico = dg.id_diagnostico
INNER JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
INNER JOIN clientes cl         ON re.id_cliente = cl.id_cliente
INNER JOIN tipo_equipo te      ON re.id_tipo_equipo = te.id_tipo_equipo
WHERE ot.estado_ot IN ('Pendiente', 'En Proceso')
AND ot.id_orden NOT IN (SELECT id_orden FROM reparacion WHERE estado <> 'Anulado')
ORDER BY ot.id_orden DESC
");
?>

<?php if ($form == 'add') { ?>

<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Registrar Reparación
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="?module=reparacion">Reparaciones</a></li>
        <li class="active">Agregar</li>
    </ol>
</section>

<section class="content">
<div class="row"><div class="col-md-12">
<div class="box box-primary"><div class="box-body">

<form role="form" class="form-horizontal" action="modules/reparacion/proses.php?accion=insertar" method="POST" id="formReparacion">

    <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">

    <h4><strong>Datos de la Orden de Trabajo</strong></h4>
    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha Registro :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" value="<?= date('d/m/Y'); ?>" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Orden de Trabajo :</label>
        <div class="col-sm-6">
            <select class="form-control select2" name="id_orden" id="id_orden" required>
                <option value="">-- Seleccione una OT pendiente --</option>
                <?php while($row = mysqli_fetch_assoc($query_ots)): ?>
                    <option value="<?= $row['id_orden']; ?>">
                        OT #<?= $row['id_orden']; ?> | <?= $row['cli_razon_social']; ?> | <?= $row['equipo_modelo']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Cliente :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="id_cliente" readonly placeholder="Se carga automáticamente...">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Equipo :</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="tipo_descrip" readonly placeholder="Se carga automáticamente...">
        </div>
        <label class="col-sm-1 control-label">Modelo :</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="equipo_modelo" readonly>
        </div>
    </div>

    <h4><strong>Insumos y Repuestos Utilizados</strong></h4>
    <hr>
    
    <div class="col-sm-offset-1 col-sm-10">
        <div class="alert alert-info alert-dismissable" style="margin-bottom: 15px; padding: 10px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-info-circle"></i> Los ítems listados abajo se <b>descontarán del stock</b> al guardar.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaDetalles">
                <thead>
                    <tr class="info">
                        <th style="width: 50%;">Producto / Repuesto</th>
                        <th style="width: 20%; text-align:center;">Stock Actual</th>
                        <th style="width: 20%; text-align:center;">Cantidad Usada</th>
                        <th style="width: 10%; text-align:center;"></th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </div>

        <button type="button" id="btnAgregarFila" class="btn btn-default btn-sm">
            <i class="fa fa-plus"></i> Agregar Insumo Manualmente
        </button>
    </div>
    <div class="clearfix"></div> <br>

    <h4><strong>Estado y Observaciones</strong></h4>
    <hr>

    <div class="form-group">
        <label class="col-sm-2 control-label">Estado Actual :</label>
        <div class="col-sm-4">
             <select class="form-control" name="estado_inicial" required>
                 <option value="Finalizada">Finalizada (Trabajo Terminado)</option>
                 <option value="En Proceso">En Proceso (Continuar luego)</option>
                 <option value="Esperando Repuesto">Esperando Repuesto</option>
             </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Observaciones :</label>
        <div class="col-sm-8">
            <textarea name="observaciones" class="form-control" rows="3" placeholder="Detalles del trabajo realizado para informe técnico..."></textarea>
        </div>
    </div>

    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" onclick="return confirm('¿Confirma la reparación y el descuento de stock?')">
                    Guardar
                </button>
                <a href="?module=reparacion" class="btn btn-default">Cancelar</a>
            </div>
        </div>
    </div>

</form>

</div></div></div></div>
</section>

<?php } ?>

<script>
// 1. AL CAMBIAR OT: TRAER DATOS
$("#id_orden").change(function(){
    let id = $(this).val();
    
    // Limpiar campos
    $("#tablaDetalles tbody").empty();
    $("#id_cliente").val("");   
    $("#tipo_descrip").val(""); 
    $("#equipo_modelo").val("");

    if(!id) return;

    // Llamada AJAX
    $.ajax({
        url: "modules/reparacion/proses.php",
        type: "GET",
        data: { accion: "datos_ot_detalles", id: id },
        dataType: "json",
        success: function(data){
            if(data){
                // Llenar inputs de cabecera
                $("#id_cliente").val(data.cliente);
                $("#tipo_descrip").val(data.equipo); 
                $("#equipo_modelo").val(data.modelo);

                // Llenar tabla de productos
                if(data.productos && data.productos.length > 0){
                    $.each(data.productos, function(i, prod){
                        // Llamamos a la función enviando ID y Cantidad
                        agregarFila(prod.id_producto, prod.cantidad); 
                    });
                }
            } else {
                alert("No se encontraron datos para la OT seleccionada.");
            }
        },
        error: function(xhr, status, error){
            console.error(xhr.responseText);
            alert("Error de conexión al buscar detalles.");
        }
    });
});

// 2. AGREGAR FILA (Función Principal)
function agregarFila(id_prod = null, cant = 1) {
    // Generamos el HTML de la fila
    let html = `
        <tr>
            <td>
                <select class="form-control select2-producto" name="id_producto[]" required style="width:100%;">
                    <option value="">-- Seleccionar --</option>
                    <?php 
                        // Generar opciones con el atributo data-stock
                        if(isset($query_productos)){
                            mysqli_data_seek($query_productos, 0); 
                            while($p = mysqli_fetch_assoc($query_productos)){
                                // IMPORTANTE: data-stock guarda la cantidad disponible
                                echo "<option value='{$p['id_producto']}' data-stock='{$p['stock']}'>{$p['p_descrip']}</option>";
                            }
                        }
                    ?>
                </select>
            </td>
            <td>
                <input type="text" class="form-control stock-view text-center" readonly style="background-color:#eee; font-weight:bold;">
            </td>
            <td>
                <input type="number" name="cantidad[]" class="form-control cant text-center" min="1" value="${cant}" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-xs btnEliminar"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    `;
    
    // Convertimos a objeto jQuery y agregamos a la tabla
    let $row = $(html);
    $("#tablaDetalles tbody").append($row);

    // =======================================================
    // CORRECCIÓN: Si viene un producto pre-cargado, poner el stock MANUALMENTE
    // =======================================================
    if(id_prod){
        let $select = $row.find(".select2-producto");
        
        // 1. Seleccionar el producto en el combo
        $select.val(id_prod);

        // 2. Obtener el stock directamente de la opción seleccionada
        let stockEncontrado = $select.find("option:selected").data("stock");

        // 3. Ponerlo en el campo visual
        if(stockEncontrado !== undefined){
            $row.find(".stock-view").val(stockEncontrado);
            $row.find(".cant").attr("max", stockEncontrado); // Opcional: limitar input
        }
    }
}

// 3. BOTÓN AGREGAR MANUAL
$("#btnAgregarFila").click(function(){ 
    agregarFila(); 
});

// 4. BOTÓN ELIMINAR
$(document).on("click", ".btnEliminar", function(){ 
    $(this).closest("tr").remove(); 
});

// 5. EVENTO CAMBIO (Para cuando cambias el producto manualmente)
$(document).on("change", ".select2-producto", function(){
    let stock = $(this).find("option:selected").data("stock");
    let $tr = $(this).closest("tr");
    
    if(stock !== undefined){
        $tr.find(".stock-view").val(stock);
        $tr.find(".cant").attr("max", stock);
    } else {
        $tr.find(".stock-view").val("");
        $tr.find(".cant").removeAttr("max");
    }
});
</script>