<?php
ini_set('display_error',1);
ini_set('display_startup_error',1);
include('inc/funciones.inc.php');
include('secure/ips.php');

$metodo_permitido = "POST";
$archivo = "../logs/log.log";
$dominio_autorizado = "localhost";
$ip = ip_in_ranges($_SERVER["REMOTE_ADDR"],$rango);
$txt_usuario_autorizado = "admin";
$txt_password_autorizado = "admin";

//SE VERIFICA QUE LA DIRECCION DE ORIGEN SEA AUTORIZADA
if(array_key_exists("HTTP_REFERER", $_SERVER)){
    //VIENE DE UNA PAGINA DENTRO DEL SISTEMA


    //limpieza de valores que vienen desde el formulario

    $valor_campo_usuario = ( (array_key_exists("txt_user", $_POST)) ? htmlspecialchars(stripslashes(trim($_POST["txt_user"])), ENT_QUOTES) : "" );
    $valor_campo_passsword = ( (array_key_exists("txt_pass", $_POST)) ? htmlspecialchars(stripslashes(trim($_POST["txt_pass"])), ENT_QUOTES) : "" );


    //verificar valores de campos diferentes de vacio

    if(($valor_campo_usuario!="" || strlen($valor_campo_usuario) > 0) and ($valor_campo_passsword!=0 || strlen($valor_campo_passsword)>0)){

        //las variables tienen valores

        $usuario = preg_match('/^[a-zA-Z0-9]{1,10}+$/', $valor_campo_usuario); //Se verifica con un patron si el valor del campo "usuario" cumple con las condiciones aceptables(Se aceptan numeros y letras mayus , minus y max de 10 caracteres y minimo de 1)
        $password = preg_match('/^[a-zA-Z0-9]{1,10}+$/', $valor_campo_passsword); //Se verifica con un patron si el valor del campo "usuario" cumple con las condiciones aceptables(Se aceptan numeros y letras mayus , minus y max de 10 caracteres y minimo de 1)


        //se verifica que los resultados del patron sean exclusivamente positivos
        if($usuario !== false and $usuario !== 0 and $password !== false and $password !== 0){
            
            if($valor_campo_usuario === $txt_usuario_autorizado and $valor_campo_passsword === $txt_password_autorizado){
                echo("HOLA MUNDO");
                crear_editar_log($archivo, "EL CLIENTE INICIO SESION SATISFACTORIAMENTE",1, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"]);
                //el usuario ingreso credenciales correctas
            } else{
                crear_editar_log($archivo, "CREEDENCIALES INCORRECTAS ENVIADAS HACIA // $_SERVER[HTTP_HOST] $_SERVER[HTTP_REQUEST_URI]",2, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"]);
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ../?status=7");
                //el usuario no ingreso credenciales correctas
            }
            
        }
    
        else{
            //los valores ingresados en los campos poseen caracteres no soportados
        }
        crear_editar_log($archivo, "Envio de datos del formulario no soportados",1, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"]);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../?status=6");

    }else{

        crear_editar_log($archivo, "Envio de campos vacios al servidor",2, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"]);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../?status=5");
        //las variables estan vacias
    

    }

    if(strpos($_SERVER["HTTP_REFERER"], $dominio_autorizado)){
        
        if($ip == true){
            //LA DIRECCION IP ESTA AUTORIZADA

            //Se verifica que el usuario haya enviado una peticion autorizada
            if($_SERVER["REQUEST_METHOD"] == $metodo_permitido){
                //el metodo enviado por el usuario esta autorizado
            } else{
                crear_editar_log($archivo, "Envio de metodo no autorizado",2, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"]);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../?status=4");
            }

        } else{
            crear_editar_log($archivo, "Direccion ip no autorizada",2, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"]);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ../?status=3");
        }

        //EL REFERER DE DONDE VIENE LA PETICION ESTA AUTORIZADO
    }
    else{
        //EL REFERER DE DONDE VIENE LA PETICION ES UN ORIGEN DESCONOCIDO

        crear_editar_log($archivo, "HA INTENTADO SUPLANTAR UN REFERER QUE NO ESTA AUTORIZADO",2, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"]);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../?status=2");
    }
}else{
//EL USUARIO DIGITO LA URL DESDE EL NAVEGADOR SIN PASAR POR EL FORMULARIO

crear_editar_log($archivo, "El usuario digito la url sin pasar por el formulario",2, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"]);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../?status=1");
}
?>
