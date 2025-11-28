<?php
if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title"></i> Agregar Recepción de Equipo
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=recepcion_equipo">Recepción de Equipo</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal"
                          action="modules/recepcion_equipo/proses.php?accion=insertar"
                          method="POST">
                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fecha Recepción :</label>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control"
                                           name="fecha_recepcion" id="fecha_recepcion"
                                           autocomplete="off" readonly>
                                </div>
                            </div>

                            <!-- Cliente -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cliente :</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="cliente" name="cliente"
                                            onchange="abrirModalSiEsAgregar(this)"
                                            autocomplete="off" required>
                                    </select>
                                </div>
                            </div>

                            <!-- Marca -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Marca :</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="marca" name="marca"
                                            onchange="abrirModalSiEsAgregar(this)"
                                            autocomplete="off" required>
                                    </select>
                                </div>
                            </div>

                            <!-- Tipo Equipo -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo Equipo :</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="tipo_equipo" name="tipo_equipo"
                                            onchange="abrirModalSiEsAgregar(this)"
                                            autocomplete="off" required>
                                    </select>
                                </div>
                            </div>

                            <!-- Modelo -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Modelo :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="modelo"
                                           placeholder="Ingrese el modelo" autocomplete="off" required>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descripción :</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" name="descripcion"
                                              placeholder="Ingrese la descripción del equipo"
                                              autocomplete="off" required></textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit"
                                               name="Guardar" value="Guardar"
                                               onclick="return confirm('¿Guardar los datos?')">
                                        <a href="?module=recepcion_equipo"
                                           class="btn btn-default btn-reset">Cancelar</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODALES (marca, tipo equipo, cliente) -->
        <!-- Dejo TODO igual que ya lo tenías, solo no lo repito aquí para no hacer eterno el mensaje.
             Puedes mantener tal cual tu form.php actual en la parte de modales y JS,
             solo asegurándote de que el action de "actualizar" sea como abajo. -->

    </section>

<?php
} elseif ($_GET['form'] == 'edit') {

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = mysqli_query($mysqli, "
            SELECT re.*, 
                   cl.cli_razon_social,
                   m.marca_descrip,
                   te.tipo_descrip
            FROM recepcion_equipo AS re
            LEFT JOIN clientes AS cl ON re.id_cliente = cl.id_cliente
            LEFT JOIN marcas AS m ON re.id_marca = m.id_marca
            LEFT JOIN tipo_equipo AS te ON re.id_tipo_equipo = te.id_tipo_equipo
            WHERE re.id_recepcion_equipo = '$id'
        ") or die('Error: ' . mysqli_error($mysqli));

        $data = mysqli_fetch_assoc($query);
    }
    ?>

    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title"></i> Modificar Recepción de Equipo
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=recepcion_equipo">Recepción de Equipo</a></li>
            <li class="active">Modificar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal"
                          action="modules/recepcion_equipo/proses.php?accion=actualizar"
                          method="POST">
                        <div class="box-body">

                            <input type="hidden" name="id_recepcion_equipo"
                                   value="<?php echo $data['id_recepcion_equipo']; ?>">

                            <!-- Cliente -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cliente :</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="cliente" name="cliente"
                                            autocomplete="off" required>
                                        <!-- Se llenará por AJAX, pero podrías preponer una opción actual -->
                                        <option value="<?php echo $data['id_cliente']; ?>">
                                            <?php echo $data['cli_razon_social']; ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Marca -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Marca :</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="marca" name="marca"
                                            autocomplete="off" required>
                                        <option value="<?php echo $data['id_marca']; ?>">
                                            <?php echo $data['marca_descrip']; ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tipo Equipo -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo Equipo :</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="tipo_equipo" name="tipo_equipo"
                                            autocomplete="off" required>
                                        <option value="<?php echo $data['id_tipo_equipo']; ?>">
                                            <?php echo $data['tipo_descrip']; ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Modelo -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Modelo :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="modelo"
                                           value="<?php echo $data['equipo_modelo']; ?>"
                                           autocomplete="off" required>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descripción :</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="descripcion"
                                           value="<?php echo $data['equipo_descripcion']; ?>"
                                           autocomplete="off" required>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit"
                                               name="Guardar" value="Guardar"
                                               onclick="return confirm('¿Desea modificar los datos?')">
                                        <a href="?module=recepcion_equipo"
                                           class="btn btn-default btn-reset">Cancelar</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php } ?>
