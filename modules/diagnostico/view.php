<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=diagnostico">Diagnostico</a></li>
    </ol><br>
    <hr>
    <h1>
        <i class="fa fa-folder icon-title">Datos del Diagnostico</i>
        <a class="btn btn-primary btn-social pull-right" href="?module=form_diagnostico&form=add" title="agregar" data-toogle="tooltip">
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
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Diagnosticos</h2>
                        <thead>
                            <tr>
                                <th class="center">CODIGO</th>
                                <th class="center">FECHA</th>
                                <th class="center">NRO RECEPCION</th>
                                <th class="center">CLIENTE</th>
                                <th class="center">EQUIPO</th>
                                <th class="center">MARCA</th>
                                <th class="center">MODELO</th>
                                <th class="center">DESCRIPCION</th>
                                <th class="center">FALLA</th>
                                <th class="center">CAUSA</th>
                                <th class="center">SOLUCION</th>
                                <th class="center">ESTADO</th>
                                <th class="center">ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nro = 1;
                            $query = mysqli_query($mysqli, "SELECT * FROM diagnostico AS dg 
                            LEFT JOIN recepcion_equipo AS re ON dg.id_recepcion_equipo = re.id_recepcion_equipo 
                            LEFT JOIN tipo_servicio AS ts ON dg.id_tipo_servicio = ts.id_tipo_servicio
                            LEFT JOIN marcas AS m ON re.id_marca = m.id_marca
                            LEFT JOIN tipo_equipo AS te ON re.id_tipo_equipo = te.id_tipo_equipo
                            LEFT JOIN clientes AS cl ON re.id_cliente = cl.id_cliente;") or die('Error' . mysqli_error($mysqli));

                            while ($data = mysqli_fetch_assoc($query)) {
                                $id_diagnostico = $data['id_diagnostico'];
                                $fecha_diagnostico = $data['fecha_diagnostico'];
                                $id_recepcion_equipo = $data['id_recepcion_equipo'];
                                $cli_razon_social = $data['cli_razon_social'];
                                $equipo_descripcion = $data['equipo_descripcion'];
                                $marca_descrip = $data['marca_descrip'];
                                $equipo_modelo = $data['equipo_modelo'];
                                $equipo_descripcion = $data['equipo_descripcion'];
                                $falla_diagnostico = $data['falla_diagnostico'];
                                $causa_diagnostico = $data['causa_diagnostico'];
                                $solucion_diagnostico = $data['solucion_diagnostico'];
                                $estado_diagnostico = $data['estado_diagnostico'];

                                if ($estado_diagnostico == 1) {
                                    $estado_texto = "Activo";
                                } else {
                                    $estado_inactivo = "class='danger'";
                                    $estado_texto = "Inactivo";
                                }


                                echo "<tr>
                               <td class='center'>$id_diagnostico</td>
                               <td class='center'>$fecha_diagnostico</td>
                               <td class='center'>$id_recepcion_equipo</td>
                               <td class='center'>$cli_razon_social</td>
                               <td class='center'>$equipo_descripcion</td>
                               <td class='center'>$marca_descrip</td> 
                               <td class='center'>$equipo_descripcion</td>
                               <td class='center'>$falla_diagnostico</td>
                               <td class='center'>$equipo_modelo</td>     
                               <td class='center'>$causa_diagnostico</td>     
                               <td class='center'>$solucion_diagnostico</td> 
                               <td class='center'>$estado_texto</td>    
                                                                 
                               <td class='center' width='170'>
                               <div>
                               <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Diagnostico' style='margin-right:5px' 
                               class='btn btn-primary btn-sm' href='?module=form_diagnostico&form=edit&id=$data[id_diagnostico]'>
                                <i class='glyphicon glyphicon-edit' style='color:#fff'></i> </a>";
                            ?>
                                <a data-toggle="tooltip" data-data-placement="top" title="Eliminar datos" class="btn btn-danger btn-sm"
                                    href="modules/diagnostico/proses.php?accion=eliminar&id_diagnostico=<?php echo $data['id_diagnostico']; ?>"
                                    onclick="return confirm('Desea eliminar este Diagnostico <?php echo $data['equipo_modelo']; ?> ?')">
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