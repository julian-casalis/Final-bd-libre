<?php
$title = "Panel de Ventas";
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

<h1 class="text-center p-3 text-white bg-dark">📊 Panel de Ventas</h1>
<!-- <h5 class="text-center text-white ">
    <?= empty($filters['categoria']) ? 'todo' : 'Categoría seleccionada: ' . htmlspecialchars($categorias[array_search($filters['categoria'], array_column($categorias, 'cate_id'))]['categoria']) ?>
</h5> -->
<div class="container-fluid bg-dark text-light p-4 rounded-3 shadow-lg">
    <!-- 🔍 FILTROS -->
    <div class="card shadow-sm mb-4 border-0 rounded-3 bg-secondary text-white">

        <div class="card shadow-sm mb-4 border-0 rounded-3 bg-secondary text-white">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <input type="hidden" name="controller" value="VentaController">
                    <input type="hidden" name="action" value="index">


                    <select name="subcategoria" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todas las subcategorías</option>
                        <?php foreach ($subcategorias as $sub): ?>
                            <option value="<?= $sub['subc_id'] ?>" <?= ($filters['subcategoria'] == $sub['subc_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sub['subc_nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="region" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todas las regiones</option>
                        <?php foreach ($regiones as $reg): ?>
                            <option value="<?= $reg['regi_id'] ?>" <?= ($filters['region'] == $reg['regi_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($reg['regi_nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="mercado" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todos los mercados</option>
                        <?php foreach ($mercados as $mer): ?>
                            <option value="<?= $mer['mer_nom'] ?>" <?= ($filters['mercado'] == $mer['mer_nom']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mer['mer_nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="ciudad" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todas las ciudades</option>
                        <?php foreach ($ciudades as $ciu): ?>
                            <option value="<?= $ciu['ciudad'] ?>" <?= ($filters['ciudad'] == $ciu['ciudad']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ciu['ciudad']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- modo de envio -->
                    <select name="modo_envio" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todos los modos</option>
                        <?php foreach ($modos_envio as $modo): ?>
                            <option value="<?= htmlspecialchars($modo['vent_mod_env']) ?>"
                                <?= ($filters['modo_envio'] == $modo['vent_mod_env']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($modo['vent_mod_env']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- prioridad -->
                    <select name="priori" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Todas prioridades</option>
                        <?php foreach ($priori as $prio): ?>
                            <option value="<?= htmlspecialchars($prio['vent_priori']) ?>"
                                <?= ($filters['priori'] == $prio['vent_priori']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prio['vent_priori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- 🔍 Campo de búsqueda -->
                    <div class="input-group" style="width: 300px;">
                        <input
                            type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="🔍Buscar..."
                            value="<?= htmlspecialchars($filters['buscar'] ?? '') ?>">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i> <!-- Icono de lupa -->
                        </button>
                    </div>
                    <!-- Reportes/Métricas -->
                    <select name="reporte" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Selecciona un reporte</option>
                        <?php foreach ($reportes as $key => $label): ?>
                            <option value="<?= $key ?>" <?= ($filtroReporte == $key) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="d-flex align-items-center mb-3">
                        <label for="ordenar_por" class="me-2 fw-bold">Ordenar por:</label>
                        <select name="ordenar_por" id="ordenar_por" class="form-select w-auto me-2" onchange="this.form.submit()">
                            <option value="codigo" <?= ($filters['ordenar_por'] ?? '') == 'codigo' ? 'selected' : '' ?>>Código</option>
                            <option value="fecha" <?= ($filters['ordenar_por'] ?? '') == 'fecha' ? 'selected' : '' ?>>Fecha</option>
                            <option value="cliente" <?= ($filters['ordenar_por'] ?? '') == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                            <option value="producto" <?= ($filters['ordenar_por'] ?? '') == 'producto' ? 'selected' : '' ?>>Producto</option>
                            <option value="prioridad" <?= ($filters['ordenar_por'] ?? '') == 'prioridad' ? 'selected' : '' ?>>Prioridad</option>
                            <option value="ventas" <?= ($filters['ordenar_por'] ?? '') == 'ventas' ? 'selected' : '' ?>>Ventas</option>
                            <option value="cantidad" <?= ($filters['ordenar_por'] ?? '') == 'cantidad' ? 'selected' : '' ?>>Cantidad</option>
                            <option value="beneficio" <?= ($filters['ordenar_por'] ?? '') == 'beneficio' ? 'selected' : '' ?>>Beneficio</option>
                            <option value="envio" <?= ($filters['ordenar_por'] ?? '') == 'envio' ? 'selected' : '' ?>>Costo Envío</option>
                        </select>

                        <label for="direccion" class="me-2 fw-bold">Dirección:</label>
                        <select name="direccion" id="direccion" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="ASC" <?= ($filters['direccion'] ?? '') == 'ASC' ? 'selected' : '' ?>>Ascendente</option>
                            <option value="DESC" <?= ($filters['direccion'] ?? '') == 'DESC' ? 'selected' : '' ?>>Descendente</option>
                        </select>
                    </div>

                    <!-- Botón opcional de "Aplicar filtros" -->
                    <!-- <button type="submit" class="btn btn-primary">Aplicar</button> -->

                </form>
            </div>
            <div class="col-12 mt-3 d-flex justify-content-center">
                <?php if (array_filter($filters)): ?>
                    <a href="index.php?controller=VentaController&action=index" class="btn btn-light btn-lg w-20">Limpiar ♻</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- 📋 TABLA -->
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="table-responsive rounded-3">
                    <table class="table table-dark table-sm table-hover align-middle shadow-lg">
                        <thead class="text-center">
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Código pedido</th>
                                <th>Cliente</th>
                                <th>Producto</th>
                                <th>Categoria</th>
                                <th>Subcategoria</th>
                                <th>Ciudad</th>
                                <th>Mercado</th>
                                <th>Región</th>
                                <th>Fecha</th>
                                <th>Modo Envío</th>
                                <th>Prioridad</th>
                                <th>precio $$$</th>
                                <th>Cantidad</th>
                                <th>Beneficio</th>
                                <th>Costo Envío</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php if (!empty($venta)): ?>
                                <?php foreach ($venta as $v): ?>
                                    <tr>
                                        <!-- <td><?= $v['vent_id'] ?></td> -->
                                        <td><?= htmlspecialchars($v['vent_ped_id']) ?></td>
                                        <td><?= htmlspecialchars($v['cliente']) ?></td>
                                        <td><?= htmlspecialchars($v['producto']) ?></td>
                                        <td><?= htmlspecialchars($v['categoria']) ?></td>
                                        <td><?= htmlspecialchars($v['subc']) ?></td>

                                        <td><?= htmlspecialchars($v['ubicacion']) ?></td>
                                        <td><?= htmlspecialchars($v['mercado']) ?></td>
                                        <td><?= htmlspecialchars($v['region']) ?></td>

                                        <td><?= htmlspecialchars($v['fecha']) ?></td>
                                        <td><?= htmlspecialchars($v['vent_mod_env']) ?></td>
                                        <td>
                                            <?php
                                            $prio = $v['vent_priori'];
                                            $color = ($prio == 'Alta') ? 'danger' : (($prio == 'Media') ? 'warning' : 'success');
                                            ?>
                                            <span class="badge bg-<?= $color ?>"><?= htmlspecialchars($prio) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($v['vent_ventas']) ?></td>
                                        <td><?= htmlspecialchars($v['vent_cant']) ?></td>
                                        <td><?= htmlspecialchars($v['vent_bene']) ?></td>
                                        <td><?= htmlspecialchars($v['vent_cost_envi']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="14" class="text-center text-muted p-3">No hay ventas para mostrar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- 📄 PAGINACIÓN -->
    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?controller=VentaController&action=index&page=<?= max(1, $currentPage - 1) ?>">&laquo;</a>
                </li>

                <?php
                $numLinksToShow = 5;
                $startPage = max(1, $currentPage - floor($numLinksToShow / 2));
                $endPage = min($totalPages, $currentPage + floor($numLinksToShow / 2));
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="?controller=VentaController&action=index&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?controller=VentaController&action=index&page=<?= min($totalPages, $currentPage + 1) ?>">&raquo;</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>


<?php if (!empty($datosReporte)): ?>
    <div class="mt-5 p-3 border rounded bg-light">
        <h4>Reporte: <?= htmlspecialchars($reportes[$filtroReporte]) ?></h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <?php foreach (array_keys($datosReporte[0]) as $col): ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosReporte as $fila): ?>
                        <tr>
                            <?php foreach ($fila as $valor): ?>
                                <td><?= htmlspecialchars($valor) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>


<?php include __DIR__ . '/../Templates/footer.php'; ?>