<?php
if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Diagnóstico</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=diagnostico">Diagnóstico</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/diagnostico/proses.php?accion=insertar" method="POST">
                        <div class="box-body">

                            <!-- Seleccionar equipo recepcionado -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Equipo Recepcionado:</label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="recepcion_equipo" name="id_recepcion_equipo" autocomplete="off" required>
                                        <option value="" selected disabled>Selecciona un equipo</option>
                                    </select>
                                </div>
                            </div>
<!--
                            // Datos del equipo (solo mostrar)
                            <div id="datos_equipo" style="margin-left:15%;">
                                <p><strong>Cliente:</strong> <span id="cliente_text"></span></p>
                                <p><strong>Marca:</strong> <span id="marca_text"></span></p>
                                <p><strong>Tipo de Equipo:</strong> <span id="tipo_text"></span></p>
                                <p><strong>Modelo:</strong> <span id="modelo_text"></span></p>
                                <p><strong>Descripción:</strong> <span id="descripcion_text"></span></p>
                                <p><strong>Fecha Recepción:</strong> <span id="fecha_text"></span></p>
                            </div>
-->
                            <!-- Datos del equipo (solo mostrar) -->

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cliente:</label>
                                <div class="col-sm-5">
                                    <input type="text" id="cli_razon_social" name="cli_razon_social" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Marca:</label>
                                <div class="col-sm-5">
                                    <input type="text" id="marca_descrip" name="marca_descrip" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo de Equipo:</label>
                                <div class="col-sm-5">
                                    <input type="text" id="tipo_descrip" name="tipo_descrip" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Modelo:</label>
                                <div class="col-sm-5">
                                    <input type="text" id="equipo_modelo" name="equipo_modelo" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descripción:</label>
                                <div class="col-sm-5">
                                    <input type="text" id="equipo_descripcion" name="equipo_descripcion" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fecha Recepción:</label>
                                <div class="col-sm-5">
                                    <input type="text" id="fecha_recepcion" name="fecha_recepcion" class="form-control" readonly>
                                </div>
                            </div>



                            <!-- Campos de diagnóstico -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Falla:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="falla_diagnostico" placeholder="Ingrese la falla" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Causa:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="causa_diagnostico" placeholder="Ingrese la causa" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Solución:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="solucion_diagnostico" placeholder="Ingrese la solución" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Observaciones:</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" name="observaciones" placeholder="Observaciones adicionales"></textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" value="Guardar" onclick="return confirm('Guardar diagnóstico?')">
                                        <a href="?module=diagnostico" class="btn btn-default btn-reset">Cancelar</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php } elseif ($_GET['form'] == 'edit') {
    if (isset($_GET['id'])) {
        include "../../config/database.php";
        $query = mysqli_query($mysqli, "SELECT * FROM diagnostico WHERE id_diagnostico='$_GET[id]'") or die(mysqli_error($mysqli));
        $data = mysqli_fetch_assoc($query);
    ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Modificar Diagnóstico</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=diagnostico">Diagnóstico</a></li>
            <li class="active">Modificar</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/diagnostico/proses.php?accion=actualizar" method="POST">
                        <div class="box-body">
                            <input type="hidden" name="id_diagnostico" value="<?php echo $data['id_diagnostico']; ?>">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Falla:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="falla_diagnostico" value="<?php echo $data['falla_diagnostico']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Causa:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="causa_diagnostico" value="<?php echo $data['causa_diagnostico']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Solución:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="solucion_diagnostico" value="<?php echo $data['solucion_diagnostico']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Observaciones:</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" name="observaciones"><?php echo $data['observaciones']; ?></textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" value="Guardar Cambios" onclick="return confirm('Actualizar diagnóstico?')">
                                        <a href="?module=diagnostico" class="btn btn-default btn-reset">Cancelar</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php }
} ?>

<script>
    // URL para obtener las recepciones
    var urlRecepcion = window.location.protocol + "//" + window.location.hostname + "/TecSystem-master/modules/diagnostico/proses.php?accion=consultarRecepcion";

    $(document).ready(function() {
        // Cargar select de recepciones
        $.getJSON(urlRecepcion, function(response) {
            var select = $('#recepcion_equipo');
            select.empty();
            select.append('<option value="" selected disabled>Selecciona un equipo</option>');
            $.each(response, function(i, item) {
                select.append('<option value="'+item.id_recepcion_equipo+'">'+item.equipo_modelo+' - '+item.cli_razon_social+'</option>');
            });
            select.trigger('chosen:updated');
        });

        // Cuando se selecciona un equipo, traer datos del cliente y equipo
        $('#recepcion_equipo').change(function() {
            var id = $(this).val();
            if(id){
                $.getJSON('modules/diagnostico/proses.php?accion=datosRecepcion&id='+id, function(data){
                    // Llenar los inputs readonly usando .val()
                    $('#cli_razon_social').val(data.cli_razon_social);
                    $('#marca_descrip').val(data.marca_descrip);
                    $('#tipo_descrip').val(data.tipo_descrip);
                    $('#equipo_modelo').val(data.equipo_modelo);
                    $('#equipo_descripcion').val(data.equipo_descripcion);
                    $('#fecha_recepcion').val(data.fecha_recepcion);
                });
            }
        });
    });
</script>

