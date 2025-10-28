<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Sistema de Ventas' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/646ac4fad6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#">mi sistema</a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?controller=ClienteController&action=index">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=ProductoController&action=index">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=CategoriaController&action=index">Categorías</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">SubCategorias</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/finaldb/">Ventas</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Fecha de Pedidos</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Geografia</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Region del Mercado</a>
                </li> -->

            </ul>
        </div>
    </nav>