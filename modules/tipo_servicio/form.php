<?php 
    if($_GET['form']=='add'){ ?>
      <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Agregar Tipo de Servicio</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=tipo_servicio"> Tipo de Servicio</a></li>
        <li class="active">Agregar</li>
      </ol>
      </section>   

      <!--funcion para validar entradas que solo permitan textos-->
      <script>
        function validarEntrada(event) {
            var tecla = event.key; // Obtener la tecla presionada

            // Permitir teclas especiales como retroceso (backspace), tab, etc.
            var teclasPermitidas = ['Backspace', 'Tab', 'Delete', 'ArrowLeft', 'ArrowRight', 'Enter'];

            // Si la tecla presionada no está en la lista de teclas permitidas y no es una letra o un espacio
            if (!teclasPermitidas.includes(tecla) && !/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]$/.test(tecla)) {
                event.preventDefault(); // Evitar que se ingrese el carácter
            }
        }
    </script>
    <!--
      <script>
        function validarEntrada(event) {
            var tecla = event.key; // Obtener la tecla presionada
            var codigoTecla = event.keyCode || event.which; // Para navegadores más antiguos

            // Permitir teclas especiales como retroceso (backspace), tab, etc.
            var teclasPermitidas = ['Backspace', 'Tab', 'Delete', 'ArrowLeft', 'ArrowRight'];

            // Si la tecla presionada no está en la lista de teclas permitidas y no es una letra o un espacio
            if (!teclasPermitidas.includes(tecla) && !/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]$/.test(tecla)) {
                event.preventDefault(); // Evitar que se ingrese el carácter
            }
        }
    </script>
    -->
<!-- ************************************************* insert ************************************************** -->
      <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/tipo_servicio/proses.php?act=insert" method="POST">
                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Servicio: </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="descripcion" placeholder="Ingrese la descripcion" autocomplete="off" onkeydown="validarEntrada(event)" required>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Guardar los datos ?')";>
                                        <a href="?module=tipo_servicio" class="btn btn-default btn-reset">Cancelar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      
      </section>  
<!-- ********************************************** update *************************************************** -->
    <?php }
    elseif($_GET['form']=='edit'){
      if(isset($_GET['id'])){
          $query = mysqli_query($mysqli, "SELECT * FROM `tipo_servicio`  WHERE id_tipo_servicio = '$_GET[id]';")
            or die('Error'.mysqli_error($mysqli));
          $data = mysqli_fetch_assoc($query);                                          
      }?> 
      <!--funcion para validar entradas que solo permitan textos-->
            <script>
        function validarEntrada(event) {
            var tecla = event.key; // Obtener la tecla presionada

            // Permitir teclas especiales como retroceso (backspace), tab, etc.
            var teclasPermitidas = ['Backspace', 'Tab', 'Delete', 'ArrowLeft', 'ArrowRight', 'Enter'];

            // Si la tecla presionada no está en la lista de teclas permitidas y no es una letra o un espacio
            if (!teclasPermitidas.includes(tecla) && !/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]$/.test(tecla)) {
                event.preventDefault(); // Evitar que se ingrese el carácter
            }
        }
    </script>
    <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Modificar Tipo Servicio</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=tipo_servicio"> Tipo Servicio </a></li>
        <li class="active">Modificar</li>
      </ol>
    </section>  
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/tipo_servicio/proses.php?act=update" method="POST">
                        <div class="box-body">
   
                            <!-- Combo buscador -->

                            <div class="form-group">
                                <div class="col-sm-5">
                                    <input type="hidden" class="form-control" name="id_tipo_servicio" value="<?php echo $data['id_tipo_servicio']; ?>" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"> Descripción: </label>
                                <div class="col-sm-5">
                                    <input type="text" onkeydown="validarEntrada(event)" class="form-control" name="descripcion" value="<?php echo $data['tipo_servicio_descrip']; ?>" autocomplete="off"  required>
                                </div>
                            </div>
                            
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Desea modificar los datos ?')";>
                                        <a href="?module=tipo_servicio" class="btn btn-default btn-reset">Cancelar</a>
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