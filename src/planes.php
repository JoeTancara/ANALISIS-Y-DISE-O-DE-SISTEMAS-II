<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "planes_de_pago";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['id_cliente']) || empty($_POST['monto_total']) || empty($_POST['plazo']) || empty($_POST['tasa_interes']) || empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin']) || empty($_POST['id_tasa_interes'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_cliente = $_POST['id_cliente'];
        $monto_total = $_POST['monto_total'];
        $plazo = $_POST['plazo'];
        $tasa_interes = $_POST['tasa_interes'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $id_tasa_interes = $_POST['id_tasa_interes'];

        $query_insert = mysqli_query($conexion, "INSERT INTO planes_de_pago(id_cliente, monto_total, plazo, tasa_interes, fecha_inicio, fecha_fin, id_tasa_interes) 
                                                 VALUES ('$id_cliente', '$monto_total', '$plazo', '$tasa_interes', '$fecha_inicio', '$fecha_fin', '$id_tasa_interes')");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">Plan de pago registrado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al registrar el plan de pago</div>';
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_plan"><i class="fas fa-plus"></i> Nuevo Plan de Pago</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Monto Total</th>
                <th>Plazo</th>
                <th>Tasa de Interés</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT p.*, c.nombre_cliente FROM planes_de_pago p 
                                              INNER JOIN clientes c ON p.id_cliente = c.id_cliente");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_plan_pago']; ?></td>
                        <td><?php echo $data['nombre_cliente']; ?></td>
                        <td><?php echo number_format($data['monto_total'], 2); ?></td>
                        <td><?php echo $data['plazo']; ?> meses</td>
                        <td><?php echo number_format($data['tasa_interes'], 2); ?>%</td>
                        <td><?php echo $data['fecha_inicio']; ?></td>
                        <td><?php echo $data['fecha_fin']; ?></td>
                        <td>
                            <a href="editar_plan.php?id=<?php echo $data['id_plan_pago']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_plan.php?id=<?php echo $data['id_plan_pago']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nuevo_plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Plan de Pago</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="id_cliente">Cliente</label>
                        <select name="id_cliente" id="id_cliente" class="form-control">
                            <option value="" selected disabled>Seleccione un cliente</option>
                            <?php
                            $query_clientes = mysqli_query($conexion, "SELECT id_cliente, nombre_cliente FROM clientes");
                            while ($cliente = mysqli_fetch_assoc($query_clientes)) {
                                echo '<option value="' . $cliente['id_cliente'] . '">' . $cliente['nombre_cliente'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="monto_total">Monto Total</label>
                        <input type="number" name="monto_total" id="monto_total" class="form-control" placeholder="Ingrese el monto total" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="plazo">Plazo (meses)</label>
                        <input type="number" name="plazo" id="plazo" class="form-control" placeholder="Ingrese el plazo en meses" required>
                    </div>
                    <div class="form-group">
                        <label for="tasa_interes">Tasa de Interés (%)</label>
                        <input type="number" name="tasa_interes" id="tasa_interes" class="form-control" placeholder="Ingrese la tasa de interés" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_tasa_interes">Tasa de Interés Asociada</label>
                        <select name="id_tasa_interes" id="id_tasa_interes" class="form-control">
                            <option value="" selected disabled>Seleccione una tasa de interés</option>
                            <?php
                            $query_tasas = mysqli_query($conexion, "SELECT id_tasa_interes, tasa FROM tasas_de_interes");
                            while ($tasa = mysqli_fetch_assoc($query_tasas)) {
                                echo '<option value="' . $tasa['id_tasa_interes'] . '">' . number_format($tasa['tasa'], 2) . '%</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Plan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
