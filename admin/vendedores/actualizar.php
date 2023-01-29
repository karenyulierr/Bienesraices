<?php
require '../../includes/app.php';

use App\Vendedor;

estaAutenticado();

//Validar que sea un id válido

$id = $_GET[ 'id' ];
$id = filter_var( $id, FILTER_VALIDATE_INT );

if ( !$id ) {
    header( 'Location:/admin' );
}

//obtener el arreglo del vendedor desde la bd

$vendedor = Vendedor::find( $id );


// arreglo con mensajes de errores
$errores = Vendedor::getErrores();


//ejecutar el codigo despues de que el usuario envia el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    //Asignar los valores
    $args = $_POST[ 'vendedor' ];
    //sincronizar objecto en memeoria con lo que el usuario escribio
    $vendedor->sincronizar($args);
    //validaciones
    $errores =    $vendedor->validar();
    if ( empty( $errores ) ) {
        $vendedor->guardar();
    }

}

incluirTemplate( 'header' );

?>

<main class='contenedor seccion'>
    <h1>Actualizar vendedor( a )</h1>

    <a href='/admin' class='boton boton-verde'>Volver</a>

    <?php foreach ( $errores as $error ):?>
    <div class='alerta error'>
        <?php echo $error;
?>
    </div>
    <?php endforeach;
?>

    <form class='formulario' method='POST'>
        <?php
include( '../../includes/templates/formulario_vendedores.php' );
?>
        <input type='submit' value='Guardar Cambios' class='boton boton-verde'>
        <br>
        <br>
    </form>
</main>

<?php
incluirTemplate( 'footer' );
?>