<?php
require '../../includes/app.php';

use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;

estaAutenticado();

//base de datos

$db = conetarDB();

$propiedad = new Propiedad();

//Concultar par abtener vendedores

$consulta = 'SELECT * FROM vendedores';
$resultado = mysqli_query( $db, $consulta );

// arreglo con mensajes de errores
$errores = Propiedad::getErrores();

//ejecutar el codigo despues de que el usuario envia el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    /**Crea una nueva instancia */
    $propiedad = new Propiedad( $_POST );
    /**Subida de archivos */

    /*Crear carpeta*/

    //generar nombre unico
    $nombreImagen = md5( uniqid( rand(), true ) ).'.jpg';

    //Setaer la imagen
    //realiza un resize a la imagen con invervention
    if ( $_FILES[ 'imagen' ][ 'tmp_name' ] ) {
        $image = Image::make( $_FILES[ 'imagen' ][ 'tmp_name' ] )->fit( 800, 600 );
        $propiedad->setImagen( $nombreImagen );
    }

    //Validaciones
    $errores = $propiedad->validar();

    if ( empty( $errores ) ) {
        //Crear la carpeta para subir imagenes
        if ( !is_dir( CARPETA_IMAGENES ) ) {
            mkdir( CARPETA_IMAGENES );
        }
        //Guarda la imagen en el servidor
        $image->save( CARPETA_IMAGENES . $nombreImagen );

        //Guarda en la db
        $resultado = $propiedad->guardar();
        //Mensaje de exito o error
        if ( $resultado ) {
            //redireccionar al usuario
            header( 'Location:/admin?resultado=1' );
        }
    }

}
incluirTemplate( 'header' );

?>

<main class='contenedor seccion'>
    <h1>Crear</h1>

    <a href='/admin' class='boton boton-verde'>Volver</a>

    <?php foreach ( $errores as $error ):?>
    <div class='alerta error'>
        <?php echo $error;
?>
    </div>
    <?php endforeach;
?>

    <form action='' class='formulario' method='POST' action='/admin/propiedades/crear.php'
        enctype='multipart/form-data'>
        <?php
            include( '../../includes/templates/formulario_propiedades.php' );
        ?>
        <input type='submit' value='Crear propiedad' class='boton boton-verde'>
        <br>
        <br>
    </form>
</main>

<?php
incluirTemplate( 'footer' );
?>