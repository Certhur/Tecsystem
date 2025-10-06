<?php 
require_once '../config/database.php';
$action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
    $x = mysqli_real_escape_string($mysqli,(strip_tags($_REQUEST['x'],ENT_QUOTES)));
    $aColumns = array('cod_producto', 'cod_tipo_prod', 'id_u_medida', 'p_descrip', 'precio');
    $sTable = "producto";
    $sWhere = "";
    if($_GET['x'] != ""){
       $sWhere = "WHERE (";
       for ($i=0; $i<count($aColumns); $i++){
           $sWhere .=$aColumns[$i]." LIKE '%".$x."%' OR ";
       }
       $sWhere = substr_replace($sWhere, "", -3);
       $sWhere .= ')';
    }
    //include paginacion *******************
    include 'paginacion.php';
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    $per_page = 5;
    $adjacents = 4;
    $offset = ($page -1) * $per_page;
    $count_query = mysqli_query($mysqli, "SELECT count(*) AS numrows FROM $sTable $sWhere;");
    $row=mysqli_fetch_array($count_query);
    $numrows = $row['numrows'];
    $total_pages = ceil($numrows/$per_page);
    $reload='./index.php';

    $sql = "SELECT * FROM $sTable $sWhere LIMIT $offset, $per_page;";
    $query = mysqli_query($mysqli,$sql);

    if($numrows>0){ ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <tr class="warning">
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Unidad de Medida</th>
                    <th>Producto</th>
                    <th><span class="pull-right">Cantidad</span></th>
                    <th><span class="pull-right">Precio</span></th>
                    <th style="width:36px;">Seleccionar</th>
                </tr>
                <?php 
                while ($row=mysqli_fetch_array($query)){
                    $id_producto=$row['cod_producto'];
                    $descrip_producto=$row['p_descrip'];
                    $codigo_tproducto=$row['cod_tipo_prod'];
                    $sql_tproducto = mysqli_query($mysqli, "SELECT t_p_descrip FROM tipo_producto WHERE cod_tipo_prod='$codigo_tproducto';");
                    $rw_tproducto = mysqli_fetch_array($sql_tproducto);
                    $tproducto_nombre= $rw_tproducto['t_p_descrip'];
                    $id_u_medida=$row['id_u_medida'];
                    $sql_umedida = mysqli_query($mysqli, "SELECT u_descrip FROM u_medida WHERE id_u_medida='$id_u_medida';");
                    $rw_u_medida = mysqli_fetch_array($sql_umedida);
                    $u_medida_nombre= $rw_u_medida['u_descrip'];
                    $precio_compra=$row['precio']; ?>
                <tr>
                    <td><?php echo $id_producto; ?></td>
                    <td><?php echo $tproducto_nombre; ?></td>
                    <td><?php echo $u_medida_nombre; ?></td>
                    <td><?php echo $descrip_producto; ?></td>
                    <td class="col-xs-1">
                        <div class="pull-right">
                            <input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $id_producto;?>" value="1">
                        </div>
                    </td>
                    <td class="col-xs-2">
                        <div class="pull-right">
                            <input type="text" class="form-control" style="text-align:right" id="precio_compra_<?php echo $id_producto;?>" value="<?php echo $precio_compra; ?>">
                        </div>
                    </td>
                    <td><span class="pull-right"><a href="#" onclick="agregar('<?php echo $id_producto; ?>')"><i class="glyphicon glyphicon-plus"></i></a></span>
                    </td>
                </tr>    
                <?php } ?>
                <tr>
                    <td colspan=5>
                    <span class="pull-right">
                    <?php echo paginate($reload, $page, $total_pages, $adjacents);?>
                    </span></td>
                </tr>
            </table>
        </div>
    <?php }} ?>