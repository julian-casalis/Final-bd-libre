<?php
require_once __DIR__ . '/../../core/Controller.php';

class ProductoController extends Controller
{
    private $productoModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cargar el modelo
        $this->productoModel = $this->model('ProductoModel');
    }

    public function index()
    {
        $currentPage = (int) ($_GET['page'] ?? 1);
        if ($currentPage < 1) $currentPage = 1;

        $search = $_GET['search'] ?? '';
        $sort   = $_GET['sort'] ?? 'id_asc';

        $producto = $this->productoModel->getFilteredPro($currentPage, $search, $sort);
        $totalRecords = $this->productoModel->getTotalRecordsFilteredPro($search);
        $recordsPerPage = ProductoModel::RECORDS_PER_PAGE;
        $totalPages = (int) ceil($totalRecords / $recordsPerPage);

        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
            $producto = $this->productoModel->getFiltered($currentPage, $search, $sort);
        }

        $this->view('Productos/index', [
            'producto' => $producto,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'search' => $search,
            'sort' => $sort
        ]);
    }

    public function registrarPro()
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
            $registroExitoso = $this->productoModel->createPro($codigo, $subc, $nombre);

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


    public function viewPro()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "⚠️ ID de producto no proporcionado.";
            header("Location: index.php?controller=ProductoController&action=index");
            exit;
        }

        $producto = $this->productoModel->getByIdPro($id);
        $this->view('Productos/edit', ['producto' => $producto]);
    }

    public function editForm($id)
    {
        $producto = $this->productoModel->getByIdPro($id);
        if (!$producto) {
            $_SESSION['error'] = "Producto no encontrado.";
            $this->redirect('index.php?controller=ProductoController&action=index');
        }

        $this->view('Productos/edit', ['producto' => $producto]);
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

        $updated = $this->productoModel->updatePro($id, $codigo, $subc, $nombre);
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

        $resultado = $this->productoModel->cambiarEstado($id, $nuevoEstado);

        $_SESSION['success'] = $resultado
            ? "Estado del producto actualizado correctamente."
            : "⚠️ No se actualizó el estado (puede que ya esté igual).";

        $this->redirect("index.php?controller=ProductoController&action=index");
    }
}
