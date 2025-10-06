<?php

require "config/database.php";

if(empty($_SESSION["username"]) && empty($_SESSION["password"])){
    echo "<meta http=equiv='refresh' content='0; url=index.php?alert=3'></meta>";
}else{
    if($_GET["module"]== "start"){
        include "modules/start/view.php";
    }
    elseif($_GET["module"]=="password"){
        include "modules/password/view.php";
    }
    elseif($_GET["module"]=="user"){
        include "modules/user/view.php";
    }
    elseif($_GET["module"]=="form_user"){
        include "modules/user/form.php";
    }
    //***************bloque********************** */
    elseif($_GET["module"]=="user_bloque_total"){
        include "modules/user/user_bloques/user_bloque_total/view.php";
    }
    elseif($_GET["module"]=="form_user"){
        include "modules/user/user_bloques/form.php";
    }
    //**** */
    elseif($_GET["module"]=="user_bloque_activos"){
        include "modules/user/user_bloques/user_bloque_activos/view.php";
    }
    elseif($_GET["module"]=="form_user"){
        include "modules/user/user_bloques/form.php";
    }
    //**** */
    elseif($_GET["module"]=="user_bloque_inactivo"){
        include "modules/user/user_bloques/user_bloque_inactivo/view.php";
    }
    elseif($_GET["module"]=="form_user"){
        include "modules/user/user_bloques/form.php";
    }

        //**contacts.php** */
        elseif($_GET["module"]=="view_update_user"){
            include "modules/user/user_bloques/user_bloque_total/view_update_user.php";
        }
        elseif($_GET["module"]=="form_user"){
            include "modules/user/user_bloques/form.php";
        }
    //************************************* */
    elseif($_GET["module"]=="perfil"){
        include "modules/perfil/view.php";
    }
    elseif($_GET["module"]=="form_perfil"){
        include "modules/perfil/form.php";
    }
    elseif($_GET["module"]=="departamento"){
        include "modules/departamento/view.php";
    }
    elseif($_GET["module"]=="form_departamento"){
        include "modules/departamento/form.php";
    }
    elseif($_GET["module"]=="ciudad"){
        include "modules/ciudad/view.php";
    }
    elseif($_GET["module"]=="form_ciudad"){
        include "modules/ciudad/form.php";
    }
    elseif($_GET["module"]=="marcas"){
        include "modules/marcas/view.php";
    }
    elseif($_GET["module"]=="form_marcas"){
        include "modules/marcas/form.php";
    }
    elseif($_GET["module"]=="tipo_equipo"){
        include "modules/tipo_equipo/view.php";
    }
    elseif($_GET["module"]=="form_tipo_equipo"){
        include "modules/tipo_equipo/form.php";
    }
    elseif($_GET["module"]=="tipo_servicio"){
        include "modules/tipo_servicio/view.php";
    }
    elseif($_GET["module"]=="form_tipo_servicio"){
        include "modules/tipo_servicio/form.php";
    }
    elseif($_GET["module"]=="clientes"){
        include "modules/clientes/view.php";
    }
    elseif($_GET["module"]=="form_clientes"){
        include "modules/clientes/form.php";
    }
    elseif($_GET["module"]=="compras"){
        include "modules/compras/view.php";
    }
    elseif($_GET["module"]=="form_compras"){
        include "modules/compras/form.php";
    }

    elseif($_GET["module"]=="stock"){
        include "modules/stock/view.php";
    }

    elseif($_GET["module"]=="ventas"){
        include "modules/ventas/view.php";
    }
    elseif($_GET["module"]=="form_ventas"){
        include "modules/ventas/form.php";
    }

    elseif($_GET["module"]=="deposito"){
        include "modules/deposito/view.php";
    }
    elseif($_GET["module"]=="form_deposito"){
        include "modules/deposito/form.php";
    }

    elseif($_GET["module"]=="proveedor"){
        include "modules/proveedor/view.php";
    }
    elseif($_GET["module"]=="form_proveedor"){
        include "modules/proveedor/form.php";
    }

    // elseif($_GET["module"]=="unidad_medida"){
    //     include "modules/unidad_medida/view.php";
    // }
    // elseif($_GET["module"]=="form_unidad_medida"){
    //     include "modules/unidad_medida/form.php";
    // }


    elseif($_GET["module"]=="producto"){
        include "modules/producto/view.php";
    }
    elseif($_GET["module"]=="form_producto"){
        include "modules/producto/form.php";
    }
    elseif($_GET["module"]=="recepcion_equipo"){
        include "modules/recepcion_equipo/view.php";
    }
    elseif($_GET["module"]=="form_recepcion_equipo"){
        include "modules/recepcion_equipo/form.php";
    }
}

?>