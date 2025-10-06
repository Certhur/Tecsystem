<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="sweetalert2/sweetalert/sweetalert2.min.css">
   <script type="text/javascript" src="sweetalert2/sweetalert/sweetalert2.min.js" ></script>
    <script type="text/javascript" src="sweetalert2/jquery.min.js"></script>

    
    <script src="sweetalert2/sweetalert/sweetalert2.all.min.js"></script> 
    <script src="sweetalert2/sweetalert/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2/sweetalert/sweetalert2.min.css">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</html>
<?php 
    if($_GET['form']=='add'){ ?>
      <section class="content-header">
      <h1>
        <i class="fa fa-edit icon-title">Agregar Ventas</i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=ventas">Ventas</a></li>
        <li class="active">Agregar</li>
      </ol>
      </section>      
      <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" class="form-horizontal" action="modules/ventas/proses.php?act=insert" method="POST">
                        <div class="box-body">
                            <?php
                            // select para obtener el ultimo id
                                $query_id = mysqli_query($mysqli,"SELECT 
                                MAX(cod_venta) AS id FROM venta;")or die('Error'.mysqli_error($mysqli));
                                $count = mysqli_num_rows($query_id);
                                if($count <> 0){
                                    $data_id = mysqli_fetch_assoc($query_id);
                                    $codigo = $data_id['id']+1;
                                } else{
                                    $codigo=1;
                                }
                                $consulta = mysqli_query($mysqli, "SELECT
                                DATE_FORMAT(NOW(),'%H:%m:%S') AS horaActual,
                                DATE_FORMAT(NOW(),'%d-%m-%Y') AS fechaActual,
                                DATE_FORMAT(NOW(),'%Y-%m-%d') AS fechaActualSinFormato;")or die('Error'.mysqli_error($mysqli));
                                $data = mysqli_fetch_assoc($consulta);
                                $horaActual = $data['horaActual'];
                                $fechaActual = $data['fechaActual'];
                                $fechaActualSinFormato = $data['fechaActualSinFormato'];
                                $fecha = date_default_timezone_set("d-m-Y");
                                $hora = date_default_timezone_set("H:i:s");               
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Código : </label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                </div> 
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fecha : </label>
                                <div class="col-sm-2">
                                    <input type="date" data-date-format="yyyy-mm-dd" id="fecha1" name="fecha1" min="2000-01-01" max="<?php echo date("Y-m-d"); ?>" autocomplete="off" 
                                    value="<?php echo date("Y-m-d"); ?>">
                                </div>

                                <label class="col-sm-2 control-label">Hora : </label>
                                <div class="col-sm-2">
                                    <!-- <input type="text" class="form-control date-picker" data-date-format="H-mm-ss" name="hora" autocomplete="of" 
                                    value="<?php //echo date("H:i:s"); ?>" readonly> -->
                                    <input type="time" class="form-control" name="hora" data-date-format="H-mm-ss" autocomplete="off" value="<?php echo $horaActual; ?>" />
                                </div>
                            </div>
                            <!-- ******************************************* select ************************************ -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cliente : </label>
                                <div class="col-sm-3">
                                    <select class="chosen-select" name="codigo_cliente" data-placeholder="Seleccione el Cliente"
                                    autocomplete="off" required>
                                        <option value=""></option>
                                        <?php 
                                            $query_prov = mysqli_query($mysqli, "SELECT
                                            id_cliente,
                                            ci_ruc,
                                            CONCAT(cli_nombre,' ',cli_apellido) AS cliente
                                            FROM clientes ORDER BY id_cliente ASC;")
                                            or die('Error'.mysqli_error($mysqli));
                                            while ($data_prov = mysqli_fetch_assoc($query_prov)){
                                                echo "<option value=\"$data_prov[id_cliente]\">
                                                $data_prov[ci_ruc] | $data_prov[cliente]</option>";
                                            } ?>
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Factura : </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="nro_factura" autocomplete="off" required>
                                </div>
                            </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Precio Unitario : </label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" name="precio_unitario" required>
                                    </div> 
                                    <label class="col-sm-2 control-label">Cantidad : </label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" name="det_cantidad" required>
                                    </div> 
                                </div>
                            <!-- <div class="form-group">
                                <label class="col-sm-2 control-label">Depósito : </label>
                                <div class="col-sm-3">
                                    <select class="chosen-select" name="codigo_deposito" data-placeholder="Seleccionar Depósito"
                                    autocomplete="off" required>
                                        <option value=""></option>
                                        <?php 
                                            // $query_dep = mysqli_query($mysqli, "SELECT cod_deposito, descrip FROM deposito
                                            // ORDER BY cod_deposito ASC;") or die ('Error'.mysqli_error($mysqli));
                                            // while ($data_dep = mysqli_fetch_assoc($query_dep)){
                                            //     echo "<option value=\"$data_dep[cod_deposito]\">$data_dep[cod_deposito] | $data_dep[descrip]</option>";
                                            // } ?>
                                    </select>
                                </div>
                            </div> -->
                            <hr>               
                            <div class="form-group">
                                <div class="col-sm-12">
                                <label class="col-sm-2 control-label">Productos : </label>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                                    <span class="glyphicon glyphicon-plus"> Agregar productos</span>
                                    </button>
                                </div>
                            </div>

                            <div id="resultados" class="col-md-9"></div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" title="Los datos se guardarán">
                                        <a href="?module=ventas" class="btn btn-default btn-reset" title="Volver a la página anterior">Atras</a>
                                        <a href="?module=form_ventas&form=add" class="btn btn-default btn-reset" title="Vaciar los campos">Cancelar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </section>  
    <?php } ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
    <script>
        // ********************************************chargin ****************************************
        $(document).ready(function(){
          load(1);
        });
        function load(page){
            var x = $("#x").val();
            var parametros={"action":"ajax","page":page,"x":x};
            $("#loader").fadeIn('slow');
            $.ajax({
                url:'./ajax/productos_pedido_ventas.php',
                data: parametros,
                beforeSend: function(objeto){
                    $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
                },
                success:function(data){
                    $(".outer_div").html(data).fadeIn('slow');
                    $('#loader').html('');
                }
            })
        }
    </script>
    <script>
        // ********************************************function validacion****************************************
        // ************************ function que envia y recibe de (agregar_pedidos_ventas)
        function agregar(id){
          var precio_compra=$('#precio_compra_'+id).val();
          var iva = $('#iva').val();
          var cantidad=$('#cantidad_'+id).val();
           if(isNaN(cantidad)){
               alert('no es numero');
               document.getElementById('cantidad_'+id).focus();
               return false;
           }
           if(isNaN(precio_compra)){
               alert('no es numero');
               document.getElementById('precio_compra_'+id).focus();
               return false;
           }
           var parametros={"id":id,"precio_compra_":precio_compra,"cantidad":cantidad,"iva":iva};
           $.ajax({
               type: "POST",
               url: "./ajax/agregar_pedido_ventas.php",
               data: parametros,
               beforeSend: function(objeto){
                   $("#resultados").html("Mensaje: Cargando...");
               },
               success: function(datos){
                   $("#resultados").html(datos);
               }
           });
        }
        // ********************************************function delete****************************************
        function eliminar(id){
            $.ajax({
                type: "GET",
                url: "./ajax/agregar_pedido_ventas.php",
                data: "id="+id,
                beforeSend: function(objeto){
                   $("#resultados").html("Mensaje: Cargando...");
               },
               success: function(datos){
                   $("#resultados").html(datos);
               }
            });
        }
    </script>
     <!-- **********************************Modal********************************************************** -->                      
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModallabel">Buscar Productos</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">
                <div class="form-group">
                   <div class="col-sm-6">
                       <input type="text" class="form-control" id="x" placeholder="Buscar productos" onkeyup="load(1);">
                   </div>
                   <button id="button" type="button" class="btn btn-default" onclick="load(1);"><span class="glyphicon glyphicon-search"></span>Buscar</button>
                </div>                            
              </form>
              <div id="loader" style="position: absolute; text-align: center; top: 55px; width:100%; display:none;"></div>
              <div class="outer_div"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>                                
         </div>
      </div>                                      
    </div>
    <script>
    </script>