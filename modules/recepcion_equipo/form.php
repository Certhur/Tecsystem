<?php
if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Agregar Recepcion de Equipo</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=form_recepcion_equipo"> Recepcion de Equipo</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>
    <!-- ************************************************* insert ************************************************** -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/recepcion_equipo/proses.php?accion=insertar" method="POST">
                        <div class="box-body">


                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fecha Recepcion : </label>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" name="fecha_recepcion" id="fecha_recepcion" autocomplete="off" readonly>
                                </div>
                            </div>

                            <!-- buscador cliente -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cliente : </label>
                                <div class="col-sm-5">
                                    <!-- select -->
                                    <select class="chosen-select" id="cliente" name="cliente" onchange="abrirModalSiEsAgregar(this)" autocomplete="off" required>
                                    </select>
                                </div>
                            </div>

                            <!-- buscador marca -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Marca : </label>
                                <div class="col-sm-5">
                                    <!-- select -->
                                    <select class="chosen-select" id="marca" name="marca" autocomplete="off" onchange="abrirModalSiEsAgregar(this)" required>

                                    </select>
                                </div>
                            </div>


                            <!-- buscador tipo equipo -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo Equipo : </label>
                                <div class="col-sm-5">
                                    <!-- select -->
                                    <select class="chosen-select" id="tipo_equipo" name="tipo_equipo" autocomplete="off" onchange="abrirModalSiEsAgregar(this)" required>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label">Modelo : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="modelo" placeholder="Ingrese el Modelo" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descripcion : </label>
                                <div class="col-sm-5">
                                    <textarea type="text" class="form-control" name="descripcion" placeholder="Ingrese la descripcion del equipo" autocomplete="off"></textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Guardar los datos ?')" ;>
                                        <a href="?module=recepcion_equipo" class="btn btn-default btn-reset">Cancelar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ********************************************** Modal Agregar Marca *************************************************** -->
        <div class="modal fade" id="modalAgregarMarca" tabindex="-1" role="dialog" aria-labelledby="modalAgregarMarcaLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarMarcaLabel">Agregar Nueva Marca</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formAgregarMarca" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nuevaMarca">Nombre de la Marca</label>
                                <input type="text" class="form-control" id="nuevaMarca" name="nuevaMarca" placeholder="Ingrese el nombre de la nueva marca" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Marca</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

               <!-- ********************************************** Modal Agregar Tipo Equipo *************************************************** -->
               <div class="modal fade" id="modalAgregarTipoEquipo" tabindex="-1" role="dialog" aria-labelledby="modalAgregarTipoEquipo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarTipoEquipoLabel">Agregar Nuevo Tipo de Equipo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formAgregarTipoEquipo" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nuevoTipoEquipo">Nombre del Tipo de Equipo</label>
                                <input type="text" class="form-control" id="nuevoTipoEquipo" name="nuevoTipoEquipo" placeholder="Ingrese el nombre del nuevo tipo de equipo" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Tipo de Equipo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ********************************************** Modal Agregar Cliente *************************************************** -->

        <div class="modal fade" id="modalAgregarCliente" tabindex="-1" role="dialog" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Nuevo Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formAgregarCliente" method="POST">
                        <div class="modal-body">
                            <div class="form-group">

                                <label for="razon_social">Razón Social</label>
                                <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Ingrese la razón social" required>
                                <label for="ruc_ci">RUC / C.I</label>
                                <input type="text" class="form-control" id="ruc_ci" name="ruc_ci" placeholder="Ingrese el RUC o C.I del cliente" required>
                                <label for="ciudad">Ciudad</label>
                                <select class="chosen-select" name="ciudad" id="ciudad" data-placeholder="Seleccionar Ciudad" autocomplete="off" required>
                                    <!-- Las opciones de ciudad se cargarán aquí mediante AJAX -->
                                </select>
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingrese la dirección" required>
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese el email" required>
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese el teléfono" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- ********************************************** update *************************************************** -->
<?php } elseif ($_GET['form'] == 'edit') {
    if (isset($_GET['id'])) {
        $query = mysqli_query($mysqli, "SELECT * FROM recepcion_equipo AS re
                            LEFT JOIN tipo_equipo AS e ON re.id_tipo_equipo = e.id_tipo_equipo
                            LEFT JOIN marcas AS m ON re.id_marca = m.id_marca
                            LEFT JOIN tipo_equipo AS te ON re.id_tipo_equipo = te.id_tipo_equipo
                            LEFT JOIN clientes AS cl ON re.id_cliente = cl.id_cliente WHERE re.id_recepcion_equipo = '$_GET[id]';")
            or die('Error' . mysqli_error($mysqli));
        $data = mysqli_fetch_assoc($query);
    } ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-edit icon-title">Modificar Recepcion Equipo</i>
        </h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=recepcion_equipo"> Recepcion Equipo</a></li>
            <li class="active">Modificar</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/recepcion_equipo/proses.php?accion=actualizar" method="POST">
                        <div class="box-body">

                            <!-- Combo buscador -->

                            <div class="form-group">
                                <div class="col-sm-5">
                                    <input type="hidden" class="form-control" name="id_recepcion_equipo" value="<?php echo $data['id_recepcion_equipo']; ?>" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cliente : </label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="cliente" name="cliente" autocomplete="off" required>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Marca: </label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="marca" name="marca" autocomplete="off" required>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo Equipo : </label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" id="tipo_equipo" name="tipo_equipo" autocomplete="off" required>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Modelo : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="modelo" value="<?php echo $data['equipo_modelo']; ?>" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descripcion : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="descripcion" value="<?php echo $data['equipo_descripcion']; ?>" autocomplete="off"
                                        onkeyPress="return goodchars(event,'0123456789', this)" required>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Desea modificar los datos ?')" ;>
                                        <a href="?module=recepcion_equipo" class="btn btn-default btn-reset">Cancelar</a>
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


<script>


    var direccionCliente = window.location.protocol + "//" + window.location.hostname + "/TecSystem-master/modules/recepcion_equipo/proses.php?accion=consultarCliente";
    var direccionMarca = window.location.protocol + "//" + window.location.hostname + "/TecSystem-master/modules/recepcion_equipo/proses.php?accion=consultarMarca";
    var direccionTipoEquipo = window.location.protocol + "//" + window.location.hostname + "/TecSystem-master/modules/recepcion_equipo/proses.php?accion=consultarTipo_Equipo";
    var direccionCiudad = window.location.protocol + "//" + window.location.hostname + "/TecSystem-master/modules/recepcion_equipo/proses.php?accion=consultarCiudad";




    $(document).ready(function() {
        // Llamar a la función pasando la URL y el ID del select con el placeholder específico
        cargarOpcionesSelect(direccionCliente, 'cliente', 'Selecciona el Cliente'); // Cargar clientes
        cargarOpcionesSelect(direccionMarca, 'marca', 'Selecciona la Marca'); // Cargar marcas
        cargarOpcionesSelect(direccionTipoEquipo, 'tipo_equipo', 'Selecciona el Tipo de Equipo');//Cargar Tipo Equipo
        cargarOpcionesSelect(direccionCiudad, 'ciudad', 'Selecciona la ciudad'); // Cargar ciudad Modal Cliente
    });

    //Funcion para devolver la consulta a los select 
    function cargarOpcionesSelect(url, selectId, placeholder) {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response); // Depurar la respuesta

                const select = $(`#${selectId}`);

                // Limpiar las opciones existentes sin eliminar el placeholder si ya está
                select.empty();

                // Añadir el placeholder primero
                select.append(`<option value="" selected disabled>${placeholder}</option>`);

                if (Array.isArray(response)) {
                    // Añadir las opciones dinámicas
                    response.forEach(item => {
                        if (selectId === 'cliente') {
                            select.append(new Option(item.cli_razon_social, item.id_cliente)); // Para cliente
                        } else if (selectId === 'marca') {
                            select.append(new Option(item.marca_descrip, item.id_marca)); // Para marca
                        } else if (selectId === 'tipo_equipo') {
                            select.append(new Option(item.tipo_descrip, item.id_tipo_equipo)); // Para tipo equipo
                        } else if (selectId === 'ciudad') {
                            select.append(new Option(item.descrip_ciudad, item.cod_ciudad)); //Para ciudad de modal cliente
                        }
                    });

                    // Añadir la opción "Agregar nuevo 
                    if (selectId === 'cliente') {
                        select.append(new Option("Agregar nuevo cliente", "nueva"));
                    } else if (selectId === 'marca') {
                        select.append(new Option("Agregar nueva marca", "nueva"));
                    } else if (selectId === 'tipo_equipo') {
                        select.append(new Option("Agregar nuevo Tipo de Equipo", "nueva"));
                    }
                    // Actualizar Chosen después de agregar las opciones
                    select.trigger("chosen:updated"); 

                } else {
                    console.error("La respuesta no es un array válido");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar las opciones:", error);
            }
        });
    }

     // Función para abrir el modal si se selecciona el select "Agregar nueva ...."

     function abrirModalSiEsAgregar(selectElement) {
        // Verificar si se seleccionó la opción de agregar nuevo (opción 'nueva')
        if (selectElement.value === "nueva") {
            // Verificar el ID del select para determinar qué modal abrir
            if (selectElement.id === "marca") {
                $('#modalAgregarMarca').modal('show'); // Abre el modal para agregar nueva marca
            } else if (selectElement.id === "cliente") {
                $('#modalAgregarCliente').modal('show'); // Abre el modal para agregar nuevo cliente
            } else if (selectElement.id === "tipo_equipo"){
                $('#modalAgregarTipoEquipo').modal('show'); // Abre el modal para agregar nuevo tipo equipo
            }
            selectElement.value = ""; // Restaura el select a su estado inicial
        }
    }


