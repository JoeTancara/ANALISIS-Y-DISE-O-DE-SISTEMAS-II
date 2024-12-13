<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "evaluacion_de_creditos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['id_cliente']) || empty($_POST['id_garantia']) || empty($_POST['estado_credito']) || empty($_POST['fecha_evaluacion']) || empty($_POST['id_scoring']) || empty($_POST['id_analista'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_cliente = $_POST['id_cliente'];
        $id_garantia = $_POST['id_garantia'];
        $estado_credito = $_POST['estado_credito'];
        $fecha_evaluacion = $_POST['fecha_evaluacion'];
        $id_scoring = $_POST['id_scoring'];
        $id_analista = $_POST['id_analista'];

        $query_insert = mysqli_query($conexion, "INSERT INTO evaluacion_de_creditos(id_cliente, id_garantia, estado_credito, fecha_evaluacion, id_scoring, id_analista) 
                                                 VALUES ('$id_cliente', '$id_garantia', '$estado_credito', '$fecha_evaluacion', '$id_scoring', '$id_analista')");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">Evaluación registrada correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al registrar la evaluación</div>';
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nueva_evaluacion"><i class="fas fa-plus"></i> Nueva Evaluación</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Garantía</th>
                <th>Estado</th>
                <th>Fecha de Evaluación</th>
                <th>Scoring</th>
                <th>Analista</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT e.*, c.nombre_cliente, g.descripcion_garantia, s.puntaje_scoring, a.nombre_analista 
                                              FROM evaluacion_de_creditos e
                                              INNER JOIN clientes c ON e.id_cliente = c.id_cliente
                                              INNER JOIN garantias g ON e.id_garantia = g.id_garantia
                                              INNER JOIN scoring s ON e.id_scoring = s.id_scoring
                                              INNER JOIN analistas_de_credito a ON e.id_analista = a.id_analista");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_evaluacion']; ?></td>
                        <td><?php echo $data['nombre_cliente']; ?></td>
                        <td><?php echo $data['descripcion_garantia']; ?></td>
                        <td><?php echo $data['estado_credito']; ?></td>
                        <td><?php echo $data['fecha_evaluacion']; ?></td>
                        <td><?php echo number_format($data['puntaje_scoring'], 2); ?></td>
                        <td><?php echo $data['nombre_analista']; ?></td>
                        <td>
                            <a href="editar_evaluacion.php?id=<?php echo $data['id_evaluacion']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_evaluacion.php?id=<?php echo $data['id_evaluacion']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nueva_evaluacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nueva Evaluación de Crédito</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="id_cliente">Cliente</label>
                        <select name="id_cliente" id="id_cliente" class="form-control">
                            <option value="" selected disabled>Seleccione un cliente</option>
                            <?php
                            $query_clientes = mysqli_query($conexion, "SELECT id_cliente, nombre_cliente FROM clientes");
                            while ($cliente = mysqli_fetch_assoc($query_clientes)) {
                                echo '<option value="' . $cliente['id_cliente'] . '">' . $cliente['nombre_cliente'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_garantia">Garantía</label>
                        <select name="id_garantia" id="id_garantia" class="form-control">
                            <option value="" selected disabled>Seleccione una garantía</option>
                            <?php
                            $query_garantias = mysqli_query($conexion, "SELECT id_garantia, descripcion_garantia FROM garantias");
                            while ($garantia = mysqli_fetch_assoc($query_garantias)) {
                                echo '<option value="' . $garantia['id_garantia'] . '">' . $garantia['descripcion_garantia'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado_credito">Estado del Crédito</label>
                        <select name="estado_credito" id="estado_credito" class="form-control">
                            <option value="Aprobado">Aprobado</option>
                            <option value="Rechazado">Rechazado</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha_evaluacion">Fecha de Evaluación</label>
                        <input type="date" name="fecha_evaluacion" id="fecha_evaluacion" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_scoring">Scoring</label>
                        <select name="id_scoring" id="id_scoring" class="form-control">
                            <option value="" selected disabled>Seleccione un scoring</option>
                            <?php
                            $query_scoring = mysqli_query($conexion, "SELECT id_scoring, puntaje_scoring FROM scoring");
                            while ($scoring = mysqli_fetch_assoc($query_scoring)) {
                                echo '<option value="' . $scoring['id_scoring'] . '">' . number_format($scoring['puntaje_scoring'], 2) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_analista">Analista</label>
                        <select name="id_analista" id="id_analista" class="form-control">
                            <option value="" selected disabled>Seleccione un analista</option>
                            <?php
                            $query_analistas = mysqli_query($conexion, "SELECT id_analista, nombre_analista FROM analistas_de_credito");
                            while ($analista = mysqli_fetch_assoc($query_analistas)) {
                                echo '<option value="' . $analista['id_analista'] . '">' . $analista['nombre_analista'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Evaluación</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
