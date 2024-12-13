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
        $id_plan_pago = $_POST['id_plan_pago'];
        $id_cliente = $_POST['id_cliente'];
        $monto_total = $_POST['monto_total'];
        $plazo = $_POST['plazo'];
        $tasa_interes = $_POST['tasa_interes'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $id_tasa_interes = $_POST['id_tasa_interes'];

        $sql_update = mysqli_query($conexion, "UPDATE planes_de_pago 
                                               SET id_cliente = '$id_cliente', 
                                                   monto_total = '$monto_total', 
                                                   plazo = '$plazo', 
                                                   tasa_interes = '$tasa_interes', 
                                                   fecha_inicio = '$fecha_inicio', 
                                                   fecha_fin = '$fecha_fin', 
                                                   id_tasa_interes = '$id_tasa_interes' 
                                               WHERE id_plan_pago = $id_plan_pago");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Plan de pago actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el plan de pago</div>';
        }
    }
}

// Obtener datos del registro
if (empty($_REQUEST['id'])) {
    header("Location: planes.php");
}
$id_plan_pago = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM planes_de_pago WHERE id_plan_pago = $id_plan_pago");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: planes.php");
} else {
    $data = mysqli_fetch_array($sql);
    $id_cliente = $data['id_cliente'];
    $monto_total = $data['monto_total'];
    $plazo = $data['plazo'];
    $tasa_interes = $data['tasa_interes'];
    $fecha_inicio = $data['fecha_inicio'];
    $fecha_fin = $data['fecha_fin'];
    $id_tasa_interes = $data['id_tasa_interes'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Plan de Pago
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_plan_pago" value="<?php echo $id_plan_pago; ?>">
                        <div class="form-group">
                            <label for="id_cliente">Cliente</label>
                            <select name="id_cliente" id="id_cliente" class="form-control">
                                <option value="" disabled>Seleccione un cliente</option>
                                <?php
                                $query_clientes = mysqli_query($conexion, "SELECT id_cliente, nombre_cliente FROM clientes");
                                while ($cliente = mysqli_fetch_assoc($query_clientes)) {
                                    $selected = ($cliente['id_cliente'] == $id_cliente) ? 'selected' : '';
                                    echo '<option value="' . $cliente['id_cliente'] . '" ' . $selected . '>' . $cliente['nombre_cliente'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="monto_total">Monto Total</label>
                            <input type="number" name="monto_total" id="monto_total" class="form-control" value="<?php echo $monto_total; ?>" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="plazo">Plazo (meses)</label>
                            <input type="number" name="plazo" id="plazo" class="form-control" value="<?php echo $plazo; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tasa_interes">Tasa de Interés (%)</label>
                            <input type="number" name="tasa_interes" id="tasa_interes" class="form-control" value="<?php echo $tasa_interes; ?>" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo $fecha_fin; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="id_tasa_interes">Tasa de Interés Asociada</label>
                            <select name="id_tasa_interes" id="id_tasa_interes" class="form-control">
                                <option value="" disabled>Seleccione una tasa de interés</option>
                                <?php
                                $query_tasas = mysqli_query($conexion, "SELECT id_tasa_interes, tasa FROM tasas_de_interes");
                                while ($tasa = mysqli_fetch_assoc($query_tasas)) {
                                    $selected = ($tasa['id_tasa_interes'] == $id_tasa_interes) ? 'selected' : '';
                                    echo '<option value="' . $tasa['id_tasa_interes'] . '" ' . $selected . '>' . number_format($tasa['tasa'], 2) . '%</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="planes.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
