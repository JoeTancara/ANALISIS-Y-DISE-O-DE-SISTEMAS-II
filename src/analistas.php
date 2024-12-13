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
        $id_usuario = $_POST['id_usuario'];
        $nombre_analista = $_POST['nombre_analista'];
        $apellido_analista = $_POST['apellido_analista'];
        $departamento = $_POST['departamento'];

        $query_insert = mysqli_query($conexion, "INSERT INTO analistas_de_credito(id_usuario, nombre_analista, apellido_analista, departamento) 
                                                 VALUES ('$id_usuario', '$nombre_analista', '$apellido_analista', '$departamento')");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">Analista registrado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al registrar el analista</div>';
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_analista"><i class="fas fa-plus"></i> Nuevo Analista</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Departamento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT a.*, u.usuario FROM analistas_de_credito a 
                                              INNER JOIN usuario u ON a.id_usuario = u.idusuario");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_analista']; ?></td>
                        <td><?php echo $data['usuario']; ?></td>
                        <td><?php echo $data['nombre_analista']; ?></td>
                        <td><?php echo $data['apellido_analista']; ?></td>
                        <td><?php echo $data['departamento']; ?></td>
                        <td>
                            <a href="editar_analista.php?id=<?php echo $data['id_analista']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_analista.php?id=<?php echo $data['id_analista']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nuevo_analista" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Analista de Crédito</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="id_usuario">Usuario</label>
                        <select name="id_usuario" id="id_usuario" class="form-control">
                            <option value="" selected disabled>Seleccione un usuario</option>
                            <?php
                            $query_usuarios = mysqli_query($conexion, "SELECT idusuario, usuario FROM usuario");
                            while ($usuario = mysqli_fetch_assoc($query_usuarios)) {
                                echo '<option value="' . $usuario['idusuario'] . '">' . $usuario['usuario'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre_analista">Nombre</label>
                        <input type="text" name="nombre_analista" id="nombre_analista" class="form-control" placeholder="Ingrese el nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_analista">Apellido</label>
                        <input type="text" name="apellido_analista" id="apellido_analista" class="form-control" placeholder="Ingrese el apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="departamento">Departamento</label>
                        <input type="text" name="departamento" id="departamento" class="form-control" placeholder="Ingrese el departamento" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Analista</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
