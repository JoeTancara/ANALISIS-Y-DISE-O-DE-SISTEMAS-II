<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "tasas_de_interes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['tipo_credito']) || empty($_POST['tasa']) || empty($_POST['fecha_aplicacion']) || empty($_POST['id_modelo_scoring'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_tasa_interes = $_POST['id_tasa_interes'];
        $tipo_credito = $_POST['tipo_credito'];
        $tasa = $_POST['tasa'];
        $fecha_aplicacion = $_POST['fecha_aplicacion'];
        $id_modelo_scoring = $_POST['id_modelo_scoring'];

        $sql_update = mysqli_query($conexion, "UPDATE tasas_de_interes 
                                               SET tipo_credito = '$tipo_credito', 
                                                   tasa = '$tasa', 
                                                   fecha_aplicacion = '$fecha_aplicacion', 
                                                   id_modelo_scoring = '$id_modelo_scoring' 
                                               WHERE id_tasa_interes = $id_tasa_interes");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Tasa actualizada correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar la tasa</div>';
        }
    }
}

// Obtener datos del registro
if (empty($_REQUEST['id'])) {
    header("Location: tasas.php");
}
$id_tasa_interes = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM tasas_de_interes WHERE id_tasa_interes = $id_tasa_interes");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: tasas.php");
} else {
    $data = mysqli_fetch_array($sql);
    $tipo_credito = $data['tipo_credito'];
    $tasa = $data['tasa'];
    $fecha_aplicacion = $data['fecha_aplicacion'];
    $id_modelo_scoring = $data['id_modelo_scoring'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Tasa de Interés
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_tasa_interes" value="<?php echo $id_tasa_interes; ?>">
                        <div class="form-group">
                            <label for="tipo_credito">Tipo de Crédito</label>
                            <input type="text" name="tipo_credito" id="tipo_credito" class="form-control" value="<?php echo $tipo_credito; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tasa">Tasa (%)</label>
                            <input type="number" name="tasa" id="tasa" class="form-control" value="<?php echo $tasa; ?>" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_aplicacion">Fecha de Aplicación</label>
                            <input type="date" name="fecha_aplicacion" id="fecha_aplicacion" class="form-control" value="<?php echo $fecha_aplicacion; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="id_modelo_scoring">Modelo de Scoring</label>
                            <select name="id_modelo_scoring" id="id_modelo_scoring" class="form-control">
                                <option value="" disabled>Seleccione un modelo de scoring</option>
                                <?php
                                $query_modelos = mysqli_query($conexion, "SELECT id_modelo_scoring, nombre_modelo FROM modelos_de_scoring");
                                while ($modelo = mysqli_fetch_assoc($query_modelos)) {
                                    $selected = ($modelo['id_modelo_scoring'] == $id_modelo_scoring) ? 'selected' : '';
                                    echo '<option value="' . $modelo['id_modelo_scoring'] . '" ' . $selected . '>' . $modelo['nombre_modelo'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="tasas_de_interes.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
