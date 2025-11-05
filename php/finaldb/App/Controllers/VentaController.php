<?php
require_once __DIR__ . '/../../core/Controller.php';

class VentaController extends Controller
{
    private $ventaModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->ventaModel = $this->model('VentaModel');
    }

    public function index()
    {
        $currentPage = (int) ($_GET['page'] ?? 1);
        if ($currentPage < 1) $currentPage = 1;

        // Sistema de filtros escalable 
        $filters = [
            'categoria' => $_GET['categoria'] ?? '',
            'subcategoria' => $_GET['subcategoria'] ?? '',
            'region' => $_GET['region'] ?? '',
            'mercado' => $_GET['mercado'] ?? '',
            'ciudad' => $_GET['ciudad'] ?? '',
            'modo_envio' => $_GET['modo_envio'] ?? '',
            'priori' => $_GET['priori'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? '',
            'ordenar_por' => $_GET['ordenar_por'] ?? 'codigo',
            'direccion' => $_GET['direccion'] ?? 'DESC',
            'buscar' => trim($_GET['buscar'] ?? '')
        ];

        // Reportes disponibles 
        $reportes = [
            'promedio_ventas' => 'Promedio vendido por producto',
            'total_ventas' => 'Cantidad total de ventas',
            'total_beneficio' => 'Total $$ ventas',
            'total_envio' => 'Costo total de envío',
            'ventas_prioridad' => 'Ventas por prioridad',
            'ventas_envio' => 'Ventas por modo de envío',
            'ventas_categoria' => 'Ventas por categoría',
            'ventas_region' => 'Ventas por región',
            'top_clientes' => 'Top 10 clientes',
            'top_productos' => 'Top 10 productos más vendidos'
        ];

        $filtroReporte = $_GET['reporte'] ?? '';

        // Obtener datos
        $ventas = $this->ventaModel->getFiltered($filters, $currentPage);
        $categorias = $this->ventaModel->getCategorias();
        $subcategorias = $this->ventaModel->getSubcategorias();
        $regiones = $this->ventaModel->getRegiones();
        $mercados = $this->ventaModel->getMercados();
        $ciudades = $this->ventaModel->getCiudades();
        $modos_envio = $this->ventaModel->getModosEnvio();
        $priori = $this->ventaModel->getPriori();
        
        $totalRecords = $this->ventaModel->getTotalRecords($filters);
        $recordsPerPage = VentaModel::RECORDS_PER_PAGE;
        $totalPages = (int) ceil($totalRecords / $recordsPerPage);

        // Reporte
        $datosReporte = [];
        if ($filtroReporte) {
            $datosReporte = $this->ventaModel->getReporte($filtroReporte, $filters);
        }

        $this->view('Ventas/index', [
            'venta' => $ventas,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
            'regiones' => $regiones,
            'mercados' => $mercados,
            'ciudades' => $ciudades,
            'modos_envio' => $modos_envio,
            'priori' => $priori,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'filters' => $filters,
            'reportes' => $reportes,
            'filtroReporte' => $filtroReporte,
            'datosReporte' => $datosReporte
        ]);
    }

    public function viewVenta()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "⚠️ ID de producto no proporcionado.";
            header("Location: index.php?controller=VentaController&action=index");
            exit;
        }

        $venta = $this->ventaModel->getById($id);
        $this->view('Ventas/edit', ['venta' => $venta]);
    }
}