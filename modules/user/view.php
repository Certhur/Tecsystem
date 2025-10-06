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
    <h1>
        <i class="fa fa-user icon-title"></i>Gestión de usuarios
        <a class="btn btn-primary btn-social pull-right" href="?module=form_user&form=add" title="Agregar" data-togle="tooltip">
            <i class="fa fa-plus"></i>Agregar
        </a>
    </h1>
</section>
<!-- ********************************************************************************************************************************* -->
<?php 
$sql = mysqli_query($mysqli, "SELECT COUNT(*)AS cantidad_total_usuarios FROM usuarios;");
$data = mysqli_fetch_assoc($sql);
$cantidad_total_usuarios = $data['cantidad_total_usuarios'];
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="col-lg-4 col-xs-6">
                <div style="background-color:#00c0ef; color:#fff" class="small-box"> 
                    <div class="inner">
                        <p><strong>Total de Usuarios</strong></p>
                        <h3><?php echo $cantidad_total_usuarios; ?></h3>
                        <ul>
                            <li>Listar</li>
                            <li>Agregar Usuarios</li>
                        </ul>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                    |</div>
                    <a href="?module=user_bloque_total" class="small-box-footer" title="Registrar Compras" data-toggle="tooltip"> 
                    <i class="fa fa-plus"></i></a>
                </div>
            </div>
<!-- ********************************************************************************************************************************* -->
            <?php 
            $sql = mysqli_query($mysqli, "SELECT COUNT(STATUS)AS cantidad_total_activos FROM usuarios WHERE STATUS = 'activo';");
            $data = mysqli_fetch_assoc($sql);
            $cantidad_total_activos = $data['cantidad_total_activos'];
            ?>
            <div class="col-lg-4 col-xs-6">
                <div style="background-color:#00a65a; color:#fff" class="small-box"> 
                    <div class="inner">
                        <p><strong>Usuarios Activos</strong> </p>
                        <h3><?php echo $cantidad_total_activos; ?></h3>
                        <ul>
                            <li>Listar</li>
                            <li>Bloquear Usuarios</li>
                        </ul>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <a href="?module=user_bloque_activos" class="small-box-footer" title="Registrar Ventas" data-toggle="tooltip"> 
                    <i class="fa fa-plus"></i></a>
                </div>
            </div>
<!-- ********************************************************************************************************************************* -->
            <?php 
            $sql = mysqli_query($mysqli, "SELECT COUNT(STATUS)AS cantidad_total_inactivos FROM usuarios WHERE STATUS = 'bloqueado';");
            $data = mysqli_fetch_assoc($sql);
            $cantidad_total_inactivos = $data['cantidad_total_inactivos'];
         ?>
            <div class="col-lg-4 col-xs-6">
                <div style="background-color:#ad0d59; color:#fff" class="small-box"> 
                    <div class="inner">
                        <p><strong>Usuarios Inactivos</strong></p>
                        <h3><?php echo $cantidad_total_inactivos; ?></h3>
                        <ul>
                            <li>Listar</li>
                            <li>Desbloquear Usuarios</li>
                        </ul>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <a href="?module=user_bloque_inactivo" class="small-box-footer" title="Registrar Ventas" data-toggle="tooltip"> 
                    <i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ********************************************************************************************************************************* -->




