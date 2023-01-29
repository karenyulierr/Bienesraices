<?php

require '../includes/app.php';

estaAutenticado();

use App\Propiedad;
use App\Vendedor;

//Implementar Meotod para obtner todas las propiedades

$propiedades = Propiedad::all();
$vendedores = Vendedor::all();

//Mostrar los resultados

//muestra mensaje condicional
$resultado = $_GET[ 'resultado' ] ?? null ;

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $id = $_POST[ 'id' ];
    $id = filter_var( $id, FILTER_VALIDATE_INT );
    if ( $id ) {
        $tipo = $_POST[ 'tipo' ];

        if ( validarTipoContenido( $tipo ) ) {
            //compara lo que vamos a eliminar
            if ( $tipo == 'propiedad' ) {
                $propiedad = Propiedad::find( $id );
                $propiedad->eliminar();
            } elseif ( $tipo == 'vendedor' ) {
                $vendedor = Vendedor::find( $id );
                $vendedor->eliminar();
            }

        }

    }
}
//incluye un template
incluirTemplate( 'header' );

?>

<main class='contenedor seccion'>
    <h1>Administrador de bienesraices</h1>
    <?php $mensaje = mostrarNotificacion( intval( $resultado ) );
        if ( $mensaje ) {?>
        <p class="alerta exito"><?php echo s($mensaje);?></p>
    <?php   }
    ?>

    <a href='/admin/propiedades/crear.php' class='boton boton-verde'>Nueva Propiedad</a>
    <a href='/admin/vendedores/crear.php' class='boton boton-amarillo'>Nuevo( a ) Vendedor</a>
    <h2>
        Propiedades
    </h2>
    <table class='propiedades'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $propiedades as $propiedad ):  ?>
            <tr>
                <td><?php echo $propiedad->id;
    ?></td>
                <td><?php echo $propiedad->titulo;
    ?></td>
                <td><img src="/imagenes/<?php echo $propiedad->imagen; ?>" alt='' class='imagen-tabla'></td>
                <td>$<?php echo $propiedad->precio;
    ?></td>
                <td>
                    <br>
                    <form method='POST' class='w-100'>
                        <input type='hidden' name='id' value="<?php echo $propiedad->id; ?>">
                        <input type='hidden' name='tipo' value='propiedad'>
                        <input type='submit' name='' value='Eliminar' class='boton-rojo-block'>
                    </form>
                    <br>
                    <a href="admin/propiedades/actualizar.php?id=<?php  echo $propiedad->id;?>"
                        class='boton-amarillo-block'>Actualizar</a>
                </td>
            </tr>
            <?php endforeach;
    ?>
        </tbody>
    </table>
    <h2>
        Vendedores
    </h2>
    <table class='propiedades'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $vendedores as $vendedor ):  ?>
            <tr>
                <td><?php echo $vendedor->id;
    ?></td>
                <td><?php echo $vendedor->nombre . ' ' . $vendedor->apellido ;
    ?>
                <td><?php echo $vendedor->telefono;
    ?></td>
                <td>
                    <br>
                    <form method='POST' class='w-100'>
                        <input type='hidden' name='id' value="<?php echo $vendedor->id; ?>">
                        <input type='hidden' name='tipo' value='vendedor'>
                        <input type='submit' name='' value='Eliminar' class='boton-rojo-block'>
                    </form>
                    <br>
                    <a href="admin/vendedores/actualizar.php?id=<?php  echo $vendedor->id;?>"
                        class='boton-amarillo-block'>Actualizar</a>
                </td>
            </tr>
            <?php endforeach;
    ?>
        </tbody>
    </table>
    <br>
    <br>
</main>

<?php

    incluirTemplate( 'footer' );
    ?>