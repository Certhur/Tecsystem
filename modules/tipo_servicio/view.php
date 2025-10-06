<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=tipo_servicio">Tipo de Servicio</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Datos de Tipo de Servicio
    <a class="btn btn-primary btn-social pull-right" href="?module=form_tipo_servicio&form=add" title="Agregar" data-toggle="tooltip">
        <i class="fa fa-plus"></i>Agregar
    </a>
</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php 
            if(empty($_GET['alert'])){
                echo "";
            }
            elseif($_GET['alert']==1){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos registrados correctamente
                </div>";
            }
            elseif($_GET['alert']==2){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos Modificados correctamente
                </div>";
            }
            elseif($_GET['alert']==3){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Se cambio el estado correctamente
                </div>";
            }
            elseif($_GET['alert']==4){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Error!</h4>
                No se pudo realizar la operación
                </div>";
            }
            ?>
            <div class="box box-primary">
                <div class="box-body">
                <!--<section class="content-header">
                    <a class="btn btn-warning btn-social pull-right" href="modules/clientes/print.php" target="_blank">
                    <i class="fa fa-print"></i>IMPRIMIR REPORTES CLIENTES
                    </a>
                </section>-->
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Tipo de Servicio</h2>
                        <thead>
                            <tr>
                                <th class="center">ID</th>
                                <th class="center">SERVICIO</th>
                                <th class="center">ESTADO</th> 
                                <th class="center">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $nro=1;
                            $query = mysqli_query($mysqli, "SELECT * FROM `tipo_servicio`")or die('Error'.mysqli_error($mysqli));
            
                            while($data = mysqli_fetch_assoc($query)){
                               $id_tipo_servicio = $data['id_tipo_servicio'];
                               $tipo_servicio_descrip = $data['tipo_servicio_descrip'];
                               $estado = $data['tipo_servicio_estado'];  
                               $estado_inactivo = "";
                               if($estado == 1){
                                $estado_texto = "Activo";
                               }else{
                                $estado_inactivo = "class='danger'";
                                $estado_texto = "Inactivo";
                               }                         

                               echo "<tr $estado_inactivo>
                               
                               <td class='center'>$id_tipo_servicio</td>
                               <td class='center'>$tipo_servicio_descrip</td>
                                    <td class='center'> $estado_texto</td>                            
                               <td class='center' width='80'>
                               <div>
                               <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Tipo de Servicio' style='margin-right:5px' 
                               class='btn btn-primary btn-sm' href='?module=form_tipo_servicio&form=edit&id=$data[id_tipo_servicio]'>
                                <i class='glyphicon glyphicon-edit' style='color:#fff'></i> </a>";
                                ?>
                                <a data-toggle="tooltip" data-data-placement="top" title="Modificar Estado" class="btn btn-danger btn-sm" 
                                href="modules/tipo_servicio/proses.php?act=update_estado&id_tipo_servicio=<?php echo $data['id_tipo_servicio']; ?> &tipo_servicio_estado=<?php echo $data['tipo_servicio_estado']; ?>"
                                onclick="return confirm('Desea cambiar el estado de <?php echo $data['tipo_servicio_descrip']; ?> ?')">
                                <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                <?php echo "</div></td></tr>" ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>