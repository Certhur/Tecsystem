<html>
    <head>
    <!-- https://sweetalert2.github.io/ -->
    <script src="plugins/sweetalert/js/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="plugins/sweetalert/css/sweetalert2.css">
    <!-- https://sweetalert.js.org/guides/#installation -->
    <script src="plugins/sweetalert_2/js/sweetalert.js"></script>
    <script src="plugins/sweetalert_2/js/sweetalert.min.js"></script>
    </head>
</html>
<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=ventas">Ventas</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Detalles de las ventas
    <a class="btn btn-primary btn-social pull-right" href="?module=form_ventas&form=add" title="Agregar" data-toggle="tooltip">
        <i class="fa fa-plus"></i>Agregar
    </a>
</h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php 
            if(empty($_GET['alert'])){
                echo "";
            }
            elseif($_GET['alert']==1){
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos registrados correctamente
                </div>";
            } 
            elseif($_GET['alert']==2){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Exitoso!</h4>
                Datos anulados correctamente
                </div>";
            }
            elseif($_GET['alert']==3){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4>  <i class='icon fa fa-check-circle'></i> Error!</h4>
                No se puedo realizar la acción
                </div>";
            }
            ?>
            <div class="box box-primary">
                <div class="box-body">
                <section class="content-header">
                </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <!-- <h2>Detalles</h2> -->
                        <thead>
                            <tr>
                                <th class="center">Id</th>
                                <th class="center">Nro. Factura</th>
                                <th class="center">Cliente</th>
                                <th class="center">Fecha Hora</th>
                                <th class="center">Total Ventas</th>
                                <th class="center">Estado</th>
                                <th class="center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $nro=1;
                            $query = mysqli_query($mysqli, "SELECT
                            vent.cod_venta AS cod_venta,
                            vent.id_cliente AS id_cliente,
                            CONCAT(vent.fecha,' / ',vent.hora) AS fechaHora,
                            vent.total_venta AS total_venta,
                            vent.estado AS estado,
                            vent.nro_factura AS nro_factura,
                            
                            cli.id_cliente AS id_cliente,
                            cli.ci_ruc AS ci_ruc,
                            CONCAT(cli.cli_nombre,' ',cli.cli_apellido)AS nombreApellidoCliente
                            
                            FROM venta vent
                            JOIN clientes cli
                            WHERE vent.id_cliente = cli.id_cliente AND vent.estado = 'activo';")or die('Error'.mysqli_error($mysqli));
                            while($data = mysqli_fetch_assoc($query)){
                               $cod_venta = $data['cod_venta'];//
                               $id_cliente = $data['id_cliente'];
                               $fechaHora = $data['fechaHora'];//
                               $total_venta = $data['total_venta'];//
                               $estado = $data['estado'];
                               $nro_factura = $data['nro_factura'];//
                               $id_cliente = $data['id_cliente'];
                               $ci_ruc = $data['ci_ruc'];
                               $nombreApellidoCliente = $data['nombreApellidoCliente'];//
                               echo "<tr>
                               <td class='center'>$cod_venta</td>
                               <td class='center'>$nro_factura</td>
                               <td class='center'>$nombreApellidoCliente</td>
                               <td class='center'>$fechaHora</td>
                               <td class='center'>$total_venta</td>
                               <td class='center'>$estado</td>            
                               <td class='center' width='80'>
                               <div>";?>
<!--                                
                               <a data-toggle="tooltip" data-placement="top" title="Anular Compra" class="btn btn-danger btn-sm"
                                id="cod_venta" href="modules/ventas/proses.php?act=anular&cod_venta=<?php //echo $data['cod_venta']; ?>"
                                onclick ="anular(); return confirm('Desea anular esta Venta <?php // echo $data['nro_factura']; ?> ?');">
                                <i style="color:#000" class="glyphicon glyphicon-trash"></i>
                                </a> -->

                                <a href="modules/ventas/proses.php?act=anular&cod_venta=<?php echo $data['cod_venta']; ?>">
                                    <button id="cod_venta" type="button" class="btn btn-danger btn-sm" onclick ="anular(); return confirm('Desea anular esta Venta <?php // echo $data['nro_factura']; ?> ?');">
                                        <i style="color:#000" class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </a>

                                <a data-toggle="tooltip" data-placement="top" title="Imprimir factura de Ventas" class="btn btn-warning btn-sm" 
                                href="modules/ventas/print.php?act=imprimir&cod_venta=<?php echo $data['cod_venta']; ?>" target="_blank">
                                <i style="color:#000" class="fa fa-print"></i>
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
<script>
    function anular(){
  swal({
  title: "ANULAR ?",
  text: "DESEA ANULAR ESTA VENTA...?",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    swal("VENTA ANULADA !", {
      icon: "success",
      buttons: false,
      timer: 2000,
    });
    if(willDelete){
        alert("codigo => ",<?php echo $data['cod_venta']; ?>);
        header("Location: modules/ventas/proses.php?act=anular&cod_venta=<?php echo $data['cod_venta']; ?>");
        //window.location.assign('modules/ventas/proses.php?act=anular&cod_venta=<?php echo $data['cod_venta']; ?>>');
       
    }
  } else {
    Swal.fire({
    position: 'top-center',
    icon: 'success',
    title: 'OPERACIÓN CANCELADA...!!!',
    showConfirmButton: false,
    timer: 2000
});
  }
});
}

function anular_venta(){

}
</script>