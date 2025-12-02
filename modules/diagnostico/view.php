<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active"><a href="?module=diagnostico">Diagnóstico de Equipos</a></li>
    </ol><br>
    <hr>
    <h1>
        <i class="fa fa-stethoscope icon-title">Diagnóstico de Equipos</i>

        <a class="btn btn-primary btn-social pull-right" href="?module=form_diagnostico&form=add" title="Agregar" data-toggle="tooltip">
            <i class="fa fa-plus"></i>Agregar
        </a>

        <a class="btn btn-warning btn-social pull-right" style="margin-right:10px;" href="modules/diagnostico/diagnostico_archivados.php">
           <i class="fa fa-archive"></i> Archivados
        </a>
        <a class="btn btn-warning btn-social pull-right" style="margin-right:10px;" href="modules/diagnostico/reporte_diagnosticos.php" target="_blank">
            <i class="fa fa-print"></i> IMPRIMIR DIAGNÓSTICOS
        </a>
        

    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($_GET['alert'])) {
                switch ($_GET['alert']) {
                    case 1:
                        echo "<div class='alert alert-success'>Diagnóstico registrado correctamente</div>"; break;
                    case 2:
                        echo "<div class='alert alert-success'>Diagnóstico actualizado correctamente</div>"; break;
                    case 3:
                        echo "<div class='alert alert-success'>Diagnóstico eliminado correctamente</div>"; break;
                    case 4:
                        echo "<div class='alert alert-danger'>Error al realizar la operación</div>"; break;
                }
            }
            ?>
            <div class="box box-primary">
                <div class="box-body">
                <section class="content-header">
                    <a class="btn btn-warning btn-social pull-right" 
                    href="modules/diagnostico/reporte_diagnosticos.php"
                    target="_blank">
                        <i class="fa fa-print"></i> IMPRIMIR DIAGNÓSTICOS
                    </a>
                </section>
                    <div style="margin-bottom:10px">
                    <button class="btn btn-secondary" id="btnVerArchivados">
                        <i class="fa fa-archive"></i> Archivados
                    </button>
                </div>
                    
                    <table id="dataTables1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Equipo</th>
                                <th>Cliente</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Modelo</th>
                                <th>Falla</th>
                                <th>Causa</th>
                                <th>Solución</th>
                                <th>Observaciones</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        include __DIR__ . '/../../config/database.php';

                        $query = mysqli_query($mysqli, "SELECT dg.*, re.equipo_modelo, m.marca_descrip, te.tipo_descrip, cl.cli_razon_social
                                                        FROM diagnostico AS dg
                                                        LEFT JOIN recepcion_equipo AS re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
                                                        LEFT JOIN marcas AS m ON re.id_marca = m.id_marca
                                                        LEFT JOIN tipo_equipo AS te ON re.id_tipo_equipo = te.id_tipo_equipo
                                                        LEFT JOIN clientes AS cl ON re.id_cliente = cl.id_cliente") 
                                                        or die(mysqli_error($mysqli));

                        while ($data = mysqli_fetch_assoc($query)) {
                            // Color de fila según estado
                            switch ($data['estado_diagnostico']) {
                                case 'Pendiente': $row_class = 'style="background-color:#d9d9d9;"'; break;
                                case 'En Proceso': $row_class = 'style="background-color:#fff3cd;"'; break;
                                case 'Finalizado': $row_class = 'style="background-color:#d4edda;"'; break;
                                case 'Cancelado': $row_class = 'style="background-color:#f8d7da;"'; break;
                                default: $row_class = '';
                            }

                            echo "<tr {$row_class}>
                                <td>{$data['id_diagnostico']}</td>
                                <td>{$data['fecha_diagnostico']}</td>
                                <td>{$data['id_recepcion_equipo']}</td>
                                <td>{$data['cli_razon_social']}</td>
                                <td>{$data['marca_descrip']}</td>
                                <td>{$data['tipo_descrip']}</td>
                                <td>{$data['equipo_modelo']}</td>
                                <td>{$data['falla_diagnostico']}</td>
                                <td>{$data['causa_diagnostico']}</td>
                                <td>{$data['solucion_diagnostico']}</td>
                                <td>{$data['observaciones']}</td>
                                <td>
                                    <button type='button' class='btn btn-info btn-sm btn-estado' 
                                            data-id='{$data['id_diagnostico']}' 
                                            data-estado='{$data['estado_diagnostico']}' 
                                            data-toggle='modal' data-target='#modalEstado'>
                                        {$data['estado_diagnostico']} <i class='fa fa-pencil'></i>
                                    </button>
                                </td>
                               <td>
                                <a class='btn btn-default btn-sm' 
                                href='modules/diagnostico/imprimir_diagnostico.php?id={$data['id_diagnostico']}'
                                target='_blank' 
                                title='Imprimir Diagnóstico'>
                                    <i class='fa fa-print'></i>
                                </a>

                                <button class='btn btn-warning btn-sm btn-archivar' data-id='{$data['id_diagnostico']}'>
                                    <i class='fa fa-archive'></i>
                                </button>
                            </td>

                            </tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog" aria-labelledby="modalEstadoLabel">
  <div class="modal-dialog" role="document">
    <form id="formCambiarEstado">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modalEstadoLabel">Cambiar Estado</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_diagnostico" id="modal_id_diagnostico">
          <div class="form-group">
            <label>Selecciona un estado:</label>
            <select name="estado_diagnostico" id="modal_estado_diagnostico" class="form-control" required>
              <option value="">--Seleccione--</option>
              <option value="Pendiente">Pendiente</option>
              <option value="En Proceso">En Proceso</option>
              <option value="Finalizado">Finalizado</option>
              <option value="Cancelado">Cancelado</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Confirmar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!--Modal confirmar para Archivado-->
<div class="modal fade" id="modalArchivar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="formArchivar">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Archivar Diagnóstico</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_diagnostico" id="archivar_id_diagnostico">
          <p>¿Está seguro que desea archivar este diagnóstico?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Confirmar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal para RESTAURAR -->
<div class="modal fade" id="modalRestaurar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="formRestaurar">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Restaurar Diagnóstico</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <p>¿Deseas restaurar este diagnóstico?</p>
          <input type="hidden" id="restaurar_id_diagnostico" name="id_diagnostico">
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Restaurar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- MODAL ARCHIVADOS -->
<div class="modal fade" id="modalArchivados" tabindex="-1">
  <div class="modal-dialog modal-xl" role="document" style="max-width:95%">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Diagnósticos Archivados</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        <!-- CONTENEDOR RESPONSIVE -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaModalArchivados">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Equipo</th>
                        <th>Cliente</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Modelo</th>
                        <th>Falla</th>
                        <th>Causa</th>
                        <th>Solución</th>
                        <th>Observaciones</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

      </div>

    </div>
  </div>
</div>

<script>
$(document).ready(function(){

    /* ===============================
       ABRIR MODAL CAMBIAR ESTADO
    ================================= */
    $(document).on('click', '.btn-estado', function(){
        $('#modal_id_diagnostico').val($(this).data('id'));
        $('#modal_estado_diagnostico').val($(this).data('estado'));
        $('#modalEstado').modal('show');
    });

    /* ===============================
       CONFIRMAR CAMBIO DE ESTADO
    ================================= */
    $('#formCambiarEstado').submit(function(e){
        e.preventDefault();

        var id = $('#modal_id_diagnostico').val();
        var estado = $('#modal_estado_diagnostico').val();

        if(!estado) return;

        $.ajax({
            url: 'modules/diagnostico/proses.php?accion=cambiar_estado',
            type: 'POST',
            dataType: 'json',
            data: {id_diagnostico: id, estado_diagnostico: estado},
            success: function(r){
                if(r.status === 'ok'){
                    $('#modalEstado').modal('hide');
                    location.reload(); // actualiza sin alert
                }
            }
        });
    });


    /* ===============================
       ARCHIVAR – abrir modal
    ================================= */
    $(document).on('click', '.btn-archivar', function(){
        $('#archivar_id_diagnostico').val($(this).data('id'));
        $('#modalArchivar').modal('show');
    });

    /* ===============================
       ARCHIVAR – confirmar
    ================================= */
    $('#formArchivar').submit(function(e){
        e.preventDefault();

        var id = $('#archivar_id_diagnostico').val();

        $.ajax({
            url: 'modules/diagnostico/proses.php?accion=archivar',
            type: 'POST',
            dataType: 'json',
            data: { id_diagnostico: id },
            success: function(r){
                if(r.status === 'ok'){
                    $('#modalArchivar').modal('hide');
                    $('button[data-id="'+id+'"]').closest('tr')
                        .fadeOut(300, function(){ $(this).remove(); });
                }
            }
        });
    });


    /* ===============================
       BOTÓN "VER ARCHIVADOS"
    ================================= */
    $('#btnVerArchivados').click(function(){

        $.ajax({
            url: 'modules/diagnostico/proses.php?accion=obtener_archivados',
            type: 'GET',
            dataType: 'json',
            success: function(data){

                let tbody = $('#tablaModalArchivados tbody');
                tbody.empty();

                data.forEach(item => {
                    tbody.append(`
                        <tr style="background-color:#e2e3e5;">
                            <td>${item.id_diagnostico}</td>
                            <td>${item.fecha_diagnostico}</td>
                            <td>${item.id_recepcion_equipo}</td>
                            <td>${item.cli_razon_social}</td>
                            <td>${item.marca_descrip}</td>
                            <td>${item.tipo_descrip}</td>
                            <td>${item.equipo_modelo}</td>
                            <td>${item.falla_diagnostico}</td>
                            <td>${item.causa_diagnostico}</td>
                            <td>${item.solucion_diagnostico}</td>
                            <td>${item.observaciones}</td>
                            <td><span class="label label-default">Archivado</span></td>
                            <td>
                                <button class="btn btn-success btn-sm btnRestaurar"
                                    data-id="${item.id_diagnostico}">
                                    Restaurar
                                </button>
                            </td>
                        </tr>
                    `);
                });

                $('#modalArchivados').modal('show');
            }
        });

    });

/* ABRIR MODAL RESTAURAR */
$(document).on('click', '.btnRestaurar', function(){
    let id = $(this).data('id');
    $('#restaurar_id_diagnostico').val(id);

    // Cerrar modal archivados primero
    $('#modalArchivados').modal('hide');

    // Esperar a que termine de cerrarse, luego abrir el siguiente
    setTimeout(function(){
        $('#modalRestaurar').modal('show');
    }, 300); // 300ms es perfecto
});

/* CONFIRMAR RESTAURAR */
$('#formRestaurar').submit(function(e){
    e.preventDefault();

    let id = $('#restaurar_id_diagnostico').val();

    $.ajax({
        url: 'modules/diagnostico/proses.php?accion=desarchivar',
        type: 'POST',
        dataType: 'json',
        data: { id_diagnostico: id },
        success: function(r){
            if(r.status === 'ok'){
                $('#modalRestaurar').modal('hide');
                $('#modalArchivados').modal('hide');
                location.reload();
            }
        }
    });

});

});
</script>
