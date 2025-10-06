<?php 
    if($_GET["form"]=="add"){ ?>
      <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Agregar Producto</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=deposito">Producto</a></li>
        <li class="active">Más</li>
      </ol>
      </section>      
      <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/producto/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php
                                $query_id = mysqli_query($mysqli, "SELECT MAX(cod_producto) as id FROM producto;")
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
                                    <input type="text" class="form-control" name="cod_producto" value="<?php echo $codigo; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo de Producto</label>
                                <div class="col-sm-5">
                                    <select name="cod_tipo_prod" class="form-control">
                                        <option value="">Seleccione el tipo de producto</option>
                                        <?php 
                                            $query = mysqli_query($mysqli, "SELECT * FROM tipo_producto;")or die('Error'.mysqli_error($mysqli));
                                            while($data = mysqli_fetch_assoc($query)){
                                            echo "<option value='".$data['cod_tipo_prod']."'";
                                            if($_POST['cod_tipo_prod']==$data['cod_tipo_prod'])
                                            echo "SELECTED";
                                            echo ">";
                                            echo $data['t_p_descrip'];
                                            echo "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Unidad de Medida</label>
                                <div class="col-sm-5">
                                    <select name="id_u_medida" class="form-control">
                                        <option value="">Selecciones la unidad de medida</option>
                                        <?php 
                                            $query = mysqli_query($mysqli, "SELECT * FROM u_medida;")or die('Error'.mysqli_error($mysqli));
                                            while($data = mysqli_fetch_assoc($query)){
                                            echo "<option value='".$data['id_u_medida']."'";
                                            if($_POST['id_u_medida']==$data['id_u_medida'])
                                            echo "SELECTED";
                                            echo ">";
                                            echo $data['u_descrip'];
                                            echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nombre Producto</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="p_descrip" placeholder="Ingrese el nombre del producto" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Precio</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" name="precio" placeholder="Ingrese el precio" required>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" title="Guardar Datos" onclick="return confirm('Guardar Datos ?')";>
                                        <a href="?module=producto" class="btn btn-default btn-reset" title="Cancelar la operación">Cancelar</a>
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
          $query = mysqli_query($mysqli, "SELECT * FROM producto WHERE cod_producto = '$_GET[id]';")or die('Error'.mysqli_error($mysqli));
          $data = mysqli_fetch_assoc($query);                                          
      }?> 
    <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Modificar Producto</i>
      </h1>
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
                    <form role="form" class="form-horizontal" action="modules/producto/proses.php?act=update" method="POST">
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="cod_producto" value="<?php echo $data['cod_producto']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo de producto</label>
                                <div class="col-sm-5">
                                    <select name="cod_tipo_prod" class="form-control">
                                        <option value="<?php echo $data['cod_tipo_prod'] ?>"><?php echo $data['p_descrip'] ?></option>
                                        <?php 
                                            $query = mysqli_query($mysqli, "SELECT * FROM tipo_producto;")
                                            or die('Error'.mysqli_error($mysqli));
                                            while($data2 = mysqli_fetch_assoc($query)){
                                                echo "<option value='".$data2['cod_tipo_prod']."'";
                                                if($_POST['cod_tipo_prod']==$data2['cod_tipo_prod'])
                                                echo "SELECTED";
                                                echo ">";
                                                echo $data2['t_p_descrip'];
                                                echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                                $queryUnidadMedida = mysqli_query($mysqli, "SELECT * FROM u_medida WHERE id_u_medida='$_GET[idMedida]';")
                                or die('Error'.mysqli_error($mysqli));
                                $dataUnidadMedida = mysqli_fetch_assoc($queryUnidadMedida);
                                // echo "id_u_medida == >". $_GET['idMedida'];
                                // echo "<br>id ==>". $_GET['id'];
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Unidad de medida</label>
                                <div class="col-sm-5">
                                    <select name="id_u_medida" class="form-control">
                                        <option value="<?php echo $data['id_u_medida'] ?>"><?php echo $dataUnidadMedida['u_descrip'] ?></option>
                                        <?php
                                            $query = mysqli_query($mysqli, "SELECT * FROM u_medida;")or die('Error'.mysqli_error($mysqli));
                                            while($data2 = mysqli_fetch_assoc($query)){
                                                echo "<option value='".$data2['id_u_medida']."'";
                                                if($_POST['id_u_medida']==$data2['id_u_medida'])
                                                echo "SELECTED";
                                                echo ">";
                                                echo $data2['u_descrip'];
                                                echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descripción del producto</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="p_descrip" value="<?php echo $data['p_descrip']; ?>" autofocus required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Precio</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" name="precio" value="<?php echo $data['precio']; ?>" autofocus required>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" title="Los datos se guardarán" onclick="return confirm('Desea modificar los datos ?')";>
                                        <a href="?module=producto" class="btn btn-default btn-reset" title="Cancelar la operación">Cancelar</a>
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