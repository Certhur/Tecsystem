<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=recepcion_equipo">Recepcion de Equipos</a></li>
    </ol><br>
    <hr>
    <h1>
        <i class="fa fa-folder icon-title"></i>Datos de Recepcion de Equipos
        <a class="btn btn-primary btn-social pull-right" href="?module=form_recepcion_equipo&form=add" title="Agregar" data-toggle="tooltip">
            <i class="fa fa-plus"></i>Agregar
        </a>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (empty($_GET['alert'])) {
                echo "";
            } elseif ($_GET['alert'] == 1) {
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos registrados correctamente
                </div>";
            } elseif ($_GET['alert'] == 2) {
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos Modificados correctamente
                </div>";
            } elseif ($_GET['alert'] == 3) {
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos Eliminados correctamente
                </div>";
            } elseif ($_GET['alert'] == 4) {
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
                    <a class="btn btn-warning btn-social pull-right" href="modules/recepcion_equipo/print.php" target="_blank">
                    <i class="fa fa-print"></i>IMPRIMIR REPORTES RECEPCION DE EQUIPOS
                    </a>
                </section>-->
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Recepcion de Equipos</h2>
                        <thead>
                            <tr>
                                <th class="center">CODIGO</th>
                                <th class="center">FECHA</th>
                                <th class="center">CLIENTE</th>
                                <th class="center">MARCA</th>
                                <th class="center">EQUIPO</th>
                                <th class="center">MODELO</th>
                                <th class="center">DESCRIPCION</th>
                                <th class="center">ESTADO</th>
                                <th class="center">ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nro = 1;
                            $query = mysqli_query($mysqli, "SELECT * FROM recepcion_equipo AS re
                            LEFT JOIN tipo_equipo AS e ON re.id_tipo_equipo = e.id_tipo_equipo
                            LEFT JOIN marcas AS m ON re.id_marca = m.id_marca
                            LEFT JOIN tipo_equipo AS te ON re.id_tipo_equipo = te.id_tipo_equipo
                            LEFT JOIN clientes AS cl ON re.id_cliente = cl.id_cliente;") or die('Error' . mysqli_error($mysqli));

                            while ($data = mysqli_fetch_assoc($query)) {
                                $id_recepcion_equipo = $data['id_recepcion_equipo'];
                                $fecha_recepcion = $data['fecha_recepcion'];
                                $cli_razon_social = $data['cli_razon_social'];
                                $marca_descrip = $data['marca_descrip'];
                                $tipo_descrip = $data['tipo_descrip'];
                                $equipo_modelo = $data['equipo_modelo'];
                                $equipo_descripcion = $data['equipo_descripcion'];
                                $recepcion_estado = $data['recepcion_estado'];

                                if ($recepcion_estado == 1) {
                                    $estado_texto = "Activo";
                                } else {
                                    $estado_inactivo = "class='danger'";
                                    $estado_texto = "Inactivo";
                                }


                                echo "<tr>
                               <td class='center'>$id_recepcion_equipo</td>
                               <td class='center'>$fecha_recepcion</td>
                               <td class='center'>$cli_razon_social</td>
                               <td class='center'>$marca_descrip</td>
                               <td class='center'>$tipo_descrip</td>
                               <td class='center'>$equipo_modelo</td> 
                               <td class='center'>$equipo_descripcion</td>
                               <td class='center'>$estado_texto</td>                                  
                               <td class='center' width='170'>
                               <div>
                               <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Recepcion de Equipos' style='margin-right:5px' 
                               class='btn btn-primary btn-sm' href='?module=form_recepcion_equipo&form=edit&id=$data[id_recepcion_equipo]'>
                                <i class='glyphicon glyphicon-edit' style='color:#fff'></i> </a>";
                            ?>
                                <a data-toggle="tooltip" data-data-placement="top" title="Eliminar datos" class="btn btn-danger btn-sm"
                                    href="modules/recepcion_equipo/proses.php?accion=eliminar&id_recepcion_equipo=<?php echo $data['id_recepcion_equipo']; ?>"
                                    onclick="return confirm('Desea eliminar esta Recepcion <?php echo $data['equipo_modelo']; ?> ?')">
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
