<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=departamento">Departamento</a></li>
    </ol><br>
    <hr>
    <h1>
        <i class="fa fa-folder icon-title"></i>Registros de Departamentos
        <a class="btn btn-primary btn-social pull-right" href="?module=form_departamento&form=add" title="Agregar"
            data-toggle="tooltip">
            <i class="fa fa-plus"></i>Agregar</a>
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (empty($_GET["alert"])) {
                echo "";
            } elseif ($_GET["alert"] == 1) {
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exito !</h4>
                Datos registrados correctamente
                </div>";
            } elseif ($_GET["alert"] == 2) {
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exito !</h4>
                Datos Modificados correctamente
                </div>";
            } elseif ($_GET["alert"] == 3) {
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exito !</h4>
                Datos Eliminados correctamente
                </div>";
            } elseif ($_GET["alert"] == 4) {
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Error !</h4>
                No se pudo realizar la operación
                </div>";
            }
            ?>
            <div class="box box-primary">
                <div class="box-body">

                    <section class="content-header">
                        <a class="btn btn-warning btn-social pull-right" href="modules/departamento/print.php"
                            target="_blank">
                            <i class="fa fa-print"></i>IMPRIMIR DEPARTAMENTO
                        </a>
                    </section>

                    <section class="content-header">
                    </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de departamentos</h2>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Departamento</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nro = 1;
                            $query = mysqli_query($mysqli, "SELECT * FROM departamento;")
                                or die('Error' . mysqli_error($mysqli));
                            while ($data = mysqli_fetch_assoc($query)) {
                                $id_departamento = $data["id_departamento"];
                                $dep_descripcion = $data["dep_descripcion"];
                                echo "<tr>
                               <td class='center'>$id_departamento</td>
                               <td class='center'>$dep_descripcion</td>
                               <td class='center' width='80'>
                               <div>
                               <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Departamento' style='margin-right:5px' 
                               class='btn btn-primary btn-sm' href='?module=form_departamento&form=edit&id=$data[id_departamento]'>
                                <i class='glyphicon glyphicon-edit' style='color:#fff'></i></a>";
                                ?>
                                <a data-toggle="tooltip" data-data-placement="top" title="Eliminar datos"
                                    class="btn btn-danger btn-sm"
                                    href="modules/departamento/proses.php?act=delete&id_departamento=<?php echo $data['id_departamento']; ?>"
                                    onclick="return confirm('Eliminar departamento ? <?php echo $data['dep_descripcion']; ?>')">
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