<?php
$title = "Panel de categorias";
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

<h2>Editar Categoria</h2>
<form method="POST" action="index.php?controller=CategoriaController&action=updateCategoria">
    <input type="hidden" name="id" value="<?= $categoria['cate_id'] ?>">

    <div class="mb-3">
        <label>Categoria</label>
        <input type="text" name="categoria" class="form-control" value="<?= $categoria['categoria'] ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="index.php?controller=CategoriaController&action=index" class="btn btn-danger">Cancelar</a>
</form>

<?php include __DIR__ . '/../Templates/footer.php'; ?>