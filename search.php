<?php
// Se comienza la sesión
session_start();

// $_SESSION['user'] = $user;
// $_SESSION['usrName'] = $usrName;
// $_SESSION['items'] = 0;


$functionsPath = $_SERVER['DOCUMENT_ROOT']."/2speedy/functions/";
include_once($functionsPath . "conn.php");
include_once($functionsPath . "functions.php");




$url = htmlspecialchars($_SERVER["PHP_SELF"]);
// echo $url;

// echo $productos->num_rows;
$errors = array();
$post = false;
$productos = '';

/**
 * Se están validando los campos del formulario y se procesa
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $post = true;
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    $productName = (empty($_POST['productName'])) ? 'Balón' : $_POST['productName'];
    $descripcion = (empty($_POST['descripcion'])) ? '' : $_POST['descripcion'];
    $minPrice = (empty($_POST['minPrice'])) ? '' : $_POST['minPrice'];
    $maxPrice = (empty($_POST['maxPrice'])) ? '' : $_POST['maxPrice'];
    $cats = (empty($_POST['cats'])) ? '' : $_POST['cats'];


    $sql ="SELECT p.productoId, p.Nombre, p.descripcion, p.imagen, p.precio, p.cantidad, c.Nombre AS categoria 
    FROM productos AS p
    JOIN categoria AS c ON p.categoriaId = c.categoriaId
    WHERE p.Nombre LIKE '%$productName%'";// AND p.descripcion LIKE '%%'";

    $sql .= (empty($descripcion)) ? '' : " AND p.descripcion LIKE '%oficial%'";
    $sql .=  (empty($minPrice)) ? '' : " AND p.precio >= '$minPrice'";
    $sql .=  (empty($maxPrice)) ? '' : " AND p.precio <= '$maxPrice'";

    if (!empty($cats)) {
        $i = 0;
        $sql .= " AND (";
        foreach($cats as $cat){
            if ($i !== 0) $sql .= " OR ";
            $sql .= "c.categoriaId = '$cat'";
            $i++;
        }
        $sql .= ")";
    }

    $productos = $conn->query($sql);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">

    <title>Búsqueda</title>
    <script defer src="scripts/search.js"></script>

</head>

<body>
    <div class="content">
        <?php include('navbar.php') ?>

        <section class="search border border-1 mt-5 p-5">
            <form id="searchProducts" action="<?php echo $url; ?>" method="post">
                <h2> Buscar productos:</h2>
                <div class="input-group mb-3">
                    <input type="text" name="productName" id="productName" class="form-control" placeholder="Buscar productos, marcas y más..." aria-label="Buscar productos" aria-describedby="basic-addon2" required>
                    <span class="input-group-text" id="basic-addon2">
                        <a class="btn btn-outline-secondary search-btn" href="index.php">
                        Buscar
                        </a>
                    </span>
                </div>
                <div class="alert alert-danger hidden">
                    Favor ingrese su búsqueda!
                </div>
                <div class="d-flex align-items-end flex-column mt-5">
                    <a class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#advanceSearch" role="button" aria-expanded="false" aria-controls="advanceSearch">
                        Búsqueda avanzada
                    </a>                
                </div>
                <div class="collapse <?php echo ($post) ? $_POST['collapse'] : '' ?>" id="advanceSearch">
                    <h2 class="mt-4">Búsqueda Avanzada:</h2>
                    <div class="input-group">
                        <span class="input-group-text">Descripción</span>
                        <input type="text" name="descripcion" class="form-control" aria-label="Descripcion">
                    </div>
                    <h3 class="mt-3">Categorías:</h3>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="cats[]" value="FUT" id="catFutbol">
                        <label class="form-check-label" for="catFutbol">Fútbol</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="cats[]" value="BAS" id="catBasquet">
                        <label class="form-check-label" for="catBasquet">Basketboll</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="cats[]" value="VOL" id="catVoleibol">
                        <label class="form-check-label" for="catVoleibol">Voleibol</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="cats[]" value="TEN" id="catTenis">
                        <label class="form-check-label" for="catTenis">Tenis</label>
                    </div>
                    <h3 class="mt-3">Filtrar por precio:</h3>
                    <div class="input-group" style="width:300px;">
                        <input type="text" id="minPrice" class="form-control price" placeholder="desde" name="minPrice" aria-label="Username">
                        <span class="input-group-text">--</span>
                        <input type="text" id="maxPrice" class="form-control price" placeholder="hasta" name="maxPrice" aria-label="Server">
                    </div>
                </div>
                <input type="hidden" name="collapse" id="isCollapse" value="">
            </form>
        </section>

        <section class="catalog">
            <div class="container">

                <?php if ($post && $productos->num_rows > 0): ?>
                    <?php echo (empty($transactionMsg)) ? '' : $transactionMsg ?>
                        <div class="row">
                            <div class="col-7">
                            <?php

                            while($row = $productos->fetch_assoc()){
                                
                                $productoId = $row['productoId'];
                                $categoria = $row['categoria'];
                                $nombre = $row['Nombre'];
                                // $isTalla = $row['talla'];
                                $descripcion = $row['descripcion'];
                                $imagen = $row['imagen'];
                                $precio = $row['precio'];
                                $precioFormated = number_format($precio);
                                $cantidad = $row['cantidad'];
                                
                                $disabled = ($cantidad < 1) ? "disabled" : "";

                                
                                echo "
                                <section data-product='$productoId'>
                                    <div class='card mt-5 mb-3' style='max-width: 800px;'>
                                        <div class='row g-0'>
                                            <div class='col-md-4'>
                                                <a href='producto.php?product=$productoId'>
                                                    <img src='img/$imagen' class='card-img-top' alt='$nombre'>
                                                </a>
                                            </div>
                                            <div class='col-md-8'>
                                                <div class='card-body ms-3 mt-4'>
                                                    <h5 class='card-title'>$nombre - $$precioFormated c/u</h5>
                                                    <p class='card-text'>$descripcion.</p>";
                                                    echo "
                                                    <div class='row'>
                                                        <div class='col-md'>
                                                            <p>$cantidad unidades ";
                                                        // if ($isTalla) echo "de talla: $isTalla";

                                                        echo "</p>
                                                        </div>
                                                    </div>
                                                    Categoría: <span class='badge rounded-pill bg-secondary'>$categoria</span>
                                                    <a href='producto.php?product=$productoId' class='btn btn-outline-secondary btn-sm ms-5 stretched-link'>Ver producto</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                ";

                            }
                            
                            ?>
                            </div>
                        </div>

                <?php elseif ($post): ?>

                    <div id="notUser" class="alert alert-secondary mt-5" role="alert">
                        No se encontró información
                    </div>

                <?php endif ?>
            </div>
        </section>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <!-- <script src="scripts/checkout.js"></script> -->

</body>

</html>