
<?php
header('Content-Type: application/json');
session_start();
require_once "../../config/database.php";


if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<meta http-equiv='refresh' content='0; url=index.php?alert=3'>";
} else {

    $accion = !empty($_REQUEST['accion']) && isset($_REQUEST['accion']) ? $_REQUEST['accion'] : null;

    switch ($accion) {

            //CONSULTA DE DATOS DE LOS SELECT

        case 'consultarCliente':
            $query = mysqli_query($mysqli, "SELECT id_cliente, cli_razon_social FROM clientes");

            if ($query) {
                $clientes = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $clientes[] = $row;
                }
                echo json_encode($clientes);
            } else {
                echo json_encode(['error' => 'Error en la consulta']);
            }

            break;

        case 'consultarMarca':
            $query = mysqli_query($mysqli, "SELECT id_marca, marca_descrip FROM marcas");

            if ($query) {
                $marcas = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $marcas[] = $row;
                }
                echo json_encode($marcas);
            } else {
                echo json_encode(['error' => 'Error en la consulta de marcas']);
            }
            break;

        case 'consultarTipo_Equipo':
            $query = mysqli_query($mysqli, "SELECT id_tipo_equipo, tipo_descrip FROM tipo_equipo");

            if ($query) {
                $tipo_equipo = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $tipo_equipo[] = $row;
                }
                echo json_encode($tipo_equipo);
            } else {
                echo json_encode(['error' => 'Error en la consulta de marcas']);
            }
            break;

        case 'consultarCiudad':
            $query = mysqli_query($mysqli, "SELECT cod_ciudad, descrip_ciudad FROM ciudad");

            if ($query) {
                $ciudad = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $ciudad[] = $row;
                }
                echo json_encode($ciudad);
            } else {
                echo json_encode(['error' => 'Error en la consulta']);
            }

            break;

            //FIN DE CONSULTA DE DATOS DE LOS SELECT

            //REGISTRAR DATOS DEL MODAL

        case 'guardarMarca':

            $nuevaMarca = $_POST['nuevaMarca'];

            // Validar que no esté vacía
            if (!empty($nuevaMarca)) {
                // Insertar la nueva marca en la tabla 'marcas'
                $query = "INSERT INTO marcas(marca_descrip) VALUES ('$nuevaMarca')";
                $result = mysqli_query($mysqli, $query);

                if ($result) {
                    // Si la inserción fue exitosa, devolver el ID de la nueva marca
                    $lastId = mysqli_insert_id($mysqli);
                    echo json_encode(['success' => true, 'id_marca' => $lastId, 'marca_descrip' => $nuevaMarca]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar la marca']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Nombre de marca vacío']);
            }

            break;

        case 'guardarTipoEquipo':

            $nuevoTipoEquipo = $_POST['nuevoTipoEquipo'];

            if (!empty($nuevoTipoEquipo)) {

                $query = "INSERT INTO tipo_equipo(tipo_descrip) VALUES ('$nuevoTipoEquipo')";
                $result = mysqli_query($mysqli, $query);

                if ($result) {
                    $lastId = mysqli_insert_id($mysqli);
                    echo json_encode(['success' => true, 'id_tipo_equipo' => $lastId, 'tipo_descrip' => $nuevoTipoEquipo]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar el tipo equipo']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Nombre de tipo equipo vacío']);
            }

            break;

        case 'guardarCliente':

            // Obtener los datos enviados por AJAX
            $razon_social = $_POST['razon_social'];
            $ruc_ci = $_POST['ruc_ci'];
            $ciudad = $_POST['ciudad'];
            $direccion = $_POST['direccion'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];

            // Validar que los campos no estén vacíos
            if (!empty($razon_social) && !empty($ruc_ci) && !empty($ciudad) && !empty($direccion) && !empty($email) && !empty($telefono)) {
                // Insertar el nuevo cliente en la tabla 'clientes'
                $query = "INSERT INTO clientes(cod_ciudad, ci_ruc, cli_razon_social, cli_direccion, cli_telefono, cli_email) 
                  VALUES ($ciudad, '$ruc_ci', '$razon_social', '$direccion', $telefono, '$email')";
                $result = mysqli_query($mysqli, $query);

                if ($result) {
                    // Si la inserción fue exitosa, devolver el ID del nuevo cliente
                    $lastId = mysqli_insert_id($mysqli);
                    echo json_encode(['success' => true, 'id_cliente' => $lastId, 'razon_social' => $razon_social]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar el cliente']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Campos vacíos']);
            }

            break;



            //FIN DEL REGISTRO DE DATOS MODAL

            //INSERT UPDATE DELETE

        case 'insertar':
            $id_cliente = $_POST['cliente'];
            $id_marca = $_POST['marca'];
            $id_tipo_equipo = $_POST['tipo_equipo'];
            $equipo_modelo = mysqli_real_escape_string($mysqli, $_POST['modelo']);
            $equipo_descripcion = mysqli_real_escape_string($mysqli, $_POST['descripcion']);
            $recepcion_estado = 1;

            // Validación básica de campos requeridos
            if (empty($id_cliente) || empty($id_marca) || empty($id_tipo_equipo) || empty($equipo_modelo) || empty($equipo_descripcion)) {
                echo json_encode(['status' => 'error', 'mensaje' => 'Faltan campos requeridos']);
                exit();
            }

            $query = mysqli_query($mysqli, "INSERT INTO recepcion_equipo(id_tipo_equipo, id_marca, equipo_modelo, equipo_descripcion, fecha_recepcion, recepcion_estado, id_cliente) 
                        VALUES ('$id_tipo_equipo', '$id_marca','$equipo_modelo', '$equipo_descripcion', NOW(), $recepcion_estado, '$id_cliente')");

            if ($query) {
                header("Location: ../../main.php?module=recepcion_equipo&alert=1");
            } else {
                header(header: "Location: ../../main.php?module=recepcion_equipo&alert=4");
            }
            break;

        case 'actualizar':

            $id_cliente = $_POST['cliente'];
            $id_marca = $_POST['marca'];
            $id_tipo_equipo = $_POST['tipo_equipo'];
            $equipo_modelo = mysqli_real_escape_string($mysqli, $_POST['modelo']);
            $equipo_descripcion = mysqli_real_escape_string($mysqli, $_POST['descripcion']);

            // Validación básica de campos requeridos
            if (empty($id_recepcion_equipo) || empty($id_cliente) || empty($id_marca) || empty($id_tipo_equipo) || empty($equipo_modelo) || empty($equipo_descripcion)) {
                echo json_encode(['status' => 'error', 'mensaje' => 'Faltan campos requeridos']);
                exit();
            }

            // Preparar la consulta de actualización
            $query = mysqli_query($mysqli, "UPDATE recepcion_equipo SET 
                                            id_cliente = '$id_cliente', 
                                            id_marca = '$id_marca',
                                            id_tipo_equipo = '$id_tipo_equipo',
                                            equipo_modelo = '$equipo_modelo', 
                                            equipo_descripcion = '$equipo_descripcion'
                                        WHERE id_recepcion_equipo = '$id_recepcion_equipo'");

            // Comprobar si la actualización fue exitosa
            if ($query) {
                echo json_encode(['status' => 'success', 'mensaje' => 'Registro actualizado correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'mensaje' => 'Error al actualizar el registro']);
            }
            break;

        case 'eliminar':

            $id_recepcion_equipo = $_GET['id_recepcion_equipo'];

            if (empty($id_recepcion_equipo)) {
                echo json_encode(['error' => 'ID no proporcionado']);
                exit();
            }
            $query = mysqli_query($mysqli, "DELETE FROM recepcion_equipo WHERE id_recepcion_equipo = $id_recepcion_equipo;");
            if ($query) {
                echo json_encode(['mensaje' => 'Registro eliminado correctamente']);
            } else {
                echo json_encode(['error' => 'Error al eliminar registro']);
            }

            break;

        default:
            echo json_encode(['error' => 'Acción no válida']);
            break;
    }
}



?>
