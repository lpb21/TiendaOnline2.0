<?php
// Se comienza la sesión
session_start();

$functionsPath = $_SERVER['DOCUMENT_ROOT']."/2speedy/functions/";
include_once($functionsPath . "conn.php");
include_once($functionsPath . "functions.php");

$sql = "SELECT  * FROM productos ";

if(!empty($_GET['cat'])) {
    $cat = $_GET['cat'];
    $sql .= " WHERE categoriaId='$cat'";
}
$productos = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">

    <title>Catálogo</title>
</head>

<body>
    <div class="content">
        <?php include('navbar.php') ?>

        <div class="header mt-4">
            <?php
            // if (!empty($_SESSION)) print_r($_SESSION);
            ?>
            <h1>Catálogo</h1>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link<?php echo (empty($_GET['cat'])) ? ' active" aria-current="page' : '' ?>"  href=".">Todos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo (!empty($_GET['cat']) && $_GET['cat'] == 'FUT') ? ' active" aria-current="page' : '' ?>" href="<?php echo $_SERVER['PHP_SELF'] . "?cat=FUT" ?>">Fútbol</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo (!empty($_GET['cat']) && $_GET['cat'] == 'BAS') ? ' active" aria-current="page' : '' ?>" href="<?php echo $_SERVER['PHP_SELF'] . "?cat=BAS" ?>">Basquetbol</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo (!empty($_GET['cat']) && $_GET['cat'] == 'VOL') ? ' active" aria-current="page' : '' ?>" href="<?php echo $_SERVER['PHP_SELF'] . "?cat=VOL" ?>">Voleibol</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo (!empty($_GET['cat']) && $_GET['cat'] == 'TEN') ? ' active" aria-current="page' : '' ?>" href="<?php echo $_SERVER['PHP_SELF'] . "?cat=TEN" ?>">Tenis</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="catalog">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 g-4">

                    <?php
                    
                    while($row = $productos->fetch_assoc()){
                        // $id = number_format($clienteId);
                        // $name = $row['name'];
                        // $saldo = $row['val'];
                        // echo $name." (CC $id) con saldo: $". number_format($saldo) . "<br><br>";
                        $productoId = $row['productoId'];
                        $categoriaId = $row['categoriaId'];
                        $nombre = $row['Nombre'];
                        $talla = $row['talla'];
                        $descripcion = $row['descripcion'];
                        $imagen = $row['imagen'];
                        $precio = number_format($row['precio']);
                        $cantidad = $row['cantidad'];

                        echo "
                        <div class='col'>
                            <div class='card h-100'>
                                <a href='producto.php?product=$productoId'>
                                    <img src='img/$imagen' class='card-img-top' alt='$nombre'>
                                </a>
                                <div class='card-body'>
                                        <h5 class='card-title'>$nombre</h5>
                                        <p class='card-text'>$descripcion.</p>
                                    <h4 class='card-title'>$ $precio</h4>
                                    <a href='producto.php?product=$productoId' class='btn btn-outline-primary btn-sm'>Ver producto</a>
                                </div>
                            </div>
                        </div>";
                    }
                    
                    ?>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</body>

</html>