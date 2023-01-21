<?php

require '../includes/funciones.php';

$auth=estaAutenticado();
if(!$auth){
    header('Location:/');
}

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


if($_SERVER['REQUEST_METHOD']==='POST'){
    $id=$_POST['id'];
    $id=filter_var($id,FILTER_VALIDATE_INT);
    if($id){
        //Eliminar el archivo
        $query="SELECT imagen FROM propiedades WHERE id = ${id}";
        $resultado=mysqli_query($db,$query);
        $propiedad=mysqli_fetch_assoc($resultado);

        unlink('../imagenes/'.$propiedad['imagen']);
        //elimina la propiedad;
        $query="DELETE FROM propiedades WHERE id=${id}";
        $resultado=mysqli_query($db,$query);
        if($resultado){
            header('Location: /admin?resultado=3');
        }
    }
}
//incluye un template
incluirTemplate('header');



?>

<main class="contenedor seccion">
    <h1>Administrador de bienesraices</h1>

    <?php if(intval( $resultado)===1):?>
    <p class="alerta exito">Anuncio Creado Correctamente</p>
    <?php elseif(intval( $resultado)===2): ?>
    <p class="alerta exito">Anuncio Actualizado Correctamente</p>
    <?php elseif(intval( $resultado)===3): ?>
    <p class="alerta exito">Anuncio Eliminado Correctamente</p>
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
                    <form method="POST" class="w-100">
                        <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                        <input type="submit" name="" value="Eliminar" class="boton-rojo-block">
                    </form>
                    <br>
                    <a href="admin/propiedades/actualizar.php?id=<?php  echo $propiedad['id'];?>"
                        class="boton-amarillo-block">Actualizar</a>
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