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

<h1 class="text-center p-3">Panel de Categorias</h1>
<div class="container-fluid row">
    <!-- Formulario -->
    <form class="col-4 p-3" method="POST" action="index.php?controller=CategoriaController&action=registrarCategoria">
        <h3 class="text-center text-secondary">Registro de Categorias</h3>

        <div class="mb-3">
            <label for="inputCodigo" class="form-label">Categoría</label>
            <input type="text" class="form-control" id="inputCodigo" name="categoria" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>

    <!-- Tabla -->

    <div class="col-8 p-4">
        <form method="GET" class="mb-3 d-flex">
            <input type="hidden" name="controller" value="CategoriaController">
            <input type="hidden" name="action" value="index">

            <input type="text" name="search" class="form-control me-2" placeholder="Buscar..." value="<?= htmlspecialchars($search ?? '') ?>">

            <select name="sort" class="form-select me-2">
                <option value="id_asc" <?= ($sort ?? '') == 'id_asc' ? 'selected' : '' ?>>ID ↑</option>
                <option value="id_desc" <?= ($sort ?? '') == 'id_desc' ? 'selected' : '' ?>>ID ↓</option>
                <option value="catego_asc" <?= ($sort ?? '') == 'catego_asc' ? 'selected' : '' ?>>Código A-Z</option>
                <option value="catego_desc" <?= ($sort ?? '') == 'catego_desc' ? 'selected' : '' ?>>Código Z-A</option>
            </select>

            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <table class="table table-striped">
            <thead class="bg-info text-white" id="categoriaTable">
                <tr>
                    <th>ID</th>
                    <th>Categoría</th>

                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categoria)): ?>
                    <?php foreach ($categoria as $categoria): ?>
                        <tr>
                            <td><?= htmlspecialchars($categoria['cate_id']) ?></td>
                            <td><?= htmlspecialchars($categoria['categoria']) ?></td>
                            <td>
                                <a href="index.php?controller=CategoriaController&action=cambiarEstadoCategoria&id=<?= $categoria['cate_id'] ?>&estado=<?= $categoria['cate_activo'] ?>"
                                    class="btn btn-sm <?= $categoria['cate_activo'] ? 'btn-danger' : 'btn-success' ?>">
                                    <?= $categoria['cate_activo'] ? 'Desactivar' : 'Activar' ?> <i class="fa-solid fa-trash"></i>

                                    <a href="index.php?controller=CategoriaController&action=viewCategoria&id=<?= $categoria['cate_id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay clientes para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= max(1, $currentPage - 1) ?>">&laquo;</a>
                    </li>

                    <?php
                    $numLinksToShow = 5;
                    $startPage = max(1, $currentPage - floor($numLinksToShow / 2));
                    $endPage = min($totalPages, $currentPage + floor($numLinksToShow / 2));
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= min($totalPages, $currentPage + 1) ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../Templates/footer.php'; ?>