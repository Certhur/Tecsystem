<section class="content-header">
    <h1>
        <i class="fa fa-user icon-title"></i>Gestión de Usuarios Total
    </h1>
</section>
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
                        echo "
                            <td>$data[username]</td>
                            <td>$data[name_user]</td>
                            <td>$data[permisos_acceso]</td>
                            <td>$data[status]</td>
                            <td class='center' width='100'>
                            <div>";
                            if($data['status']=='activo'){?>
                            <!-- <a data-togle="tooltip" data-placement="top" title="Bloqueado" style="margin-right:5px" 
                            class="btn btn-warning btn-sm" href="modules/user/proses.php?act=off&id=<?php // echo $data['id_user']; ?>">
                            <i class="glyphicon glyphicon-off"></i></a>     -->
                        <?php }else{ ?>
                            <!-- <a data-togle="tooltip" data-placement="top" title="Bloqueado" style="margin-right:5px" 
                            class="btn btn-warning btn-sm" href="modules/user/proses.php?act=on&id=<?php // echo $data['id_user']; ?>">
                            <i class="glyphicon glyphicon-off"></i></a> -->
                        <?php }
                        echo "
                            <a href='?module=view_update_user&form=edit&id=$data[id_user]' class='btn btn-success btn-social' title='Editar Usuario' data-togle='tooltip'>
                            <i class='fa fa-pencil-square-o'></i>Editar
                        </a>
                        </div>
                        </td>";
                        $nro++;
                    } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="form-horizontal" >
    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
            <a href="?module=view_update_user&form=add" class="btn btn-primary btn-social" title="Agregar Usuarios" data-togle="tooltip">
                <i class="fa fa-plus"></i>Agregar
            </a>

            <a href="?module=user" class="btn btn-primary btn-social" title="Volver a la página anterior" data-togle="tooltip">
                <i class="fa fa-arrow-left"></i>Atras
            </a>

            <!-- <a href="?module=user" class="btn btn-primary btn-submit" title="Volver a la página anterior">
                <i class="fa fa-plus"></i>Atras
            </a> -->
            </div>
        </div>
    </div>
</div>


</div>
</div>
</section>
