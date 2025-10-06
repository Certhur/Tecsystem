<?php 
    if($_GET['form']=='add'){ ?>
      <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Agregar Marca</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=marcas"> Marcas</a></li>
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
<!-- ************************************************* insert ************************************************** -->
      <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/marcas/proses.php?act=insert" method="POST">
                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descripción: </label>
                                <div class="col-sm-5">
                                    <input type="text" onkeydown="validarEntrada(event)" class="form-control" name="descripcion" placeholder="Ingrese la descripcion" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Guardar los datos ?')";>
                                        <a href="?module=marcas" class="btn btn-default btn-reset">Cancelar</a>
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
          $query = mysqli_query($mysqli, "SELECT * FROM `marcas`  WHERE id_marca = '$_GET[id]';")
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
        <i class="fa fa-edit icon-title">Modificar Marca</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=marcas"> Marca </a></li>
        <li class="active">Modificar</li>
      </ol>
    </section>  
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/marcas/proses.php?act=update" method="POST">
                        <div class="box-body">
   
                            <!-- Combo buscador -->

                            <div class="form-group">
                                <div class="col-sm-5">
                                    <input type="hidden" class="form-control" name="id_marca" value="<?php echo $data['id_marca']; ?>" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"> Descripción: </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" onkeydown="validarEntrada(event)" name="descripcion" value="<?php echo $data['marca_descrip']; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Desea modificar los datos ?')";>
                                        <a href="?module=marcas" class="btn btn-default btn-reset">Cancelar</a>
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