<?php
require '../../includes/funciones.php';

$auth=estaAutenticado();
if(!$auth){
    header('Location:/');
}
//validar por id valido
$id = $_GET[ 'id' ];
$id = filter_var( $id, FILTER_VALIDATE_INT );

if ( !$id ) {
    header( 'Location: /admin' );
}
//base de datos

require '../../includes/config/database.php';
$db = conetarDB();

//datos propiedad
$consulta = "SELECT * FROM propiedades WHERE id = ${id}";
$resultado = mysqli_query( $db, $consulta );
$propiedad = mysqli_fetch_assoc( $resultado );

//Concultar par abtener vendedores
$consulta = 'SELECT * FROM vendedores';
$resultado = mysqli_query( $db, $consulta );

// arreglo con mensajes de errores
$errores = [];

//iniciando variables
$titulo = $propiedad[ 'titulo' ];
$precio = $propiedad[ 'precio' ];
$descripcion = $propiedad[ 'descripcion' ];
$habitaciones = $propiedad[ 'habitaciones' ];
$wc = $propiedad[ 'wc' ];
$estacionamiento = $propiedad[ 'estacionamiento' ];
$vendedorId = $propiedad[ 'vendedorId' ];
$imagenPropiedad = $propiedad[ 'imagen' ];
// $imagen = '';

//ejecutar el codigo despues de que el usuario envia el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    // echo '<pre>';
    // echo var_dump( $_POST );
    // echo '</pre>';
    // echo '<pre>';
    // echo var_dump( $_FILES );
    // echo '</pre>';

    $titulo = mysqli_real_escape_string( $db, $_POST[ 'titulo' ] );
    $precio = mysqli_real_escape_string( $db, $_POST[ 'precio' ] );
    $descripcion = mysqli_real_escape_string( $db, $_POST[ 'descripcion' ] );
    $habitaciones = mysqli_real_escape_string( $db, $_POST[ 'habitaciones' ] );
    $wc = mysqli_real_escape_string( $db, $_POST[ 'wc' ] );
    $estacionamiento = mysqli_real_escape_string( $db, $_POST[ 'estacionamiento' ] );
    $vendedorId = mysqli_real_escape_string( $db, $_POST[ 'vendedor' ] );
    $creado = date( 'Y/m/d' );

    //Asignar files hacia una variable
    $imagen = $_FILES[ 'imagen' ];

    //Validaciones
    if ( !$titulo ) {
        $errores[] = 'Debes añadir un titulo';
    }
    if ( !$precio ) {
        $errores[] = 'El precio es obligatorio';
    }
    if ( strlen( $descripcion )<50 ) {
        $errores[] = 'La descripción es obligatoria y debe tener al menos 50 caracteres';
    }
    if ( !$habitaciones ) {
        $errores[] = 'El número de habitación es obligatorio';
    }
    if ( !$wc ) {
        $errores[] = 'El número de baños es obligatorio';
    }
    if ( !$estacionamiento ) {
        $errores[] = 'El número de lugares de estacionamiento es obligatorio';
    }
    if ( !$vendedorId ) {
        $errores[] = 'Elige un vendedor';
    }

    //vqalidar tamaño  ( 1mb maximo )
    $medida = 1000*1000;
    if ( $imagen[ 'size' ] > $medida ) {
        $errores[] = 'La imagen es muy pesada';
    }

    // echo '<pre>';
    // var_dump ( $errores );
    // echo '</pre>';
    // exit;

    //revisar que el arreglo de errores este vacio
    if ( empty( $errores ) ) {

        /**Subida de archivos */

        /*Crear carpeta*/

        $capetaImagenes = '../../imagenes/';
        if ( !is_dir( $capetaImagenes ) ) {
            mkdir( $capetaImagenes );
        }

        $nombreImagen = '';
        if ( $imagen[ 'name' ] ) {
            //eliminar imagen previa
            unlink( $capetaImagenes . $propiedad[ 'imagen' ] );
            // //generar nombre unico
            $nombreImagen = md5( uniqid( rand(), true ) ).'.jpg';
            // //Subir imagen
            move_uploaded_file( $imagen[ 'tmp_name' ], $capetaImagenes.$nombreImagen );
        }else{
            $nombreImagen=$propiedad['imagen'];
        }

        //Isertar en la bd
        $query = "UPDATE propiedades SET titulo='${titulo}',precio='${precio}',imagen='${nombreImagen}',descripcion='${descripcion}',habitaciones=${habitaciones},wc=${wc},estacionamiento=${estacionamiento},vendedorId=${vendedorId} WHERE id=${id}";
        // echo $query;
        $resultado = mysqli_query( $db, $query );
        if ( $resultado ) {
            //redireccionar al usuario
            header( 'Location:/admin?resultado=2' );
        }
    }

}


incluirTemplate( 'header' );

?>

<main class='contenedor seccion'>
    <h1>Actualizar propiedad</h1>

    <a href='/admin' class='boton boton-verde'>Volver</a>

    <?php foreach ( $errores as $error ):?>
    <div class='alerta error'>
        <?php echo $error;
?>
    </div>
    <?php endforeach;
?>

    <form action='' class='formulario' method='POST' enctype='multipart/form-data'>
        <fieldset>
            <legend>Información General</legend>
            <label for='titulo'>Titulo:</label>
            <input type='text' id='titulo' name='titulo' placeholder='Titulo propiedad' value="<?php echo $titulo; ?>">
            <label for='precio'>Precio:</label>
            <input type='number' id='precio' name='precio' placeholder='Precio propiedad'
                value="<?php echo $precio; ?>">
            <label for='imagen'>Imagen:</label>
            <input type='file' id='imagen' name='imagen' accept='image/jpge,image/png'>

            <img src="/imagenes/<?php echo $imagenPropiedad; ?>" alt='' class='imagen-small'>
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
            <select name='vendedor' id=''>
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
        <input type='submit' value='Actualizar propiedad' class='boton boton-verde'>
        <br>
        <br>
    </form>
</main>

<?php
incluirTemplate( 'footer' );
?>