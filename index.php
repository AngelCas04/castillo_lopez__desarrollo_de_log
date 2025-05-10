<?php
    ini_set("display_errors",'1'); 
    ini_set("display_startup_errors",'1'); 
    error_reporting(E_ALL); 

    session_start();

    include("core/inc/funciones.inc.php"); 
    include("core/secure/ips.php"); 

    // Archivo de log
    $archivo_log = "./logs/log.log";

    // Verificar IP
    $ip_permitida = ip_in_ranges($_SERVER["REMOTE_ADDR"], $rango);

    // Función para escribir en el log
    function escribir_log($mensaje, $archivo = "./logs/log.log") {
        $fecha = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'];
        $linea = "[$fecha][$ip] $mensaje" . PHP_EOL;
        file_put_contents($archivo, $linea, FILE_APPEND);
    }

    // Mensajes de alerta
    $mensaje = "";
    $alerta_tipo = "";

    // Procesar formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST['txt_user'] ?? '';
        $pass = $_POST['txt_pass'] ?? '';

        // Aquí puedes cambiar a validación con base de datos
        if ($user === 'admin' && $pass === '1234') {
            $_SESSION['usuario'] = $user;
            $mensaje = "Bienvenido, $user";
            $alerta_tipo = "success";
            escribir_log("Inicio de sesión exitoso para usuario: $user", $archivo_log);
        } else {
            $mensaje = "Usuario o contraseña incorrectos";
            $alerta_tipo = "error";
            escribir_log("Fallo en el inicio de sesión para usuario: $user", $archivo_log);
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión de Angel Castillo</title>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link href="fonts/fontawesome/css/all.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="js/sweetalert.all.js"></script>
    <script type="text/javascript" src="fonts/fontawesome/js/all.js"></script>
</head>
<body>
    <?php if ($mensaje): ?>
        <script>
            Swal.fire({
                icon: '<?= $alerta_tipo ?>',
                title: '<?= $mensaje ?>',
                confirmButtonText: 'Aceptar'
            });
        </script>
    <?php endif; ?>

    <div class="alert alert-warning text-center" role="alert">
        <b>Por favor, ingrese sus datos para continuar.</b>
    </div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            
            <div class="col-md-5 text-center mb-4 mb-md-0">
                <img src="media/logo/logo_corporativo.png" class="img-fluid" alt="Logo Corporativo" />
            </div>
          
            <div class="col-md-5">
                <h1 class="text-center mb-4">Diseñando Estrategias para la Recuperación y Migración de Base de Datos (RBK0)</h1>
                <form name="frm_iniciar_sesion" id="frm_iniciar_sesion" action="" method="post">
                    <div class="form-group">
                        <label for="txt_user">Usuario:</label>
                        <input type="text" class="form-control" id="txt_user" name="txt_user" maxlength="10" required>
                        <small id="txt_userHelp" class="form-text text-muted">Digite un usuario (campo obligatorio)</small>
                    </div>
                    <div class="form-group">
                        <label for="txt_pass">Contraseña:</label>
                        <input type="password" class="form-control" id="txt_pass" name="txt_pass" maxlength="10" required>
                        <small id="txt_passHelp" class="form-text text-muted">La contraseña es obligatoria.</small>
                    </div>
                    <button type="submit" id="btn_ingresar" class="btn btn-primary btn-block">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
