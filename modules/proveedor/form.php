<?php 
    if($_GET["form"]=="add"){ ?>
      <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Agregar Proveedor</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=deposito">Proveedor</a></li>
        <li class="active">Más</li>
      </ol>
      </section>      
      <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/proveedor/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php
                                $query_id = mysqli_query($mysqli, "SELECT MAX(cod_proveedor) as id FROM proveedor;")
                                or die('Error'.mysqli_error($mysqli));
                                $count = mysqli_num_rows($query_id);  
                                if($count <> 0){
                                    $data_id = mysqli_fetch_assoc($query_id);
                                    $codigo = $data_id["id"]+1;
                                } else{
                                    $codigo=1;
                                }                      
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="cod_proveedor" value="<?php echo $codigo; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Razón Social</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="razon_social" placeholder="Ingrese el razón social" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ruc</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="ruc" placeholder="Ingrese el ruc" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Dirección</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="direccion" placeholder="Ingrese la dirección" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">teléfono</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="telefono" placeholder="Ingrese el teléfono" required>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" title="Guardar Datos" onclick="return confirm('Guardar Datos ?')";>
                                        <a href="?module=proveedor" class="btn btn-default btn-reset" title="Cancelar la operación">Cancelar</a>
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
    elseif($_GET["form"]=="edit"){
      if(isset($_GET["id"])){
          $query = mysqli_query($mysqli, "SELECT * FROM proveedor WHERE cod_proveedor = '$_GET[id]';")
            or die('Error'.mysqli_error($mysqli));
          $data = mysqli_fetch_assoc($query);                                          
      }?> 
    <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Modificar Proveedor</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=deposito">Proveedor</a></li>
        <li class="active">Modificar</li>
      </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/proveedor/proses.php?act=update" method="POST">
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="cod_proveedor" value="<?php echo $data['cod_proveedor']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Razón Social</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="razon_social" value="<?php echo $data['razon_social']; ?>" autofocus required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ruc</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="ruc" value="<?php echo $data['ruc']; ?>" autofocus required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Dirección</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="direccion" value="<?php echo $data['direccion']; ?>" autofocus required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Telefóno</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="telefono" value="<?php echo $data['telefono']; ?>" autofocus required>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" title="Los datos se guardarán" onclick="return confirm('Desea modificar los datos ?')";>
                                        <a href="?module=proveedor" class="btn btn-default btn-reset" title="Cancelar la operación">Cancelar</a>
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