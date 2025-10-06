<?php 
    if($_GET['form']=='add'){ ?>
      <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Agregar Clientes</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=clientes"> Clientes</a></li>
        <li class="active">Agregar</li>
      </ol>
      </section>      
<!-- ************************************************* insert ************************************************** -->
      <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/clientes/proses.php?act=insert" method="POST">
                        <div class="box-body">
    
                            <!--<div class="form-group">
                                <label class="col-sm-2 control-label">Código : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                </div>
                            </div>-->

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Razon Social : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="razon_social" placeholder="Ingrese la razon social" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">RUC / C.I : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="ci_ruc" placeholder="Ingrese c.i o ruc" autocomplete="off" 
                                    onkeyPress="return goodchars(event,'0123456789', this)" required>
                                </div>
                            </div>

                                <!-- buscador -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ciudad : </label>
                                <div class="col-sm-5">
                                    <!-- select -->
                                    <select class="chosen-select" name="codigo_ciudad" data-placeholder="Seleccionar Ciudad--" autocomplete="off" required>
                                        <option value=""></option>
                                        <?php 
                                            $query_ciu = mysqli_query($mysqli, "SELECT  ciu.cod_ciudad, ciu.descrip_ciudad, dep.id_departamento, dep.dep_descripcion 
                                            FROM  ciudad ciu INNER JOIN  departamento dep ON  ciu.id_departamento = dep.id_departamento ORDER BY cod_ciudad ASC")
                                            or die('Error'.mysqli_error($mysqli));
                                            while ($data_ciu = mysqli_fetch_assoc($query_ciu)){
                                                echo "<option value=\"$data_ciu[cod_ciudad]\"> | $data_ciu[descrip_ciudad]</option>"; 
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                           
    
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Dirección : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="direccion" placeholder="Ingrese la Dirección" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Teléfono : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="telefono" placeholder="Ingrese el número de teléfono" autocomplete="off" onkeyPress="return goodchars(event,'0123456789', this)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="email" placeholder="Ingrese el email:" autocomplete="off">
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Guardar los datos ?')";>
                                        <a href="?module=clientes" class="btn btn-default btn-reset">Cancelar</a>
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
          $query = mysqli_query($mysqli, "SELECT * FROM `clientes` as cl join ciudad c on cl.cod_ciudad = c.cod_ciudad WHERE cl.id_cliente = '$_GET[id]';")
            or die('Error'.mysqli_error($mysqli));
          $data = mysqli_fetch_assoc($query);                                          
      }?> 
    <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Modificar Clientes</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=clientes"> Clientes</a></li>
        <li class="active">Modificar</li>
      </ol>
    </section>  
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/clientes/proses.php?act=update" method="POST">
                        <div class="box-body">
   
                            <!-- Combo buscador -->

                            <div class="form-group">
                                <div class="col-sm-5">
                                    <input type="hidden" class="form-control" name="id_cliente" value="<?php echo $data['id_cliente']; ?>" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Razon Social : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="razon_social" value="<?php echo $data['cli_razon_social']; ?>" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">RUC / C.I : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="ci_ruc" value="<?php echo $data['ci_ruc']; ?>" autocomplete="off" 
                                    onkeyPress="return goodchars(event,'0123456789', this)" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ciudad : </label>
                                <div class="col-sm-5">
                                    <select class="chosen-select" name="codigo_ciudad" data-placeholder="Seleccionar Ciudad--" autocomplete="off" required>
                                        <option value="<?php echo $data['cod_ciudad']; ?>"><?php echo $data['descrip_ciudad']; ?></option>
                                        <?php 
                                            $query_ciu = mysqli_query($mysqli, "SELECT cod_ciudad, descrip_ciudad
                                            FROM ciudad ciu
                                            ORDER BY cod_ciudad ASC;")
                                            or die('Error'.mysqli_error($mysqli));
                                            while ($data_ciu = mysqli_fetch_assoc($query_ciu)){
                                                echo "<option value=\"$data_ciu[cod_ciudad]\">| $data_ciu[descrip_ciudad]</option>"; 
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Dirección : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="direccion" value="<?php echo $data['cli_direccion']; ?>"  autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="email" value="<?php echo $data['cli_email']; ?>" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Teléfono : </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="telefono" value="<?php echo $data['cli_telefono']; ?>" autocomplete="off" 
                                    onkeyPress="return goodchars(event,'0123456789', this)">
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Desea modificar los datos ?')";>
                                        <a href="?module=clientes" class="btn btn-default btn-reset">Cancelar</a>
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