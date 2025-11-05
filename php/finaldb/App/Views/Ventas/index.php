<?php
$title = "Tabla de Ventas";
include __DIR__ . '/../Templates/header.php';

// Mostrar alertas
if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <i class='bi bi-check-circle-fill me-2'></i>{$_SESSION['success']}
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <i class='bi bi-exclamation-triangle-fill me-2'></i>{$_SESSION['error']}
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
    unset($_SESSION['error']);
}
?>

<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .header-panel {
        padding: 2rem;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .header-panel h1 {
        color: white;
        font-weight: 700;
        margin: 0;
        font-size: 2.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .filtros-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
        border: none;
    }
    
    .filtros-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f3f5;
    }
    
    .filtros-header h5 {
        margin: 0;
        color: #2c3e50;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .filtros-header i {
        font-size: 1.5rem;
        margin-right: 0.8rem;
        color: #495057;
    }
    
    .filtros-section {
        margin-bottom: 1.5rem;
    }
    
    .filtros-section-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        padding-left: 0.5rem;
        border-left: 3px solid #495057;
    }
    
    .form-select, .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #495057;
        box-shadow: 0 0 0 0.2rem rgba(73, 80, 87, 0.15);
    }
    
    .btn-limpiar {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    
    .btn-limpiar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.25);
        color: white;
    }
    
    .tabla-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }
    
    .table-modern {
        margin: 0;
    }
    
    .table-modern thead {
        background: linear-gradient(135deg, #212529 0%, #343a40 100%);
        color: white;
    }
    
    .table-modern thead th {
        border: none;
        padding: 0.6rem 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
    }
    
    .table-modern tbody tr {
        border-bottom: 1px solid #f1f3f5;
        transition: all 0.2s ease;
    }
    
    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .table-modern tbody td {
        padding: 0.6rem 0.5rem;
        vertical-align: middle;
        color: #495057;
        font-size: 0.85rem;
    }
    
    .badge-prioridad {
        padding: 0.35rem 0.7rem;
        border-radius: 15px;
        font-weight: 600;
        font-size: 0.7rem;
    }
    
    .pagination .page-link {
        border: 2px solid #e9ecef;
        color: #495057;
        margin: 0 3px;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    
    .pagination .page-link:hover {
        background-color: #495057;
        color: white;
        border-color: #495057;
        transform: translateY(-2px);
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #495057 0%, #343a40 100%);
        border-color: #495057;
    }
    
    .reporte-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        margin-top: 2rem;
    }
    
    .reporte-card h4 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #e9ecef;
    }
    
    .filter-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .empty-state {
        padding: 3rem;
        text-align: center;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
</style>


<div class="container-fluid px-4">
    <!-- Filtros -->
    <div class="filtros-card">
        <div class="filtros-header">
            <i class="bi bi-funnel-fill"></i>
            <h5>Filtros de Búsqueda</h5>
        </div>
        
        <form method="GET" class="row g-3">
            <input type="hidden" name="controller" value="VentaController">
            <input type="hidden" name="action" value="index">

            <!--UBICACIÖN -->
            <div class="col-12">
                <div class="filtros-section-title">
                    <i class="bi bi-geo-alt me-2"></i>Ubicación
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="filter-label">Región</label>
                <select name="region" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las regiones</option>
                    <?php foreach ($regiones as $reg): ?>
                        <option value="<?= htmlspecialchars($reg['regi_nom']) ?>" <?= ($filters['region'] == $reg['regi_nom']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($reg['regi_nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="filter-label">Mercado</label>
                <select name="mercado" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos los mercados</option>
                    <?php foreach ($mercados as $mer): ?>
                        <option value="<?= $mer['mer_nom'] ?>" <?= ($filters['mercado'] == $mer['mer_nom']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($mer['mer_nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="filter-label">Ciudad</label>
                <select name="ciudad" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las ciudades</option>
                    <?php foreach ($ciudades as $ciu): ?>
                        <option value="<?= $ciu['ciudad'] ?>" <?= ($filters['ciudad'] == $ciu['ciudad']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ciu['ciudad']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!--PRODUCTOS -->
            <div class="col-12 mt-4">
                <div class="filtros-section-title">
                    <i class="bi bi-box-seam me-2"></i>Productos
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="filter-label">Categoría</label>
                <select name="categoria" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['cate_id'] ?>" <?= ($filters['categoria'] == $cat['cate_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="filter-label">Subcategoría</label>
                <select name="subcategoria" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las subcategorías</option>
                    <?php foreach ($subcategorias as $sub): ?>
                        <option value="<?= $sub['subc_id'] ?>" <?= ($filters['subcategoria'] == $sub['subc_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sub['subc_nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!--  ENVIO -->
            <div class="col-12 mt-4">
                <div class="filtros-section-title">
                    <i class="bi bi-truck me-2"></i>Envío y Prioridad
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="filter-label">Modo de Envío</label>
                <select name="modo_envio" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos los modos</option>
                    <?php foreach ($modos_envio as $modo): ?>
                        <option value="<?= htmlspecialchars($modo['vent_mod_env']) ?>"
                            <?= ($filters['modo_envio'] == $modo['vent_mod_env']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($modo['vent_mod_env']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="filter-label">Prioridad</label>
                <select name="priori" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las prioridades</option>
                    <?php foreach ($priori as $prio): ?>
                        <option value="<?= htmlspecialchars($prio['vent_priori']) ?>"
                            <?= ($filters['priori'] == $prio['vent_priori']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prio['vent_priori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- FECHAS Y BUSQUEDA -->
            <div class="col-12 mt-4">
                <div class="filtros-section-title">
                    <i class="bi bi-calendar-range me-2"></i>Fechas y Búsqueda
                </div>
            </div>

            <div class="col-md-6 col-lg-2">
                <label class="filter-label">Fecha Desde</label>
                <input type="date" name="fecha_desde" class="form-control" 
                       value="<?= htmlspecialchars($filters['fecha_desde'] ?? '') ?>" 
                       onchange="this.form.submit()">
            </div>

            <div class="col-md-6 col-lg-2">
                <label class="filter-label">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" 
                       value="<?= htmlspecialchars($filters['fecha_hasta'] ?? '') ?>" 
                       onchange="this.form.submit()">
            </div>

            <div class="col-md-12 col-lg-4">
                <label class="filter-label">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0" 
                           placeholder="Buscar..." value="<?= htmlspecialchars($filters['buscar'] ?? '') ?>">
                    <button class="btn btn-dark" type="submit">Buscar</button>
                </div>
            </div>

            <!-- ORDENAMIENTO -->
            <div class="col-12 mt-4">
                <div class="filtros-section-title">
                    <i class="bi bi-sort-down me-2"></i>Ordenamiento
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="filter-label">Ordenar por</label>
                <select name="ordenar_por" class="form-select" onchange="this.form.submit()">
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
            </div>

            <div class="col-md-6 col-lg-2">
                <label class="filter-label">Dirección</label>
                <select name="direccion" class="form-select" onchange="this.form.submit()">
                    <option value="ASC" <?= ($filters['direccion'] ?? '') == 'ASC' ? 'selected' : '' ?>>Ascendente ↑</option>
                    <option value="DESC" <?= ($filters['direccion'] ?? '') == 'DESC' ? 'selected' : '' ?>>Descendente ↓</option>
                </select>
            </div>

            <?php if (array_filter($filters)): ?>
                <div class="col-12 text-center mt-4">
                    <a href="index.php?controller=VentaController&action=index" class="btn-limpiar">
                        <i class="bi bi-arrow-clockwise me-2"></i>Limpiar Filtros
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabla -->
    <div class="tabla-container">
        <div class="table-responsive">
            <table class="table table-modern table-hover">
                <thead>
                    <tr>
                        <th>Código Pedido</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Ciudad</th>
                        <th>Mercado</th>
                        <th>Región</th>
                        <th>Fecha</th>
                        <th>Modo Envío</th>
                        <th>Prioridad</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Beneficio</th>
                        <th>Costo Envío</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($venta)): ?>
                        <?php foreach ($venta as $v): ?>
                            <tr>
                                <td><strong style="font-size: 0.85rem;"><?= htmlspecialchars($v['vent_ped_id']) ?></strong></td>
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
                                    <span class="badge badge-prioridad bg-<?= $color ?>">
                                        <?= htmlspecialchars($prio) ?>
                                    </span>
                                </td>
                                <td><strong style="font-size: 0.85rem;">$<?= htmlspecialchars($v['vent_ventas']) ?></strong></td>
                                <td><?= htmlspecialchars($v['vent_cant']) ?></td>
                                <td class="text-success"><strong style="font-size: 0.85rem;">$<?= htmlspecialchars($v['vent_bene']) ?></strong></td>
                                <td>$<?= htmlspecialchars($v['vent_cost_envi']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="15" class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h5>No hay ventas para mostrar</h5>
                                <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- paginacion -->
    <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php
                $filterParams = http_build_query(array_filter([
                    'controller' => 'VentaController',
                    'action' => 'index',
                    'categoria' => $filters['categoria'] ?? '',
                    'subcategoria' => $filters['subcategoria'] ?? '',
                    'region' => $filters['region'] ?? '',
                    'mercado' => $filters['mercado'] ?? '',
                    'ciudad' => $filters['ciudad'] ?? '',
                    'modo_envio' => $filters['modo_envio'] ?? '',
                    'priori' => $filters['priori'] ?? '',
                    'fecha_desde' => $filters['fecha_desde'] ?? '',
                    'fecha_hasta' => $filters['fecha_hasta'] ?? '',
                    'buscar' => $filters['buscar'] ?? '',
                    'ordenar_por' => $filters['ordenar_por'] ?? '',
                    'direccion' => $filters['direccion'] ?? '',
                    'reporte' => $filtroReporte ?? ''
                ]));
                ?>
                
                <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= $filterParams ?>&page=<?= max(1, $currentPage - 1) ?>">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>

                <?php
                $numLinksToShow = 5;
                $startPage = max(1, $currentPage - floor($numLinksToShow / 2));
                $endPage = min($totalPages, $currentPage + floor($numLinksToShow / 2));
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= $filterParams ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= $filterParams ?>&page=<?= min($totalPages, $currentPage + 1) ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

    <!--  REPORTES -->
    <?php if (!empty($datosReporte)): ?>
        <div class="reporte-card">
            <h4><i class="bi bi-file-earmark-bar-graph me-2"></i>Reporte: <?= htmlspecialchars($reportes[$filtroReporte]) ?></h4>
            <div class="table-responsive">
                <table class="table table-modern table-hover">
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

    <!-- selector de Reportes -->
    <div class="reporte-card">
        <div class="filtros-header">
            
            <h5>Generar Reporte</h5>
        </div>
        <form method="GET" class="row g-3">
            <input type="hidden" name="controller" value="VentaController">
            <input type="hidden" name="action" value="index">
            
            <div class="col-md-8">
                <label class="filter-label">Selecciona un tipo de reporte</label>
                <select name="reporte" class="form-select">
                    <option value="">-- Selecciona un reporte --</option>
                    <?php foreach ($reportes as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($filtroReporte == $key) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-dark w-100">
                    </i>Generar Reporte
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../Templates/footer.php'; ?>