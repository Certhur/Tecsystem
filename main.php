<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php include "head.php"    ?>

<body class="skin-blue fixed">
    <div class="wrapper">
        <header class="main-header">
            <a href="#" class="logo">
                <img src="assets/img/logo_2019.png" alt="Logo Sysweb">
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <?php include "top-menu.php" ?>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <?php include "sidebar-menu.php" ?>
            </section>
        </aside>
        <div class="content-wrapper">
            <?php include "content.php"; ?>
            <div class="modal fade" id="logout">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                                <h4 class="modal-title"><i class="fa fa-sign-out">Salir</i></h4>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Seguro que quieres salir ?</p>
                        </div>
                        <div class="modal-footer">
                            <a type="button" class="btn btn-danger" href="logout.php">Si, salir</a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer">
            <strong>Copyright &copy; <?php echo date('Y'); ?> - <a href="#" target="_blank">Desarrollado por Ivan Florentin</a>.</strong>
        </footer>
    </div>
    <?php include "scripts.php"  ?>
    <!-- ***************************************************************************** -->
</body>

</html>