<?php

//Importar la conexion
require '../includes/config/database.php';
$db = conetarDB();

//Escribir query
$query="SELECT * FROM propiedades";

//Consultar bd
$resultadoConsulta=mysqli_query($db,$query);

//Mostrar los resultados


//muestra mensaje condicional
$resultado=$_GET['resultado'] ?? null ;
//incluye un template
require '../includes/funciones.php';
incluirTemplate('header');



?>

<main class="contenedor seccion">
    <h1>Administrador de bienesraices</h1>

    <?php if(intval( $resultado)===1):?>
    <p class="alerta exito">Anuncio creado correctamente</p>
    <?php endif; ?>

    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva propiedad</a>
    <table class="propiedades">
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
            <?php while($propiedad=mysqli_fetch_assoc($resultadoConsulta)):  ?>
            <tr>
                <td><?php echo $propiedad['id']; ?></td>
                <td><?php echo $propiedad['titulo']; ?></td>
                <td><img src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="" class="imagen-tabla"></td>
                <td>$<?php echo $propiedad['precio']; ?></td>
                <td>
                    <br>
                    <a href="#" class="boton-rojo-block">Eliminar</a>
                    <br>
                    <a href="#" class="boton-amarillo-block">Actualizar</a>
                </td>
            </tr>
            <?php endwhile;  ?>
        </tbody>
    </table>

    <br>
    <br>
</main>

<?php

//Cerrar conexion
mysqli_close($db);

incluirTemplate('footer');
?>