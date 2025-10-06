<?php 
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT * FROM u_medida;")or die('Error'.mysqli_error($mysqli));
$count = mysqli_num_rows($query);    
?>
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de las unidades de medidas</title>
        <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    </head>
    <body>
        <div align="center">
            <!-- <img src="../../images/asuncion.jpg"> -->
        </div>
        <div>
            REPORTE DE LAS UNIDADES DE MEDIDAS
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
                        <th height="30" align="center" valign="middle"><small>UNIDAD DE MEDIDA</small></th>                        
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($data = mysqli_fetch_assoc($query)){
                        $id_u_medida = $data['id_u_medida'];
                        $u_descrip = $data['u_descrip'];
                        echo "<tr>
                        <td width='100' align='left'>$id_u_medida</td>
                        <td width='120' align='left'>$u_descrip</td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>