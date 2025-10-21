<?php
require_once __DIR__ . '/../../core/Controller.php';

class ClienteController extends Controller
{
    private $clienteModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cargar el modelo
        $this->clienteModel = $this->model('ClienteModel');
    }

    public function index()
    {
        $currentPage = (int) ($_GET['page'] ?? 1);
        if ($currentPage < 1) $currentPage = 1;

        $search = $_GET['search'] ?? '';
        $sort   = $_GET['sort'] ?? 'id_asc';

        $clientes = $this->clienteModel->getFiltered($currentPage, $search, $sort);
        $totalRecords = $this->clienteModel->getTotalRecordsFiltered($search);
        $recordsPerPage = ClienteModel::RECORDS_PER_PAGE;
        $totalPages = (int) ceil($totalRecords / $recordsPerPage);

        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
            $clientes = $this->clienteModel->getFiltered($currentPage, $search, $sort);
        }

        $this->view('Clientes/index', [
            'clientes' => $clientes,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'search' => $search,
            'sort' => $sort
        ]);
    }

    public function registrarCliente()
    {
        $redirect_url = "index.php?controller=ClienteController&action=index";

        if (empty($_POST["codigo"]) || empty($_POST["nombre"]) || empty($_POST["seg"])) {
            $_SESSION['error'] = "⚠️ Todos los campos son obligatorios.";
            header("Location: $redirect_url");
            exit;
        }

        // Sanitizar datos
        $codigo = htmlspecialchars(trim($_POST["codigo"]));
        $nombre = htmlspecialchars(trim($_POST["nombre"]));
        $seg    = htmlspecialchars(trim($_POST["seg"]));

        try {
            $registroExitoso = $this->clienteModel->createCliente($codigo, $nombre, $seg);

            if ($registroExitoso) {
                $_SESSION['success'] = "✅ ¡Cliente registrado exitosamente!";
            } else {
                $_SESSION['error'] = "❌ Error al registrar el cliente.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "🚨 Error en la base de datos: " . $e->getMessage();
        }

        // Siempre redirigir al panel principal
        header("Location: $redirect_url");
        exit;
    }

    public function viewCliente()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "⚠️ ID de cliente no proporcionado.";
            header("Location: index.php?controller=ClienteController&action=index");
            exit;
        }

        $cliente = $this->clienteModel->getById($id);
        $this->view('Clientes/edit', ['cliente' => $cliente]);
    }

    public function editForm($id)
    {
        $cliente = $this->clienteModel->getById($id);
        if (!$cliente) {
            $_SESSION['error'] = "Cliente no encontrado.";
            $this->redirect('index.php?controller=ClienteController&action=index');
        }

        $this->view('Clientes/edit', ['cliente' => $cliente]);
    }

    // Guardar cambios de edición
    public function updateCliente()
    {
        $id = $_POST['id'] ?? null;
        $codigo = $_POST['codigo'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $seg    = $_POST['seg'] ?? '';

        if (!$id || !$codigo || !$nombre || !$seg) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            $this->redirect("index.php?controller=ClienteController&action=editForm&id=$id");
        }

        $updated = $this->clienteModel->updateCliente($id, $codigo, $nombre, $seg);
        $_SESSION['success'] = $updated ? "Cliente actualizado correctamente." : "Error al actualizar cliente.";
        $this->redirect("index.php?controller=ClienteController&action=index");
    }

    // Eliminar cliente
    public function cambiarEstadoCliente($id)
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        $estado = isset($_GET['estado']) ? intval($_GET['estado']) : null;

        if ($id === null || $estado === null) {
            $_SESSION['error'] = "Datos inválidos para cambiar estado.";
            $this->redirect("index.php?controller=ClienteController&action=index");
            return;
        }

        // ✅ invertimos el valor
        $nuevoEstado = $estado == 1 ? 0 : 1;

        $resultado = $this->clienteModel->cambiarEstado($id, $nuevoEstado);

        $_SESSION['success'] = $resultado
            ? "Estado del cliente actualizado correctamente."
            : "⚠️ No se actualizó el estado (puede que ya esté igual).";

        $this->redirect("index.php?controller=ClienteController&action=index");
    }
}
