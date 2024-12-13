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
        $id_scoring = $_POST['id_scoring'];
        $id_cliente = $_POST['id_cliente'];
        $puntaje_scoring = $_POST['puntaje_scoring'];
        $nivel_riesgo = $_POST['nivel_riesgo'];
        $id_buro_credito = $_POST['id_buro_credito'];
        $id_modelo_scoring = $_POST['id_modelo_scoring'];
        $fecha_calculo = $_POST['fecha_calculo'];

        $sql_update = mysqli_query($conexion, "UPDATE scoring 
                                               SET id_cliente = '$id_cliente', 
                                                   puntaje_scoring = '$puntaje_scoring', 
                                                   nivel_riesgo = '$nivel_riesgo', 
                                                   id_buro_credito = '$id_buro_credito', 
                                                   id_modelo_scoring = '$id_modelo_scoring', 
                                                   fecha_calculo = '$fecha_calculo' 
                                               WHERE id_scoring = $id_scoring");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Scoring actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar el scoring</div>';
        }
    }
}

// Obtener datos del registro
if (empty($_REQUEST['id'])) {
    header("Location: scoring.php");
}
$id_scoring = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM scoring WHERE id_scoring = $id_scoring");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: scoring.php");
} else {
    $data = mysqli_fetch_array($sql);
    $id_cliente = $data['id_cliente'];
    $puntaje_scoring = $data['puntaje_scoring'];
    $nivel_riesgo = $data['nivel_riesgo'];
    $id_buro_credito = $data['id_buro_credito'];
    $id_modelo_scoring = $data['id_modelo_scoring'];
    $fecha_calculo = $data['fecha_calculo'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Scoring
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_scoring" value="<?php echo $id_scoring; ?>">
                        <div class="form-group">
                            <label for="id_cliente">Cliente</label>
                            <select name="id_cliente" id="id_cliente" class="form-control">
                                <option value="" disabled>Seleccione un cliente</option>
                                <?php
                                $query_clientes = mysqli_query($conexion, "SELECT id_cliente, nombre_cliente FROM clientes");
                                while ($cliente = mysqli_fetch_assoc($query_clientes)) {
                                    $selected = ($cliente['id_cliente'] == $id_cliente) ? 'selected' : '';
                                    echo '<option value="' . $cliente['id_cliente'] . '" ' . $selected . '>' . $cliente['nombre_cliente'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="puntaje_scoring">Puntaje</label>
                            <input type="number" name="puntaje_scoring" id="puntaje_scoring" class="form-control" value="<?php echo $puntaje_scoring; ?>" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="nivel_riesgo">Nivel de Riesgo</label>
                            <select name="nivel_riesgo" id="nivel_riesgo" class="form-control">
                                <option value="Bajo" <?php echo ($nivel_riesgo == "Bajo") ? 'selected' : ''; ?>>Bajo</option>
                                <option value="Moderado" <?php echo ($nivel_riesgo == "Moderado") ? 'selected' : ''; ?>>Moderado</option>
                                <option value="Alto" <?php echo ($nivel_riesgo == "Alto") ? 'selected' : ''; ?>>Alto</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_buro_credito">Buró de Crédito</label>
                            <select name="id_buro_credito" id="id_buro_credito" class="form-control">
                                <option value="" disabled>Seleccione un buró de crédito</option>
                                <?php
                                $query_buro = mysqli_query($conexion, "SELECT id_buro_credito, nombre_buro FROM buro_de_credito");
                                while ($buro = mysqli_fetch_assoc($query_buro)) {
                                    $selected = ($buro['id_buro_credito'] == $id_buro_credito) ? 'selected' : '';
                                    echo '<option value="' . $buro['id_buro_credito'] . '" ' . $selected . '>' . $buro['nombre_buro'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_modelo_scoring">Modelo de Scoring</label>
                            <select name="id_modelo_scoring" id="id_modelo_scoring" class="form-control">
                                <option value="" disabled>Seleccione un modelo de scoring</option>
                                <?php
                                $query_modelos = mysqli_query($conexion, "SELECT id_modelo_scoring, nombre_modelo FROM modelos_de_scoring");
                                while ($modelo = mysqli_fetch_assoc($query_modelos)) {
                                    $selected = ($modelo['id_modelo_scoring'] == $id_modelo_scoring) ? 'selected' : '';
                                    echo '<option value="' . $modelo['id_modelo_scoring'] . '" ' . $selected . '>' . $modelo['nombre_modelo'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fecha_calculo">Fecha de Cálculo</label>
                            <input type="date" name="fecha_calculo" id="fecha_calculo" class="form-control" value="<?php echo $fecha_calculo; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="scoring.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
