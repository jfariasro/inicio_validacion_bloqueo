<?php session_start();
include('conexion.php');

if (!$conexion) {
    die('No hubo conexión');
}

if (isset($_POST['Enviar'])) {
    $email = $_POST['email'];
    $clave = $_POST['clave'];

    $query = "SELECT * FROM usuario WHERE email = '$email' AND clave = '$clave'";
    $execute = mysqli_query($conexion, $query);
    $resultado = mysqli_fetch_assoc($execute);

    //Shift + Alt + F para identar el código
    $email_valido = $resultado['email'] ?? '';
    $clave_valido = $resultado['clave'] ?? '';

    if (isset($_SESSION['intento']) && $_SESSION['intento'] >= 3) {
        if (!isset($_SESSION['tiempo']) || $_SESSION['tiempo'] == 0) {
            $_SESSION['tiempo'] = time() + (15 * 60);
        }

        $actual = time();
        $tiempo = $_SESSION['tiempo'];

        if ($actual < $tiempo) {
            $contador = ceil(($tiempo - $actual) / 60);
            echo "<script>
            alert('Has agotado tus 3 intentos, intentalo de nuevo en $contador minutos');
            </script>";
        } else {
            $_SESSION['intento'] = 0;
            $_SESSION['tiempo'] = 0;
        }
    }

    if (!isset($_SESSION['intento']) || $_SESSION['intento'] < 3) {
        if ($email == $email_valido && $clave == $clave_valido) {
            $_SESSION['intento'] = 0;
            header('location: inicio.php');
        } else {
            if (!isset($_SESSION['intento'])) {
                $_SESSION['intento'] = 1;
            } else {
                $_SESSION['intento']++;
            }
            echo 'Credenciales incorrectas, intento ' . $_SESSION['intento'];
        }
    }
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="post">
        <input type="email" name="email" id="email" placeholder="Ingresar Email" required>
        <br><br>

        <input type="password" name="clave" id="clave" placeholder="Ingresar Clave" required>
        <br><br>

        <input type="submit" value="Enviar" name="Enviar">
    </form>
</body>

</html>