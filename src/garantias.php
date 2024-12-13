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
        $id_cliente = $_POST['id_cliente'];
        $descripcion_garantia = $_POST['descripcion_garantia'];
        $valor_garantia = $_POST['valor_garantia'];
        $estado_garantia = $_POST['estado_garantia'];

        $query_insert = mysqli_query($conexion, "INSERT INTO garantias(id_cliente, descripcion_garantia, valor_garantia, estado_garantia) 
                                                 VALUES ('$id_cliente', '$descripcion_garantia', '$valor_garantia', '$estado_garantia')");
        if ($query_insert) {
            $alert = '<div class="alert alert-success" role="alert">Garantía registrada correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al registrar la garantía</div>';
        }
    }
    mysqli_close($conexion);
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nueva_garantia"><i class="fas fa-plus"></i> Nueva Garantía</button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Descripción</th>
                <th>Valor</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT g.*, c.nombre_cliente FROM garantias g 
                                              INNER JOIN clientes c ON g.id_cliente = c.id_cliente");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['id_garantia']; ?></td>
                        <td><?php echo $data['nombre_cliente']; ?></td>
                        <td><?php echo $data['descripcion_garantia']; ?></td>
                        <td><?php echo number_format($data['valor_garantia'], 2); ?></td>
                        <td><?php echo $data['estado_garantia']; ?></td>
                        <td>
                            <a href="editar_garantia.php?id=<?php echo $data['id_garantia']; ?>" class="btn btn-success"><i class="fas fa-edit"></i></a>
                            <form action="eliminar_garantia.php?id=<?php echo $data['id_garantia']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>
<div id="nueva_garantia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nueva Garantía</h5>
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
                        <label for="descripcion_garantia">Descripción</label>
                        <input type="text" name="descripcion_garantia" id="descripcion_garantia" class="form-control" placeholder="Descripción de la garantía" required>
                    </div>
                    <div class="form-group">
                        <label for="valor_garantia">Valor</label>
                        <input type="number" name="valor_garantia" id="valor_garantia" class="form-control" placeholder="Valor de la garantía" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="estado_garantia">Estado</label>
                        <select name="estado_garantia" id="estado_garantia" class="form-control">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Activa">Activa</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Guardar Garantía</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
