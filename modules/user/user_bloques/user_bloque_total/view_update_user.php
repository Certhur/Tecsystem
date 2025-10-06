<html>
    <head>
        <style>
            body{
        margin-top:20px;
        color: #1a202c;
        text-align: left;
        background-color: #e2e8f0;    
    }
    .main-body {
        padding: 15px;
    }
    .card {
        box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1rem;
    }

    .gutters-sm {
        margin-right: -8px;
        margin-left: -8px;
    }

    .gutters-sm>.col, .gutters-sm>[class*=col-] {
        padding-right: 8px;
        padding-left: 8px;
    }
    .mb-3, .my-3 {
        margin-bottom: 1rem!important;
    }

    .bg-gray-300 {
        background-color: #e2e8f0;
    }
    .h-100 {
        height: 100%!important;
    }
    .shadow-none {
        box-shadow: none!important;
    }
            </style>
        </head>
</html>
<?php if($_GET['form']=='add'){ ?>
    <section class="content-header">
    <h1><i class="fa fa-user"></i>Modificar Usuario</h1>
        <div class="container">
            <form role="form" class="form-horizontal" method="post" action="modules/user/proses.php?act=update" enctype="multipart/form-data">
                <div class="main-body">
                    <input type="hidden" name="id_user" value="<?php echo $data['id_user']; ?>">
                    <div class="row gutters-sm">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="images/user/sin-foto-hombre.jpg" alt="Admin" class="rounded-circle" width="340">
                                    <div class="mt-3">
                                        <h3><i class="fa fa-user"></i></h3>
                                        <h4><p class="text-secondary mb-1"><i class="fa fa-bar-chart"></i></p></h4>
                                        <h5><p class="text-muted font-size-sm"><i class="fa fa-phone"></i></p></h5>
                                        <input type="file" name="foto" class="btn btn-primary"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nombre de Usuario</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="username" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nombre y Apellido</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="name_user" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Correo</label>
                                            <div class="col-sm-5">
                                                <input type="email" class="form-control" name="email" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Teléfono</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="telefono" autocomplete="off" maxlength="12" onkeypress="return goodchars(event,'0123456789',this)" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Permisos de acceso</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="permisos_acceso" required>
                                                    <option value="Super Admin"></option>
                                                    <option value="Super Admin">Administrador de Sistemas </option>
                                                    <option value="Compras">Usuario de Compras</option>
                                                    <option value="Ventas">Usuario de Ventas</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                        <!-- <input type="submit"class="btn btn-primary btn-social" name="Guardar" value="Guardar"><i class="fa fa-plus"></i>
                                            <a href="" class="btn btn-primary btn-social" title="Agregar Usuarios" data-togle="tooltip">
                                                <i class="fa fa-plus"></i>
                                            </a> -->
                                            <button type="submit" class="btn btn-primary btn-social" name="Guardar" onclick=""><i class="fa fa-plus"></i>Guardar</button>
                                            <a href="javascript:window.location.reload()"><button type="button" class="btn btn-repeat btn-social"><i class="fa fa-plus"></i>Cancelar</button></a>
                                            <a href="?module=user_bloque_total" class="btn btn-primary btn-social" title="Volver a la página anterior" data-togle="tooltip">
                                                <i class="fa fa-arrow-left"></i>Atras
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <form>
        </div>
    </div>
</section>
<!-- ***************************************** update user ******************************************* -->
<?php }elseif ($_GET['form']=='edit') {
if(isset($_GET["id"])){
    $query = mysqli_query($mysqli, "SELECT * FROM usuarios WHERE id_user ='$_GET[id]';")
    or die('error'.mysqli_error($mysqli));
    $data = mysqli_fetch_assoc($query);
}?>
<section class="content-header">
    <h1><i class="fa fa-user"></i>Modificar Usuario</h1>
        <div class="container">
            <form role="form" class="form-horizontal" method="post" action="modules/user/proses.php?act=update" enctype="multipart/form-data">
                <div class="main-body">
                    <input type="hidden" name="id_user" value="<?php echo $data['id_user']; ?>">
                    <div class="row gutters-sm">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                    <?php  
                                        if ($data['foto']=="") { ?>
                                        <img src="images/user/sin-foto-hombre.jpg" alt="Admin" class="rounded-circle" width="340">
                                        <?php
                                        }else { ?>
                                        <img src="images/user/<?php echo $data['foto']; ?>" alt="Admin" class="rounded-circle" height="340" width="340">
                                        <?php } ?>
                                    <div class="mt-3">
                                        <h3><i class="fa fa-user"></i><?php echo $data['name_user']; ?></h3>
                                        <h4><p class="text-secondary mb-1"><i class="fa fa-bar-chart"></i><?php echo $data["permisos_acceso"]; ?></p></h4>
                                        <h5><p class="text-muted font-size-sm"><i class="fa fa-phone"></i><?php echo $data['telefono']; ?></p></h5>
                                        <input type="file" name="foto" class="btn btn-primary"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nombre de Usuario</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="username" autocomplete="off" value="<?php echo $data['username']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nombre y Apellido</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="name_user" autocomplete="off" value="<?php echo $data['name_user']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Correo</label>
                                            <div class="col-sm-5">
                                                <input type="email" class="form-control" name="email" autocomplete="off" value="<?php echo $data['email']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Teléfono</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="telefono" autocomplete="off" maxlength="12" onkeypress="return goodchars(event,'0123456789',this)"
                                                value="<?php echo $data['telefono']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Permisos de acceso</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="permisos_acceso" required>
                                                    <option value="<?php echo $data["permisos_acceso"]; ?>"><?php echo $data["permisos_acceso"]; ?></option>
                                                    <option value="Super Admin">Administrador de Sistemas </option>
                                                    <option value="Compras">Usuario de Compras</option>
                                                    <option value="Ventas">Usuario de Ventas</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                        <!-- <input type="submit"class="btn btn-primary btn-social" name="Guardar" value="Guardar"><i class="fa fa-plus"></i>
                                            <a href="" class="btn btn-primary btn-social" title="Agregar Usuarios" data-togle="tooltip">
                                                <i class="fa fa-plus"></i>
                                            </a> -->
                                            <button type="submit" class="btn btn-primary btn-social" name="Guardar" onclick=""><i class="fa fa-plus"></i>Guardar</button>
                                            <a href="javascript:window.location.reload()"><button type="button" class="btn btn-repeat btn-social"><i class="fa fa-plus"></i>Cancelar</button></a>
                                            <a href="?module=user_bloque_total" class="btn btn-primary btn-social" title="Volver a la página anterior" data-togle="tooltip">
                                                <i class="fa fa-arrow-left"></i>Atras
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <form>
        </div>
    </div>
</section>
<?php } ?>
<script>
function bloquear_usuario(id_user){
var cod_user = id_user;
  swal({
  title: "BLOQUEAR ?",
  text: "DESEA BLOQUEAR ESTE USUARIO...?",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    // swal("USUARIO BLOQUEADO !", {
    //   icon: "success",
    //   buttons: false,
    //   timer: 2000,
    // });
    if(willDelete){
        //window.location.href='modules/user/proses.php?act=off&id='+cod_user;
        location.href = "modules/user/proses.php?act=off&id="+cod_user;
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
</script>