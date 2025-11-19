<?php 
    if($_GET["form"]=="add"){ ?>
      <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Agregar Producto</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=producto">Producto</a></li>
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
                                $query_id = mysqli_query($mysqli, "SELECT MAX(id_producto) as id FROM productos;")
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
                                    <input type="text" class="form-control" name="id_producto" value="<?php echo $codigo; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Unidad de Medida</label>
                                <div class="col-sm-5">
                                    <select name="id_u_medida" class="form-control">
                                        <option value="">Seleccione la unidad de medida</option>
                                        <?php 
                                            $query = mysqli_query($mysqli, "SELECT * FROM u_medida;")or die('Error'.mysqli_error($mysqli));
                                            while($data = mysqli_fetch_assoc($query)){
                                            echo "<option value='".$data['id_u_medida']."'";
                                            if(isset($_POST['id_u_medida']) && $_POST['id_u_medida']==$data['id_u_medida'])
                                            echo "selected";
                                            echo ">";
                                            echo $data['u_descrip'];
                                            echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                                <div class="form-group">
                                <label class="col-sm-2 control-label">Proveedor</label>
                                <div class="col-sm-5">
                                    <select name="cod_proveedor" class="form-control">
                                        <option value="">Seleccione el Proveedor</option>
                                        <?php 
                                            $query = mysqli_query($mysqli, "SELECT * FROM proveedor;")or die('Error'.mysqli_error($mysqli));
                                            while($data = mysqli_fetch_assoc($query)){
                                            echo "<option value='".$data['cod_proveedor']."'";
                                            if(isset($_POST['cod_proveedor']) && $_POST['cod_proveedor']==$data['cod_proveedor'])
                                            echo "selected";
                                            echo ">";
                                            echo $data['razon_social'];
                                            echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Marca</label>
                                <div class="col-sm-5">
                                    <select name="id_marca" id="" class="form-control">      
                                        <option value="">Selecciona la Marca</option>
                                            <?php 
                                                $query = mysqli_query($mysqli, "SELECT * FROM marcas;")or die('Error'.mysqli_error($mysqli));
                                                while($data = mysqli_fetch_assoc($query)){
                                                    echo "<option value='".$data['id_marca']."'";
                                                    /*if($_POST['id_marca']==$data['id_marca'])*/
                                                    if (isset($_POST['id_marca']) && $_POST['id_marca'] == $data['id_marca'])
                                                    echo "selected";
                                                    echo ">";
                                                    echo $data['marca_descrip'];
                                                    echo "</option>";
                                                }
                                            ?>
                                    </select>    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Producto</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="p_descrip" placeholder="Ingrese el nombre del producto" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Precio</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" name="p_costo_actual" placeholder="Ingrese el precio" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Precio Servicio</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" name="p_precio_servicio" placeholder="Ingrese el precio" required>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" title="Guardar Datos" onclick="return confirm('Guardar Datos ?');">
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
      if(isset($_GET["id_producto"])){
          $query = mysqli_query($mysqli, "SELECT * FROM productos WHERE id_producto = '$_GET[id_producto]';")or die('Error'.mysqli_error($mysqli));
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
                                    <input type="text" class="form-control" name="id_producto" value="<?php echo $data['id_producto']; ?>" readonly>
                                </div>
                            </div>
                            <?php
                                $queryUnidadMedida = mysqli_query($mysqli, "SELECT * FROM u_medida WHERE id_u_medida='$_GET[id_u_medida]';")
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
                                                if(isset($_POST['id_u_medida']) && $_POST['id_u_medida']==$data2['id_u_medida'])
                                                echo "SELECTED";
                                                echo ">";
                                                echo $data2['u_descrip'];
                                                echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                                $queryProveedor = mysqli_query($mysqli, "SELECT * FROM proveedor WHERE cod_proveedor='$_GET[cod_proveedor]';")
                                or die('Error'.mysqli_error($mysqli));
                                $dataProveedor = mysqli_fetch_assoc($queryProveedor);
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Proveedor</label>
                                <div class="col-sm-5">
                                    <select name="cod_proveedor" class="form-control">
                                        <option value="<?php echo $data['cod_proveedor'] ?>"><?php echo $dataProveedor['razon_social'] ?></option>
                                        <?php
                                            $query = mysqli_query($mysqli, "SELECT * FROM proveedor;")or die('Error'.mysqli_error($mysqli));
                                            while($data2 = mysqli_fetch_assoc($query)){
                                                echo "<option value='".$data2['cod_proveedor']."'";
                                                if(isset($_POST['cod_proveedor']) && $_POST['cod_proveedor']==$data2['cod_proveedor'])
                                                echo "SELECTED";
                                                echo ">";
                                                echo $data2['razon_social'];
                                                echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                             <?php
                                $queryMarca = mysqli_query($mysqli, "SELECT * FROM marcas WHERE id_marca='$_GET[id_marca]';")
                                or die('Error'.mysqli_error($mysqli));
                                $dataMarca = mysqli_fetch_assoc($queryMarca);
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Marca</label>
                                <div class="col-sm-5">
                                    <select name="id_marca" class="form-control">
                                        <option value="<?php echo $data['id_marca'] ?>"><?php echo $dataMarca['marca_descrip'] ?></option>
                                        <?php
                                            $query = mysqli_query($mysqli, "SELECT * FROM marcas;")or die('Error'.mysqli_error($mysqli));
                                            while($data2 = mysqli_fetch_assoc($query)){
                                                echo "<option value='".$data2['id_marca']."'";
                                                if(isset($_POST['id_marca']) && $_POST['id_marca']==$data2['id_marca'])
                                                echo "SELECTED";
                                                echo ">";
                                                echo $data2['marca_descrip'];
                                                echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Producto</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="p_descrip" value="<?php echo $data['p_descrip']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Precio</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" name="p_costo_actual" value="<?php echo $data['p_costo_actual']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Precio Servicio</label>
                                <div class="col-sm-5">
                                    <input type="number" class="form-control" name="p_precio_servicio" value="<?php echo $data['p_precio_servicio']; ?>" required>
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