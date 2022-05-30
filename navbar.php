<!-- // $_SESSION['user'] = $user;
// $_SESSION['usrName'] = $usrName;
// $_SESSION['items'] = 0; -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="img/speedy-logo3.png" alt="" width="30" height="30" class="d-inline-block align-top"> 2Speedy Sports
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Catálogo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="search.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                                    <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                                </svg> Buscar productos
                            </a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <ul class="navbar-nav">
                        <?php if (empty($_SESSION['user'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="signin.php"> Iniciar sesión</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="registro.php"> Registrarse</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Hola, <?php echo $_SESSION['usrName'] ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?close=1">
                                    Cerrar Sesión</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo (empty($_SESSION['user'])) ? '#' : 'checkout.php' ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                    </svg>
                                    <?php echo (empty($_SESSION['items'])) ? '' : $_SESSION['items'] ?>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </nav>
