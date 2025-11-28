<?php 
// ============================================================
// FORMULARIO: AGREGAR / EDITAR UNIDAD DE MEDIDA
// ============================================================

// AGREGAR
if ($_GET["form"] == "add") { ?>
    
<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Agregar Unidad de Medida
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=unidad_medida">Unidad de Medida</a></li>
        <li class="active">Agregar</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <form role="form" class="form-horizontal" 
                      action="modules/unidad_medida/proses.php?act=insert" method="POST">

                    <div class="box-body">

                        <?php
                        // Obtener el próximo ID
                        $query_id = mysqli_query($mysqli, "SELECT MAX(id_u_medida) AS id FROM u_medida")
                                    or die('Error: ' . mysqli_error($mysqli));
                        $data_id = mysqli_fetch_assoc($query_id);
                        $codigo = ($data_id["id"] != "") ? $data_id["id"] + 1 : 1;
                        ?>

                        <!-- Código -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Código</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control"
                                       name="id_u_medida" value="<?php echo $codigo; ?>" readonly>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Unidad de Medida</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control"
                                       name="u_descrip" placeholder="Ej: Unidad, Metro, Caja"
                                       required>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="box-footer">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="Guardar" class="btn btn-primary"
                                        onclick="return confirm('¿Guardar datos?')">
                                    Guardar
                                </button>

                                <a href="?module=unidad_medida" class="btn btn-default">
                                    Cancelar
                                </a>
                            </div>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>
</section>

<?php 
// ============================================================
// EDITAR
// ============================================================
} elseif ($_GET["form"] == "edit") {

    if (isset($_GET["id"])) {

        $query = mysqli_query($mysqli, "
            SELECT * FROM u_medida
            WHERE id_u_medida = '$_GET[id]'
        ") or die('Error: ' . mysqli_error($mysqli));

        $data = mysqli_fetch_assoc($query);
    }
?>

<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i> Modificar Unidad de Medida
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=unidad_medida">Unidad de Medida</a></li>
        <li class="active">Modificar</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <form role="form" class="form-horizontal" 
                      action="modules/unidad_medida/proses.php?act=update" method="POST">

                    <div class="box-body">

                        <!-- Código -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Código</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control"
                                       name="id_u_medida" value="<?php echo $data["id_u_medida"]; ?>" readonly>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Unidad de Medida</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control"
                                       name="u_descrip"
                                       value="<?php echo $data["u_descrip"]; ?>"
                                       required>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="box-footer">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="Guardar" class="btn btn-primary"
                                        onclick="return confirm('¿Desea modificar los datos?')">
                                    Guardar
                                </button>

                                <a href="?module=unidad_medida" class="btn btn-default">
                                    Cancelar
                                </a>
                            </div>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>
</section>

<?php } ?>
