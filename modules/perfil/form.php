<?php 
    if(isset($_POST["id_user"])){
        $query = mysqli_query($mysqli, "SELECT * FROM usuarios WHERE id_user ='$_POST[id_user]';")
        or die ('Error'.mysqli_error($mysqli));
        $data = mysqli_fetch_assoc($query);
    }
?>
<section class="content-header">
    <h1>
        <i class="fa fa-edit icon-title"></i>Modificar Usuario
    </h1>
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li><a href="?module=perfil">Perfil del usuario</a></li>
        <li class="active">Modificar</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form role="form" class="form-horizontal" method="POST" action="modules/perfil/proses.php?act=update" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="id_user" value="<?php echo $data["id_user"]; ?>">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nombre de usuario</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="username" autocomplete="off" value="<?php echo $data["username"]; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nombre y Apellido</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="name_user" autocomplete="off" value="<?php echo $data["name_user"]; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Correo</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="email" autocomplete="off" value="<?php echo $data["email"]; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Teléfono</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="telefono" autocomplete="off" value="<?php echo $data["telefono"]; ?>" maxlength="12" onkeypress="return goodchars(event,'0123456789',this)" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Foto</label>
                            <div class="col-sm-5">
                                <input type="file" name="foto">
                                <br>
                                <?php 
                                    if($data["foto"]==""){ ?>
                                    <img style="border:1px solid #eaeaea;border-radius:5px;" src="images/user/user-feault.png" widht="280">
                                <?php    }
                                    else { ?>
                                    <img style="border:1px solid #eaeaea;border-radius:5px;" src="images/user/<?php echo $data["foto"]; ?>" width="280">
                                <?php    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class=form-group>
                            <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-submit" name="Guardar" value="Guardar" title="Los datos Ingresados se guardarán">
                            <a href="?module=perfil" class="btn btn-default btn-reset" title="Cancelar la operación">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>