<section class="content">

    <div class="row">
        <div class="col-md-12">
        <?php
            if(empty($_GET["alert"])){
                echo "";
            }elseif($_GET["alert"]==1){
                echo"<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Exito !!!</h4>
                Los nuevos datos de usuarios se han registrado correctamente.
                </div>";
            }elseif($_GET["alert"]==2){
                echo"<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Exito !!!</h4>
                Los nuevos datos del usuario se han modificado correctamente!
                </div>";
            }elseif($_GET["alert"]==3){
                echo"<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Correcto !!!</h4>
                La nueva contraseña ingresada fue exitosa !.
                </div>";
            }elseif($_GET["alert"]==4){
                echo"<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Correcto !!!</h4>
                El usuario se ha bloqueado correctamente!
                </div>";
            }
            if ($_GET["alert"] == 4) {?>
                <script>
                    swal("USUARIO BLOQUEADO !", {
                    icon: "success",
                    buttons: false,
                    timer: 2000,
                    });
                </script>
           <?php } 
            elseif($_GET["alert"]==5){
                echo"<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-times-circle'></i>Error !!!</h4>
                Asegurece que el formato de la imagen sea correcto !.
                </div>";
            }elseif($_GET["alert"]==6){
                echo"<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-times-circle'></i>Correcto !!!</h4>
               El archivo debe ser menor de 1MB !.
                </div>";
            }elseif($_GET["alert"]==7){
                echo"<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-times-circle'></i>Correcto !!!</h4>
                Asegurece que el archivo sea *.JPG *.JPEG *.PNG !.
                </div>";
            }elseif($_GET["alert"]==8){
                echo"<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Correcto !!!</h4>
                El usuario se ha desbloqueado correctamente !
                </div>";
            }
            if ($_GET["alert"] == 8) {?>
                <script>
                    swal("USUARIO ACTIVADO !", {
                    icon: "success",
                    buttons: false,
                    timer: 2000,
                    });
                </script>
           <?php } 
            ?>
<!-- Aplicar tablas -->
<div class="bos box-primary">
    <div class="box-body">
        <table id="dataTable1" class="table table-bordered table-stripec table-hover">
            <thead>
                <tr>
                    <th class="center">Nro.</th>
                    <th class="center">Foto</th>
                    <th class="center">Nombre del usuario</th>
                    <th class="center">Nombre</th>
                    <th class="center">Permisos de acceso</th>
                    <th class="center">Status</th>
                    <th class="center">Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php 
                    $nro = 1;
                    $query = mysqli_query($mysqli,"SELECT * FROM usuarios ORDER BY id_user DESC;")
                    or die("error".mysqli_error($mysqli));
                    while($data = mysqli_fetch_assoc($query)){
                        echo "<tr>
                        <td width='50' class'center'>$nro</td>";
                        if($data['foto']==""){?>
                            <td class="center"><img class="img-user" src="images/user/user-default.png" width="45"></td>
                        <?php }else{ ?>
                            <td class="center"><img class="img-user" src="images/user/<?php echo $data['foto']; ?>" width="45"></td>
                        <?php } 
                        echo "<td>$data[username]</td>
                            <td>$data[name_user]</td>
                            <td>$data[permisos_acceso]</td>
                            <td>$data[status]</td>
                            <td class='center' width='100'>
                            <div>";
                            if($data['status']=='activo'){?>
                            <a data-togle="tooltip" data-placement="top" title="Bloqueado" style="margin-right:5px" 
                            class="btn btn-warning btn-sm" href="modules/user/proses.php?act=off&id=<?php echo $data['id_user']; ?>">
                            <i class="glyphicon glyphicon-off"></i>    
                        </a>
                        <?php }else{ ?>
                            <a data-togle="tooltip" data-placement="top" title="Bloqueado" style="margin-right:5px" 
                            class="btn btn-warning btn-sm" href="modules/user/proses.php?act=on&id=<?php echo $data['id_user']; ?>">
                            <i class="glyphicon glyphicon-off"></i>
                            </a>
                        <?php }
                        echo "<a data-togle='tooltip' data-placement='top' title='Modificar' class='btn btn-primary btn-sm'
                        href='?module=form_user&form=edit&id=$data[id_user]'>
                        <i style='color:#fff' class='glyphicon glyphicon-edit'></id>
                        </a>
                        </div>
                        </td>
                        </tr>";
                        $nro++;
                    } ?>   
        </tbody>
        </table>
    </div>
</div>
</div>
</div>
</section>

