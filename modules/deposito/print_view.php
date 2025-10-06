<?php 
require_once "../../config/database.php";

$query = mysqli_query($mysqli, "SELECT * FROM deposito;")or die('Error'.mysqli_error($mysqli));
$count = mysqli_num_rows($query);    
?>
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de los Depósitos</title>
        <link rel="stylesheet" type="text/css" href="../../assets/img/favicon.ico">
    </head>
    <body>
        <div align="center">
            <!-- <img src="../../images/asuncion.jpg"> -->
        </div>
        <div>
            REPORTE DE LOS DEPÓSITOS EXISTENTES
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
                        <th height="30" align="center" valign="middle"><small>DEPÓSITO</small></th>                        
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($data = mysqli_fetch_assoc($query)){
                        $id_departamento = $data['cod_deposito'];
                        $dep_descripcion = $data['descrip'];
                        $descrip_ciudad = $data['descrip_ciudad'];
                        $cli_nombre = $data['cli_nombre'];
                        $cli_apellido = $data['cli_apellido'];                            
                        $cli_telefono = $data['cli_telefono'];
                        echo "<tr>
                        <td width='100' align='left'>$id_departamento</td>
                        <td width='120' align='left'>$dep_descripcion</td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>