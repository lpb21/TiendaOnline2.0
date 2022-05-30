<?php
// Se comienza la sesión
session_start();

// $_SESSION['user'] = $user;
// $_SESSION['usrName'] = $usrName;
// $_SESSION['items'] = 0;


$functionsPath = $_SERVER['DOCUMENT_ROOT']."/2speedy/functions/";
include_once($functionsPath . "conn.php");
include_once($functionsPath . "functions.php");


if(empty($_GET['product'])) {
    echo "<h1>Debe especificar el producto</h1>";
    die();
}


$product = $_GET['product'];

$url = htmlspecialchars($_SERVER["PHP_SELF"]."?product=$product");
// echo $url;

$sql = "SELECT  * FROM productos
        WHERE productoId='$product'";


$productos = $conn->query($sql);
// echo $productos->num_rows;


/**
 * Se están validando los campos del formulario y se procesa
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // print_r($_POST);

    if (empty($_SESSION['user'])) {
        echo "<h1>Debes ingresar sesión para añadir al carrito</h1>";
        die();
    } else {
        $userId = $_SESSION['user'];
    }

    $productoId = $_POST['productoId'];
    $cantidad = $_POST['cantidad'];
    $talla = (empty($_POST['talla'])) ? '' : $_POST['talla'];

    // Turn autocommit off
    $conn -> autocommit(FALSE);

    try {
        /* Start transaction */
        $conn->begin_transaction();


        $sql = "INSERT INTO carrito (productoId, usuarioId, cantidad, talla)
                VALUES ('$productoId', '$userId', '$cantidad', '$talla' )";
        
        $errors = array();

        if (!$conn->query($sql)) $errors[] = $conn->error;

        if (count($errors) === 0) {
            $conn->commit();
            $transactionMsg = "<div class='alert alert-success' role='alert'>
                                El producto se ha añadido al carrito.
                            </div>";

            // Tener en cuenta colocar el número de itmes... hay que verificar que ya no haya ingresado el ITEM
            // $_SESSION['items'];
        }

    } catch(mysqli_sql_exception $exception) {
        $conn->rollback();
        echo "<h1>Error!!- ".$exception->getMessage()."</h1>";
        throw $exception;
    }

    // Turn autocommit on
    $conn -> autocommit(TRUE);

    $sql = "SELECT count(productoId) AS nProducts
            FROM carrito
            WHERE usuarioId = '$userId' AND comprado = '0'
            GROUP BY productoId, talla";
    
    $nProducts = $conn->query($sql);

    $_SESSION['items'] = $nProducts->num_rows;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">

    <title>Producto</title>
</head>

