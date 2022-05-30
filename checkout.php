<?php
// Se comienza la sesión
session_start();

// $_SESSION['user'] = $user;
// $_SESSION['usrName'] = $usrName;
// $_SESSION['items'] = 0;


$functionsPath = $_SERVER['DOCUMENT_ROOT']."/2speedy/functions/";
include_once($functionsPath . "conn.php");
include_once($functionsPath . "functions.php");


if(empty($_SESSION['user'])) {
    echo "<h1>Debe estar registrado</h1>";
    die();
}
$compraTotal = 0;

$user = $_SESSION['user'];
// $product = $_GET['product'];

$url = htmlspecialchars($_SERVER["PHP_SELF"]);
// echo $url;

// echo $productos->num_rows;
$errors = array();

/**
 * Se están validando los campos del formulario y se procesa
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // print_r($_POST);

    if (empty($_POST['delete'])) {

        // echo "Buy products" . $_POST['delete'];

        // Turn autocommit off
        $conn -> autocommit(FALSE);

        foreach($_POST as $key => $value) {
            // Only get product code = exclude talla y usuario
            if ($key !== 'user' && !strpos($key, 'isTalla') && !strpos($key, 'cantidad') && $key !== 'delete') {
                $item = explode('-', $key);
                $actualTalla = (empty($_POST["$item[0]-isTalla"])) ? '' : $_POST["$item[0]-isTalla"];
                $cantidad = $_POST["$item[0]-cantidad"];
                // echo "$key => $value => $actualTalla<br>";



                try {
                    $conn->begin_transaction();



                    $sql = "SELECT cantidad
                            FROM productos
                            WHERE productoId = '$value' AND cantidad >= $cantidad";

                    $chkProductInventory = $conn->query($sql);

                    if ($chkProductInventory->num_rows === 0) $errors[] = "No queda la cantidad que seleccionaste";
                    else {

                        $sql = "UPDATE carrito
                                SET comprado = 1
                                WHERE productoId = '$value' AND talla = '$actualTalla'";

                        // echo $sql;
                        if (!$conn->query($sql))  {
                            $errors[] = $conn->error;
                            print_r($errors);
                            die();
                        }

                        $sql = "UPDATE productos
                                SET cantidad = cantidad - $cantidad
                                WHERE productoId = '$value'";

                        // echo $sql;
                        if (!$conn->query($sql))  {
                            $errors[] = $conn->error;
                            print_r($errors);
                            die();
                        }

                        $sql = "SELECT count(productoId) AS nProducts
                        FROM carrito
                        WHERE usuarioId = '$user' AND comprado = '0'
                        GROUP BY productoId, talla";
                
                        $nProducts = $conn->query($sql);

                        $_SESSION['items'] = $nProducts->num_rows;
                }

                } catch (mysqli_sql_exception $exception) {
                    $conn->rollback();
                    echo "<h1>Error!!- ".$exception->getMessage()."</h1>";
                    throw $exception;
                }
            }
        }

        if (count($errors) === 0) $conn->commit();
        else {
            print_r($errors);
            die();
        }

        // Turn autocommit on
        $conn -> autocommit(TRUE);
    } else {

        $delete = TRUE;
        $deleteItem = explode('-', $_POST['delete']);
        $sql = "DELETE FROM carrito
                WHERE productoId = '$deleteItem[0]' AND talla = '$deleteItem[1]'";
        // echo $sql;
        if (!$conn->query($sql)) echo "Error";
        $_SESSION['items']--;
    }
}

$sql ="SELECT p.Nombre, c.productoId,  sum(c.cantidad) AS cantidad, c.talla, p.descripcion, p.imagen, p.precio, p.categoriaId, p.cantidad AS cantidadExistente
        FROM carrito AS c
        JOIN productos AS p ON p.productoId = c.productoId
        WHERE c.usuarioId = '$user' AND c.comprado = 0
        GROUP BY c.productoId, c.talla";


$productos = $conn->query($sql);

$disableBuyButton = 0;
$showOutOfInventory = 'hidden';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">

    <title>Carrito</title>
</head>

<body>
    <div class="content">
        <?php include('navbar.php') ?>



        <div class="catalog">
            <div class="container">

                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($delete)): ?>
                    <div id="notUser" class="alert alert-success mt-5" role="alert">
                        Tu compra se realizó con éxito
                    </div>
                <?php elseif ($productos->num_rows === 0): ?>
                    <div id="notUser" class="alert alert-secondary mt-5" role="alert">
                        Tu carrito está vacío
                    </div>
                <?php else: ?>

                    <div id="notUser" style="display:none;" class="alert alert-danger" role="alert">
                        Debes ingresar con tu usuario para poder hacer pedidos. Ingresa acá: <a href='signin.php' class='alert-link'>Inicio sesión</a>
                    </div>
                    <?php echo (empty($transactionMsg)) ? '' : $transactionMsg ?>
                    <form id="checkoutProducts" action="<?php echo $url; ?>" method="post">
                        <input id='user' name='user' value='<?php echo (empty($_SESSION['user'])) ? '' : $_SESSION['user'] ?>' type='hidden'>
                        <input name='delete' class="delete" value='0' type="hidden">
                        <div class="row">
                            <div class="col-7">
                            <?php
                            $i = 0;

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
                                $precio = $row['precio'];
                                $precioFormated = number_format($precio);
                                $cantidad = $row['cantidad'];
                                $cantidadExistente = $row['cantidadExistente'];
                                
                                // $disabled = ($cantidad < 1) ? "disabled" : "";

                                if($cantidad > $cantidadExistente) {
                                    $disableBuyButton++;
                                    $showOutOfInventory = '';
                                }

                                $subTotalUnidad = $cantidad * $precio;
                                $subTotalUnidadFormated = number_format($subTotalUnidad);
                                $compraTotal += $subTotalUnidad;
                                
                                echo "
                                <section data-product='$productoId' data-talla='$isTalla'>
                                
                                    <div class='card mt-5 mb-3' style='max-width: 800px;'>
                                        <div class='row g-0'>
                                            <div id='notInventoryMsg' class='alert alert-danger $showOutOfInventory' role='alert'>
                                                Lo sentimos, se nos agotó este producto.
                                            </div>
            
                                            <input name='$i-productoId' value='$productoId' type='hidden'>
                                            <input id='$i-isTalla' name='$i-isTalla' value='$isTalla' type='hidden'>
                                            <input id='$i-cantidad' name='$i-cantidad' value='$cantidad' type='hidden'>
                                            <div class='col-md-4'>
                                                <img src='img/$imagen' class='card-img-top' alt='$nombre'>
                                            </div>
                                            <div class='col-md-8'>
                                                <div class='card-body'>
                                                    <h5 class='card-title'>$nombre - $$precioFormated c/u</h5>
                                                    <p class='card-text'>$descripcion.</p>";
                                                    echo "
                                                    <div class='row'>
                                                        <div class='col-md'>
                                                            <p>$cantidad unidades ";
                                                        if ($isTalla) echo "de talla: $isTalla";

                                                        echo "</p>
                                                        </div>
                                                    </div>
                                                    <div class='d-flex justify-content-between'>
                                                            <h4 class='card-title mt-3'>Subtotal: $ $subTotalUnidadFormated</h4>
                                                            <button index='$i' type='button' class='btn btn-outline-danger btn-sm delete'>Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                ";

                                $i++;
                                $showOutOfInventory = 'hidden';
                            }
                            
                            ?>
                            </div>
                            <div class="col-5 mt-5">
                                <div class="border-start px-4">
                                    <div class="d-flex justify-content-between">
                                        <h4>Subtotal (<?php echo $productos->num_rows ." prod.):</h4><h4> $" . number_format($compraTotal) ?> </h4>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h4>Iva (19%):</h4>
                                        <h4>$<?php echo number_format($compraTotal * 0.19)?></h4>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <h2>Total:</h2>
                                        <h2>$<?php echo number_format($compraTotal * 1.19) ?></h2>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary buy<?php echo ($disableBuyButton === 0) ? '' : ' disabled' ?>" type="button">Comprar</button>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </form>
                <?php endif ?>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="scripts/checkout.js"></script>
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