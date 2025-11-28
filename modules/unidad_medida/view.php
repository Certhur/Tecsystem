<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="?module=unidad_medida">Unidad de Medida</a></li>
    </ol>
    <br><hr>

    <h1>
        <i class="fa fa-folder icon-title"></i> Registros de Unidades de Medida

        <a class="btn btn-primary btn-social pull-right"
           href="?module=form_unidad_medida&form=add"
           title="Agregar"
           data-toggle="tooltip">
            <i class="fa fa-plus"></i> Agregar
        </a>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <?php 
            // ALERTAS
            if (!empty($_GET["alert"])) {
                switch ($_GET["alert"]) {
                    case 1:
                        echo "<div class='alert alert-success alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                <h4><i class='icon fa fa-check-circle'></i> Exito!</h4>
                                Datos registrados correctamente.
                              </div>";
                        break;

                    case 2:
                        echo "<div class='alert alert-success alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                <h4><i class='icon fa fa-check-circle'></i> Exito!</h4>
                                Datos modificados correctamente.
                              </div>";
                        break;

                    case 3:
                        echo "<div class='alert alert-success alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                <h4><i class='icon fa fa-check-circle'></i> Exito!</h4>
                                Datos eliminados correctamente.
                              </div>";
                        break;

                    case 4:
                        echo "<div class='alert alert-danger alert-dismissable'>
                                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                <h4><i class='icon fa fa-exclamation-circle'></i> Error!</h4>
                                No se pudo realizar la operación.
                              </div>";
                        break;

                    case 5:
                        echo "<div class='alert alert-warning alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <h4><i class='icon fa fa-exclamation-triangle'></i> Atención!</h4>
                        No se puede eliminar la unidad de medida porque está siendo utilizada en uno o más productos.
                        </div>";
                    break;
                }
            }
            ?>

            <div class="box box-primary">
                <div class="box-body">

                    <!-- BOTÓN IMPRIMIR -->
                    <section class="content-header">
                        <a class="btn btn-warning btn-social pull-right"
                           href="modules/unidad_medida/print.php"
                           target="_blank">
                            <i class="fa fa-print"></i> IMPRIMIR UNIDADES
                        </a>
                    </section>

                    <h2>Lista de Unidades de Medida</h2>

                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="center">Código</th>
                                <th class="center">Unidad de Medida</th>
                                <th class="center">Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $query = mysqli_query($mysqli, "SELECT * FROM u_medida ORDER BY id_u_medida ASC")
                                     or die('Error: '.mysqli_error($mysqli));

                            while ($data = mysqli_fetch_assoc($query)) {

                                echo "
                                <tr>
                                    <td class='center'>{$data['id_u_medida']}</td>
                                    <td class='center'>{$data['u_descrip']}</td>

                                    <td class='center' width='120'>
                                        <div>

                                            <!-- MODIFICAR -->
                                            <a href='?module=form_unidad_medida&form=edit&id={$data['id_u_medida']}'
                                               class='btn btn-primary btn-sm'
                                               title='Modificar' 
                                               data-toggle='tooltip'
                                               style='margin-right:5px'>
                                                <i class='glyphicon glyphicon-edit'></i>
                                            </a>

                                            <!-- ELIMINAR -->
                                            <a href='modules/unidad_medida/proses.php?act=delete&id_u_medida={$data['id_u_medida']}'
                                               class='btn btn-danger btn-sm'
                                               title='Eliminar'
                                               data-toggle='tooltip'
                                               onclick=\"return confirm('¿Eliminar Unidad de Medida: {$data['u_descrip']}?')\">
                                                <i class='glyphicon glyphicon-trash'></i>
                                            </a>

                                        </div>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</section>
