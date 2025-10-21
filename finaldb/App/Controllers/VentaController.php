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

        // Cargar el modelo de ventas
        $this->ventaModel = $this->model('VentaModel');
    }

    public function index()
    {
        $currentPage = (int) ($_GET['page'] ?? 1);
        if ($currentPage < 1) $currentPage = 1;

        // Traer las ventas paginadas
        $venta = $this->ventaModel->getPaginated($currentPage);
        $totalRecords = $this->ventaModel->getTotalRecords();
        $recordsPerPage = VentaModel::RECORDS_PER_PAGE;
        $totalPages = (int) ceil($totalRecords / $recordsPerPage);

        // Si la página actual supera el total, redirigir al final
        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
            $venta = $this->ventaModel->getPaginated($currentPage);
        }

        // Mostrar la vista correcta
        $this->view('Ventas/index', [
            'venta' => $venta,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }


    public function registrarVenta()
    {
        $redirect_url = "index.php?controller=ProductoController&action=index";

        if (empty($_POST["codigo"]) ||  empty($_POST["subc"])  || empty($_POST["nombre"])) {
            $_SESSION['error'] = "⚠️ Todos los campos son obligatorios.";
            header("Location: $redirect_url");
            exit;
        }

        // Sanitizar datos
        $codigo = htmlspecialchars(trim($_POST["codigo"]));
        $subc   = htmlspecialchars(trim($_POST["subc"]));
        $nombre = htmlspecialchars(trim($_POST["nombre"]));


        try {
            $registroExitoso = $this->ventaModel->createPro($codigo, $subc, $nombre);

            if ($registroExitoso) {
                $_SESSION['success'] = "✅ ¡Producto registrado exitosamente!";
            } else {
                $_SESSION['error'] = "❌ Error al registrar el Producto.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "🚨 Error en la base de datos: " . $e->getMessage();
        }

        // Siempre redirigir al panel principal
        header("Location: $redirect_url");
        exit;
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

    public function editForm($id)
    {
        $venta = $this->ventaModel->getById($id);
        if (!$venta) {
            $_SESSION['error'] = "Producto no encontrado.";
            $this->redirect('index.php?controller=VentaController&action=index');
        }

        $this->view('Ventas/edit', ['venta' => $venta]);
    }


    // Guardar cambios de edición
    public function updatePro()
    {
        $id = $_POST['id'] ?? null;
        $codigo = $_POST['codigo'] ?? '';
        $subc    = $_POST['subc'] ?? '';
        $nombre = $_POST['nombre'] ?? '';

        if (!$id || !$codigo  || !$subc || !$nombre) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            $this->redirect("index.php?controller=ProductoController&action=editForm&id=$id");
        }

        $updated = $this->ventaModel->updatePro($id, $codigo, $subc, $nombre);
        $_SESSION['success'] = $updated ? "Producto actualizado correctamente." : "Error al actualizar cliente.";
        $this->redirect("index.php?controller=ProductoController&action=index");
    }

    // Eliminar cliente
    public function cambiarEstadoPro($id)
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        $estado = isset($_GET['estado']) ? intval($_GET['estado']) : null;

        if ($id === null || $estado === null) {
            $_SESSION['error'] = "Datos inválidos para cambiar estado.";
            $this->redirect("index.php?controller=ProductoController&action=index");
            return;
        }

        // ✅ invertimos el valor
        $nuevoEstado = $estado == 1 ? 0 : 1;

        $resultado = $this->ventaModel->cambiarEstado($id, $nuevoEstado);

        $_SESSION['success'] = $resultado
            ? "Estado del producto actualizado correctamente."
            : "⚠️ No se actualizó el estado (puede que ya esté igual).";

        $this->redirect("index.php?controller=ProductoController&action=index");
    }
}
