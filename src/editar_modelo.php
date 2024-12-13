<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "modelos_de_scoring";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre_modelo']) || empty($_POST['descripcion']) || empty($_POST['version'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_modelo_scoring = $_POST['id_modelo_scoring'];
        $nombre_modelo = $_POST['nombre_modelo'];
        $descripcion = $_POST['descripcion'];
        $version = $_POST['version'];

        $sql_update = mysqli_query($conexion, "UPDATE modelos_de_scoring 
                                               SET nombre_modelo = '$nombre_modelo', 
                                                   descripcion = '$descripcion', 
                                                   version = '$version' 
                                               WHERE id_modelo_scoring = $id_modelo_scoring");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Modelo actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el modelo</div>';
        }
    }
}

// Obtener datos del registro
if (empty($_REQUEST['id'])) {
    header("Location: modelos.php");
}
$id_modelo_scoring = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM modelos_de_scoring WHERE id_modelo_scoring = $id_modelo_scoring");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: modelos.php");
} else {
    $data = mysqli_fetch_array($sql);
    $nombre_modelo = $data['nombre_modelo'];
    $descripcion = $data['descripcion'];
    $version = $data['version'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Modelo de Scoring
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_modelo_scoring" value="<?php echo $id_modelo_scoring; ?>">
                        <div class="form-group">
                            <label for="nombre_modelo">Nombre</label>
                            <input type="text" name="nombre_modelo" id="nombre_modelo" class="form-control" value="<?php echo $nombre_modelo; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required><?php echo $descripcion; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="version">Versión</label>
                            <input type="text" name="version" id="version" class="form-control" value="<?php echo $version; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="modelo.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
