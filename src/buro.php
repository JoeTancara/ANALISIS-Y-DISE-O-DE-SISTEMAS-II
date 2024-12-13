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
        $nombre_buro = $_POST['nombre_buro'];
        $historial_crediticio = $_POST['historial_crediticio'];
        $calificacion_buro = $_POST['calificacion_buro'];

        $query_insert = mysqli_query($conexion, "INSERT INTO buro_de_credito(nombre_buro, historial_crediticio, calificacion_buro) 
                                                 VALUES ('$nombre_buro', '$historial_crediticio', '$calificacion_buro')");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">Registro añadido correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al registrar en el buró de crédito</div>';
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_buro"><i class="fas fa-plus"></i> Nuevo Registro</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Historial Crediticio</th>
                <th>Calificación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM buro_de_credito");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_buro_credito']; ?></td>
                        <td><?php echo $data['nombre_buro']; ?></td>
                        <td><?php echo $data['historial_crediticio']; ?></td>
                        <td><?php echo $data['calificacion_buro']; ?></td>
                        <td>
                            <a href="editar_buro.php?id=<?php echo $data['id_buro_credito']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_buro.php?id=<?php echo $data['id_buro_credito']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nuevo_buro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Registro</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre_buro">Nombre</label>
                        <input type="text" name="nombre_buro" id="nombre_buro" class="form-control" placeholder="Ingrese el nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="historial_crediticio">Historial Crediticio</label>
                        <textarea name="historial_crediticio" id="historial_crediticio" class="form-control" rows="4" placeholder="Ingrese el historial crediticio" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="calificacion_buro">Calificación</label>
                        <select name="calificacion_buro" id="calificacion_buro" class="form-control">
                            <option value="" disabled selected>Seleccione una calificación</option>
                            <option value="Excelente">Excelente</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Malo">Malo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Registro</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
