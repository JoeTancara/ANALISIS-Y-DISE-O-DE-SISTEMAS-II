<?php include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "garantias";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['id_cliente']) || empty($_POST['descripcion_garantia']) || empty($_POST['valor_garantia']) || empty($_POST['estado_garantia'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $id_garantia = $_POST['id_garantia'];
        $id_cliente = $_POST['id_cliente'];
        $descripcion_garantia = $_POST['descripcion_garantia'];
        $valor_garantia = $_POST['valor_garantia'];
        $estado_garantia = $_POST['estado_garantia'];

        $sql_update = mysqli_query($conexion, "UPDATE garantias 
                                               SET id_cliente = '$id_cliente', 
                                                   descripcion_garantia = '$descripcion_garantia', 
                                                   valor_garantia = '$valor_garantia', 
                                                   estado_garantia = '$estado_garantia' 
                                               WHERE id_garantia = $id_garantia");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Garantía actualizada correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al actualizar la garantía</div>';
        }
    }
}

// Obtener datos de la garantía
if (empty($_REQUEST['id'])) {
    header("Location: garantias.php");
}
$id_garantia = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM garantias WHERE id_garantia = $id_garantia");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header("Location: garantias.php");
} else {
    $data = mysqli_fetch_array($sql);
    $id_cliente = $data['id_cliente'];
    $descripcion_garantia = $data['descripcion_garantia'];
    $valor_garantia = $data['valor_garantia'];
    $estado_garantia = $data['estado_garantia'];
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Garantía
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id_garantia" value="<?php echo $id_garantia; ?>">
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
                            <label for="descripcion_garantia">Descripción</label>
                            <input type="text" name="descripcion_garantia" id="descripcion_garantia" class="form-control" value="<?php echo $descripcion_garantia; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="valor_garantia">Valor</label>
                            <input type="number" name="valor_garantia" id="valor_garantia" class="form-control" value="<?php echo $valor_garantia; ?>" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="estado_garantia">Estado</label>
                            <select name="estado_garantia" id="estado_garantia" class="form-control">
                                <option value="Pendiente" <?php echo ($estado_garantia == "Pendiente") ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="Activa" <?php echo ($estado_garantia == "Activa") ? 'selected' : ''; ?>>Activa</option>
                                <option value="Cancelada" <?php echo ($estado_garantia == "Cancelada") ? 'selected' : ''; ?>>Cancelada</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        <a href="garantias.php" class="btn btn-danger">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>
