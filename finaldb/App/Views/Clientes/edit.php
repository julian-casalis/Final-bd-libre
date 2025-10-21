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

<h2>Editar Cliente</h2>
<form method="POST" action="index.php?controller=ClienteController&action=updateCliente">
    <input type="hidden" name="id" value="<?= $cliente['clie_id'] ?>">

    <div class="mb-3">
        <label>Código</label>
        <input type="text" name="codigo" class="form-control" value="<?= $cliente['clie_cod'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= $cliente['clie_nom'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Segmento</label>
        <input type="text" name="seg" class="form-control" value="<?= $cliente['clie_seg'] ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="index.php?controller=ClienteController&action=index" class="btn btn-danger">Cancelar</a>
</form>

<?php include __DIR__ . '/../Templates/footer.php'; ?>