<?php

use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;
require '../../includes/app.php';

estaAutenticado();

//validar por id valido
$id = $_GET[ 'id' ];
$id = filter_var( $id, FILTER_VALIDATE_INT );

if ( !$id ) {
    header( 'Location: /admin' );
}

//datos propiedad

$propiedad = Propiedad::find( $id );
//Concultar par abtener vendedores
$consulta = 'SELECT * FROM vendedores';
$resultado = mysqli_query( $db, $consulta );

// arreglo con mensajes de errores
$errores = Propiedad::getErrores();
//ejecutar el codigo despues de que el usuario envia el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    //Asignar los atributos
    $args =  $_POST[ 'propiedad' ];

    $propiedad->sincronizar( $args );

    //Validacion
    $errores = $propiedad->validar();

    $nombreImagen = md5( uniqid( rand(), true ) ).'.jpg';

    //subida de archivos
    //realiza un resize a la imagen con invervention
    if ( $_FILES[ 'propiedad' ][ 'tmp_name' ][ 'imagen' ] ) {
        $image = Image::make( $_FILES[ 'propiedad' ][ 'tmp_name' ][ 'imagen' ] )->fit( 800, 600 );
        $propiedad->setImagen( $nombreImagen );
    }

    //revisar que el arreglo de errores este vacio
    if ( empty( $errores ) ) {

        //Almacenar la imagen

        $image->save(CARPETA_IMAGENES.$nombreImagen);
        $propiedad->guardar();
        //Isertar en la bd
    }

}

incluirTemplate( 'header' );

?>

<main class = 'contenedor seccion'>
<h1>Actualizar propiedad</h1>

<a href = '/admin' class = 'boton boton-verde'>Volver</a>

<?php foreach ( $errores as $error ):?>
<div class = 'alerta error'>
<?php echo $error;
?>
</div>
<?php endforeach;
?>

<form action = '' class = 'formulario' method = 'POST' enctype = 'multipart/form-data'>

<?php
include( '../../includes/templates/formulario_propiedades.php' );
?>
<br>
<input type = 'submit' value = 'Actualizar propiedad' class = 'boton boton-verde'>
<br>
<br>
</form>
</main>

<?php
incluirTemplate( 'footer' );
?>