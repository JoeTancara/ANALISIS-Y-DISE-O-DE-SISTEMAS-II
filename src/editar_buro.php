<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "buro_de_credito";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre_buro']) || empty($_POST['historial_crediticio']) || empty($_POST['calificacion_buro'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_buro_credito = $_POST['id_buro_credito'];
        $nombre_buro = $_POST['nombre_buro'];
        $historial_crediticio = $_POST['historial_crediticio'];
        $calificacion_buro = $_POST['calificacion_buro'];

        $sql_update = mysqli_query($conexion, "UPDATE buro_de_credito 
                                               SET nombre_buro = '$nombre_buro', 
                                                   historial_crediticio = '$historial_crediticio', 
                                                   calificacion_buro = '$calificacion_buro' 
                                               WHERE id_buro_credito = $id_buro_credito");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Registro actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el registro</div>';
        }
    }
}

// Obtener datos del registro
if (empty($_REQUEST['id'])) {
    header("Location: buro.php");
}
$id_buro_credito = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM buro_de_credito WHERE id_buro_credito = $id_buro_credito");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: buro.php");
} else {
    $data = mysqli_fetch_array($sql);
    $nombre_buro = $data['nombre_buro'];
    $historial_crediticio = $data['historial_crediticio'];
    $calificacion_buro = $data['calificacion_buro'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Registro del Buró de Crédito
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_buro_credito" value="<?php echo $id_buro_credito; ?>">
                        <div class="form-group">
                            <label for="nombre_buro">Nombre</label>
                            <input type="text" name="nombre_buro" id="nombre_buro" class="form-control" value="<?php echo $nombre_buro; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="historial_crediticio">Historial Crediticio</label>
                            <textarea name="historial_crediticio" id="historial_crediticio" class="form-control" rows="4" required><?php echo $historial_crediticio; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="calificacion_buro">Calificación</label>
                            <select name="calificacion_buro" id="calificacion_buro" class="form-control">
                                <option value="Excelente" <?php echo ($calificacion_buro == "Excelente") ? 'selected' : ''; ?>>Excelente</option>
                                <option value="Bueno" <?php echo ($calificacion_buro == "Bueno") ? 'selected' : ''; ?>>Bueno</option>
                                <option value="Regular" <?php echo ($calificacion_buro == "Regular") ? 'selected' : ''; ?>>Regular</option>
                                <option value="Malo" <?php echo ($calificacion_buro == "Malo") ? 'selected' : ''; ?>>Malo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="buro.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
