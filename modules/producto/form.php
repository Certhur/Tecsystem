<?php
// =============================================================
// FORMULARIO DE PRODUCTOS (AGREGAR / EDITAR)
// =============================================================

// Cargar conexión
include __DIR__ . '/../../config/database.php';

// Validación de tipo de formulario
$form = $_GET["form"] ?? "";


// =============================================================
// ============   FORMULARIO: AGREGAR PRODUCTO   ===============
// =============================================================
if ($form == "add") {

    // Obtener siguiente ID
    $query_id = mysqli_query($mysqli, "SELECT MAX(id_producto) AS id FROM productos");
    $data_id = mysqli_fetch_assoc($query_id);
    $id_siguiente = $data_id ? $data_id["id"] + 1 : 1;
?>

<section class="content-header">
    <h1><i class="fa fa-edit icon-title"></i> Agregar Producto</h1>

    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=producto">Producto</a></li>
        <li class="active">Agregar</li>
    </ol>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">

    <div class="box box-primary">
        <form class="form-horizontal" action="modules/producto/proses.php?act=insert" method="POST">

            <div class="box-body">

                <!-- Código -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Código</label>
                    <div class="col-sm-5">
                        <input type="text" name="id_producto" class="form-control"
                               value="<?php echo $id_siguiente; ?>" readonly>
                    </div>
                </div>

                <!-- Tipo de Producto -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Tipo</label>
                    <div class="col-sm-5">
                        <select name="tipo_producto" class="form-control" required>
                            <option value="">Seleccione el tipo</option>
                            <option value="repuesto">Repuesto</option>
                            <option value="servicio">Servicio</option>
                        </select>
                    </div>
                </div>

                <!-- Unidad de medida -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Unidad de Medida</label>
                    <div class="col-sm-5">
                        <select name="id_u_medida" class="form-control" required>
                            <option value="">Seleccione unidad de medida</option>
                            <?php
                            $q = mysqli_query($mysqli, "SELECT * FROM u_medida");
                            while ($row = mysqli_fetch_assoc($q)) {
                                echo "<option value='{$row['id_u_medida']}'>{$row['u_descrip']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Proveedor -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Proveedor</label>
                    <div class="col-sm-5">
                        <select name="cod_proveedor" class="form-control" required>
                            <option value="">Seleccione proveedor</option>
                            <?php
                            $q = mysqli_query($mysqli, "SELECT * FROM proveedor");
                            while ($row = mysqli_fetch_assoc($q)) {
                                echo "<option value='{$row['cod_proveedor']}'>{$row['razon_social']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Marca -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Marca</label>
                    <div class="col-sm-5">
                        <select name="id_marca" class="form-control" required>
                            <option value="">Seleccione marca</option>
                            <?php
                            $q = mysqli_query($mysqli, "SELECT * FROM marcas");
                            while ($row = mysqli_fetch_assoc($q)) {
                                echo "<option value='{$row['id_marca']}'>{$row['marca_descrip']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Nombre -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Producto</label>
                    <div class="col-sm-5">
                        <input type="text" name="p_descrip" class="form-control"
                               placeholder="Nombre del producto" required>
                    </div>
                </div>

                <!-- Precio costo -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Precio costo</label>
                    <div class="col-sm-5">
                        <input type="number" name="p_costo_actual" class="form-control"
                               placeholder="Costo actual" required>
                    </div>
                </div>

                <!-- Precio servicio -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Precio servicio</label>
                    <div class="col-sm-5">
                        <input type="number" name="p_precio_servicio" class="form-control"
                               placeholder="Precio al cliente" required>
                    </div>
                </div>

                <!-- Estado -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Estado</label>
                    <div class="col-sm-5">
                        <select name="estado" class="form-control" required>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>

                <!-- Botones -->
                <div class="box-footer">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="Guardar" class="btn btn-primary"
                                onclick="return confirm('¿Guardar datos del producto?')">Guardar</button>

                        <a href="?module=producto" class="btn btn-default">Cancelar</a>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>
</div>
</section>


<?php
// =============================================================
// ============     FORMULARIO: EDITAR PRODUCTO     ============
// =============================================================
} elseif ($form == "edit") {

    if (!isset($_GET["id_producto"])) {
        echo "<script>alert('ID de producto no recibido');</script>";
        exit;
    }

    $id = $_GET["id_producto"];

    $query = mysqli_query($mysqli, "SELECT * FROM productos WHERE id_producto='$id'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        echo "<script>alert('Producto no encontrado');</script>";
        exit;
    }
?>

<section class="content-header">
    <h1><i class="fa fa-edit icon-title"></i> Modificar Producto</h1>

    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=producto">Producto</a></li>
        <li class="active">Modificar</li>
    </ol>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">

    <div class="box box-primary">
        <form class="form-horizontal" action="modules/producto/proses.php?act=update" method="POST">
            <div class="box-body">

                <!-- Código -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Código</label>
                    <div class="col-sm-5">
                        <input type="text" name="id_producto" class="form-control"
                               value="<?php echo $data['id_producto']; ?>" readonly>
                    </div>
                </div>

                <!-- Tipo producto -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Tipo</label>
                    <div class="col-sm-5">
                        <select name="tipo_producto" class="form-control" required>
                            <option value="repuesto" <?= ($data['tipo_producto']=="repuesto")?"selected":"" ?>>
                                Repuesto
                            </option>
                            <option value="servicio" <?= ($data['tipo_producto']=="servicio")?"selected":"" ?>>
                                Servicio
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Unidad -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Unidad medida</label>
                    <div class="col-sm-5">
                        <select name="id_u_medida" class="form-control">
                            <?php
                            $q = mysqli_query($mysqli, "SELECT * FROM u_medida");
                            while ($row = mysqli_fetch_assoc($q)) {
                                $sel = ($row['id_u_medida'] == $data['id_u_medida']) ? "selected" : "";
                                echo "<option value='{$row['id_u_medida']}' $sel>{$row['u_descrip']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Proveedor -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Proveedor</label>
                    <div class="col-sm-5">
                        <select name="cod_proveedor" class="form-control">
                            <?php
                            $q = mysqli_query($mysqli, "SELECT * FROM proveedor");
                            while ($row = mysqli_fetch_assoc($q)) {
                                $sel = ($row['cod_proveedor'] == $data['cod_proveedor']) ? "selected" : "";
                                echo "<option value='{$row['cod_proveedor']}' $sel>{$row['razon_social']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Marca -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Marca</label>
                    <div class="col-sm-5">
                        <select name="id_marca" class="form-control">
                            <?php
                            $q = mysqli_query($mysqli, "SELECT * FROM marcas");
                            while ($row = mysqli_fetch_assoc($q)) {
                                $sel = ($row['id_marca'] == $data['id_marca']) ? "selected" : "";
                                echo "<option value='{$row['id_marca']}' $sel>{$row['marca_descrip']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Nombre producto -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Producto</label>
                    <div class="col-sm-5">
                        <input type="text" name="p_descrip" class="form-control"
                               value="<?php echo $data['p_descrip']; ?>" required>
                    </div>
                </div>

                <!-- Precios -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Costo</label>
                    <div class="col-sm-5">
                        <input type="number" name="p_costo_actual" class="form-control"
                               value="<?php echo $data['p_costo_actual']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Precio servicio</label>
                    <div class="col-sm-5">
                        <input type="number" name="p_precio_servicio" class="form-control"
                               value="<?php echo $data['p_precio_servicio']; ?>" required>
                    </div>
                </div>

                <!-- Estado -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Estado</label>
                    <div class="col-sm-5">
                        <select name="estado" class="form-control">
                            <option value="1" <?= ($data['estado']==1?"selected":"") ?>>Activo</option>
                            <option value="0" <?= ($data['estado']==0?"selected":"") ?>>Inactivo</option>
                        </select>
                    </div>
                </div>

                <!-- Botones -->
                <div class="box-footer">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="Guardar" class="btn btn-primary"
                                onclick="return confirm('¿Guardar cambios?')">Guardar</button>

                        <a href="?module=producto" class="btn btn-default">Cancelar</a>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>
</div>
</section>

<?php } ?>
