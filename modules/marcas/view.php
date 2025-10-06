<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=marcas">Marcas</a></li>
    </ol><br>
    <hr>
    <h1>
        <i class="fa fa-folder icon-title"></i>Datos de Marcas
        <a class="btn btn-primary btn-social pull-right" href="?module=form_marcas&form=add" title="Agregar" data-toggle="tooltip">
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
                Se cambio el estado correctamente
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
                <section class="content-header">
                        <a class="btn btn-warning btn-social pull-right" href="modules/marcas/reporte_marca.php"
                            target="_blank">
                            <i class="fa fa-print"></i>IMPRIMIR MARCAS
                        </a>
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Marcas</h2>
                        <thead>
                            <tr>
                                <th class="center">ID</th>
                                <th class="center">DESCRIPCIÓN</th>
                                <th class="center">ESTADO</th>
                                <th class="center">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nro = 1;
                            $query = mysqli_query($mysqli, "SELECT * FROM `marcas`") or die('Error' . mysqli_error($mysqli));

                            while ($data = mysqli_fetch_assoc($query)) {
                                $id_marca = $data['id_marca'];
                                $descripcion = $data['marca_descrip'];
                                $estado = $data['marca_estado'];
                                $estado_inactivo = "";
                                if ($estado == 1) {
                                    $estado_texto = "Activo";
                                } else {
                                    $estado_inactivo = "class='danger'";
                                    $estado_texto = "Inactivo";
                                }

                                echo "<tr $estado_inactivo>
                               
                               <td class='center'>$id_marca</td>
                               <td class='center'>$descripcion</td>
                               <td class='center'> $estado_texto</td>                            
                               <td class='center' width='120'>
                               <div>
                               <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Marcas' style='margin-right:5px' 
                               class='btn btn-primary btn-sm' href='?module=form_marcas&form=edit&id=$data[id_marca]'>
                                <i class='glyphicon glyphicon-edit' style='color:#fff'></i> </a>";
                            ?>
                                <a data-toggle="tooltip" data-data-placement="top" title="Modificar Estado" class="btn btn-danger btn-sm"
                                    href="modules/marcas/proses.php?act=update_estado&id_marca=<?php echo $data['id_marca']; ?> &marca_estado=<?php echo $data['marca_estado']; ?>"
                                    onclick="return confirm('Desea cambiar el estado de <?php echo $data['marca_descrip']; ?> ?')">
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