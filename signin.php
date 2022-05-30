<?php
// Se comienza la sesión
session_start();

// $_SESSION['user'] = $user;
// $_SESSION['usrName'] = $usrName;
// $_SESSION['items'] = 0;


$functionsPath = $_SERVER['DOCUMENT_ROOT']."/2speedy/functions/";
include_once($functionsPath . "conn.php");
include_once($functionsPath . "functions.php");

/**
 * Se están validando los campos del formulario y se procesa
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = $pwd =  "";
    $errorMsg = array();

    if (empty($_POST['user'])) {
        array_push($errorMsg, "Debe ingresar un usuario");
    } else if (!filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)) {
        array_push($errorMsg, "Debe ingresar un correo válido");
    } else {
        $user = clean_input($_POST['user']);
    }

    if (empty($_POST['pwd'])) {
        array_push($errorMsg, "Debe ingresar una contraseña");
    } else {
        $pwd = clean_input($_POST['pwd']);
    }


    if (empty($errorMsg)){
        $transactionMsg = "";

        // Se verifica que no exista el usuario

        $sql = "SELECT  usuarioId, nombre
                FROM usuarios
                WHERE usuarioId = '$user' AND pwd = '$pwd'";

        $usr = $conn->query($sql);

        if ($usr->num_rows != 0) {
            // El usuario ya existe

            while ($row = $usr->fetch_assoc()) {

                $_SESSION['user'] = $row['usuarioId'];
                $_SESSION['usrName'] = $row['nombre'];
            }

            // $sql = "SELECT  count(usuarioId) AS items
            //         FROM carrito
            //         WHERE usuarioId = '$user' AND comprado = '0'";

            // $items = $conn->query($sql);

            // while ($row = $items->fetch_assoc()) {
            //     $_SESSION['items'] = $row['items'];
            // }

            $sql = "SELECT count(productoId) AS nProducts
            FROM carrito
            WHERE usuarioId = '$user' AND comprado = '0'
            GROUP BY productoId";
    
            $nProducts = $conn->query($sql);

            $_SESSION['items'] = $nProducts->num_rows;


            $catalogPath = "http://" . $_SERVER['HTTP_HOST'] . "/2speedy/index.php";

            header("location: $catalogPath");
            exit;


            $transactionMsg = "<div class='alert alert-info' role='alert'>
                                    El usuario $user ya existe.<br>Ingrese sesión siguiendo el siguiente <a href='signin.php' class='alert-link'>link</a>.
                                </div>";
        } else {
            // No existe hay que ingresar los datos
            $transactionMsg = "<div class='alert alert-info' role='alert'>
                                    El usuario o contraseña no concuerdan.
                                </div>";

            // Turn autocommit off
            // $conn -> autocommit(FALSE);

            // try {
            //     /* Start transaction */
            //     $conn->begin_transaction();


            //     $sql = "INSERT INTO usuarios (usuarioId, nombre, apellido, pwd, tel, ciudad)
            //             VALUES ('$user', '$usrName', '$usrLastName', '$pwd', '$usrPhone', '$usrCity')";
                
            //     $errors = array();

            //     if (!$conn->query($sql)) $errors[] = $conn->error;

            //     if (count($errors) === 0) {
            //         $conn->commit();
            //         $transactionMsg = "<div class='alert alert-success' role='alert'>
            //                             El usuario $user se ha ingresado con éxito.<br>Visite nuestro <a href='index.php' class='alert-link'>catálogo</a>.
            //                         </div>";
            //         $_SESSION['user'] = $user;
            //         $_SESSION['usrName'] = $usrName;
            //         $_SESSION['items'] = 0;
            //     }

            // } catch(mysqli_sql_exception $exception) {
            //     $conn->rollback();
            //     echo "<h1>Error!!- ".$exception->getMessage()."</h1>";
            //     throw $exception;
            // }

            // // Turn autocommit on
            // $conn -> autocommit(TRUE);

        }

    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">

    <title>Registro</title>
</head>

<body>
    <div class="content">
        <div class="registro border border-secondary rounded mt-5">
            <div class="container p-4">
                
                <!-- Logo -->
                <div class="d-flex justify-content-center navbar-brand">
                    <img src="img/speedy-logo3.png" alt="" width="30" height="30" class="d-inline-block align-top"> 2Speedy Sports
                </div>


                <!-- Verifica estado de la transacción en caso que hubiera habido -->
                    <h4 class="mt-3">Datos de ingreso</h4>
                    <div class="mt-4">

                        <!-- Muestra errores del formulario -->
                        <?php if (!empty($errorMsg)): ?>
                            <div class="alert alert-danger" role="alert">
                                <p>Corrija los siguientes errores:</p>
                                <hr>
                                <ul>
                                    <?php
                                    foreach($errorMsg as $value){
                                        echo "<li>$value</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($transactionMsg)) echo $transactionMsg; ?>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                            <div class="mb-3">
                                <label for="userInputEmail1" class="form-label">Usuario:</label>
                                <input type="email" class="form-control" id="userInputEmail1" aria-describedby="userEmail" name="user" <?php echo (empty($user))?: "value='$user'"?> >
                                <div id="userEmail" class="form-text">No se compartirá con nadie esta información.</div>
                            </div>
                            <div class="mb-3">
                                <label for="userInputPassword1" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="userInputPassword1" name="pwd" <?php echo (empty($pwd))?: "value='$pwd'"?> >
                            </div>
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="submit" name="submit" class="btn btn-outline-secondary btn-sm">Ingresar</button>
                            </div>
                        </form>
                    </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</body>

</html>