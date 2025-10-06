<!DOCTYPE html>
<html lang="en">
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximun-scale=1, user-scalable=yes" name="viewport">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta name="autor" content="Nicolás Picaguá">
        <link rel="shortcut icon" href="assets/img/favicon.ico" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugin/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

<style>
body {
  /* padding: auto px;
  background: url(assets/img/fondoBlancoNico.png);
  background-repeat: no-repeat;
  background-size: 100% 100%;
  object-fit: cover; */
}
</style>

    <body>     
        <div class="login-box">
            <div style="color:#3c8dbc" class="login-logo">
                <img style="margin-top:-15px" src="assets/img/favicon.ico" alt="SysWeb" width="70" height="10">
                <b>J.N.P</b>
            </div>

            <?php
                if(empty($_GET["alert"])){
                echo"";
                }
                elseif($_GET["alert"]==1){
                echo"<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-times-circle'></i>Error al iniciar sesión</h4>
                Usuario o contraseña incorrectos, vuelva a ingresar sus datos!.
                </div>";
                }elseif($_GET["alert"]==2){
                echo"<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Exito !!!</h4>
                Ha cerrado la sección !.
                </div>";
                }elseif($_GET["alert"]==3){
                    echo"<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4> <i class='icon fa fa-check-circle'></i>Atención !!!</h4>
                Debes ingresar el usuario y la contraseña !.
                </div>";
                }
            ?>

            <div class="login-box-body">
                <center><p class="login-box-msg"><i class="fa fa-user icon-title"></i><h3> Iniciar seccion !</h3></p></center>
                <br>
                <form action="login-check.php" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="username" placeholder="Nombre de usuario" autocomplete="off" required>
                        <span class="glyphicon glyphicon-user form-control-feedback">
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" name="password" placeholder="Contraseña" autocomplete="off" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="submit" class="btn btn-primary btn-lg btn-block btn-flat" name="login" value="Ingresar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="assets/plugins/jQuery-2.1.3.min.js"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>
