<?php

//base de datos

require '../../includes/config/database.php';
$db = conetarDB();

//Concultar par abtener vendedores

$consulta="SELECT * FROM vendedores";
$resultado=mysqli_query($db,$consulta);


// arreglo con mensajes de errores
$errores = [];

//iniciando variables
$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorId = '';

//ejecutar el codigo despues de que el usuario envia el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    // echo '<pre>';
    // echo var_dump( $_POST );
    // echo '</pre>';

    $titulo = $_POST[ 'titulo' ];
    $precio = $_POST[ 'precio' ];
    $descripcion = $_POST[ 'descripcion' ];
    $habitaciones = $_POST[ 'habitaciones' ];
    $wc = $_POST[ 'wc' ];
    $estacionamiento = $_POST[ 'estacionamiento' ];
    $vendedorId = $_POST[ 'vendedor' ];
    $creado = date('Y/m/d');

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

    // echo '<pre>';
    // var_dump ( $errores );
    // echo '</pre>';
    // exit;

    //revisar que el arreglo de errores este vacio
    if ( empty( $errores ) ) {
        //Isertar en la bd
        $query = "INSERT INTO propiedades (titulo,precio,descripcion,habitaciones,wc,estacionamiento,creado,vendedorId ) 
        VALUES ('$titulo','$precio','$descripcion','$habitaciones','$wc','$estacionamiento','$creado','$vendedorId') ";

        // echo $query;
        $resultado = mysqli_query( $db, $query );
        if ( $resultado ) {
            //redireccionar al usuario
            header('Location:/admin');
        }
    }

}

require '../../includes/funciones.php';
incluirTemplate( 'header' );

?>

<main class='contenedor seccion'>
    <h1>Crear</h1>


    <a href='/admin' class='boton boton-verde'>Volver</a>

    <?php foreach($errores as $error):?>
    <div class="alerta error">
        <?php echo $error; ?>
    </div>
    <?php endforeach; ?>

    <form action='' class='formulario' method='POST' action='/admin/propiedades/crear.php'>
        <fieldset>
            <legend>Información General</legend>
            <label for='titulo'>Titulo:</label>
            <input type='text' id='titulo' name='titulo' placeholder='Titulo propiedad' value="<?php echo $titulo; ?>">
            <label for='precio'>Precio:</label>
            <input type='number' id='precio' name='precio' placeholder='Precio propiedad'
                value="<?php echo $precio; ?>">
            <label for='imagen'>Imagen:</label>
            <input type='file' id='imagen' accept='image/jpge,image/png'>
            <label for='descripcion'>Descripción:</label>
            <textarea name='descripcion' id='descripcion' cols='30' rows='10'> <?php echo $descripcion; ?></textarea>
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
                <?php while($row = mysqli_fetch_assoc($resultado)): ?>
                <option <?php echo $vendedorId === $row['id'] ? 'selected':''; ?> value='<?php echo $row['id'];  ?>'>
                    <?php echo $row['nombre'] . " " . $row['apellido'];?></option>
                <?php endwhile; ?>
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