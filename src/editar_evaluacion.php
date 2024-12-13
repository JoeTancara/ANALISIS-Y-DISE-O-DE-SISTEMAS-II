<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "evaluacion_de_creditos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['id_cliente']) || empty($_POST['id_garantia']) || empty($_POST['estado_credito']) || empty($_POST['fecha_evaluacion']) || empty($_POST['id_scoring']) || empty($_POST['id_analista'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_evaluacion = $_POST['id_evaluacion'];
        $id_cliente = $_POST['id_cliente'];
        $id_garantia = $_POST['id_garantia'];
        $estado_credito = $_POST['estado_credito'];
        $fecha_evaluacion = $_POST['fecha_evaluacion'];
        $id_scoring = $_POST['id_scoring'];
        $id_analista = $_POST['id_analista'];

        $sql_update = mysqli_query($conexion, "UPDATE evaluacion_de_creditos 
                                               SET id_cliente = '$id_cliente', 
                                                   id_garantia = '$id_garantia', 
                                                   estado_credito = '$estado_credito', 
                                                   fecha_evaluacion = '$fecha_evaluacion', 
                                                   id_scoring = '$id_scoring', 
                                                   id_analista = '$id_analista' 
                                               WHERE id_evaluacion = $id_evaluacion");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Evaluación actualizada correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar la evaluación</div>';
        }
    }
}

// Obtener datos del registro
if (empty($_REQUEST['id'])) {
    header("Location: evaluaciones.php");
}
$id_evaluacion = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM evaluacion_de_creditos WHERE id_evaluacion = $id_evaluacion");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: evaluaciones.php");
} else {
    $data = mysqli_fetch_array($sql);
    $id_cliente = $data['id_cliente'];
    $id_garantia = $data['id_garantia'];
    $estado_credito = $data['estado_credito'];
    $fecha_evaluacion = $data['fecha_evaluacion'];
    $id_scoring = $data['id_scoring'];
    $id_analista = $data['id_analista'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Evaluación de Crédito
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_evaluacion" value="<?php echo $id_evaluacion; ?>">
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
                            <label for="id_garantia">Garantía</label>
                            <select name="id_garantia" id="id_garantia" class="form-control">
                                <option value="" disabled>Seleccione una garantía</option>
                                <?php
                                $query_garantias = mysqli_query($conexion, "SELECT id_garantia, descripcion_garantia FROM garantias");
                                while ($garantia = mysqli_fetch_assoc($query_garantias)) {
                                    $selected = ($garantia['id_garantia'] == $id_garantia) ? 'selected' : '';
                                    echo '<option value="' . $garantia['id_garantia'] . '" ' . $selected . '>' . $garantia['descripcion_garantia'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado_credito">Estado del Crédito</label>
                            <select name="estado_credito" id="estado_credito" class="form-control">
                                <option value="Aprobado" <?php echo ($estado_credito == "Aprobado") ? 'selected' : ''; ?>>Aprobado</option>
                                <option value="Rechazado" <?php echo ($estado_credito == "Rechazado") ? 'selected' : ''; ?>>Rechazado</option>
                                <option value="Pendiente" <?php echo ($estado_credito == "Pendiente") ? 'selected' : ''; ?>>Pendiente</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fecha_evaluacion">Fecha de Evaluación</label>
                            <input type="date" name="fecha_evaluacion" id="fecha_evaluacion" class="form-control" value="<?php echo $fecha_evaluacion; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="id_scoring">Scoring</label>
                            <select name="id_scoring" id="id_scoring" class="form-control">
                                <option value="" disabled>Seleccione un scoring</option>
                                <?php
                                $query_scoring = mysqli_query($conexion, "SELECT id_scoring, puntaje_scoring FROM scoring");
                                while ($scoring = mysqli_fetch_assoc($query_scoring)) {
                                    $selected = ($scoring['id_scoring'] == $id_scoring) ? 'selected' : '';
                                    echo '<option value="' . $scoring['id_scoring'] . '" ' . $selected . '>' . number_format($scoring['puntaje_scoring'], 2) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_analista">Analista</label>
                            <select name="id_analista" id="id_analista" class="form-control">
                                <option value="" disabled>Seleccione un analista</option>
                                <?php
                                $query_analistas = mysqli_query($conexion, "SELECT id_analista, nombre_analista FROM analistas_de_credito");
                                while ($analista = mysqli_fetch_assoc($query_analistas)) {
                                    $selected = ($analista['id_analista'] == $id_analista) ? 'selected' : '';
                                    echo '<option value="' . $analista['id_analista'] . '" ' . $selected . '>' . $analista['nombre_analista'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="evaluaciones.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