//Funcion para asignar la fecha (dd/mm/aaaa) automaticamente 
    $(document).ready(function() {
        function agregarFechaActual() {
            // Obtener la fecha actual
            const fechaActual = new Date();

            // Formatear la fecha a "YYYY-MM-DD"
            const year = fechaActual.getFullYear();
            const month = String(fechaActual.getMonth() + 1).padStart(2, '0');
            const day = String(fechaActual.getDate()).padStart(2, '0');

            const fechaFormateada = `${year}-${month}-${day}`;

            // Asignar la fecha al input
            $('#fecha_recepcion').val(fechaFormateada);
        }

        // Llamar a la función para agregar la fecha al cargar la página
        agregarFechaActual();
    });



   


    $(document).ready(function() {

        // Enviar el formulario del modal al hacer clic en "Guardar Marca"
        $('#formAgregarMarca').submit(function(e) {
            e.preventDefault(); // Evitar el envío normal del formulario
            const nuevaMarca = $('#nuevaMarca').val();
            const submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true);

            $.ajax({
                url: "modules/recepcion_equipo/proses.php?accion=guardarMarca", // Ruta a tu archivo de procesamiento
                type: "POST",
                data: {
                    nuevaMarca: nuevaMarca
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Agregar la nueva opción al select
                        $('#marca option[value="nueva"]').remove(); // Quitar temporalmente la opción "Agregar nueva marca"
                        $('#marca').append(new Option(response.marca_descrip, response.id_marca)); // Añadir la nueva marca
                        $('#marca').append(new Option("Agregar nueva marca", "nueva")); // Reagregar "Agregar nueva marca" al final



                        // Actualizar y refrescar el select si estás usando un plugin como Chosen o Select2
                        $('#marca').trigger('chosen:updated');


                        // Limpiar y cerrar el modal
                        $('#formAgregarMarca')[0].reset();
                        $('#modalAgregarMarca').modal('hide');
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function() {
                    alert("Error al guardar la nueva marca");
                }
            });
        });
    });

    $(document).ready(function() {

// Enviar el formulario del modal al hacer clic en "Guardar Tipo Equipo"
$('#formAgregarTipoEquipo').submit(function(e) {
    e.preventDefault(); // Evitar el envío normal del formulario
    const nuevoTipoEquipo= $('#nuevoTipoEquipo').val();
    const submitButton = $(this).find('button[type="submit"]');
    submitButton.prop('disabled', true);

    $.ajax({
        url: "modules/recepcion_equipo/proses.php?accion=guardarTipoEquipo", // Ruta a tu archivo de procesamiento
        type: "POST",
        data: {
            nuevoTipoEquipo: nuevoTipoEquipo
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                // Agregar la nueva opción al select
                $('#tipo_equipo option[value="nueva"]').remove(); // Quitar temporalmente la opción "Agregar nueva marca"
                $('#tipo_equipo').append(new Option(response.tipo_descrip, response.id_tipo_equipo)); // Añadir la nueva marca
                $('#tipo_equipo').append(new Option("Agregar nuevo Tipo de Equipo", "nueva")); // Reagregar "Agregar nueva marca" al final



                // Actualizar y refrescar el select si estás usando un plugin como Chosen o Select2
                $('#tipo_equipo').trigger('chosen:updated');


                // Limpiar y cerrar el modal
                $('#formAgregarTipoEquipo')[0].reset();
                $('#modalAgregarTipoEquipo').modal('hide');
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function() {
            alert("Error al guardar el nuevo tipo de equipo");
        }
    });
});
});


    $(document).ready(function() {

        $('#formAgregarCliente').submit(function(e) {
            e.preventDefault();
            const razon_social = $('#razon_social').val();
            const ruc_ci = $('#ruc_ci').val();
            const ciudad = $('#ciudad').val();
            const direccion = $('#direccion').val();
            const email = $('#email').val();
            const telefono = $('#telefono').val();

            const submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true); // Deshabilitar el botón de enviar para evitar múltiples envíos

            // Realizar la solicitud AJAX
            $.ajax({
                url: "modules/recepcion_equipo/proses.php?accion=guardarCliente", // Asegúrate de que esta ruta sea correcta
                type: "POST",
                data: {
                    razon_social: razon_social,
                    ruc_ci: ruc_ci,
                    ciudad: ciudad,
                    direccion: direccion,
                    email: email,
                    telefono: telefono
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Agregar el nuevo cliente al select de clientes si es necesario
                        $('#cliente option[value="nueva"]').remove(); // Quitar temporalmente la opción Agregar nuevo cliente
                        $('#cliente').append(new Option(response.razon_social, response.id_cliente));
                        $('#cliente').append(new Option("Agregar nuevo cliente", "nueva")); // Reagregar "Agregar nueva cliente al final


                        // Actualizar y refrescar el select si estás usando un plugin como Chosen o Select2
                        $('#cliente').trigger('chosen:updated');

                        // Limpiar y cerrar el modal
                        $('#formAgregarCliente')[0].reset();
                        $('#modalAgregarCliente').modal('hide');
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function() {
                    alert("Error al guardar el nuevo cliente");
                },
                complete: function() {
                    submitButton.prop('disabled', false); // Habilitar el botón de submit de nuevo
                }
            });

        });
    });

    
</script>