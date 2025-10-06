<?php 
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT * FROM producto;")or die('Error'.mysqli_error($mysqli));
$count = mysqli_num_rows($query);    
?>
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de los Productos</title>
        <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    </head>
    <body>
        <div align="center">
            <!-- <img src="../../images/asuncion.jpg"> -->
        </div>
        <div align="center">
            REPORTE DE LOS PRODUCTOS EXISTENTES
        </div>
        <br><br>
        <div align="center">
            Cantidad: <?php echo $count; ?>
        </div>
        <hr>
        <div id="tabla">
        <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
                <thead style="background:#e8ecee">
                    <tr class="table-title">
                        <th height="30" align="center" valign="middle"><small>CÓDIGO</small></th>                      
                        <th height="30" align="center" valign="middle"><small>PRODUCTO</small></th>      
                        <th height="30" align="center" valign="middle"><small>PRCIO</small></th>               
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($data = mysqli_fetch_assoc($query)){
                        $cod_producto = $data['cod_producto'];
                        $p_descrip = $data['p_descrip'];
                        $precio = $data['precio'];
                        echo "<tr>
                        <td width='100' align='left'>$cod_producto</td>
                        <td width='120' align='left'>$p_descrip</td>
                        <td width='120' align='left'>$precio</td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>