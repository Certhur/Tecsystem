<?php 
include "config/database.php";

if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1><i class="fa fa-edit icon-title"></i> Agregar Recepción de Equipo</h1>
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
        <form role="form" class="form-horizontal" action="modules/recepcion_equipo/proses.php?accion=insertar" method="POST">
        <div class="box-body">

            <!-- FECHA -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Fecha Recepción :</label>
                <div class="col-sm-5">
                    <input type="date" class="form-control" name="fecha_recepcion" id="fecha_recepcion"
                           value="<?= date('Y-m-d'); ?>" readonly>
                </div>
            </div>

            <!-- CLIENTE -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Cliente :</label>
                <div class="col-sm-5">
                    <select class="chosen-select" id="cliente" name="cliente" required>
                        <option value="" disabled selected>Seleccione un cliente</option>
                    </select>
                </div>
            </div>

            <!-- MARCA -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Marca :</label>
                <div class="col-sm-5">
                    <select class="chosen-select" id="marca" name="marca" required>
                        <option value="" disabled selected>Seleccione una marca</option>
                    </select>
                </div>
            </div>

            <!-- TIPO EQUIPO -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Tipo Equipo :</label>
                <div class="col-sm-5">
                    <select class="chosen-select" id="tipo_equipo" name="tipo_equipo" required>
                        <option value="" disabled selected>Seleccione un tipo de equipo</option>
                    </select>
                </div>
            </div>

            <!-- MODELO -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Modelo :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="modelo" placeholder="Ingrese el modelo" required>
                </div>
            </div>

            <!-- DESCRIPCIÓN -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Descripción :</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="descripcion" placeholder="Ingrese la descripción del equipo" required></textarea>
                </div>
            </div>

            <!-- BOTONES -->
            <div class="box-footer">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-primary btn-submit" value="Guardar"
                           onclick="return confirm('¿Guardar los datos?')">
                    <a href="?module=recepcion_equipo" class="btn btn-default btn-reset">Cancelar</a>
                </div>
            </div>

        </div></form></div></div></div>

        <!-- === CARGA AUTOMÁTICA DE SELECTS === -->
        <script>
        function cargarSelect(endpoint, selectId, placeholder) {
            $.getJSON("modules/recepcion_equipo/proses.php?accion=" + endpoint, function(data){
                let $sel = $("#" + selectId);
                $sel.empty();
                $sel.append(`<option value="" disabled selected>${placeholder}</option>`);

                data.forEach(item => {
                    let id = Object.values(item)[0];
                    let nombre = Object.values(item)[1];
                    $sel.append(`<option value="${id}">${nombre}</option>`);
                });

                $sel.trigger("chosen:updated");
            });
        }

        $(document).ready(function(){
            cargarSelect("consultarCliente", "cliente", "Seleccione un cliente");
            cargarSelect("consultarMarca", "marca", "Seleccione una marca");
            cargarSelect("consultarTipo_Equipo", "tipo_equipo", "Seleccione un tipo de equipo");
        });
        </script>

    </section>

<?php
} elseif ($_GET['form'] == 'edit') {

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = mysqli_query($mysqli, "
            SELECT re.*, cl.cli_razon_social, m.marca_descrip, te.tipo_descrip
            FROM recepcion_equipo re
            LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
            LEFT JOIN marcas m ON re.id_marca = m.id_marca
            LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
            WHERE re.id_recepcion_equipo = '$id'
        ");
        $data = mysqli_fetch_assoc($query);
    }
?>

<section class="content-header">
    <h1><i class="fa fa-edit icon-title"></i> Modificar Recepción de Equipo</h1>
</section>

<section class="content">
<div class="row"><div class="col-md-12"><div class="box box-primary">
<form role="form" class="form-horizontal" action="modules/recepcion_equipo/proses.php?accion=actualizar" method="POST">
<div class="box-body">

    <input type="hidden" name="id_recepcion_equipo" value="<?= $data['id_recepcion_equipo']; ?>">

    <!-- CLIENTE -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Cliente :</label>
        <div class="col-sm-5">
            <select class="chosen-select" id="cliente" name="cliente" required>
                <option value="<?= $data['id_cliente']; ?>"><?= $data['cli_razon_social']; ?></option>
            </select>
        </div>
    </div>

    <!-- MARCA -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Marca :</label>
        <div class="col-sm-5">
            <select class="chosen-select" id="marca" name="marca" required>
                <option value="<?= $data['id_marca']; ?>"><?= $data['marca_descrip']; ?></option>
            </select>
        </div>
    </div>

    <!-- TIPO EQUIPO -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Tipo Equipo :</label>
        <div class="col-sm-5">
            <select class="chosen-select" id="tipo_equipo" name="tipo_equipo" required>
                <option value="<?= $data['id_tipo_equipo']; ?>"><?= $data['tipo_descrip']; ?></option>
            </select>
        </div>
    </div>

    <!-- MODELO -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Modelo :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="modelo" value="<?= $data['equipo_modelo']; ?>" required>
        </div>
    </div>

    <!-- DESCRIPCIÓN -->
    <div class="form-group">
        <label class="col-sm-2 control-label">Descripción :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="descripcion" value="<?= $data['equipo_descripcion']; ?>" required>
        </div>
    </div>

    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" class="btn btn-primary btn-submit" value="Guardar"
                   onclick="return confirm('¿Desea modificar los datos?')">
            <a href="?module=recepcion_equipo" class="btn btn-default btn-reset">Cancelar</a>
        </div>
    </div>

</div></form></div></div></div>

<script>
// rellenar selects también en editar
$(document).ready(function(){
    cargarSelect("consultarCliente", "cliente", "Seleccione un cliente");
    cargarSelect("consultarMarca", "marca", "Seleccione una marca");
    cargarSelect("consultarTipo_Equipo", "tipo_equipo", "Seleccione un tipo");
});
</script>

</section>

<?php } ?>
