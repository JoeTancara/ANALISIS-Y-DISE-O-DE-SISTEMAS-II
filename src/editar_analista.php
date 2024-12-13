<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "analistas_de_credito";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['id_usuario']) || empty($_POST['nombre_analista']) || empty($_POST['apellido_analista']) || empty($_POST['departamento'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_analista = $_POST['id_analista'];
        $id_usuario = $_POST['id_usuario'];
        $nombre_analista = $_POST['nombre_analista'];
        $apellido_analista = $_POST['apellido_analista'];
        $departamento = $_POST['departamento'];

        $sql_update = mysqli_query($conexion, "UPDATE analistas_de_credito 
                                               SET id_usuario = '$id_usuario', 
                                                   nombre_analista = '$nombre_analista', 
                                                   apellido_analista = '$apellido_analista', 
                                                   departamento = '$departamento' 
                                               WHERE id_analista = $id_analista");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Analista actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el analista</div>';
        }
    }
}

// Obtener datos del registro
if (empty($_REQUEST['id'])) {
    header("Location: analistas.php");
}
$id_analista = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM analistas_de_credito WHERE id_analista = $id_analista");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: analistas.php");
} else {
    $data = mysqli_fetch_array($sql);
    $id_usuario = $data['id_usuario'];
    $nombre_analista = $data['nombre_analista'];
    $apellido_analista = $data['apellido_analista'];
    $departamento = $data['departamento'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Analista de Crédito
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_analista" value="<?php echo $id_analista; ?>">
                        <div class="form-group">
                            <label for="id_usuario">Usuario</label>
                            <select name="id_usuario" id="id_usuario" class="form-control">
                                <option value="" disabled>Seleccione un usuario</option>
                                <?php
                                $query_usuarios = mysqli_query($conexion, "SELECT idusuario, usuario FROM usuario");
                                while ($usuario = mysqli_fetch_assoc($query_usuarios)) {
                                    $selected = ($usuario['idusuario'] == $id_usuario) ? 'selected' : '';
                                    echo '<option value="' . $usuario['idusuario'] . '" ' . $selected . '>' . $usuario['usuario'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre_analista">Nombre</label>
                            <input type="text" name="nombre_analista" id="nombre_analista" class="form-control" value="<?php echo $nombre_analista; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido_analista">Apellido</label>
                            <input type="text" name="apellido_analista" id="apellido_analista" class="form-control" value="<?php echo $apellido_analista; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="departamento">Departamento</label>
                            <input type="text" name="departamento" id="departamento" class="form-control" value="<?php echo $departamento; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="analistas.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
