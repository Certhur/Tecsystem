<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=producto">Producto</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Registros de Producto
    <a class="btn btn-primary btn-social pull-right" href="?module=form_producto&form=add" title="Agregar" data-toggle="tooltip">
        <i class="fa fa-plus"></i>Agregar</a>
</h1>
</section">
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php 
            if(empty($_GET["alert"])){
                echo "";
            }
            elseif($_GET["alert"]==1){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exito !</h4>
                Datos registrados correctamente
                </div>";
            }
            elseif($_GET["alert"]==2){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exito !</h4>
                Datos Modificados correctamente
                </div>";
            }
            elseif($_GET["alert"]==3){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exito !</h4>
                Datos Eliminados correctamente
                </div>";
            }
            elseif($_GET["alert"]==4){
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
                    <a class="btn btn-warning btn-social pull-right" href="modules/producto/print.php" target="_blank">
                        <i class="fa fa-print"></i>IMPRIMIR PRODUCTO
                    </a>
                </section>

                <section class="content-header">
                </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <h2>Lista de Productos</h2>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Tipo de producto</th>
                                <th>Unidad de Medida</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $nro=1;
                            $query = mysqli_query($mysqli, "SELECT
                            pro.cod_producto AS cod_producto,
                            pro.cod_tipo_prod AS cod_tipo_prod,
                            pro.id_u_medida AS id_u_medida,
                            pro.p_descrip AS p_descrip,
                            pro.precio AS precio,
                            tipo.t_p_descrip AS t_p_descrip,
                            uni.u_descrip AS u_descrip
                            FROM producto pro
                            JOIN tipo_producto tipo
                            JOIN u_medida uni
                            WHERE pro.cod_tipo_prod = tipo.cod_tipo_prod
                            AND pro.id_u_medida = uni.id_u_medida;")
                            or die('Error'.mysqli_error($mysqli));
                            while($data = mysqli_fetch_assoc($query)){
                               $cod_producto = $data["cod_producto"];
                               $p_descrip = $data["p_descrip"];
                               $precio = $data["precio"];
                               $t_p_descrip = $data["t_p_descrip"];
                               $u_descrip = $data["u_descrip"];
                               $id_u_medida = $data["id_u_medida"];
                               echo "<tr>
                               <td class=''>$cod_producto</td>
                               <td class=''>$t_p_descrip</td>
                               <td class=''>$u_descrip</td>
                               <td class=''>$p_descrip</td>
                               <td class=''>$precio</td>
                               <td class='' width='80'>
                               <div>
                               <a data-toggle='tooltip' data-placement='top' title='Modificar datos de Proveedor' style='margin-right:5px' 
                               class='btn btn-primary btn-sm' href='?module=form_producto&form=edit&id=$data[cod_producto]&idMedida=$data[id_u_medida]'>
                                <i class='glyphicon glyphicon-edit' style='color:#fff'></i></a>";
                                ?>
                                <a data-toggle="tooltip" data-data-placement="top" title="Eliminar datos" class="btn btn-danger btn-sm" 
                                href="modules/producto/proses.php?act=delete&cod_producto=<?php echo $data['cod_producto']; ?>"
                                onclick="return confirm('Eliminar Producto ? <?php echo $data['p_descrip']; ?>')">
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