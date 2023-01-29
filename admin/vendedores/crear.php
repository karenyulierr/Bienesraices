<?php
require '../../includes/app.php';

use App\Vendedor;

estaAutenticado();

$vendedor = new Vendedor;

// debuguear( $vendedores );
// arreglo con mensajes de errores
$errores = Vendedor::getErrores();

//ejecutar el codigo despues de que el usuario envia el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    //Crear una nueva instacia
    $vendedor = new Vendedor( $_POST[ 'vendedor' ] );

    $errores=$vendedor->validar();

    //vsalidar que ni allarn campos vacios

    if ( empty( $errores ) ) {
        $vendedor->guardar();

    }

}

incluirTemplate( 'header' );

?>

<main class = 'contenedor seccion'>
<h1>Registrar vendedor( a )</h1>

<a href = '/admin' class = 'boton boton-verde'>Volver</a>

<?php foreach ( $errores as $error ):?>
<div class = 'alerta error'>
<?php echo $error;
?>
</div>
<?php endforeach;
?>

<form class = 'formulario' method = 'POST' action = '/admin/vendedores/crear.php'>
<?php
include( '../../includes/templates/formulario_vendedores.php' );
?>
<input type = 'submit' value = 'Registrar vendedor(a)' class = 'boton boton-verde'>
<br>
<br>
</form>
</main>

<?php
incluirTemplate( 'footer' );
?>