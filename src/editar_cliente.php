<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");

$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre_cliente']) || empty($_POST['email_cliente']) || empty($_POST['direccion']) || empty($_POST['telefono'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_cliente = $_POST['id_cliente'];
        $nombre_cliente = $_POST['nombre_cliente'];
        $email_cliente = $_POST['email_cliente'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        // Verifica que el email no esté duplicado
        $query = mysqli_query($conexion, "SELECT * FROM clientes WHERE email_cliente = '$email_cliente' AND id_cliente != $id_cliente");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">El email ya está registrado en otro cliente</div>';
        } else {
            $sql_update = mysqli_query($conexion, "UPDATE clientes SET nombre_cliente = '$nombre_cliente', email_cliente = '$email_cliente', direccion = '$direccion', telefono = '$telefono' WHERE id_cliente = $id_cliente");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Cliente actualizado correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el cliente</div>';
            }
        }
    }
}

// Obtener datos del cliente
if (empty($_REQUEST['id'])) {
    header("Location: clientes.php");
}
$id_cliente = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM clientes WHERE id_cliente = $id_cliente");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: clientes.php");
} else {
    $data = mysqli_fetch_array($sql);
    $nombre_cliente = $data['nombre_cliente'];
    $email_cliente = $data['email_cliente'];
    $direccion = $data['direccion'];
    $telefono = $data['telefono'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Cliente
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
                        <div class="form-group">
                            <label for="nombre_cliente">Nombre</label>
                            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" value="<?php echo $nombre_cliente; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email_cliente">Email</label>
                            <input type="email" name="email_cliente" id="email_cliente" class="form-control" value="<?php echo $email_cliente; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo $direccion; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo $telefono; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="clientes.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
