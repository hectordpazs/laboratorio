<?php
require_once 'config/auth.php';
?>

<?php
require_once 'plantillas/head.php';
?>

<div class="container min-vh-100 mt-4">
    <div class="card text-start col-sm-12 col-md-12 col-lg-6 m-auto">
        <div class="card-body">
            <h3 class="card-title">Bienvenido, <?php echo $usuario_actual['usuario']; ?></h3>
            <p class="card-text">Al mejor sistema del mundo</p>
        </div>
    </div>

</div>


<?php
require_once 'plantillas/foot.php';
?>