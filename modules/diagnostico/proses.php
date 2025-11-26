<?php
header('Content-Type: application/json');
session_start();
require_once "../../config/database.php";

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
} else {

    $accion = !empty($_REQUEST['accion']) ? $_REQUEST['accion'] : null;

    switch ($accion) {

        // CONSULTAS PARA SELECT Y DATOS DE RECEPCION
        case 'consultarRecepcion':
            $query = mysqli_query($mysqli, "SELECT re.id_recepcion_equipo, re.equipo_modelo, cl.cli_razon_social 
                                            FROM recepcion_equipo AS re 
                                            LEFT JOIN clientes AS cl ON re.id_cliente = cl.id_cliente");
            $recepciones = [];
            while ($row = mysqli_fetch_assoc($query)) {
                $recepciones[] = $row;
            }
            echo json_encode($recepciones);
            break;
        case 'cambiar_estado':
            if(isset($_POST['id_diagnostico']) && isset($_POST['estado_diagnostico'])){
                $id = mysqli_real_escape_string($mysqli, $_POST['id_diagnostico']);
                $estado = mysqli_real_escape_string($mysqli, $_POST['estado_diagnostico']);

                $query = mysqli_query($mysqli, "UPDATE diagnostico SET estado_diagnostico='$estado' WHERE id_diagnostico='$id'");
                if($query){
                    echo json_encode(['status' => 'ok']); // <- ahora devuelve JSON
                } else {
                    echo json_encode(['status' => 'error']);
                }
            } else {
                echo json_encode(['status' => 'error']);
            }
            exit(); // detener ejecución aquí
        break;


        case 'archivar':
                $id = $_POST['id_diagnostico'];

                $query = mysqli_query($mysqli,
                    "UPDATE diagnostico SET estado_diagnostico = 'Archivado'
                    WHERE id_diagnostico = '$id'"
                );

                echo json_encode(["status" => $query ? "ok" : "error"]);
                exit();
        break;

        case 'obtener_archivados':
            header('Content-Type: application/json');

            $sql = mysqli_query($mysqli,
                "SELECT dg.*, re.equipo_modelo, cl.cli_razon_social, m.marca_descrip, te.tipo_descrip
                FROM diagnostico dg
                LEFT JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
                LEFT JOIN clientes cl ON re.id_cliente = cl.id_cliente
                LEFT JOIN marcas m ON re.id_marca = m.id_marca
                LEFT JOIN tipo_equipo te ON re.id_tipo_equipo = te.id_tipo_equipo
                WHERE dg.estado_diagnostico = 'Archivado'"
            );

            $rows = [];
            while($r = mysqli_fetch_assoc($sql)){
                $rows[] = $r;
            }

            echo json_encode($rows);
            exit();
        break;
        
        case 'desarchivar':
            header('Content-Type: application/json');

            $id = intval($_POST['id_diagnostico']);
            $q = mysqli_query($mysqli,
                "UPDATE diagnostico SET estado_diagnostico='Pendiente'
                WHERE id_diagnostico=$id"
            );

            echo json_encode(["status" => $q ? "ok" : "error"]);
            exit();
        break;






        case 'datosRecepcion':
            $id = intval($_GET['id']);
            $query = mysqli_query($mysqli, "SELECT re.*, cl.cli_razon_social, m.marca_descrip, te.tipo_descrip 
                                           FROM recepcion_equipo AS re
                                           LEFT JOIN clientes AS cl ON re.id_cliente = cl.id_cliente
                                           LEFT JOIN marcas AS m ON re.id_marca = m.id_marca
                                           LEFT JOIN tipo_equipo AS te ON re.id_tipo_equipo = te.id_tipo_equipo
                                           WHERE re.id_recepcion_equipo = $id");
            echo json_encode(mysqli_fetch_assoc($query));
            break;

        // INSERTAR DIAGNOSTICO
    case 'insertar':
        $id_recepcion_equipo = $_POST['id_recepcion_equipo'];
        $falla_diagnostico = mysqli_real_escape_string($mysqli, $_POST['falla_diagnostico']);
        $causa_diagnostico = mysqli_real_escape_string($mysqli, $_POST['causa_diagnostico']);
        $solucion_diagnostico = mysqli_real_escape_string($mysqli, $_POST['solucion_diagnostico']);
        $observaciones = mysqli_real_escape_string($mysqli, $_POST['observaciones']);

        // ✅ Asignamos por defecto "Pendiente"
        $estado_diagnostico = 'Pendiente';

        if (empty($id_recepcion_equipo) || empty($falla_diagnostico) || empty($causa_diagnostico) || empty($solucion_diagnostico)) {
            echo json_encode(['status' => 'error', 'mensaje' => 'Faltan campos requeridos']);
            exit();
        }

        $query = mysqli_query($mysqli, "INSERT INTO diagnostico 
            (id_recepcion_equipo, falla_diagnostico, causa_diagnostico, solucion_diagnostico, observaciones, estado_diagnostico, fecha_diagnostico)
            VALUES
            ('$id_recepcion_equipo', '$falla_diagnostico', '$causa_diagnostico', '$solucion_diagnostico', '$observaciones', '$estado_diagnostico', NOW())");

        if ($query) {
            header("Location: ../../main.php?module=diagnostico&alert=1");
        } else {
            header("Location: ../../main.php?module=diagnostico&alert=4");
        }
        break;

        // ACTUALIZAR DIAGNOSTICO
        case 'actualizar':
            $id_diagnostico = $_POST['id_diagnostico'];
            $falla_diagnostico = mysqli_real_escape_string($mysqli, $_POST['falla_diagnostico']);
            $causa_diagnostico = mysqli_real_escape_string($mysqli, $_POST['causa_diagnostico']);
            $solucion_diagnostico = mysqli_real_escape_string($mysqli, $_POST['solucion_diagnostico']);
            $observaciones = mysqli_real_escape_string($mysqli, $_POST['observaciones']);

            $query = mysqli_query($mysqli, "UPDATE diagnostico SET 
                                            falla_diagnostico = '$falla_diagnostico',
                                            causa_diagnostico = '$causa_diagnostico',
                                            solucion_diagnostico = '$solucion_diagnostico',
                                            observaciones = '$observaciones'
                                            WHERE id_diagnostico = '$id_diagnostico'");

            if ($query) {
                header("Location: ../../main.php?module=diagnostico&alert=2");
            } else {
                header("Location: ../../main.php?module=diagnostico&alert=4");
            }
            break;

        // ELIMINAR DIAGNOSTICO
        case 'eliminar':
            $id_diagnostico = intval($_GET['id_diagnostico']);
            $query = mysqli_query($mysqli, "DELETE FROM diagnostico WHERE id_diagnostico = $id_diagnostico");
            if ($query) {
                echo json_encode(['mensaje' => 'Diagnóstico eliminado correctamente']);
            } else {
                echo json_encode(['error' => 'Error al eliminar diagnóstico']);
            }
            break;

        default:
            echo json_encode(['error' => 'Acción no válida']);
            break;
    }
}
?>
