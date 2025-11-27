<?php
// Vista de productos
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=producto">Productos</a></li>
    </ol><br>
    <hr>

    <h1>
        <i class="fa fa-folder icon-title"></i> Gestión de Productos
        <a class="btn btn-primary btn-social pull-right"
           href="?module=form_producto&form=add"
           title="Agregar" data-toggle="tooltip">
            <i class="fa fa-plus"></i>Agregar
        </a>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <?php
            // ALERTAS
            if (!empty($_GET['alert'])) {
                $mensajes = [
                    1 => ["success", "Datos registrados correctamente"],
                    2 => ["success", "Datos modificados correctamente"],
                    3 => ["success", "Datos eliminados correctamente"],
                    4 => ["danger", "No se pudo realizar la operación"]
                ];

                $tipo = $mensajes[$_GET['alert']][0];
                $mensaje = $mensajes[$_GET['alert']][1];

                echo "
                <div class='alert alert-$tipo alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                    <h4><i class='icon fa fa-check-circle'></i> Resultado</h4>
                    $mensaje
                </div>";
            }
            ?>

            <div class="box box-primary">
                <div class="box-body">

                    <!-- Botón imprimir -->
                    <section class="content-header">
                        <a class="btn btn-warning btn-social pull-right"
                           href="modules/producto/print.php" target="_blank">
                            <i class="fa fa-print"></i> IMPRIMIR PRODUCTOS
                        </a>
                    </section>

                    <h2>Lista de Productos</h2>

                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="center">Código</th>
                            <th class="center">Tipo</th>
                            <th class="center">Unidad</th>
                            <th class="center">Proveedor</th>
                            <th class="center">Marca</th>
                            <th class="center">Producto</th>
                            <th class="center">Costo</th>
                            <th class="center">Precio Servicio</th>
                            <th class="center">Estado</th>
                            <th class="center">Acción</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        include __DIR__ . '/../../config/database.php';

                        $query = mysqli_query($mysqli, "
                            SELECT 
                                pro.id_producto,
                                pro.p_descrip,
                                pro.p_precio_servicio,
                                pro.p_costo_actual,
                                pro.tipo_producto,
                                pro.estado,
                                m.marca_descrip,
                                uni.u_descrip,
                                prov.razon_social
                            FROM productos pro
                            LEFT JOIN proveedor prov ON pro.cod_proveedor = prov.cod_proveedor
                            LEFT JOIN marcas m ON pro.id_marca = m.id_marca
                            LEFT JOIN u_medida uni ON pro.id_u_medida = uni.id_u_medida
                            ORDER BY pro.id_producto ASC
                        ");

                        while ($data = mysqli_fetch_assoc($query)) {

                            $estado_texto = ($data["estado"] == 1) ? "Activo" : "Inactivo";
                            $fila_clase   = ($data["estado"] == 1) ? "" : "class='danger'";

                            echo "
                            <tr $fila_clase>
                                <td class='center'>{$data['id_producto']}</td>
                                <td class='center'>".ucfirst($data['tipo_producto'])."</td>
                                <td class='center'>{$data['u_descrip']}</td>
                                <td class='center'>{$data['razon_social']}</td>
                                <td class='center'>{$data['marca_descrip']}</td>
                                <td class='center'>{$data['p_descrip']}</td>
                                <td class='center'>{$data['p_costo_actual']}</td>
                                <td class='center'>{$data['p_precio_servicio']}</td>
                                <td class='center'>{$estado_texto}</td>

                                <td class='center'>
                                    <div>
                                        <a class='btn btn-primary btn-sm'
                                           data-toggle='tooltip'
                                           title='Modificar Producto'
                                           href='?module=form_producto&form=edit&id_producto={$data['id_producto']}'>
                                            <i class='glyphicon glyphicon-edit'></i>
                                        </a>

                                        <a class='btn btn-danger btn-sm'
                                           data-toggle='tooltip'
                                           title='Eliminar Producto'
                                           href='modules/producto/proses.php?act=delete&id_producto={$data['id_producto']}'
                                           onclick=\"return confirm('¿Eliminar producto {$data['p_descrip']}?')\">
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
