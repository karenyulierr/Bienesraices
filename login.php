<?php
// importatr la conexion
require ('includes/config/database.php');
$db=conetarDB();
//autenticar usuario

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){

    // var_dump($_POST);
    // exit;
    $email=mysqli_real_escape_string($db, filter_var($_POST['email'],FILTER_VALIDATE_EMAIL));
    $password=mysqli_real_escape_string($db, $_POST['contra']);

    if(!$email){
        $errors[]='El email es obligario o no es válido';
    }
    if(!$password){
        $errors[]='El password es obligario';
    }

    if(empty($errors)){
        //Revisar si el usuario existe

        $query="SELECT * FROM  usuarios WHERE email='${email}'";
        $resultado=mysqli_query($db, $query);
        if($resultado->num_rows){
            //revisar si el password es correcto
            $usuario=mysqli_fetch_assoc($resultado);
            //verificar si el password es correcto o no
            $auth=password_verify($password,$usuario['password']);
            if($auth){
            //el usuario esta autenticado
            session_start();

            //lLENAR EL ARREGLO DE LA SESION
            $_SESSION['usuario']=$usuario['email'];
            $_SESSION['login']=true;
            header('Location:/admin');
            }else{
                $errors[]='El password es incorrecto';
            }
        }else{
            $errors[]='El usuario no existe';
        }
    }

}

//Incluir el header

require 'includes/funciones.php';
incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar sesion</h1>

    <?php foreach ($errors as $error ):?>
    <div class="alerta error">
        <?php echo $error; ?>
    </div>
    <?php endforeach; ?>


    <form class="formulario " method="POST">
        <fieldset>
            <legend>Email y password</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu Email" id="email" required>

            <label for="password">password</label>
            <input type="password" name="contra" placeholder="Tu password" id="password" required>
        </fieldset>
        <br>
        <input type="submit" value="Inicar sesión" class="boton boton-verde">
    </form>
</main>

<br><br>
<?php
incluirTemplate('footer');
?>