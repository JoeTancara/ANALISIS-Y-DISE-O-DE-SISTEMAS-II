<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "tasas_de_interes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['tipo_credito']) || empty($_POST['tasa']) || empty($_POST['fecha_aplicacion']) || empty($_POST['id_modelo_scoring'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $tipo_credito = $_POST['tipo_credito'];
        $tasa = $_POST['tasa'];
        $fecha_aplicacion = $_POST['fecha_aplicacion'];
        $id_modelo_scoring = $_POST['id_modelo_scoring'];

        $query_insert = mysqli_query($conexion, "INSERT INTO tasas_de_interes(tipo_credito, tasa, fecha_aplicacion, id_modelo_scoring) 
                                                 VALUES ('$tipo_credito', '$tasa', '$fecha_aplicacion', '$id_modelo_scoring')");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">Tasa registrada correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al registrar la tasa</div>';
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nueva_tasa"><i class="fas fa-plus"></i> Nueva Tasa</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Tipo de Crédito</th>
                <th>Tasa (%)</th>
                <th>Fecha de Aplicación</th>
                <th>Modelo de Scoring</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT t.*, m.nombre_modelo FROM tasas_de_interes t 
                                              INNER JOIN modelos_de_scoring m ON t.id_modelo_scoring = m.id_modelo_scoring");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_tasa_interes']; ?></td>
                        <td><?php echo $data['tipo_credito']; ?></td>
                        <td><?php echo number_format($data['tasa'], 2); ?>%</td>
                        <td><?php echo $data['fecha_aplicacion']; ?></td>
                        <td><?php echo $data['nombre_modelo']; ?></td>
                        <td>
                            <a href="editar_tasa.php?id=<?php echo $data['id_tasa_interes']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_tasa.php?id=<?php echo $data['id_tasa_interes']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nueva_tasa" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nueva Tasa de Interés</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="tipo_credito">Tipo de Crédito</label>
                        <input type="text" name="tipo_credito" id="tipo_credito" class="form-control" placeholder="Ingrese el tipo de crédito" required>
                    </div>
                    <div class="form-group">
                        <label for="tasa">Tasa (%)</label>
                        <input type="number" name="tasa" id="tasa" class="form-control" placeholder="Ingrese la tasa" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_aplicacion">Fecha de Aplicación</label>
                        <input type="date" name="fecha_aplicacion" id="fecha_aplicacion" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_modelo_scoring">Modelo de Scoring</label>
                        <select name="id_modelo_scoring" id="id_modelo_scoring" class="form-control">
                            <option value="" disabled selected>Seleccione un modelo de scoring</option>
                            <?php
                            $query_modelos = mysqli_query($conexion, "SELECT id_modelo_scoring, nombre_modelo FROM modelos_de_scoring");
                            while ($modelo = mysqli_fetch_assoc($query_modelos)) {
                                echo '<option value="' . $modelo['id_modelo_scoring'] . '">' . $modelo['nombre_modelo'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Tasa</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
