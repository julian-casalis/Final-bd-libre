<?php
require_once __DIR__ . '/../../core/Controller.php';

class CategoriaController extends Controller
{
    private $categoriaModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cargar el modelo
        $this->categoriaModel = $this->model('CategoriaModel');
    }

    public function index()
    {
        $currentPage = (int) ($_GET['page'] ?? 1);
        if ($currentPage < 1) $currentPage = 1;

        $search = $_GET['search'] ?? '';
        $sort   = $_GET['sort'] ?? 'id_asc';

        $categoria = $this->categoriaModel->getFiltered($currentPage, $search, $sort);
        $totalRecords = $this->categoriaModel->getTotalRecordsFiltered($search);
        $recordsPerPage = CategoriaModel::RECORDS_PER_PAGE;
        $totalPages = (int) ceil($totalRecords / $recordsPerPage);

        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
            $categoria = $this->categoriaModel->getFiltered($currentPage, $search, $sort);
        }

        $this->view('Categorias/index', [
            'categoria' => $categoria,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'search' => $search,
            'sort' => $sort
        ]);
    }

    public function registrarCategoria()
    {
        $redirect_url = "index.php?controller=CategoriaController&action=index";

        if (empty($_POST["categoria"])) {
            $_SESSION['error'] = "⚠️ Todos los campos son obligatorios.";
            header("Location: $redirect_url");
            exit;
        }

        // Sanitizar datos
        $categoria = htmlspecialchars(trim($_POST["categoria"]));
        try {
            $registroExitoso = $this->categoriaModel->createCategoria($categoria);

            if ($registroExitoso) {
                $_SESSION['success'] = "✅ ¡categoria registrado exitosamente!";
            } else {
                $_SESSION['error'] = "❌ Error al registrar el categoria.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "🚨 Error en la base de datos: " . $e->getMessage();
        }

        // Siempre redirigir al panel principal
        header("Location: $redirect_url");
        exit;
    }

    public function viewCategoria()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "⚠️ ID de categoria no proporcionado.";
            header("Location: index.php?controller=CategoriaController&action=index");
            exit;
        }

        $categoria = $this->categoriaModel->getById($id);
        $this->view('Categorias/edit', ['categoria' => $categoria]);
    }

    public function editForm($id)
    {
        $categoria = $this->categoriaModel->getById($id);
        if (!$categoria) {
            $_SESSION['error'] = "Categoria no encontrado.";
            $this->redirect('index.php?controller=CategoriaController&action=index');
        }

        $this->view('Categorias/edit', ['categoria' => $categoria]);
    }


    // Guardar cambios de edición
    public function updateCategoria()
    {
        $id = $_POST['id'] ?? null;
        $categoria = $_POST['categoria'] ?? '';


        if (!$id || !$categoria) {
            $_SESSION['error'] = "Todoos los campos son obligatorios.";
            $this->redirect("index.php?controller=CategoriaController&action=editForm&id=$id");
            return;
        }

        $updated = $this->categoriaModel->updateCategoria($id, $categoria);

        $_SESSION['success'] = $updated
            ? "Categoría actualizada correctamente."
            : "Error al actualizar categoría.";

        $this->redirect("index.php?controller=CategoriaController&action=index");
    }

    // Eliminar cliente
    public function cambiarEstadoCategoria($id)
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        $estado = isset($_GET['estado']) ? intval($_GET['estado']) : null;

        if ($id === null || $estado === null) {
            $_SESSION['error'] = "Datos inválidos para cambiar estado.";
            $this->redirect("index.php?controller=CategoriaController&action=index");
            return;
        }

        // ✅ invertimos el valor
        $nuevoEstado = $estado == 1 ? 0 : 1;

        $resultado = $this->categoriaModel->cambiarEstado($id, $nuevoEstado);

        $_SESSION['success'] = $resultado
            ? "Estado del categoria actualizado correctamente."
            : "⚠️ No se actualizó el estado (puede que ya esté igual).";

        $this->redirect("index.php?controller=CategoriaController&action=index");
    }
}
