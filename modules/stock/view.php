<section class="content-header">
<ol class="breadcrumb">
    <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
    <li class="active"><a href="?module=stock">Stock</a></li>
</ol><br><hr>
<h1>
    <i class="fa fa-folder icon-title"></i>Stock de los productos
</h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <!-- *********************************** deposito *********************************************** -->
                    <form role="form" class="form-horizontal" method="POST">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Depósito</label>
                                <div class="col-sm-3">
                                    <select class="chosen-select" name="codigo_deposito" data-placeholder="Seleccione el Depósito"
                                    autocomplete="off" required>
                                        <option value=""></option>
                                        <?php 
                                            $query_dep = mysqli_query($mysqli, "SELECT cod_deposito, descrip FROM deposito
                                            ORDER BY cod_deposito ASC;") or die ('Error'.mysqli_error($mysqli));
                                            while ($data_dep = mysqli_fetch_assoc($query_dep)){
                                                echo "<option value=\"$data_dep[cod_deposito]\">$data_dep[cod_deposito] | $data_dep[descrip]</option>";
                                            } ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary btn-social btn-submit" style="width:200px">
                                        <i class="fa fa-file-text-o icon-title"></i>Buscar Depósito
                                    </button>
                                </div>
                            </div>
                    </form>   
                            <!-- *********************************** producto *********************************************** -->
                        <form role="form" class="form-horizontal" method="POST">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Producto</label>
                                <div class="col-sm-3">
                                    <select class="chosen-select" name="codigo_producto" data-placeholder="Seleccione el producto" autocomplete="off" required>
                                        <option value=""></option>
                                        <?php 
                                            $query_select_producto = mysqli_query($mysqli, "SELECT cod_producto,p_descrip 
                                            FROM producto ORDER BY p_descrip ASC;") or die ('Error'.mysqli_error($mysqli));
                                            while ($data_producto = mysqli_fetch_assoc($query_select_producto)){
                                                echo "<option value=\"$data_producto[cod_producto]\">$data_producto[cod_producto] | $data_producto[p_descrip]</option>";
                                            } ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary btn-social btn-submit" style="width:200px">
                                        <i class="fa fa-file-text-o icon-title"></i>Buscar Producto
                                    </button>
                                </div>
                            </div>
                        </form>
                    <!-- ******************************************************************************************** -->
                <section class="content-header">
                </section>
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <?php
                        if (isset($_POST['codigo_producto'])) {
                            if (!empty($_POST['codigo_producto'])) {
                                $codigo_producto = $_POST['codigo_producto'];
                            }else {
                                $codigo_producto = 1;
                            }
                            $query_select = mysqli_query($mysqli, "SELECT 
                            stock.cod_deposito,
                            stock.cod_producto,
                            stock.cantidad,
    
                            depo.cod_deposito,
                            depo.descrip,
    
                            produ.cod_producto,
                            produ.cod_tipo_prod,
                            produ.id_u_medida,
                            produ.p_descrip as p_descrip,
    
                            tipo.cod_tipo_prod,
                            tipo.t_p_descrip,
    
                            unidad.id_u_medida,
                            unidad.u_descrip
                            FROM stock stock
                            JOIN deposito depo
                            JOIN producto produ
                            JOIN tipo_producto tipo 
                            JOIN u_medida unidad
                            WHERE stock.cod_deposito = depo.cod_deposito
                            AND stock.cod_producto = produ.cod_producto
                            AND produ.cod_tipo_prod = tipo.cod_tipo_prod
                            AND produ.id_u_medida = unidad.id_u_medida 
                            AND produ.cod_producto = $codigo_producto;")or die('Error'.mysqli_error($mysqli));
                            while($data_select = mysqli_fetch_assoc($query_select)){
                                $p_descrip = $data_select['p_descrip'];
                            }?>                    
                            <h2>Stock de Productos : <?php echo $p_descrip;?> </h2>
                            <thead>
                                <tr>
                                    <th class="center">Tipo de producto</th>
                                    <th class="center">Depósito</th>
                                    <th class="center">Producto</th>
                                    <th class="center">Unidad de medida</th>
                                    <th class="center">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $nro=1;
                                $query_producto = mysqli_query($mysqli, "SELECT 
                                stock.cod_deposito,
                                stock.cod_producto,
                                stock.cantidad as cantidad,
        
                                depo.cod_deposito,
                                depo.descrip as descrip,
        
                                produ.cod_producto,
                                produ.cod_tipo_prod,
                                produ.id_u_medida,
                                produ.p_descrip as p_descrip,
        
                                tipo.cod_tipo_prod,
                                tipo.t_p_descrip as t_p_descrip,
        
                                unidad.id_u_medida,
                                unidad.u_descrip as u_descrip
    
                                FROM stock stock
                                JOIN deposito depo
                                JOIN producto produ
                                JOIN tipo_producto tipo 
                                JOIN u_medida unidad
                                WHERE stock.cod_deposito = depo.cod_deposito
                                AND stock.cod_producto = produ.cod_producto
                                AND produ.cod_tipo_prod = tipo.cod_tipo_prod
                                AND produ.id_u_medida = unidad.id_u_medida 
                                AND produ.cod_producto = $codigo_producto;")or die('Error'.mysqli_error($mysqli));
                                while($data_producto_select = mysqli_fetch_assoc($query_producto)){
                                   $t_p_descrip = $data_producto_select['t_p_descrip'];
                                   $descrip = $data_producto_select['descrip'];
                                   $p_descrip = $data_producto_select['p_descrip'];
                                   $u_descrip = $data_producto_select['u_descrip'];
                                   $cantidad = $data_producto_select['cantidad'];
                                   echo "<tr>
                                   <td class='center'>$t_p_descrip</td>
                                   <td class='center'>$descrip</td>
                                   <td class='center'> $p_descrip</td>
                                   <td class='center'>$u_descrip</td>
                                   <td class='center'>$cantidad</td>
                                    </tr>" ?>
                                <?php } ?>
                            </tbody>
                            <!-- *********************************************************************************************************************** -->
                       <?php }elseif(isset($_POST['codigo_deposito'])){
                        if(!empty($_POST['codigo_deposito'])){
                            $cod_deposito = $_POST['codigo_deposito'];
                        }else {
                            $cod_deposito = 1;
                        }
                        $query = mysqli_query($mysqli, "SELECT * FROM v_stock WHERE cod_deposito = $cod_deposito;")
                        or die('Error'.mysqli_error($mysqli));
                        while($data = mysqli_fetch_assoc($query)){
                            $dep_descrip=$data['descrip'];
                        } ?>                    
                        <h2>Stock de Productos : <?php echo $dep_descrip;?> </h2>
                        <thead>
                            <tr>
                                <th class="center">Depósito</th>
                                <th class="center">Tipo de producto</th>
                                <th class="center">Producto</th>
                                <th class="center">Unidad de medida</th>
                                <th class="center">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $nro=1;
                            $query = mysqli_query($mysqli, "SELECT * FROM v_stock WHERE cod_deposito = $cod_deposito;")
                            or die('Error'.mysqli_error($mysqli));
                            while($data = mysqli_fetch_assoc($query)){
                               $cod_producto=$data['cod_producto'];
                               $p_descrip=$data['p_descrip'];
                               $cod_deposito=$data['cod_deposito'];
                               $dep_descrip=$data['descrip'];
                               $t_p_descrip=$data['t_p_descrip'];
                               $u_descrip=$data['u_descrip'];
                               $catidad=$data['cantidad'];
                               echo "<tr>
                               <td class='center'>$dep_descrip</td>
                               <td class='center'>$t_p_descrip</td>
                               <td class='center'> $p_descrip</td>
                               <td class='center'>$u_descrip</td>
                               <td class='center'>$catidad</td>
                                </tr>" ?>
                            <?php } ?>
                        </tbody>
                        <?php } else {
                            echo "<center><h3><b>Selecciones un elemento en la barra de la busqueda</b></h3></center>";} ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>