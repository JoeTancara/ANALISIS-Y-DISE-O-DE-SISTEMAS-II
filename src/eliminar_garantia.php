<?php
session_start();
require "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "garantias";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_GET['id'])) {
    $id_garantia = $_GET['id'];

    // Realiza la eliminación
    $query_delete = mysqli_query($conexion, "DELETE FROM garantias WHERE id_garantia = $id_garantia");

    if ($query_delete) {
        header("Location: garantias.php");
    } else {
        echo '<div class="alert alert-danger" role="alert">Error al eliminar la garantía</div>';
    }
    mysqli_close($conexion);
} else {
    header("Location: garantias.php");
}
