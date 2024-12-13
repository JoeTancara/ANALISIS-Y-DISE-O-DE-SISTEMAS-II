<?php include_once "includes/header.php";
require "../conexion.php";

// Consultas para obtener el total de registros en cada tabla
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$totalU = mysqli_num_rows($usuarios);

$analistas = mysqli_query($conexion, "SELECT * FROM analistas_de_credito");
$totalAnalistas = mysqli_num_rows($analistas);

$gerentes = mysqli_query($conexion, "SELECT * FROM gerentes_de_credito");
$totalGerentes = mysqli_num_rows($gerentes);

$evaluaciones = mysqli_query($conexion, "SELECT * FROM evaluacion_de_creditos");
$totalEvaluaciones = mysqli_num_rows($evaluaciones);

?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Usuarios -->
    <a class="col-xl-3 col-md-6 mb-4" href="usuarios.php">
        <div class="card border-left-primary shadow h-100 py-2 bg-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Usuarios</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalU; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Analistas -->
    <a class="col-xl-3 col-md-6 mb-4" href="analistas.php">
        <div class="card border-left-info shadow h-100 py-2 bg-info">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Analistas</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalAnalistas; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Gerentes -->
    <a class="col-xl-3 col-md-6 mb-4" href="gerentes.php">
        <div class="card border-left-success shadow h-100 py-2 bg-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Gerentes</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalGerentes; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Evaluaciones -->
    <a class="col-xl-3 col-md-6 mb-4" href="evaluaciones.php">
        <div class="card border-left-warning shadow h-100 py-2 bg-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Evaluaciones</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalEvaluaciones; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

</div>

<?php include_once "includes/footer.php"; ?>
