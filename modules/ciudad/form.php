<?php 
    if($_GET["form"]=="add"){ ?>
      <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Agregar Ciudad</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=ciudad"> Ciudad</a></li>
        <li class="active">Agregar</li>
      </ol>
      </section>      
      <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/ciudad/proses.php?act=insert" method="POST">
                        <div class="box-body">

                            <?php
                                $query_id = mysqli_query($mysqli, "SELECT MAX(cod_ciudad) as id FROM ciudad;")
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
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Departamento</label>
                                <div class="col-sm-5">
                                    <select name="departamento" class="form-control" >
                                        <option value="" disabled selected>Seleccione un departamento</option>
                                        <?php 
                                            $query = mysqli_query($mysqli, "SELECT * from departamento;")
                                            or die('Error'.mysqli_error($mysqli));
                                            while($data = mysqli_fetch_assoc($query)){
                                            echo "<option value='".$data['id_departamento']."'";
                                            if(isset($_POST['departamento']) && $_POST['departamento']==$data['id_departamento'])
                                            echo "SELECTED";
                                            echo ">";
                                            echo $data['dep_descripcion'];
                                            echo "</option>";
                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ciudad</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="descrip_ciudad" pleaceholder="Ingrese el nombre de la ciudad" required>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" onclick="return confirm('Guardar Registros ?')">
                                        <a href="?module=ciudad" class="btn btn-default btn-reset">Cancelar</a>
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
          $query = mysqli_query($mysqli, "SELECT cod_ciudad, descrip_ciudad, dep.id_departamento, dep.dep_descripcion 
                                    FROM ciudad ciu 
                                    JOIN departamento dep ON ciu.id_departamento = dep.id_departamento 
                                    WHERE ciu.cod_ciudad = '$_GET[id]';")
            or die('Error'.mysqli_error($mysqli));
          $data = mysqli_fetch_assoc($query);                                          
      }?> 
      

    <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Modificar Ciudad</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=ciudad"> Ciudad</a></li>
        <li class="active">Modificar</li>
      </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/ciudad/proses.php?act=update" method="POST">
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="codigo" value="<?php echo $data['cod_ciudad']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Departamento</label>
                                <div class="col-sm-5">
                                    <select name="departamento" class="form-control">
                                        
                                        <option value="<?php echo $data['id_departamento'] ?>"><?php echo $data['dep_descripcion'] ?></option>
                                        
                                        <?php 
                                            $query = mysqli_query($mysqli, "SELECT * FROM departamento;")
                                            or die('Error'.mysqli_error($mysqli));
                                            while($data2 = mysqli_fetch_assoc($query)){
                                                echo "<option value=\"$data2[id_departamento]\">| $data2[dep_descripcion]</option>"; 

                                            }                    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ciudad</label>
                                <div class="col-sm-5">
                                <input type="text" class="form-control" name="descrip_ciudad"  value="<?php echo $data['descrip_ciudad'];?>" required>
                                </div>
                            </div>            
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar">
                                        <a href="?module=ciudad" class="btn btn-default btn-reset">Cancelar</a>
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