<body>
    <div class="content">
        <?php include('navbar.php') ?>

        <div class="catalog">
            <div class="container">

                <?php if ($productos->num_rows == 0): ?>
                    <h1 class="mt-5">NO SE ENCONTRÓ NINGÚN PRODUCTO CON ESA REFERENCIA</h1>
                <?php else: ?>

                    <div class="card mt-5 mb-3" style="max-width: 800px;">
                        <div id="notUser" style="display:none;" class="alert alert-danger" role="alert">
                            Debes ingresar con tu usuario para poder hacer pedidos. Ingresa acá: <a href='signin.php' class='alert-link'>Inicio sesión</a>
                        </div>
                        <div id="notInventoryMsg" class="alert alert-danger hidden" role="alert">
                            No tenemos en inventario la cantidad solicitada.
                        </div>
                        <?php echo (empty($transactionMsg)) ? '' : $transactionMsg ?>
                        <form id="addProduct" action="<?php echo $url; ?>" method="post">
                        <input id='user' name='user' value='<?php echo (empty($_SESSION['user'])) ? '' : $_SESSION['user'] ?>' type='hidden'>
                        <?php

                        while($row = $productos->fetch_assoc()){
                            // $id = number_format($clienteId);
                            // $name = $row['name'];
                            // $saldo = $row['val'];
                            // echo $name." (CC $id) con saldo: $". number_format($saldo) . "<br><br>";

                            $productoId = $row['productoId'];
                            $categoriaId = $row['categoriaId'];
                            $nombre = $row['Nombre'];
                            $isTalla = $row['talla'];
                            $descripcion = $row['descripcion'];
                            $imagen = $row['imagen'];
                            $precio = number_format($row['precio']);
                            $cantidad = $row['cantidad'];

                            $disabled = ($cantidad < 1) ? "disabled" : "";

                            
                            echo "
                            <div class='row g-0'>
                                <input name='productoId' value='$productoId' type='hidden'>
                                <input id='isTalla' name='isTalla' value='$isTalla' type='hidden'>
                                <div class='col-md-4'>
                                    <img src='img/$imagen' class='card-img-top' alt='$nombre'>
                                </div>
                                <div class='col-md-8'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>$nombre</h5>
                                        <p class='card-text'>$descripcion.</p>
                                        <input class='cantidad-inventario' value='$cantidad' type='hidden'>";
                                        echo ($cantidad > 9) ? "<p class='text-success'>Stock disponible ($cantidad unidades)</p>" : "<p class='text-danger'>Quedan $cantidad productos</p>";
                                        echo "
                                        <div class='row g-2'>
                                            <div class='col-md'>
                                                <div class='form-floating'>
                                                    <select name='cantidad' class='form-select' id='cantidadSelect' aria-label='Escoger cantidad' $disabled>
                                                        <option value='' selected> Escoja la cantidad</option>
                                                        <option value='1'>1 unidad</option>
                                                        <option value='2'>2 unidades</option>
                                                        <option value='3'>3 unidades</option>
                                                        <option value='4'>4 unidades</option>
                                                        <option value='5'>5 unidades</option>
                                                    </select>
                                                    <label for='cantidadSelect'>Cantidad:</label>
                                                    <p id='errorCantidad' style='color:red; display:none;'>Ingrese la cantidad</p>
                                                </div>
                                            </div>
                                            <div class='col-md'>";
                                            if ($isTalla){
                                                echo "
                                                <div class='form-floating'>
                                                    <select name='talla' class='form-select' id='tallaSelect' aria-label='Escoger talla' $disabled>
                                                        <option value='' selected> Escoja la talla</option>
                                                        <option value='s'>Small</option>
                                                        <option value='m'>Medium</option>
                                                        <option value='l'>Large</option>
                                                    </select>
                                                    <label for='tallaSelect'>Talla:</label>
                                                    <p id='errorTalla' style='color:red; display:none;'>Ingrese la talla</p>
                                                </div>";
                                            };
                                            echo "
                                            </div>
                                        </div>

                                        <h4 class='card-title mt-3'>Precio: $ $precio</h4>
                                        <a  class='btn btn-outline-secondary btn-sm btn--add--cart $disabled'>Agregar al carrito</a>
                                    </div>
                                </div>
                            </div>";


                        }         
                        ?>

                        </form>
                    </div>
                <?php endif ?>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script defer src="scripts/producto.js"></script>
    <script>
    //     const cantidad = document.getElementById('cantidadSelect');
    //     const talla = document.getElementById('tallaSelect');
    //     const isTalla = document.getElementById('isTalla');
    //     const form = document.getElementById('addProduct');
    //     const errorCantidad = document.getElementById('errorCantidad');
    //     const errorTalla = document.getElementById('errorTalla');
    //     const user = document.getElementById('user');
    //     const notUser = document.getElementById('notUser');
    
    // function validateForm() {


    //     // console.log(typeof talla.value );

        

    //     if ( user.value == "" ) {
    //         notUser.style.display = 'block';
    //     } else {

    //         notUser.style.display = 'none';

    //         if (cantidad.value == "") {
    //             console.log("Un error");
    //             errorCantidad.style.display='block';
    //         } else if  (isTalla.value==1 && talla.value == '') {
    //             console.log(typeof talla);
    //             console.log(typeof talla.value);
    //             console.log(talla.value);
    //             console.log("Un error2");
    //             errorCantidad.style.display='none';
    //             errorTalla.style.display='block';
    //         } else {
    //             console.log("Todo OK");
    //             form.submit();

    //         }
    //     }

    // }
    
    </script>

</body>

</html>