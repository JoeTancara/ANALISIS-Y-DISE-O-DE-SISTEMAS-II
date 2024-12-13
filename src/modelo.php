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
        $nombre_modelo = $_POST['nombre_modelo'];
        $descripcion = $_POST['descripcion'];
        $version = $_POST['version'];

        $query_insert = mysqli_query($conexion, "INSERT INTO modelos_de_scoring(nombre_modelo, descripcion, version) 
                                                 VALUES ('$nombre_modelo', '$descripcion', '$version')");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">Modelo registrado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al registrar el modelo</div>';
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_modelo"><i class="fas fa-plus"></i> Nuevo Modelo</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Versión</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM modelos_de_scoring");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_modelo_scoring']; ?></td>
                        <td><?php echo $data['nombre_modelo']; ?></td>
                        <td><?php echo $data['descripcion']; ?></td>
                        <td><?php echo $data['version']; ?></td>
                        <td>
                            <a href="editar_modelo.php?id=<?php echo $data['id_modelo_scoring']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_modelo.php?id=<?php echo $data['id_modelo_scoring']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nuevo_modelo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Modelo de Scoring</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre_modelo">Nombre</label>
                        <input type="text" name="nombre_modelo" id="nombre_modelo" class="form-control" placeholder="Ingrese el nombre del modelo" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="4" placeholder="Ingrese una descripción" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="version">Versión</label>
                        <input type="text" name="version" id="version" class="form-control" placeholder="Ingrese la versión" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Modelo</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
