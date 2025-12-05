<?php
include "config/database.php";

if ($_GET['form'] == 'add') { ?>
    <section class="content-header">
        <h1><i class="fa fa-edit icon-title"></i> Agregar Presupuesto</h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=presupuesto">Presupuestos</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>

    <section class="content">
      <div class="row"><div class="col-md-12">
      <div class="box box-primary"><div class="box-body">

        <form role="form" class="form-horizontal"
              action="modules/presupuesto/proses.php?accion=insertar"
              method="POST" id="formPresupuesto">

          <!-- FECHA -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Fecha Presupuesto :</label>
              <div class="col-sm-5">
                  <input type="date" class="form-control"
                         value="<?= date('Y-m-d'); ?>" readonly>
              </div>
          </div>

          <!-- DIAGNÓSTICO (FINALIZADO) -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Diagnóstico :</label>
              <div class="col-sm-5">
                  <select class="chosen-select" id="id_diagnostico" name="id_diagnostico" required>
                      <option value="" disabled selected>Seleccione un diagnóstico finalizado</option>
                  </select>
              </div>
          </div>

          <!-- BLOQUE DATOS CLIENTE / EQUIPO -->
          <div class="form-group">
              <div class="col-sm-6">
                  <h4>Datos del Cliente</h4>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Cliente :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="cli_razon_social" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">CI/RUC :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="ci_ruc" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Teléfono :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="cli_telefono" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Dirección :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="cli_direccion" readonly>
                      </div>
                  </div>
              </div>

              <div class="col-sm-6">
                  <h4>Datos del Equipo</h4>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Tipo :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="tipo_descrip" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Marca :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="marca_descrip" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Modelo :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="equipo_modelo" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Descripción :</label>
                      <div class="col-sm-8">
                          <textarea class="form-control" id="equipo_descripcion" rows="2" readonly></textarea>
                      </div>
                  </div>
              </div>
          </div>

          <hr>

          <!-- TABLA DE ÍTEMS -->
          <h4>Ítems del Presupuesto</h4>
          <div class="table-responsive">
            <table class="table table-bordered" id="tablaDetalles">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th style="width:80px;">Cant.</th>
                        <th style="width:120px;">Precio Unit.</th>
                        <th style="width:120px;">Subtotal</th>
                        <th style="width:50px;">Acción</th>
                    </tr>
                </thead>
                <tbody id="tbodyDetalles">
                    <tr>
                        <td>
                            <input type="text" name="detalle_descripcion[]" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="detalle_cantidad[]" class="form-control input-cant" min="1" value="1" required>
                        </td>
                        <td>
                            <input type="number" name="detalle_precio[]" class="form-control input-precio" min="0" step="0.01" value="0.00" required>
                        </td>
                        <td>
                            <input type="text" name="detalle_subtotal[]" class="form-control input-subtotal" value="0.00" readonly>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm btnEliminarFila">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
          </div>

          <button type="button" class="btn btn-default btn-sm" id="btnAgregarFila">
              <i class="fa fa-plus"></i> Agregar ítem
          </button>

          <hr>

          <!-- TOTALES -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Mano de Obra :</label>
              <div class="col-sm-3">
                  <input type="number" step="0.01" min="0"
                         class="form-control" name="mano_obra" id="mano_obra" value="0.00">
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">Subtotal :</label>
              <div class="col-sm-3">
                  <input type="text" class="form-control" name="subtotal" id="subtotal" value="0.00" readonly>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">Total :</label>
              <div class="col-sm-3">
                  <input type="text" class="form-control" name="total" id="total" value="0.00" readonly>
              </div>
          </div>

          <!-- OBSERVACIONES -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Observaciones :</label>
              <div class="col-sm-6">
                  <textarea class="form-control" name="observaciones" rows="3"></textarea>
              </div>
          </div>

          <div class="box-footer">
              <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" class="btn btn-primary btn-submit" value="Guardar"
                         onclick="return confirm('¿Guardar presupuesto?')">
                  <a href="?module=presupuesto" class="btn btn-default btn-reset">Cancelar</a>
              </div>
          </div>

        </form>

      </div></div></div></div>

      <script>
      // ===============================
      // Cargar diagnósticos finalizados
      // ===============================
      function cargarDiagnosticosFinalizados() {
          $.getJSON("modules/presupuesto/proses.php?accion=consultarDiagnosticosFinalizados", function(data){
              let $sel = $("#id_diagnostico");
              $sel.empty();
              $sel.append('<option value="" disabled selected>Seleccione un diagnóstico finalizado</option>');

              data.forEach(function(item){
                  // item.id_diagnostico, item.texto
                  $sel.append(
                      '<option value="'+item.id_diagnostico+'">'+item.texto+'</option>'
                  );
              });

              $sel.trigger("chosen:updated");
          });
      }

      // ===============================
      // Datos del diagnóstico seleccionado
      // ===============================
      function cargarDatosDiagnostico(id) {
          if(!id) return;
          $.getJSON("modules/presupuesto/proses.php?accion=datosDiagnostico&id="+id, function(d){
              if(!d) return;
              $("#cli_razon_social").val(d.cli_razon_social);
              $("#ci_ruc").val(d.ci_ruc);
              $("#cli_telefono").val(d.cli_telefono);
              $("#cli_direccion").val(d.cli_direccion);
              $("#tipo_descrip").val(d.tipo_descrip);
              $("#marca_descrip").val(d.marca_descrip);
              $("#equipo_modelo").val(d.equipo_modelo);
              $("#equipo_descripcion").val(d.equipo_descripcion);
          });
      }

      // ===============================
      // Detalle: cálculos
      // ===============================
      function recalcularFila($tr){
          let cant   = parseFloat($tr.find(".input-cant").val())   || 0;
          let precio = parseFloat($tr.find(".input-precio").val()) || 0;
          let sub    = cant * precio;
          $tr.find(".input-subtotal").val( sub.toFixed(2) );
      }

      function recalcularTotales(){
          let subtotal = 0;
          $(".input-subtotal").each(function(){
              subtotal += parseFloat($(this).val()) || 0;
          });
          $("#subtotal").val( subtotal.toFixed(2) );

          let mano = parseFloat($("#mano_obra").val()) || 0;
          let total = subtotal + mano;
          $("#total").val( total.toFixed(2) );
      }

      $(document).ready(function(){

          cargarDiagnosticosFinalizados();

          $("#id_diagnostico").change(function(){
              let id = $(this).val();
              cargarDatosDiagnostico(id);
          });

          // Eventos detalle
          $(document).on("keyup change", ".input-cant, .input-precio", function(){
              let $tr = $(this).closest("tr");
              recalcularFila($tr);
              recalcularTotales();
          });

          $("#mano_obra").on("keyup change", function(){
              recalcularTotales();
          });

          // Agregar fila
          $("#btnAgregarFila").click(function(){
              let fila = `
                <tr>
                    <td><input type="text" name="detalle_descripcion[]" class="form-control" required></td>
                    <td><input type="number" name="detalle_cantidad[]" class="form-control input-cant" min="1" value="1" required></td>
                    <td><input type="number" name="detalle_precio[]" class="form-control input-precio" min="0" step="0.01" value="0.00" required></td>
                    <td><input type="text" name="detalle_subtotal[]" class="form-control input-subtotal" value="0.00" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnEliminarFila">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
              $("#tbodyDetalles").append(fila);
          });

          // Eliminar fila
          $(document).on("click", ".btnEliminarFila", function(){
              let filas = $("#tbodyDetalles tr").length;
              if(filas <= 1){
                  alert("Debe existir al menos una fila.");
                  return;
              }
              $(this).closest("tr").remove();
              recalcularTotales();
          });

      });
      </script>

    </section>

<?php
// =============== EDITAR ===============
} elseif ($_GET['form'] == 'edit') {

    if (isset($_GET['id'])) {
        $id_presupuesto = intval($_GET['id']);

        // Cabecera de presupuesto + joins para mostrar cliente/equipo
        $q = mysqli_query($mysqli, "
            SELECT p.*,
                   dg.id_diagnostico,
                   re.equipo_modelo,
                   re.equipo_descripcion,
                   m.marca_descrip,
                   te.tipo_descrip,
                   cl.cli_razon_social,
                   cl.ci_ruc,
                   cl.cli_telefono,
                   cl.cli_direccion
            FROM presupuesto p
            LEFT JOIN diagnostico dg      ON p.id_diagnostico      = dg.id_diagnostico
            LEFT JOIN recepcion_equipo re ON dg.id_recepcion_equipo = re.id_recepcion_equipo
            LEFT JOIN clientes cl         ON re.id_cliente         = cl.id_cliente
            LEFT JOIN marcas m            ON re.id_marca           = m.id_marca
            LEFT JOIN tipo_equipo te      ON re.id_tipo_equipo     = te.id_tipo_equipo
            WHERE p.id_presupuesto = $id_presupuesto
        ");
        $cab = mysqli_fetch_assoc($q);

        // Detalle
        $detalles = [];
        $qd = mysqli_query($mysqli, "
            SELECT * FROM presupuesto_detalle
            WHERE id_presupuesto = $id_presupuesto
            ORDER BY id_detalle ASC
        ");
        while($r = mysqli_fetch_assoc($qd)) {
            $detalles[] = $r;
        }
    }
    ?>
    <section class="content-header">
        <h1><i class="fa fa-edit icon-title"></i> Modificar Presupuesto</h1>
        <ol class="breadcrumb">
            <li><a href="?module=start"><i class="fa fa-home"></i>Inicio</a></li>
            <li><a href="?module=presupuesto">Presupuestos</a></li>
            <li class="active">Modificar</li>
        </ol>
    </section>

    <section class="content">
      <div class="row"><div class="col-md-12">
      <div class="box box-primary"><div class="box-body">

        <form role="form" class="form-horizontal"
              action="modules/presupuesto/proses.php?accion=actualizar"
              method="POST" id="formPresupuestoEdit">

          <input type="hidden" name="id_presupuesto"
                 value="<?php echo $cab['id_presupuesto']; ?>">

          <!-- FECHA (solo muestra) -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Fecha Presupuesto :</label>
              <div class="col-sm-5">
                  <input type="text" class="form-control"
                         value="<?php echo $cab['fecha_presupuesto']; ?>" readonly>
              </div>
          </div>

          <!-- DIAGNÓSTICO (no editable) -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Diagnóstico :</label>
              <div class="col-sm-5">
                  <input type="text" class="form-control"
                         value="ID #<?php echo $cab['id_diagnostico']; ?> - <?php echo $cab['cli_razon_social']; ?> - <?php echo $cab['equipo_modelo']; ?>"
                         readonly>
                  <input type="hidden" name="id_diagnostico"
                         value="<?php echo $cab['id_diagnostico']; ?>">
              </div>
          </div>

          <!-- BLOQUE DATOS CLIENTE / EQUIPO (solo lectura) -->
          <div class="form-group">
              <div class="col-sm-6">
                  <h4>Datos del Cliente</h4>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Cliente :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control"
                                 value="<?php echo $cab['cli_razon_social']; ?>" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">CI/RUC :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control"
                                 value="<?php echo $cab['ci_ruc']; ?>" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Teléfono :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control"
                                 value="<?php echo $cab['cli_telefono']; ?>" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Dirección :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control"
                                 value="<?php echo $cab['cli_direccion']; ?>" readonly>
                      </div>
                  </div>
              </div>

              <div class="col-sm-6">
                  <h4>Datos del Equipo</h4>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Tipo :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control"
                                 value="<?php echo $cab['tipo_descrip']; ?>" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Marca :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control"
                                 value="<?php echo $cab['marca_descrip']; ?>" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Modelo :</label>
                      <div class="col-sm-8">
                          <input type="text" class="form-control"
                                 value="<?php echo $cab['equipo_modelo']; ?>" readonly>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label">Descripción :</label>
                      <div class="col-sm-8">
                          <textarea class="form-control" rows="2" readonly><?php echo $cab['equipo_descripcion']; ?></textarea>
                      </div>
                  </div>
              </div>
          </div>

          <hr>

          <!-- TABLA DETALLES -->
          <h4>Ítems del Presupuesto</h4>
          <div class="table-responsive">
              <table class="table table-bordered" id="tablaDetallesEdit">
                  <thead>
                      <tr>
                          <th>Descripción</th>
                          <th style="width:80px;">Cant.</th>
                          <th style="width:120px;">Precio Unit.</th>
                          <th style="width:120px;">Subtotal</th>
                          <th style="width:50px;">Acción</th>
                      </tr>
                  </thead>
                  <tbody id="tbodyDetallesEdit">
                      <?php
                      if (count($detalles) == 0) {
                          // fila vacía
                          ?>
                          <tr>
                              <td><input type="text" name="detalle_descripcion[]" class="form-control" required></td>
                              <td><input type="number" name="detalle_cantidad[]" class="form-control input-cant" min="1" value="1" required></td>
                              <td><input type="number" name="detalle_precio[]" class="form-control input-precio" min="0" step="0.01" value="0.00" required></td>
                              <td><input type="text" name="detalle_subtotal[]" class="form-control input-subtotal" value="0.00" readonly></td>
                              <td class="text-center">
                                  <button type="button" class="btn btn-danger btn-sm btnEliminarFila">
                                      <i class="fa fa-trash"></i>
                                  </button>
                              </td>
                          </tr>
                          <?php
                      } else {
                          foreach($detalles as $d) {
                              ?>
                              <tr>
                                  <td>
                                      <input type="text" name="detalle_descripcion[]" class="form-control"
                                             value="<?php echo htmlspecialchars($d['descripcion']); ?>" required>
                                  </td>
                                  <td>
                                      <input type="number" name="detalle_cantidad[]" class="form-control input-cant"
                                             min="1" value="<?php echo (int)$d['cantidad']; ?>" required>
                                  </td>
                                  <td>
                                      <input type="number" name="detalle_precio[]" class="form-control input-precio"
                                             min="0" step="0.01" value="<?php echo number_format($d['precio_unitario'],2,'.',''); ?>" required>
                                  </td>
                                  <td>
                                      <input type="text" name="detalle_subtotal[]" class="form-control input-subtotal"
                                             value="<?php echo number_format($d['subtotal'],2,'.',''); ?>" readonly>
                                  </td>
                                  <td class="text-center">
                                      <button type="button" class="btn btn-danger btn-sm btnEliminarFila">
                                          <i class="fa fa-trash"></i>
                                      </button>
                                  </td>
                              </tr>
                              <?php
                          }
                      }
                      ?>
                  </tbody>
              </table>
          </div>

          <button type="button" class="btn btn-default btn-sm" id="btnAgregarFilaEdit">
              <i class="fa fa-plus"></i> Agregar ítem
          </button>

          <hr>

          <!-- TOTALES -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Mano de Obra :</label>
              <div class="col-sm-3">
                  <input type="number" step="0.01" min="0"
                         class="form-control" name="mano_obra" id="mano_obra_edit"
                         value="<?php echo number_format($cab['mano_obra'],2,'.',''); ?>">
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">Subtotal :</label>
              <div class="col-sm-3">
                  <input type="text" class="form-control" name="subtotal" id="subtotal_edit"
                         value="<?php echo number_format($cab['subtotal'],2,'.',''); ?>" readonly>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">Total :</label>
              <div class="col-sm-3">
                  <input type="text" class="form-control" name="total" id="total_edit"
                         value="<?php echo number_format($cab['total'],2,'.',''); ?>" readonly>
              </div>
          </div>

          <!-- ESTADO -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Estado :</label>
              <div class="col-sm-3">
                  <select name="estado" class="form-control" required>
                      <?php
                      $estados = ['Pendiente','Enviado','Aprobado','Rechazado'];
                      foreach($estados as $e){
                          $sel = ($e == $cab['estado']) ? "selected" : "";
                          echo "<option value='$e' $sel>$e</option>";
                      }
                      ?>
                  </select>
              </div>
          </div>

          <!-- OBSERVACIONES -->
          <div class="form-group">
              <label class="col-sm-2 control-label">Observaciones :</label>
              <div class="col-sm-6">
                  <textarea class="form-control" name="observaciones" rows="3"><?php
                      echo htmlspecialchars($cab['observaciones']);
                  ?></textarea>
              </div>
          </div>

          <div class="box-footer">
              <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" class="btn btn-primary btn-submit" value="Guardar"
                         onclick="return confirm('¿Desea modificar los datos del presupuesto?')">
                  <a href="?module=presupuesto" class="btn btn-default btn-reset">Cancelar</a>
              </div>
          </div>

        </form>

      </div></div></div></div>

      <script>
      function recalcularFilaEdit($tr){
          let cant   = parseFloat($tr.find(".input-cant").val())   || 0;
          let precio = parseFloat($tr.find(".input-precio").val()) || 0;
          let sub    = cant * precio;
          $tr.find(".input-subtotal").val( sub.toFixed(2) );
      }

      function recalcularTotalesEdit(){
          let subtotal = 0;
          $("#tbodyDetallesEdit .input-subtotal").each(function(){
              subtotal += parseFloat($(this).val()) || 0;
          });
          $("#subtotal_edit").val( subtotal.toFixed(2) );

          let mano = parseFloat($("#mano_obra_edit").val()) || 0;
          let total = subtotal + mano;
          $("#total_edit").val( total.toFixed(2) );
      }

      $(document).ready(function(){

          // Recalcular al cargar
          $("#tbodyDetallesEdit tr").each(function(){
              recalcularFilaEdit($(this));
          });
          recalcularTotalesEdit();

          $(document).on("keyup change", "#tbodyDetallesEdit .input-cant, #tbodyDetallesEdit .input-precio", function(){
              let $tr = $(this).closest("tr");
              recalcularFilaEdit($tr);
              recalcularTotalesEdit();
          });

          $("#mano_obra_edit").on("keyup change", function(){
              recalcularTotalesEdit();
          });

          $("#btnAgregarFilaEdit").click(function(){
              let fila = `
                <tr>
                    <td><input type="text" name="detalle_descripcion[]" class="form-control" required></td>
                    <td><input type="number" name="detalle_cantidad[]" class="form-control input-cant" min="1" value="1" required></td>
                    <td><input type="number" name="detalle_precio[]" class="form-control input-precio" min="0" step="0.01" value="0.00" required></td>
                    <td><input type="text" name="detalle_subtotal[]" class="form-control input-subtotal" value="0.00" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnEliminarFila">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
              $("#tbodyDetallesEdit").append(fila);
          });

          $(document).on("click", ".btnEliminarFila", function(){
              let filas = $("#tbodyDetallesEdit tr").length;
              if(filas <= 1){
                  alert("Debe existir al menos una fila.");
                  return;
              }
              $(this).closest("tr").remove();
              recalcularTotalesEdit();
          });

      });
      </script>

    </section>

<?php } ?>
