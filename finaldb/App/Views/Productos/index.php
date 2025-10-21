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

<h1 class="text-center p-3">Panel de Productos</h1>
<div class="container-fluid row">
    <!-- Formulario -->
    <form class="col-4 p-3" method="POST" action="index.php?controller=ProductoController&action=registrarPro">
        <h3 class="text-center text-secondary">Registro de Productos</h3>

        <div class="mb-3">
            <label for="inputCodigo" class="form-label">Código</label>
            <input type="text" class="form-control" id="inputCodigo" name="codigo" required>
        </div>


        <div class="mb-3">
            <label for="inputSegmento" class="form-label">Subcategoria</label>
            <input type="text" class="form-control" id="inputSegmento" name="subc" required>
        </div>

        <div class="mb-3">
            <label for="inputNombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="inputNombre" name="nombre" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>

    <!-- Tabla -->

    <div class="col-8 p-4">
        <form method="GET" class="mb-3 d-flex">
            <input type="hidden" name="controller" value="ProductoController">
            <input type="hidden" name="action" value="index">

            <input type="text" name="search" class="form-control me-2" placeholder="Buscar..." value="<?= htmlspecialchars($search ?? '') ?>">

            <select name="sort" class="form-select me-2">
                <option value="id_asc" <?= ($sort ?? '') == 'id_asc' ? 'selected' : '' ?>>ID ↑</option>
                <option value="id_desc" <?= ($sort ?? '') == 'id_desc' ? 'selected' : '' ?>>ID ↓</option>
                <option value="codigo_asc" <?= ($sort ?? '') == 'codigo_asc' ? 'selected' : '' ?>>Código A-Z</option>
                <option value="codigo_desc" <?= ($sort ?? '') == 'codigo_desc' ? 'selected' : '' ?>>Código Z-A</option>
                <option value="nombre_asc" <?= ($sort ?? '') == 'nombre_asc' ? 'selected' : '' ?>>Nombre A-Z</option>
                <option value="nombre_desc" <?= ($sort ?? '') == 'nombre_desc' ? 'selected' : '' ?>>Nombre Z-A</option>
                <option value="seg_asc" <?= ($sort ?? '') == 'seg_asc' ? 'selected' : '' ?>>Segmento A-Z</option>
                <option value="seg_desc" <?= ($sort ?? '') == 'seg_desc' ? 'selected' : '' ?>>Segmento Z-A</option>
            </select>

            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <table class="table table-striped">
            <thead class="bg-info text-white" id="productoTable">
                <tr>
                    <th>ID</th>
                    <th>Codigo</th>
                    <th>Subcategoria</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($producto)): ?>
                    <?php foreach ($producto as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['prod_id']) ?></td>
                            <td><?= htmlspecialchars($producto['prod_cod']) ?></td>
                            <td><?= htmlspecialchars($producto['prod_subc']) ?></td>
                            <td><?= htmlspecialchars($producto['prod_nom']) ?></td>
                            <td>
                                <a href="index.php?controller=ProductoController&action=cambiarEstadoPro&id=<?= $producto['prod_id'] ?>&estado=<?= $producto['prod_activo'] ?>"
                                    class="btn btn-sm <?= $producto['prod_activo'] ? 'btn-danger' : 'btn-success' ?>">
                                    <?= $producto['prod_activo'] ? 'Desactivar' : 'Activar' ?> <i class="fa-solid fa-trash"></i>

                                    <a href="index.php?controller=ProductoController&action=viewPro&id=<?= $producto['prod_id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay producto para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?controller=ProductoController&action=index&page=<?= max(1, $currentPage - 1) ?>">&laquo;</a>
                    </li>

                    <?php
                    $numLinksToShow = 5;
                    $startPage = max(1, $currentPage - floor($numLinksToShow / 2));
                    $endPage = min($totalPages, $currentPage + floor($numLinksToShow / 2));
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="?controller=ProductoController&action=index&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?controller=ProductoController&action=index&page=<?= min($totalPages, $currentPage + 1) ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../Templates/footer.php'; ?>