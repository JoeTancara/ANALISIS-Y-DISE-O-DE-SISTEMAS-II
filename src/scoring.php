<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "scoring";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['id_cliente']) || empty($_POST['puntaje_scoring']) || empty($_POST['nivel_riesgo']) || empty($_POST['id_buro_credito']) || empty($_POST['id_modelo_scoring']) || empty($_POST['fecha_calculo'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_cliente = $_POST['id_cliente'];
        $puntaje_scoring = $_POST['puntaje_scoring'];
        $nivel_riesgo = $_POST['nivel_riesgo'];
        $id_buro_credito = $_POST['id_buro_credito'];
        $id_modelo_scoring = $_POST['id_modelo_scoring'];
        $fecha_calculo = $_POST['fecha_calculo'];

        $query_insert = mysqli_query($conexion, "INSERT INTO scoring(id_cliente, puntaje_scoring, nivel_riesgo, id_buro_credito, id_modelo_scoring, fecha_calculo) 
                                                 VALUES ('$id_cliente', '$puntaje_scoring', '$nivel_riesgo', '$id_buro_credito', '$id_modelo_scoring', '$fecha_calculo')");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">Scoring registrado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al registrar el scoring</div>';
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_scoring"><i class="fas fa-plus"></i> Nuevo Scoring</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Puntaje</th>
                <th>Nivel de Riesgo</th>
                <th>Buró de Crédito</th>
                <th>Modelo de Scoring</th>
                <th>Fecha de Cálculo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT s.*, c.nombre_cliente, b.nombre_buro, m.nombre_modelo 
                                              FROM scoring s 
                                              INNER JOIN clientes c ON s.id_cliente = c.id_cliente 
                                              INNER JOIN buro_de_credito b ON s.id_buro_credito = b.id_buro_credito 
                                              INNER JOIN modelos_de_scoring m ON s.id_modelo_scoring = m.id_modelo_scoring");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_scoring']; ?></td>
                        <td><?php echo $data['nombre_cliente']; ?></td>
                        <td><?php echo number_format($data['puntaje_scoring'], 2); ?></td>
                        <td><?php echo $data['nivel_riesgo']; ?></td>
                        <td><?php echo $data['nombre_buro']; ?></td>
                        <td><?php echo $data['nombre_modelo']; ?></td>
                        <td><?php echo $data['fecha_calculo']; ?></td>
                        <td>
                            <a href="editar_scoring.php?id=<?php echo $data['id_scoring']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_scoring.php?id=<?php echo $data['id_scoring']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nuevo_scoring" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Scoring</h5>
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
                        <label for="puntaje_scoring">Puntaje</label>
                        <input type="number" name="puntaje_scoring" id="puntaje_scoring" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="nivel_riesgo">Nivel de Riesgo</label>
                        <select name="nivel_riesgo" id="nivel_riesgo" class="form-control">
                            <option value="Bajo">Bajo</option>
                            <option value="Moderado">Moderado</option>
                            <option value="Alto">Alto</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_buro_credito">Buró de Crédito</label>
                        <select name="id_buro_credito" id="id_buro_credito" class="form-control">
                            <option value="" selected disabled>Seleccione un buró de crédito</option>
                            <?php
                            $query_buro = mysqli_query($conexion, "SELECT id_buro_credito, nombre_buro FROM buro_de_credito");
                            while ($buro = mysqli_fetch_assoc($query_buro)) {
                                echo '<option value="' . $buro['id_buro_credito'] . '">' . $buro['nombre_buro'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_modelo_scoring">Modelo de Scoring</label>
                        <select name="id_modelo_scoring" id="id_modelo_scoring" class="form-control">
                            <option value="" selected disabled>Seleccione un modelo de scoring</option>
                            <?php
                            $query_modelos = mysqli_query($conexion, "SELECT id_modelo_scoring, nombre_modelo FROM modelos_de_scoring");
                            while ($modelo = mysqli_fetch_assoc($query_modelos)) {
                                echo '<option value="' . $modelo['id_modelo_scoring'] . '">' . $modelo['nombre_modelo'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha_calculo">Fecha de Cálculo</label>
                        <input type="date" name="fecha_calculo" id="fecha_calculo" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Scoring</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
