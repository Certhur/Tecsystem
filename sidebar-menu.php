<!-- ****************************************Super Admin************************************************************ -->
<?php 
$urlAbsoluto = 'http://'.$_SERVER['HTTP_HOST'].'/Tecsystem-Master';


if($_SESSION['permisos_acceso']=='Super Admin'){ ?>
<ul class="sidebar-menu">
    <li class="header"><h3>Menú</h3></li>
    <?php 
        if($_GET["module"]=="start"){
            $active_home = "active";
        }else{
            $active_home = "";
        }
    ?>
    <li class="<?php echo $active_home; ?>">
        <a href="?module=start"><i class="fa fa-home"></i>Inicio</a>    
    </li>

    <?php 
        //if($_GET['module']=="start") {?>
        
            <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-cog"></i><span>Referencias Generales</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?module=departamento"><i class="fa fa-circle-o"></i>Departamento</a></li>
                    <li><a href="?module=ciudad"><i class="fa fa-circle-o"></i>Ciudad</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-cog"></i><span>Referencias Compras</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?module=deposito"><i class="fa fa-circle-o"></i>Depósito</a></li>
                    <li><a href="?module=proveedor"><i class="fa fa-circle-o"></i>Proveedor</a></li>
                    <li><a href="?module=producto"><i class="fa fa-circle-o"></i>Producto</a></li>
    </ul>
            </li>
            <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-cog"></i><span>Referencias Servicios</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?module=marcas"><i class="fa fa-circle-o"></i>Marcas</a></li>
                    <li><a href="?module=tipo_equipo"><i class="fa fa-circle-o"></i>Tipo Equipo</a></li>
                    <li><a href="?module=tipo_servicio"><i class="fa fa-circle-o"></i>Tipo Servicio</a></li>
                    <li><a href="?module=clientes"><i class="fa fa-circle-o"></i>Clientes</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-pencil"></i><span>Movimientos Servicios</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?module=recepcion_equipo"><i class="fa fa-circle-o"></i>Recepcion de Equipo</a></li>
                </ul>
            </li>



            <?php
            if($_GET["module"]=="user" || $_GET["module"]=="form_user"){
            ?>
            <li class="active">
                <a href="?module=user"><i class="fa fa-user"></i>Administrar de usuarios</a>
            </li>
            <?php }else{ ?>
            <li>
                <a href="?module=user"><i class="fa fa-user"></i>Administrar usuarios</a>
            </li>
            <?php } 
             if($_GET["module"]=="password"){
                ?>
                <li class="active">
                    <a href="?module=password"><i class="fa fa-user"></i>Cambiar contraseña</a>
                </li>
                <?php }else{ ?>
                <li>
                    <a href="?module=password"><i class="fa fa-user"></i>Cambiar contraseña</a>
                </li>
                <?php }?>
            <?php// } ?>
</ul> 
<!-- ****************************************Compras************************************************************ -->
<?php }elseif($_SESSION["permisos_acceso"]=="Compras"){ ?>
<ul class="sidebar-menu">
    <li class="header">Menú</li>
    <?php 
        if($_GET["module"]=="start"){
            $active_home = "active";
        }else{
            $active_home = "";
        }
    ?>
    <li class="<?php echo $active_home; ?>">
        <a href="?module=start"><i class="fa fa-home"></i>Inicio</a>    
    </li>
    <?php 
        //if($_GET['module']=="start") {?>
            <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-file-text"></i><span>Referencias Generales</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?module=departamento"><i class="fa fa-circle-o"></i>Departamento</a></li>
                    <li><a href="?module=ciudad"><i class="fa fa-circle-o"></i>Ciudad</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i>Prueba</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-file-text"></i><span>Referencias Compras</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?module=clientes"><i class="fa fa-circle-o"></i>Clientes</a></li>
                    <li><a href="?module=deposito"><i class="fa fa-circle-o"></i>Depósito</a></li>
                    <li><a href="?module=proveedor"><i class="fa fa-circle-o"></i>Proveedor</a></li>
                    <li><a href="?module=producto"><i class="fa fa-circle-o"></i>Producto</a></li>
                    <li><a href="?module=unidad_medida"><i class="fa fa-circle-o"></i>Unidad de medida</a></li>
                </ul>
            </li>
            <!-- <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-certificate"></i><span>Referencias Ventas</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i>Clientes</a></li>
                </ul>
            </li> -->
            <!-- <?php
           // if($_GET["module"]=="user" || $_GET["module"]=="form_user"){
            ?>
            <li class="active">
                <a href="?module=user"><i class="fa fa-user"></i>Administrar de usuarios</a>
            </li>
            <?php //}else{ ?>
            <li>
                <a href="?module=user"><i class="fa fa-user"></i>Administrar usuarios</a>
            </li> -->
            <?php //} 
             if($_GET["module"]=="password"){
                ?>
                <li class="active">
                    <a href="?module=password"><i class="fa fa-user"></i>Cambiar contraseña</a>
                </li>
                <?php }else{ ?>
                <li>
                    <a href="?module=password"><i class="fa fa-user"></i>Cambiar contraseña</a>
                </li>
                <?php }?>
            <?php// } ?>
</ul>
<!-- *************************************************** ventas **************************************************************** -->
<?php }elseif($_SESSION["permisos_acceso"]=="Ventas"){ ?>

<li class="header">Menú</li>
    <?php 
        if($_GET["module"]=="start"){
            $active_home = "active";
        }else{
            $active_home = "";
        }
    ?>
<ul class="sidebar-menu">
    <li class="<?php echo $active_home; ?>">
        <a href="?module=start"><i class="fa fa-home"></i>Inicio</a>    
    </li>
    <li class="treeview">
        <a href="javascript:void(0);">
            <i class="fa fa-file-text"></i><span>Referencias Generales</span><i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="?module=departamento"><i class="fa fa-circle-o"></i>Departamento</a></li>
            <li><a href="?module=ciudad"><i class="fa fa-circle-o"></i>Ciudad</a></li>
        </ul>
    </li>
    <li class="treeview">
                <a href="javascript:void(0);">
                    <i class="fa fa-certificate"></i><span>Referencias Ventas</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?module=clientes"><i class="fa fa-circle-o"></i>Clientes</a></li>
                </ul>
            <?php if($_GET["module"]=="password"){
                ?>
                <li class="active">
                    <a href="?module=password"><i class="fa fa-user"></i>Cambiar contraseña</a>
                </li>
                <?php }else{ ?>
                <li>
                    <a href="?module=password"><i class="fa fa-user"></i>Cambiar contraseña</a>
                </li>
            <?php }?>
    </li>
</ul>
<?php } ?>



