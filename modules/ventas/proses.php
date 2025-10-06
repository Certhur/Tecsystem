<?php 
session_start();
$session_id = session_id();
require_once '../../config/database.php';
if(empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=alert=3'>";
}
else{
    if($_GET['act']=='insert'){
        if(isset($_POST['Guardar'])){
            $codigo = $_POST['codigo'];
            $codigo_cliente = $_POST['codigo_cliente'];
            $precio_unitario = $_POST['precio_unitario'];
            $det_cantidad = $_POST['det_cantidad'];
            $fecha1 = $_POST['fecha1'];
            echo $fecha1;

            $sql=mysqli_query($mysqli, "SELECT * FROM producto, tmp WHERE producto.cod_producto = tmp.id_producto;");
            while($row = mysqli_fetch_array($sql)){
                $codigo_producto= $row['id_producto'];
                //$precio= $row['precio_tmp'];
                $cantidad= $row['cantidad_tmp'];
                // insert detalle
                $insert_detalle = mysqli_query($mysqli, "INSERT INTO det_venta(cod_producto,cod_venta,cod_deposito,det_precio_unit,det_cantidad) 
                VALUES
                ($codigo_producto,$codigo,$codigo_producto,$precio_unitario,$cantidad);")
                or die('Error'.mysqli_error($mysqli));
                //*******************************insert stock****************************************
                $query = mysqli_query($mysqli, "SELECT * FROM stock WHERE cod_producto=$codigo_producto AND cod_deposito=$codigo_producto;") 
                or die('Error'.mysqli_error($mysqli));
                if($count = mysqli_num_rows($query)==0){
                // ******************************* insert **********************************
                    $insertar_stock = mysqli_query($mysqli, "INSERT INTO stock (cod_deposito, cod_producto, cantidad)VALUES ($codigo_producto,$codigo_producto,$cantidad);")
                    or die('Error'.mysqli_error($mysqli));
                }else {
                    $actualizar_stock = mysqli_query($mysqli, "UPDATE stock SET cantidad = cantidad + $cantidad
                     WHERE cod_producto = $codigo_producto AND cod_deposito = $codigo_producto;")
                    or die('Error'.mysqli_error($mysqli));
                }
            }
            //************************************* */ insert cabecera de compra**********************************
            //***************************************/ */ definir variables **************************************
            $codigo = $_POST['codigo'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $codigo_cliente = $_POST['codigo_cliente'];
            $nro_factura=$_POST['nro_factura'];
            $precio_unitario=$_POST['precio_unitario'];
            $det_cantidad=$_POST['det_cantidad'];
            $estado='activo';
            $sql=mysqli_query($mysqli, "SELECT * FROM producto, tmp WHERE producto.cod_producto=tmp.id_producto and tmp.session_id='".$session_id."';");
            while ($row = mysqli_fetch_array($sql)) {
                $precio_compra_f=number_format($precio_compra_); //***********************Format para para dividir en decimales en ,
                $precio_compra_r=str_replace(",","",$precio_compra_f);//******************remplace las comas si existe.
                $precio_total=$precio_compra_r*$cantidad;//*******************************multiplica el total
                $precio_total_f=number_format($precio_total);//***************************Format para para dividir en decimales en ,
                $precio_total_r=str_replace(",","",$precio_total_f);
                $suma_total+=$precio_total_r; //*****************************************suma total
    
                $ivaCadaUno = number_format($precio_total  * $iva / 100);
                $ivaCadaUno10 = ($precio_total  * $iva / 100);
                $ivaTotal+=$ivaCadaUno10;
                $suma = $precio_unitario * $det_cantidad;
            }
            $total_suma = $ivaTotal + $suma_total;
            $query = mysqli_query($mysqli, "INSERT INTO venta(cod_venta,id_cliente,fecha,total_venta,estado,hora,nro_factura)
            VALUES ($codigo,$codigo_cliente,'$fecha1','$suma','$estado','$hora','$nro_factura');")
            or die('Error'.mysqli_error($mysqli));
            if($query){
                header("Location: ../../main.php?module=ventas&alert=1");
            } else {
                header("Location: ../../main.php?module=ventas&alert=3");
            }
        }
    }
    elseif($_GET['act']=='anular'){//proviene de view.php
        if(isset($_GET['cod_venta'])){
            $codigo = $_GET['cod_venta'];
            //***************************************update anular**************************************** 
            $query = mysqli_query($mysqli, "UPDATE venta SET estado='anulado' WHERE cod_venta = $codigo;")
            or die('Error'.mysqli_error($mysqli));
            //**********************************select detalle compra************************************
            $sql = mysqli_query($mysqli, "SELECT * FROM det_venta WHERE cod_venta = $codigo;");
            while($row = mysqli_fetch_array($sql)){
                $codigo_producto = $row['cod_producto'];
                $codigo_deposito = $row['cod_deposito'];
                $cantidad = $row['det_cantidad'];
                $actualizar_stock = mysqli_query($mysqli, "UPDATE stock set cantidad = cantidad - $cantidad 
                WHERE cod_producto = $codigo_producto AND cod_deposito = $codigo_deposito;")
                or die('Error'.mysqli_error($mysqli));
            }
            if($query){
                header("Location: ../../main.php?module=ventas&alert=2");
            } else {
                header("Location: ../../main.php?module=ventas&alert=3");
            }
        }
    }
}
?>