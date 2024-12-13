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
        $nombre_cliente = $_POST['nombre_cliente'];
        $email_cliente = $_POST['email_cliente'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $id_usuario = $id_user;

        $query = mysqli_query($conexion, "SELECT * FROM clientes WHERE email_cliente = '$email_cliente'");
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<div class="alert alert-danger" role="alert">El email ya está registrado</div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO clientes(nombre_cliente, email_cliente, direccion, telefono, id_usuario) 
                                                     VALUES ('$nombre_cliente', '$email_cliente', '$direccion', '$telefono', '$id_usuario')");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">Cliente registrado correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al registrar el cliente</div>';
            }
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_cliente"><i class="fas fa-plus"></i> Nuevo Cliente</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT c.*, u.nombre AS usuario FROM clientes c 
                                              INNER JOIN usuario u ON c.id_usuario = u.idusuario");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_cliente']; ?></td>
                        <td><?php echo $data['nombre_cliente']; ?></td>
                        <td><?php echo $data['email_cliente']; ?></td>
                        <td><?php echo $data['direccion']; ?></td>
                        <td><?php echo $data['telefono']; ?></td>
                        <td>
                            <a href="editar_cliente.php?id=<?php echo $data['id_cliente']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_cliente.php?id=<?php echo $data['id_cliente']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nuevo_cliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Cliente</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre_cliente">Nombre</label>
                        <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" placeholder="Ingrese el nombre del cliente" required>
                    </div>
                    <div class="form-group">
                        <label for="email_cliente">Email</label>
                        <input type="email" name="email_cliente" id="email_cliente" class="form-control" placeholder="Ingrese el email" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Ingrese la dirección" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Ingrese el teléfono" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Cliente</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
