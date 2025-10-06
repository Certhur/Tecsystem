<?php 
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT * FROM proveedor;")or die('Error'.mysqli_error($mysqli));
$count = mysqli_num_rows($query);    
?>
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de los Proveedores</title>
        <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    </head>
    <body>
        <div align="center">
            <!-- <img src="../../images/asuncion.jpg"> -->
        </div>
        <div>
            REPORTE DE LOS PROVEEDORES EXISTENTES
        </div>
        <div align="center">
            Cantidad: <?php echo $count; ?>
        </div>
        <hr>
        <div id="tabla">
        <table width="100%" border="0.3" cellpadding="0" cellspacing="0" align="center">
                <thead style="background:#e8ecee">
                    <tr class="table-title">
                        <th height="30" align="center" valign="middle"><small>CÓDIGO</small></th>                      
                        <th height="30" align="center" valign="middle"><small>RAZÓN SOCIAL</small></th>      
                        <th height="30" align="center" valign="middle"><small>RUC</small></th> 
                        <th height="30" align="center" valign="middle"><small>DIRECCIÓN</small></th> 
                        <th height="30" align="center" valign="middle"><small>TELÉFONO</small></th>                   
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($data = mysqli_fetch_assoc($query)){
                        $cod_proveedor = $data['cod_proveedor'];
                        $razon_social = $data['razon_social'];
                        $ruc = $data['ruc'];
                        $direccion = $data['direccion'];
                        $telefono = $data['telefono'];
                        echo "<tr>
                        <td width='100' align='left'>$cod_proveedor</td>
                        <td width='120' align='left'>$razon_social</td>
                        <td width='120' align='left'>$ruc</td>
                        <td width='120' align='left'>$direccion</td>
                        <td width='120' align='left'>$telefono</td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>