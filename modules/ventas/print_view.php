<?php 
    require_once "../../config/database.php";
    
    if($_GET['act']=='imprimir'){
        if(isset($_GET['cod_venta'])){
            $cod_venta = $_GET['cod_venta'];
            //select para la cabezera de la compras
            $cabecera_compra = mysqli_query($mysqli, "SELECT
            cli.id_cliente,
            cli.cli_nombre,
            cli.cli_apellido,
            
            vent.cod_venta as cod_venta,
            vent.nro_factura as nro_factura,
            
            DATE_FORMAT(vent.fecha,'%d-%m-%Y') AS fecha,
            DATE_FORMAT(vent.hora,'%H:%i:%s') AS hora,
            vent.total_venta
            
            FROM clientes cli
            JOIN venta vent
            WHERE cli.id_cliente = vent.id_cliente
            AND vent.cod_venta =  $cod_venta;")or die('Error'.mysqli_error($mysqli));
            while($data = mysqli_fetch_assoc($cabecera_compra)){
            $cod_venta = $data['cod_venta'];
            $fecha = $data['fecha'];
            $hora = $data['hora'];
            $nro_factura = $data['nro_factura'];
            $total_venta = $data['total_venta'];
 //****************************select detalle venta************************************** */           
            $detalle_venta = mysqli_query($mysqli, " SELECT 
            det_vent.cod_producto AS cod_producto,
            det_vent.cod_venta AS cod_venta,
            det_vent.cod_deposito,
            det_vent.det_precio_unit AS det_precio_unit,
            det_vent.det_cantidad as det_cantidad,
            
            prod.cod_producto,
            prod.p_descrip AS p_descrip,
            prod.precio as precioProducto,
            
            vent.cod_venta,
            vent.nro_factura AS nro_factura,
            CONCAT(vent.fecha,' / ',vent.hora)AS fechaHora,
            vent.estado AS estado,
            
            cli.id_cliente,
            cli.ci_ruc as ciRuc,
            CONCAT(cli.cli_nombre,' ',cli.cli_apellido)AS nombreApellido,
            
            depo.cod_deposito,
            depo.descrip AS depositoDescripcion
            
            FROM det_venta det_vent
            JOIN producto prod
            JOIN venta vent
            JOIN deposito depo
            JOIN clientes cli
            WHERE prod.cod_producto = det_vent.cod_producto
            AND vent.cod_venta = det_vent.cod_venta
            AND depo.cod_deposito = det_vent.cod_deposito
            AND cli.id_cliente = vent.id_cliente
            AND vent.cod_venta = $cod_venta;")or die('Error'.mysqli_error($mysqli));
        }
    }
}
    ?> 
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title> Factura de compra</title>
    </head>
    <body>
        <div align='center'>
            Registro de factura de ventas<br>
            <label><strong>Código : </strong><?php echo $cod_venta; ?></label><br>
            <label><strong>Fecha Venta : </strong><?php echo $fecha; ?></label><br>
            <label><strong>Hora Venta : </strong><?php echo $hora; ?></label><br>
            <label><strong>Nro. Factura : </strong><?php echo $nro_factura; ?></label><br>
        </div>
        <hr>
            <div>
                <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
                    <thead style="background:#e8ecee">
                        <tr class="tabla-title">
                            <th height="20" align="center" valign="middle"><small>Código</small></th>
                            <th height="20" align="center" valign="middle"><small>Producto</small></th>
                            <th height="20" align="center" valign="middle"><small>Precio Unitario</small></th>
                            <th height="20" align="center" valign="middle"><small>Cantidad</small></th>
                            <th height="20" align="center" valign="middle"><small>Ci</small></th>
                            <th height="20" align="center" valign="middle"><small>Cliente</small></th>
                            <th height="20" align="center" valign="middle"><small>Deposito</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while ($data2 = mysqli_fetch_assoc($detalle_venta)){
                            $cod_producto = $data2['cod_producto'];
                            $p_descrip = $data2['p_descrip'];
                            $precioProducto = $data2['precioProducto'];
                            $det_cantidad = $data2['det_cantidad'];
                            $ciRuc = $data2['ciRuc'];
                            $nombreApellido = $data2['nombreApellido'];
                            $depositoDescripcion = $data2['depositoDescripcion'];
                            $precioCantidad = $precioProducto * $det_cantidad;
                            $total += $precioCantidad;
                            echo "<tr>
                            <td width='100' align='left'>$cod_producto</td>
                            <td width='80' align='left'>$p_descrip</td>
                            <td width='80' align='left'>$precioProducto</td>
                            <td width='80' align='left'>$det_cantidad</td>
                            <td width='80' align='left'>$ciRuc</td>
                            <td width='80' align='left'>$nombreApellido</td>
                            <td width='80' align='left'>$depositoDescripcion</td>
                            </tr> "; } ?>
                    </tbody>
                </table>         
            </div>
            <hr>
            <div align='center'>
             <label><strong>El total de la compra es Gs = <h3><?php echo number_format($total); ?></h3></strong></label> 
            </div>
    </body>
</html>