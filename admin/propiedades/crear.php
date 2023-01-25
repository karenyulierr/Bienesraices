<?php
require '../../includes/app.php';

use App\Propiedad;

estaAutenticado();

//base de datos

$db = conetarDB();

//Concultar par abtener vendedores

$consulta = 'SELECT * FROM vendedores';
$resultado = mysqli_query( $db, $consulta );

// arreglo con mensajes de errores
$errores = Propiedad::getErrores();

//iniciando variables
$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorId = '';
// $imagen = '';

//ejecutar el codigo despues de que el usuario envia el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {

    $propiedad = new Propiedad( $_POST );
    $errores = $propiedad->validar();

    if ( empty( $errores ) ) {
        $propiedad->guardar();

        //Asignar files hacia una variable
        $imagen = $_FILES[ 'imagen' ];

        //revisar que el arreglo de errores este vacio

        /**Subida de archivos */

        /*Crear carpeta*/

        $capetaImagenes = '../../imagenes/';
        if ( !is_dir( $capetaImagenes ) ) {
            mkdir( $capetaImagenes );
        }

        //generar nombre unico
        $nombreImagen = md5( uniqid( rand(), true ) ).'.jpg';
        //Subir imagen
        move_uploaded_file( $imagen[ 'tmp_name' ], $capetaImagenes.$nombreImagen );

        // echo $query;
        $resultado = mysqli_query( $db, $query );
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
        <fieldset>
            <legend>Información General</legend>
            <label for='titulo'>Titulo:</label>
            <input type='text' id='titulo' name='titulo' placeholder='Titulo propiedad' value="<?php echo $titulo; ?>">
            <label for='precio'>Precio:</label>
            <input type='number' id='precio' name='precio' placeholder='Precio propiedad'
                value="<?php echo $precio; ?>">
            <label for='imagen'>Imagen:</label>
            <input type='file' id='imagen' name='imagen' accept='image/jpge,image/png'>
            <label for='descripcion'>Descripción:</label>
            <textarea name='descripcion' id='descripcion' cols='30' rows='10'> <?php echo $descripcion;
?></textarea>
        </fieldset>

        <fieldset>
            <legend>Informacion Propiedad</legend>
            <label for='habitaciones'>Habitaciones:</label>
            <input type='number' id='habitaciones' name='habitaciones' placeholder='Ej: 3' min='1' max='9'
                value="<?php echo $habitaciones; ?>">
            <label for='wc'>Baños:</label>
            <input type='number' id='wc' placeholder='Ej: 3' name='wc' min='1' max='9' value="<?php echo $wc; ?>">
            <label for='estacionamiento'>Estacionamiento:</label>
            <input type='number' id='estacionamiento' name='estacionamiento' placeholder='Ej: 3' min='1' max='9'
                value="<?php echo $estacionamiento; ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>
            <select name='vendedorId' id=''>
                <option value=''>-- Seleccione --</option>
                <?php while( $row = mysqli_fetch_assoc( $resultado ) ): ?>
                <option <?php echo $vendedorId === $row[ 'id' ] ? 'selected':'';
?> value='<?php echo $row['id'];  ?>'>
                    <?php echo $row[ 'nombre' ] . ' ' . $row[ 'apellido' ];
?></option>
                <?php endwhile;
?>
            </select>
        </fieldset>
        <br>
        <input type='submit' value='Crear propiedad' class='boton boton-verde'>
        <br>
        <br>
    </form>
</main>

<?php
incluirTemplate( 'footer' );
?>