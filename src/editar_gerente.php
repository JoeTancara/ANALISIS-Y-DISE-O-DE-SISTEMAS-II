<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "gerentes_de_credito";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['id_usuario']) || empty($_POST['nombre_gerente']) || empty($_POST['apellido_gerente']) || empty($_POST['departamento'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_gerente = $_POST['id_gerente'];
        $id_usuario = $_POST['id_usuario'];
        $nombre_gerente = $_POST['nombre_gerente'];
        $apellido_gerente = $_POST['apellido_gerente'];
        $departamento = $_POST['departamento'];

        $sql_update = mysqli_query($conexion, "UPDATE gerentes_de_credito 
                                               SET id_usuario = '$id_usuario', 
                                                   nombre_gerente = '$nombre_gerente', 
                                                   apellido_gerente = '$apellido_gerente', 
                                                   departamento = '$departamento' 
                                               WHERE id_gerente = $id_gerente");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Gerente actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el gerente</div>';
        }
    }
}

// Obtener datos del registro
if (empty($_REQUEST['id'])) {
    header("Location: gerentes.php");
}
$id_gerente = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM gerentes_de_credito WHERE id_gerente = $id_gerente");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: gerentes.php");
} else {
    $data = mysqli_fetch_array($sql);
    $id_usuario = $data['id_usuario'];
    $nombre_gerente = $data['nombre_gerente'];
    $apellido_gerente = $data['apellido_gerente'];
    $departamento = $data['departamento'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Gerente de Crédito
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_gerente" value="<?php echo $id_gerente; ?>">
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
                            <label for="nombre_gerente">Nombre</label>
                            <input type="text" name="nombre_gerente" id="nombre_gerente" class="form-control" value="<?php echo $nombre_gerente; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido_gerente">Apellido</label>
                            <input type="text" name="apellido_gerente" id="apellido_gerente" class="form-control" value="<?php echo $apellido_gerente; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="departamento">Departamento</label>
                            <input type="text" name="departamento" id="departamento" class="form-control" value="<?php echo $departamento; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="gerentes.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
