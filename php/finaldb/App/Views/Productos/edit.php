<?php
$title = "Panel de clientes";
include __DIR__ . '/../Templates/header.php';

// Mostrar alertas
if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success text-center'>{$_SESSION['success']}</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger text-center'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}

?>

<h2>Editar Producto</h2>
<form method="POST" action="index.php?controller=ProductoController&action=updatePro">
    <input type="hidden" name="id" value="<?= $producto['prod_id'] ?>">

    <div class="mb-3">
        <label>Código</label>
        <input type="text" name="codigo" class="form-control" value="<?= $producto['prod_cod'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Subcategoria</label>
        <input type="text" name="subc" class="form-control" value="<?= $producto['prod_subc'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= $producto['prod_nom'] ?>" required>
    </div>


    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="index.php?controller=ProductoController&action=index" class="btn btn-danger">Cancelar</a>
</form>

<?php include __DIR__ . '/../Templates/footer.php'; ?>